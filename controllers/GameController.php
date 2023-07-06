<?php
const GAME_OPEN = 1;
const GAME_LOCKED = 2;
const GAME_FINISHED = 0;
const MAX_PLAYERS = 4;

class GameController
{
    public static function schedule()
    {
        // verify if user is logged in
        if(!isLoggedIn()) {
            SessionController::setFlashMessage('login','Tens de estar ligado para ver esta página');
            header('Location: /login');
            exit;
        }

        require_once BASE_PATH . 'views/jogo/game_schedule.php';
    }

    public static function addGame()
    {
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['league_id'])) {
            $user_id = $_SESSION['user']['id'];
            $league_id = $_POST['league_id'];

            // Verify if user is a member of the league
            if (!LeagueController::isUserMemberOfLeague($user_id, $league_id)) {
                SessionController::setFlashMessage('access_error', 'Não és um membro desta liga.');
                header('Location: /error');
                exit();
            }

            $local = $_POST['local'];
            $data_hora = $_POST['data_hora'];


            // Insert the new game into the database
            $conn = dbConnect();
            $stmt = $conn->prepare('INSERT INTO Jogos (id_liga, local, data_hora, status) VALUES (:league_id, :local, :data_hora, 1)');
            $stmt->bindParam(':league_id', $league_id);
            $stmt->bindParam(':local', $local);
            $stmt->bindParam(':data_hora', $data_hora);
            $stmt->execute();

            // Get the ID of the newly created game
            $game_id = $conn->lastInsertId();

            // Add the creator to the game
            $stmt = $conn->prepare('INSERT INTO Jogadores_Jogo (id_jogo, id_utilizador) VALUES (:game_id, :user_id)');
            $stmt->bindParam(':game_id', $game_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();


            //Send notifications to members

            // Get all league users
            $users = LeagueController::getLeagueUsers($league_id);

            // Obtain League info to use the Name
            $league_name = LeagueController::getLeagueInfo($league_id);

            // filter game creator out
            $users = array_filter($users, function ($user_in_league) use ($user_id) {
                return $user_in_league != $user_id;
            });


            // Notificate each league member
            $content = $_SESSION['user']['nome_utilizador'] . " criou um novo jogo na liga " . $league_name['nome'] . ". Vêm participar!";

            // game link
            $link = "/game?id=" . $game_id;

            NotificationController::notifyUsers($users, $content, $link);


            // Redirect to the league page
            header('Location: /league?id=' . $league_id);

        } else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['league_id'])) {
            $league_id = $_GET['league_id'];
            require_once "../views/jogo/create.php";
        } else {
            // If the form is not submitted or the league_id is not set, redirect to the error page
            // need to deal with error messages later ... remember
            header('Location: /error');
        }
    }

    public static function show()
    {
        if(!isLoggedIn()){
            SessionController::setFlashMessage('login', 'Tens de estar ligado para ver esta página');
            header('Location: /login');
            exit();
        }

        $game_id = $_GET['id'];
        $currentUserId = $_SESSION['user']['id'];

        // Query the database for the game with the given ID
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT id, id_liga, local, data_hora, status, team1_score, team2_score FROM Jogos WHERE id = :game_id');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();

        // Fetch the game from the returned results
        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        // Query the database to check if the current user is a member of the league
        $stmt = $conn->prepare('SELECT * FROM Membros_Liga WHERE id_liga = :league_id AND id_utilizador = :user_id');

        $stmt->bindParam(':league_id', $game['id_liga']); // Use 'id_liga' from the game
        $stmt->bindParam(':user_id', $currentUserId);
        $stmt->execute();

        // Fetch the league membership from the returned results
        $membership = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($game && $membership) { // if the game and membership are found
            $players = self::getPlayersInGame($game_id);
            $playerIds = self::getPlayerIdsInGame($game_id);
            $resultsExist = self::resultsExist($game_id);

            // Render the game view
            require_once '../views/jogo/show.php';
        } else {
            // remember error messages
            header('Location: /error');
        }
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
        $game_id = $_GET['id'];

        // Check if the user is logged in
        if (isset($_SESSION['user']['id'])) {
            // Get the current user ID
            $user_id = $_SESSION['user']['id'];

            // Insert the new subscription into the database
            $conn = dbConnect();
            $stmt = $conn->prepare('INSERT INTO Jogadores_Jogo (id_jogo, id_utilizador) VALUES (:game_id, :user_id)');
            $stmt->bindParam(':game_id', $game_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            // Redirect back to the game page
            header('Location: /game?id=' . $game_id);
        } else {
            // If the user is not logged in, redirect to the login page
            header('Location: /login');
        }
    }

    public
    static function unsubscribe()
    {
        // Get the game ID from the GET parameters
        $game_id = $_GET['id'];
        $currentUserId = $_SESSION['user']['id']; // assuming user id is stored in session

        // Check if the game is still open
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT status FROM Jogos WHERE id = :game_id');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();
        $gameStatus = $stmt->fetch(PDO::FETCH_ASSOC)['status'];

        // If the game is open
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
        // Obter o ID do jogo da URL
        $game_id = $_GET['game_id'];

        // Tentar bloquear o jogo
        $success = GameController::lockGame($game_id);

        // Redirecionar o usuário para a página do jogo
        header("Location: /game?id=" . $game_id);
    }


    public static function lockGame($game_id)
    {
        $conn = dbConnect();

        // Check if the game exists and is open
        $stmt = $conn->prepare('SELECT * FROM Jogos WHERE id = :game_id AND status = :status');
        $stmt->bindParam(':game_id', $game_id);
        $stmt->bindValue(':status', GAME_OPEN);
        $stmt->execute();
        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        // Only proceed if the game exists and is open
        if ($game) {
            // Fetch the players who are subscribed to the game and their rankings
            $stmt = $conn->prepare('SELECT Jogadores_Jogo.id_utilizador, Ranking.pontos FROM Jogadores_Jogo
                                LEFT JOIN Ranking ON Jogadores_Jogo.id_utilizador = Ranking.id_utilizador
                                WHERE Jogadores_Jogo.id_jogo = :game_id
                                ORDER BY Ranking.pontos DESC');
            $stmt->bindParam(':game_id', $game_id);
            $stmt->execute();
            $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Only proceed if the correct number of players is subscribed
            if (count($players) == MAX_PLAYERS) {
                // Assign the players to teams
                for ($i = 0; $i < MAX_PLAYERS; $i++) {
                    $currentPlayer = $players[$i]; // Current player

                    // Determine the team for the player
                    $team = 0;
                    if ($i == 0 || $i == 3) { // 1st and 4th strongest players
                        $team = 1;
                    } else { // 2nd and 3rd strongest players
                        $team = 2;
                    }

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

                return true;
            }
        }

        return false;
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
            $gameId = $_GET['id'];

            $team1_score = $_POST['team1_score'];
            $team2_score = $_POST['team2_score'];

            self::updateResults($gameId, $team1_score, $team2_score);

            header("Location: /game?id=$gameId");
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

        // Se qualquer um dos resultados for NULL ou 0, então os resultados ainda não foram inseridos
        return (!is_null($team1_score) && $team1_score != 0) && (!is_null($team2_score) && $team2_score != 0);
    }

    public static function finishGame()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Apenas aceitar POST requests
            return;
        }

        $id_jogo = $_POST['game_id'];

        $conn = dbConnect();

        // Search for game score
        $stmt = $conn->prepare('SELECT team1_score, team2_score FROM Jogos WHERE id = :id_jogo');
        $stmt->bindParam(':id_jogo', $id_jogo);
        $stmt->execute();

        $game_scores = $stmt->fetch(PDO::FETCH_ASSOC);

        // check if game ended
        $stmt = $conn->prepare('SELECT status FROM Jogos WHERE id = :id_jogo');
        $stmt->bindParam(':id_jogo', $id_jogo);
        $stmt->execute();

        $status = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($status['status'] != GAME_LOCKED) {
            echo "O jogo já terminou!";
            return;
        }

        // winning team
        $team_winner = $game_scores['team1_score'] > $game_scores['team2_score'] ? 1 : 2;

        // loosing team
        $team_loser = ($team_winner == 1) ? 2 : 1;

        // winning team players
        $stmt = $conn->prepare('SELECT id_utilizador FROM Jogadores_Jogo WHERE id_jogo = :id_jogo AND equipa = :team_winner');
        $stmt->bindParam(':id_jogo', $id_jogo);
        $stmt->bindParam(':team_winner', $team_winner);
        $stmt->execute();

        $winning_players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // uupdate winning team score
        foreach ($winning_players as $player) {

            $stmt = $conn->prepare('UPDATE Ranking SET pontos = pontos + 1, jogos_jogados = jogos_jogados + 1, jogos_ganhos = jogos_ganhos + 1 WHERE id_utilizador = :id_utilizador AND id_liga = (SELECT id_liga FROM Jogos WHERE id = :id_jogo)');
            $stmt->bindParam(':id_utilizador', $player['id_utilizador']);
            $stmt->bindParam(':id_jogo', $id_jogo);
            $stmt->execute();

        }

        // loosing team players
        $stmt = $conn->prepare('SELECT id_utilizador FROM Jogadores_Jogo WHERE id_jogo = :id_jogo AND equipa = :team_loser');
        $stmt->bindParam(':id_jogo', $id_jogo);
        $stmt->bindParam(':team_loser', $team_loser);
        $stmt->execute();

        $losing_players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // update loosing team score
        foreach ($losing_players as $player) {
            $stmt = $conn->prepare('UPDATE Ranking SET jogos_jogados = jogos_jogados + 1, jogos_perdidos = jogos_perdidos + 1 WHERE id_utilizador = :id_utilizador AND id_liga = (SELECT id_liga FROM Jogos WHERE id = :id_jogo)');
            $stmt->bindParam(':id_utilizador', $player['id_utilizador']);
            $stmt->bindParam(':id_jogo', $id_jogo);
            $stmt->execute();
        }

        // change game status to Terminated (0)
        $stmt = $conn->prepare('UPDATE Jogos SET status = 0 WHERE id = :id_jogo');
        $stmt->bindParam(':id_jogo', $id_jogo);
        $stmt->execute();

        header('Location: /game?id=' . $id_jogo);
        exit;
    }





}

