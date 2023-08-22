<?php
const GAME_OPEN = 1;
const GAME_LOCKED = 2;
const GAME_FINISHED = 0;
const MAX_PLAYERS = 4;

class GameController
{
    public static function schedule()
    {
        if (!isLoggedIn()) {
            SessionController::setFlashMessage('login', 'Tens de estar ligado para ver esta página');
            header('Location: /login');
            exit;
        }

        require_once BASE_PATH . 'views/jogo/game_schedule.php';
    }

    public static function addGame()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['league_id'])) {
            $user_id = $_SESSION['user']['id'];
            $league_id = $_POST['league_id'];
            self::validateUserInput($user_id, $league_id);

            $local = $_POST['local'];
            $data_hora = $_POST['data_hora'];

            $game_id = self::addGameToDatabase($league_id, $local, $data_hora, $user_id);
            self::addPlayerToGame($game_id, $user_id);
            self::notifyUsersNewGame($game_id, $user_id, $league_id);

            // Redirect to the league page
            header('Location: /league?id=' . $league_id);

        } else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['league_id'])) {
            $league_id = $_GET['league_id'];
            require_once "../views/jogo/create.php";
        } else {
            SessionController::setFlashMessage('error', 'Link Inválido.');
            header('Location: /error');
        }
    }

    public static function addGameToDatabase($league_id, $local, $data_hora, $user_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('INSERT INTO Jogos (id_liga, local, data_hora, status, criador) VALUES (:league_id, :local, :data_hora, 1, :creator_id)');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->bindParam(':local', $local);
        $stmt->bindParam(':data_hora', $data_hora);
        $stmt->bindParam(':creator_id', $user_id);
        $stmt->execute();
        return $conn->lastInsertId();  // return the game ID
    }


    public static function validateUserInput($user_id, $league_id)
    {
        if (!isLoggedIn()) {
            SessionController::setFlashMessage('login', 'Tens de estar ligado para ver esta página');
            header('Location: /login');
            exit;
        }

        if (!LeagueController::isUserMemberOfLeague($user_id, $league_id)) {
            SessionController::setFlashMessage('access_error', 'Não és um membro desta liga.');
            header('Location: /error');
            exit();
        }
    }

    public static function addPlayerToGame($game_id, $user_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('INSERT INTO Jogadores_Jogo (id_jogo, id_utilizador) VALUES (:game_id, :user_id)');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    public static function notifyUsersNewGame($game_id, $user_id, $league_id)
    {
        $users = LeagueController::getLeagueUsers($league_id);
        $league_name = LeagueController::getLeagueInfo($league_id);

        // filter game creator out
        $users = array_filter($users, function ($user_in_league) use ($user_id) {
            return $user_in_league != $user_id;
        });

        $content = $_SESSION['user']['nome_utilizador'] . " criou um novo jogo na liga " . $league_name['nome'] . ". Vêm participar!";
        $link = "/game?id=" . $game_id;

        NotificationController::notifyUsers($users, $content, $link);
    }


    public static function show()
    {
        SessionController::start();
        $currentUserId = $_SESSION['user']['id'];
        $game_id = $_GET['id'];
        $game = self::getGameData($game_id);
        $league_id = $game['id_liga'];
        $creator_id = $game['criador'];
        $isVisitor = false;


      //  self::validateUserInput($currentUserId, $league_id);

        $membership = LeagueController::checkLeagueMembership($game['id_liga'], $currentUserId);
        $league_type = LeagueController::getLeagueType($league_id);

        if($league_type == 'publica' && !$membership) {
            $isVisitor = true;
        }

        // if the game and membership are found
        if ($game && $membership || $game && $isVisitor) {
            $players = self::getPlayersInGame($game_id);
            $playerIds = self::getPlayerIdsInGame($game_id);
            $resultsExist = self::resultsExist($game_id);

            require_once '../views/jogo/show.php';
        } elseif($league_type == 'privada' && !$membership) {
            SessionController::setFlashMessage('error', 'Esta Liga é Privada.');
            header('Location: /error');
        }
    }

    public static function getGameData($game_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT id, id_liga, local, data_hora, status, team1_score, team2_score, criador, fim_jogo FROM Jogos WHERE id = :game_id');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();

        // Fetch the game from the returned results
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getPlayersInGame($game_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT Utilizadores.id, Utilizadores.nome_utilizador, Utilizadores.avatar, Jogadores_Jogo.equipa 
                        FROM Jogadores_Jogo 
                        JOIN Utilizadores ON Jogadores_Jogo.id_utilizador = Utilizadores.id 
                        WHERE Jogadores_Jogo.id_jogo = :game_id
                        ORDER BY Jogadores_Jogo.equipa ASC');
        $stmt->bindValue(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function getPlayerIdsInGame($game_id)
    {
        $conn = dbConnect();
        $game_id_int = (int)$game_id;

        $stmt = $conn->prepare('SELECT Utilizadores.id FROM Jogadores_Jogo 
                        JOIN Utilizadores ON Jogadores_Jogo.id_utilizador = Utilizadores.id 
                        WHERE Jogadores_Jogo.id_jogo = :game_id');
        $stmt->bindValue(':game_id', $game_id_int, PDO::PARAM_INT);
        $stmt->execute();

        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_column($players, 'id');

    }

    public static function subscribe()
    {
        if (!isLoggedIn()) {
            SessionController::setFlashMessage('login', 'Faz Login para ver esta página');
            header('Location: /login');
            exit;
        }

        $game_id = $_GET['id'];
        $user_id = $_SESSION['user']['id'];

        // Insert the new subscription into the database
        $conn = dbConnect();
        $stmt = $conn->prepare('INSERT INTO Jogadores_Jogo (id_jogo, id_utilizador) VALUES (:game_id, :user_id)');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Redirect back to the game page
        header('Location: /game?id=' . $game_id);
    }

    public static function unsubscribe()
    {
        if (!isLoggedIn()) {
            SessionController::setFlashMessage('login', 'Faz Login para ver esta página');
            header('Location: /login');
            exit;
        }
        if (!isset($_GET['id'])) {
            SessionController::setFlashMessage('error', 'Endereço Inválido.');
            header('Location: /error');
            exit;
        }
        // sanitize 'id' as an integer
        $game_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $currentUserId = $_SESSION['user']['id'];

        // Check if the game is still open
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT status FROM Jogos WHERE id = :game_id');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();
        $gameStatus = $stmt->fetch(PDO::FETCH_ASSOC)['status'];

        if ($gameStatus == GAME_OPEN) {
            // Remove the current user from the game
            $stmt = $conn->prepare('DELETE FROM Jogadores_Jogo WHERE id_jogo = :game_id AND id_utilizador = :user_id');
            $stmt->bindParam(':game_id', $game_id);
            $stmt->bindParam(':user_id', $currentUserId);
            $stmt->execute();
        }

        // Redirect to the game page
        header('Location: /game?id=' . $game_id);
    }


    public static function handleLockGame()
    {
        if (!isset($_GET['game_id'])) {
            SessionController::setFlashMessage('error', 'Endereço Inválido.');
            header('Location: /error');
            exit;
        }

        $game_id = filter_var($_GET['game_id'], FILTER_SANITIZE_NUMBER_INT);
        $result = self::lockGame($game_id);
        if ($result !== true) {
            SessionController::setFlashMessage('error', $result);
            header('Location: /error');
            exit;
        }
        header("Location: /game?id=" . $game_id);
    }

    public static function lockGame($game_id)
    {
        $conn = dbConnect();
        $conn->beginTransaction();

        // Check if the game exists and is open
        $stmt = $conn->prepare('SELECT * FROM Jogos WHERE id = :game_id AND status = :status');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->bindValue(':status', GAME_OPEN);
        $stmt->execute();
        $game = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$game) {
            return 'O jogo não existe ou está fechado.';
        }

        // Fetch subscribed players and rankings
        $players = self::getSubscribedPlayers($game_id);

        // check if it's 4 players
        if (count($players) !== MAX_PLAYERS) {
            return 'Não estão 4 jogadores inscritos';
        }

        // Assign the players to teams
        for ($i = 0; $i < MAX_PLAYERS; $i++) {
            $currentPlayer = $players[$i]; // Current player

            // Determine the team for the player
            $team = ($i == 0 || $i == 3) ? 1 : 2;

            // Assign the player to the team
            $stmt = $conn->prepare('UPDATE Jogadores_Jogo SET equipa = :team WHERE id_utilizador = :user_id AND id_jogo = :game_id');
            $stmt->bindParam(':user_id', $currentPlayer['id_utilizador']);
            $stmt->bindParam(':team', $team);
            $stmt->bindParam(':game_id', $game_id);
            $stmt->execute();
        }

        // Lock the game
        $stmt = $conn->prepare('UPDATE Jogos SET status = :status WHERE id = :game_id');
        $stmt->bindValue(':status', GAME_LOCKED);
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();

        $conn->commit();

        return true;
    }

    public static function startChangeTeams()
    {
        checkLoggedIn();
        $game_id = $_GET['id'];
        $_SESSION['adjustTeams'] = $game_id;
        header("Location: /game?id=$game_id");
    }

    public static function submitChangeTeams()
    {
        $game_id = $_POST['game_id'];

        $new_teams = $_POST['team'];

        $result = self::changeTeams($game_id, $new_teams);
        if ($result === true) {
            unset($_SESSION['adjustTeams']);
            SessionController::setFlashMessage('success', 'Equipas Alteradas');
            header("Location: /game?id=$game_id");
        } else {
            SessionController::setFlashMessage('error', 'Algo correu mal, tenta de novo.');
            header('Location /error');
        }
    }


    public static function changeTeams($game_id, $new_teams)
    {
        $conn = dbConnect();
        $conn->beginTransaction();


        $stmt = $conn->prepare('SELECT * FROM Jogos WHERE id = :game_id AND status = :status');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->bindValue(':status', GAME_LOCKED);
        $stmt->execute();
        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$game) {
            return 'O jogo não existe ou não está trancado.';
        }

        // Atualize as equipes dos jogadores
        foreach ($new_teams as $user_id => $team) {
            $stmt = $conn->prepare('UPDATE Jogadores_Jogo SET equipa = :team WHERE id_utilizador = :user_id AND id_jogo = :game_id');
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':team', $team);
            $stmt->bindParam(':game_id', $game_id);
            $stmt->execute();
        }

        $conn->commit();

        return true;
    }
    public static function getLastUserGameDate($user_id){
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT MAX(Jogos.data_hora) as last_game_date 
                            FROM Jogos 
                            INNER JOIN Jogadores_Jogo ON Jogos.id = Jogadores_Jogo.id_jogo 
                            WHERE Jogadores_Jogo.id_utilizador = :user_id AND Jogos.fim_jogo = 1');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['last_game_date'];
    }

    public static function getPlayerGames($user_id) {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT Jogos.id, Jogos.local, Jogos.data_hora, Jogos.team1_score, Jogos.team2_score, Jogos.fim_jogo,
                            Jogadores_Jogo.equipa, Jogadores_Jogo.pontuacao, 
                            CASE 
                                WHEN Jogadores_Jogo.equipa = 1 AND Jogos.team1_score > Jogos.team2_score THEN "Vitória"
                                WHEN Jogadores_Jogo.equipa = 2 AND Jogos.team1_score < Jogos.team2_score THEN "Vitória"
                                ELSE "Derrota"
                            END as Resultado
                            FROM Jogos 
                            INNER JOIN Jogadores_Jogo ON Jogos.id = Jogadores_Jogo.id_jogo 
                            WHERE Jogadores_Jogo.id_utilizador = :user_id AND Jogos.fim_jogo = 1 
                            ORDER BY Jogos.data_hora DESC');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public static function getSubscribedPlayers($game_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('
    SELECT Jogadores_Jogo.id_utilizador, Ranking.pontos 
    FROM Jogadores_Jogo
    LEFT JOIN Ranking 
    ON Jogadores_Jogo.id_utilizador = Ranking.id_utilizador
    AND Ranking.id_liga = (SELECT id_liga FROM Jogos WHERE id = :game_id) 
    WHERE Jogadores_Jogo.id_jogo = :game_id
    ORDER BY Ranking.pontos DESC
');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function registerResults()
    {
        $game_id = $_GET['id'];
        $game = self::getGame($game_id);

        require_once '../views/jogo/register_results.php';
    }

    public static function getGame($game_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare("SELECT * FROM Jogos WHERE id = :game_id");
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function submitResults()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $game_id = $_GET['id'];
            $game = self::getGame($game_id);
            $team1_score = $_POST['team1_score'];
            $team2_score = $_POST['team2_score'];

            if($team1_score == $team2_score) {
                SessionController::setFlashMessage('error', 'Não é permitido empates.');
                include(BASE_PATH . 'views/jogo/register_results.php');
                exit();
            }

            self::updateSetScore($game_id, $team1_score, $team2_score);  // call updateSetScore instead of updateResults
            $game = self::getGame($game_id); // update $game after updating the scores

            if ($game['team1_score'] < 2 && $game['team2_score'] < 2) {
                // The game hasn't ended, so render the result submission form again
                include(BASE_PATH . 'views/jogo/register_results.php');
            } else {
                self::setGameFinished($game_id);  // New function to set game_finished to 1
                // Then redirect to the game page
                header('Location: /game?id=' . $game_id);
            }
            exit();
        }
    }



    public static function updateResults($gameId, $team1_score, $team2_score)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare("UPDATE Jogos SET team1_score = :team1_score, team2_score = :team2_score WHERE id = :gameId");

        $stmt->bindParam(':team1_score', $team1_score, PDO::PARAM_INT);
        $stmt->bindParam(':team2_score', $team2_score, PDO::PARAM_INT);
        $stmt->bindParam(':gameId', $gameId, PDO::PARAM_INT);

        $stmt->execute();
    }

    public static function resultsExist($gameId)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT team1_score, team2_score FROM Jogos WHERE id = :game_id');
        $stmt->bindParam(':game_id', $gameId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $team1_score = $result['team1_score'];
        $team2_score = $result['team2_score'];

        // check if the results are null or zero
        return (!is_null($team1_score) && $team1_score != 0) || (!is_null($team2_score) && $team2_score != 0);
    }

    public static function finishGame()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            SessionController::setFlashMessage('error', 'Endereço Inválido.');
            header('Location: /error');
            exit;
        }

        $game_id = filter_var($_POST['game_id'], FILTER_SANITIZE_NUMBER_INT);

        $conn = dbConnect();
        $conn->beginTransaction();

        $game = self::getGame($game_id);

        if (!$game) {
            SessionController::setFlashMessage('error', 'O jogo não existe!');
            header('Location: /game?id=' . $game_id);
            exit;
        }

        if ($game['status'] != GAME_LOCKED) {
            SessionController::setFlashMessage('error', 'O jogo já terminou!');
            header('Location: /game?id=' . $game_id);
            exit;
        }

        $team_winner = $game['team1_score'] > $game['team2_score'] ? 1 : 2;
        $team_loser = ($team_winner == 1) ? 2 : 1;

        self::updateScores($game_id, $team_winner, true);
        self::updateScores($game_id, $team_loser, false);

        // Change status to 0 (finished)
        $stmt = $conn->prepare('UPDATE Jogos SET status = 0 WHERE id = :game_id');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();

        $conn->commit();

        header('Location: /game?id=' . $game_id);
        exit;
    }

    private static function updateScores($game_id, $team, $isWinner)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT id_utilizador FROM Jogadores_Jogo WHERE id_jogo = :game_id AND equipa = :team');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->bindParam(':team', $team);
        $stmt->execute();

        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($players as $player) {
            if ($isWinner) {
                $stmt = $conn->prepare('UPDATE Ranking SET pontos = pontos + 1, jogos_jogados = jogos_jogados + 1, jogos_ganhos = jogos_ganhos + 1 WHERE id_utilizador = :id_utilizador AND id_liga = (SELECT id_liga FROM Jogos WHERE id = :game_id)');
            } else {
                $stmt = $conn->prepare('UPDATE Ranking SET jogos_jogados = jogos_jogados + 1, jogos_perdidos = jogos_perdidos + 1 WHERE id_utilizador = :id_utilizador AND id_liga = (SELECT id_liga FROM Jogos WHERE id = :game_id)');
            }
            $stmt->bindParam(':id_utilizador', $player['id_utilizador']);
            $stmt->bindParam(':game_id', $game_id);
            $stmt->execute();
        }
    }

    public static function getSets($game_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT * FROM Sets WHERE game_id = :game_id ORDER BY sequence_number ASC');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function updateSetScore($game_id, $team1_score, $team2_score)
    {
        $conn = dbConnect();
        $conn->beginTransaction();

        // Determine the sequence number for this set
        $stmt = $conn->prepare('SELECT MAX(sequence_number) as max_sequence_number FROM Sets WHERE game_id = :game_id');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $sequence_number = $result['max_sequence_number'] + 1;

        // Insert the set into the database
        $stmt = $conn->prepare('INSERT INTO Sets (game_id, sequence_number, team1_score, team2_score) VALUES (:game_id, :sequence_number, :team1_score, :team2_score)');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->bindParam(':sequence_number', $sequence_number);
        $stmt->bindParam(':team1_score', $team1_score);
        $stmt->bindParam(':team2_score', $team2_score);
        $stmt->execute();

        // Determine the winner of the set
        $set_winner = ($team1_score > $team2_score) ? 1 : 2;

        // Update the game score in the database
        $stmt = $conn->prepare('UPDATE Jogos SET team' . $set_winner . '_score = team' . $set_winner . '_score + 1 WHERE id = :game_id');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();

        $conn->commit();

        // If a team has won 2 sets, call updateScores
        $game = self::getGame($game_id);
        if ($game['team1_score'] == 2 || $game['team2_score'] == 2) {
            self::updateScores($game_id, $set_winner, true);

        }
    }

    public static function setGameFinished($game_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare("UPDATE Jogos SET fim_jogo = 1 WHERE id = ?");
        return $stmt->execute([$game_id]);
    }


    public static function getTotalGames() {
        $conn = dbConnect();
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM Jogos");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public static function cancelGame() {

        $gameId = $_POST['game_id'];
        $leagueId = $_POST['league_id'];

        $conn = dbConnect();

        $stmt1 = $conn->prepare("DELETE FROM Jogadores_Jogo WHERE id_jogo = :game_id");
        $stmt1->bindParam(':game_id', $gameId);
        $stmt1->execute();

        $stmt2 = $conn->prepare("DELETE FROM Jogos WHERE id = :game_id");
        $stmt2->bindParam(':game_id', $gameId);
        $stmt2->execute();

        SessionController::setFlashMessage('success', 'Jogo cancelado com sucesso.');
        header("Location: /league?id=$leagueId");
    }
}

