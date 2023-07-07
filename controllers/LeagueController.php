<?php
const GAME_STATUS_OPEN = 1;
const GAME_STATUS_CLOSED = 0;
const GAME_STATUS_ONGOING = 2;
class LeagueController
{
    public static function create()
    {
        checkLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['league'];
            $description = $_POST['descricao'];
            $creator_id = $_SESSION['user']['id'];

            if (empty($name) || empty($description)) {
                SessionController::setFlashMessage('league_error', 'Os campos não podem estar vazios');
                header('Location: /leagues/create');
                exit();
            }

            $invite_code = self::generateRandomInviteCode();

            $league_id = self::insertLeague($name, $description, $creator_id, $invite_code);

            // insert league creator as member of the league
            self::addMemberToLeague($creator_id, $league_id, 1); // 1 for admin

            header('Location: /dashboard');
        }
        require_once '../views/liga/league_create.php';
    }

    private static function insertLeague($name, $description, $creator_id, $invite_code)
    {
        $creation_date = date("Y-m-d H:i:s");
        $conn = dbConnect();
        $stmt = $conn->prepare('INSERT INTO Ligas (nome, descricao, id_criador, data_criacao, codigo_convite) VALUES (:name, :description, :creator_id, :creation_date, :invite_code)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':creator_id', $creator_id);
        $stmt->bindParam(':creation_date', $creation_date);
        $stmt->bindParam(':invite_code', $invite_code);
        $stmt->execute();
        return $conn->lastInsertId();
    }

    private static function addMemberToLeague($user_id, $league_id, $admin)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('INSERT INTO Membros_Liga (id_utilizador, id_liga, admin) VALUES (:user_id, :league_id, :admin)');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':league_id', $league_id);
        $stmt->bindValue(':admin', $admin);  // 1 for admin 0 for user
        $stmt->execute();
    }

    public static function generateRandomInviteCode($length = 5)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function getLeagueUsers($liga_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT id_utilizador FROM Membros_Liga WHERE id_liga = :liga_id;');
        $stmt->bindParam(':liga_id', $liga_id, PDO::PARAM_INT);
        $stmt->execute();

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row['id_utilizador'];
        }

        return $users;
    }

    public static function getLeaguesUser($user_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare(
            'SELECT Ligas.id, Ligas.nome, Ligas.descricao, Ligas.data_criacao, COUNT(DISTINCT Membros_Liga_Count.id_utilizador) AS membros_ativos
FROM Ligas
JOIN Membros_Liga ON Ligas.id = Membros_Liga.id_liga
LEFT JOIN Membros_Liga AS Membros_Liga_Count ON Ligas.id = Membros_Liga_Count.id_liga
WHERE Membros_Liga.id_utilizador = :user_id
GROUP BY Ligas.id;'

        );
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function viewLeague()
    {
        checkLoggedIn();

        if (isset($_GET['id'])) {
            $league_id = $_GET['id'];
            $user_id = $_SESSION['user']['id'];
            // Verify if user is a member of the league
            if (!self::isUserMemberOfLeague($user_id, $league_id)) {
                SessionController::setFlashMessage('access_error', 'Tu não és membro desta liga.');
                header('Location: /error');
                exit();
            }

            // Get all league data
            $leagueDetails = self::getLeagueInfo($league_id);
            $leagueGames = self::getLeagueGames($league_id);
            $leagueMembers = self::getLeagueMembers($league_id);
            $openLeagueGames = self::getLeagueGames($league_id, GAME_STATUS_OPEN);
            $ongoingLeagueGames = self::getLeagueGames($league_id, GAME_STATUS_ONGOING);
            $inviteCode = self::getInviteCode($league_id);
            $lastFiveGames = self::lastGames($league_id, 5);
            $ranking = self::getPlayerRankings($league_id);

            require_once '../views/liga/league.php';

        } else {
            SessionController::setFlashMessage('error', 'Endereço Inválido');
            header('Location: /error');
        }
    }

    public static function getInviteCode($league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT codigo_convite FROM Ligas WHERE id = :league_id');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public static function lastGames($league_id, $numberOfGames = null)
    {
        $conn = dbConnect();
        $query = 'SELECT * FROM Jogos WHERE id_liga = :league_id AND status = :game_status ORDER BY data_hora DESC';
        // If number of games is provided add LIMIT clause
        if ($numberOfGames !== null) {
            $query .= ' LIMIT :numberOfGames';
        }

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':league_id', $league_id);
        $stmt->bindValue(':game_status', 0);

        // Bind value only if provided
        if ($numberOfGames !== null) {
            $stmt->bindValue(':numberOfGames', (int)$numberOfGames, PDO::PARAM_INT);
        }

        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($games as &$game) {
            $players = GameController::getPlayersInGame($game['id']);
            $game['players'] = $players;
        }

        return $games;
    }


    public static function isUserMemberOfLeague($user_id, $league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT * FROM Membros_Liga WHERE id_utilizador = :user_id AND id_liga = :league_id');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        $membership = $stmt->fetch(PDO::FETCH_ASSOC);
        return $membership != false;  // return true if its a member, false otherwise
    }

    public static function getLeagueInfo($league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT nome, descricao, id_criador, data_criacao FROM Ligas WHERE id = :league_id');
        $stmt->bindParam(':league_id', $league_id, PDO::PARAM_INT);
        $stmt->execute();
        $leagueInfo = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($leagueInfo === false) {
            return false;
        }

        // get creator info
        $creatorData = UserController::getUserData($leagueInfo['id_criador']);

        return array_merge($leagueInfo, $creatorData);
    }

    public static function getLeagueMembers($league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT Utilizadores.nome_utilizador, Utilizadores.id, Utilizadores.avatar, Membros_Liga.data_admissao FROM Utilizadores JOIN Membros_Liga ON Utilizadores.id = Membros_Liga.id_utilizador WHERE Membros_Liga.id_liga = :league_id');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getLeagueGames($league_id, $status_filter = null)
    {
        $conn = dbConnect();
        $query = 'SELECT Jogos.id, Jogos.local, Jogos.data_hora, Jogos.status, 
          GROUP_CONCAT(Utilizadores.nome_utilizador ORDER BY Jogadores_Jogo.equipa ASC) as jogadores 
          FROM Jogos 
          LEFT JOIN Jogadores_Jogo ON Jogos.id = Jogadores_Jogo.id_jogo
          LEFT JOIN Utilizadores ON Jogadores_Jogo.id_utilizador = Utilizadores.id
          WHERE Jogos.id_liga = :league_id';

        if ($status_filter !== null) {
            $query .= ' AND Jogos.status = :status_filter';
        }

        $query .= ' GROUP BY Jogos.id ORDER BY Jogos.data_hora ASC';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':league_id', $league_id);

        if ($status_filter !== null) {
            $stmt->bindParam(':status_filter', $status_filter);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function getPlayerRankings($league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT Ranking.id_utilizador, Utilizadores.nome_utilizador, Utilizadores.avatar, Ranking.pontos as total_pontuacao, Ranking.jogos_jogados, Ranking.jogos_ganhos as vitorias, Ranking.jogos_perdidos as derrotas, (Ranking.jogos_ganhos / Ranking.jogos_jogados * 100) as win_rate FROM Ranking
JOIN Utilizadores ON Ranking.id_utilizador = Utilizadores.id
WHERE Ranking.id_liga = :league_id 
ORDER BY total_pontuacao DESC');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // adds a rank key based on position in array
        foreach ($results as $key => $result) {
            $results[$key]['rank'] = $key + 1;
        }

        return $results;
    }

    public static function getPlayerRankingInLeague($league_id, $user_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT Ranking.id_utilizador, Utilizadores.nome_utilizador, Utilizadores.avatar, Ranking.pontos as total_pontuacao, Ranking.jogos_jogados, Ranking.jogos_ganhos as vitorias, Ranking.jogos_perdidos as derrotas, (Ranking.jogos_ganhos / Ranking.jogos_jogados * 100) as win_rate FROM Ranking
JOIN Utilizadores ON Ranking.id_utilizador = Utilizadores.id
WHERE Ranking.id_liga = :league_id 
ORDER BY total_pontuacao DESC');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // adds a rank key based on position in array
        foreach ($results as $key => $result) {
            $results[$key]['rank'] = $key + 1;
        }

        // return only the player specified
        foreach ($results as $result) {
            if ($result['id_utilizador'] == $user_id) {
                return $result;
            }
        }

        // return null if the player is not found
        return null;
    }


    public static function joinLeague()
    {
        checkLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            require_once '../views/liga/join.php';
        } else {
            // Process POST request
            $invite_code = $_POST['invite_code'] ?? null;
            $user_id = $_SESSION['user']['id'];

            // Check if invite code is given
            if (!$invite_code) {
                SessionController::setFlashMessage('league_join_error', 'Código de convite ausente');
                header('Location: /league/join');
                exit();
            }

            $league_id = self::getLeagueIdFromInvite($invite_code);

            // Check if invite code is valid
            if (!$league_id) {
                SessionController::setFlashMessage('league_join_error', 'Código de convite inválido');
                header('Location: /league/join');
                exit();
            }

            // Check if user is already in the league
            if (self::isUserInLeague($user_id, $league_id)) {
                SessionController::setFlashMessage('league_join_error', 'Já estás inscrito nessa liga');
                header('Location: /league/join');
                exit();
            }

            // adding user to the league
            self::addUserToLeague($user_id, $league_id);

            // adding user to league ranking with all 0
            self::addUserToRanking($user_id, $league_id);

            header("Location: /league?id=$league_id");
        }
    }

    private static function getLeagueIdFromInvite($invite_code)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare("SELECT id FROM Ligas WHERE codigo_convite = :invite_code");
        $stmt->bindParam(':invite_code', $invite_code);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['id'] ?? null;
    }

    private static function isUserInLeague($user_id, $league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare("SELECT 1 FROM Membros_Liga WHERE id_utilizador = :user_id AND id_liga = :league_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    private static function addUserToLeague($user_id, $league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare("INSERT INTO Membros_Liga (id_utilizador, id_liga) VALUES (:user_id, :league_id)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
    }

    private static function addUserToRanking($user_id, $league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare("INSERT INTO Ranking (id_utilizador, id_liga, pontos, jogos_jogados, jogos_ganhos, jogos_perdidos) 
                            VALUES (:user_id, :league_id, 0, 0, 0, 0)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
    }

    public static function settings()
    {
        checkLoggedIn();

        if (isset($_GET['id'])) {
            $league_id = $_GET['id'];
            $user_id = $_SESSION['user']['id'];

            // Verify if user is a member of the league
            if (!self::isUserMemberOfLeague($user_id, $league_id)) {
                SessionController::setFlashMessage('access_error', 'Tu não és membro desta liga.');
                header('Location: /error');
                exit();
            }

            $leagueDetails = self::getLeagueInfo($league_id);

            // Check if it is admin of the league
            self::checkLeagueCreator($league_id,$user_id);


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newLeagueName = $_POST['league-name'];
                $newLeagueDescription = $_POST['description'];

                if (empty($newLeagueDescription) || empty($newLeagueName)) {
                    SessionController::setFlashMessage('error', 'Não pode ficar vazio.');
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit;
                }

                $nameUpdateStatus = self::updateLeagueName($league_id, $newLeagueName);
                $descriptionUpdateStatus = self::updateLeagueDescription($league_id, $newLeagueDescription);

                if ($nameUpdateStatus && $descriptionUpdateStatus) {
                    header('Location: /league?id=' . $league_id);
                    exit();
                } else {
                    SessionController::setFlashMessage('error', 'Erro ao atualizar a informação da liga.');
                }
            }
        }

        require_once '../views/liga/settings.php';
    }


    public static function updateLeagueName($league_id, $newLeagueName)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare("UPDATE Ligas SET nome = :newName WHERE id = :league_id");
        $stmt->bindParam(':newName', $newLeagueName, PDO::PARAM_STR);
        $stmt->bindParam(':league_id', $league_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function updateLeagueDescription($league_id, $newLeagueDescription)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare("UPDATE Ligas SET descricao = :newDescription WHERE id = :league_id");
        $stmt->bindParam(':newDescription', $newLeagueDescription, PDO::PARAM_STR);
        $stmt->bindParam(':league_id', $league_id, PDO::PARAM_INT);

        // Returns true if the update was successful
        return $stmt->execute();
    }


    public static function confirmDelete()
    {
        checkLoggedIn();
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
            $league_id = $_GET['id'];
            $user_id = $_SESSION['user']['id'];


            // Verify if user is the creator of the league
            $leagueDetails = self::getLeagueInfo($league_id);
            if ($user_id != $leagueDetails['id_criador']) {
                SessionController::setFlashMessage('access_error', 'Não és administrador desta liga.');
                header('Location: /error');
                exit;
            }
            require_once '../views/liga/confirm_delete.php';
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = $_POST['password'];
            $user_id = $_POST['user_id'];
            $league_id = $_POST['league_id'];

            if (UserController::verifyPassword($user_id, $password)) {
                self::deleteLeague($league_id);
                SessionController::setFlashMessage('success', 'A liga foi eliminada com sucesso.');
                header('Location: /dashboard');
                exit;
            } else {
                SessionController::setFlashMessage('error', 'A palavra-passe está errada.');
                require_once '../views/liga/confirm_delete.php';
            }
        } else {
            // If it's neither a GET nor a POST request, redirect to the error page
            header('Location: /error');
            exit;
        }
    }


    public static function deleteLeague($league_id)
    {
        $conn = dbConnect();

        // Delete the league
        $stmt = $conn->prepare('DELETE FROM Ligas WHERE id = :league_id');
        $stmt->bindParam(':league_id', $league_id, PDO::PARAM_INT);

        return $stmt->execute();
    }


    public static function checkLeagueMembership($league_id, $user_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT * FROM Membros_Liga WHERE id_liga = :league_id AND id_utilizador = :user_id');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Fetch the league membership from the returned results
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function checkLeagueCreator($league_id, $user_id){
        $leagueDetails = self::getLeagueInfo($league_id);
        if (!isset($leagueDetails['id_criador'])) {
            SessionController::setFlashMessage('access_error', 'Liga inválida.');
            header('Location: /error');
            exit;
        }

        if ($user_id != $leagueDetails['id_criador']) {
            SessionController::setFlashMessage('access_error', 'Não és administrador desta liga.');
            header('Location: /error');
            exit;
        }
    }
}



