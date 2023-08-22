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
            $league_type = $_POST['league_type'];
            $description = $_POST['descricao'];
            $creator_id = $_SESSION['user']['id'];
            if(strlen($name) > 20){
                SessionController::setFlashMessage('league_error', 'O nome da liga não pode ter mais de 20 caracteres');
                header('Location: /leagues/create');
                exit();
            }
            if(strlen($name) < 3){
                SessionController::setFlashMessage('league_error', 'O nome da liga não pode ter menos de 3 caracteres');
                header('Location: /leagues/create');
                exit();
            }
            if (empty($name) || empty($description)) {
                SessionController::setFlashMessage('league_error', 'Os campos não podem estar vazios');
                header('Location: /leagues/create');
                exit();
            }

            if(self::checkString($name) || self::checkString($description)){
                SessionController::setFlashMessage('league_error', 'Os campos não podem conter caracteres especiais');
                header('Location: /leagues/create');
                exit();
            }

            $invite_code = self::generateRandomInviteCode();

            $league_id = self::insertLeague($name, $description, $league_type, $creator_id, $invite_code);

            // insert league creator as member of the league
            self::addMemberToLeague($creator_id, $league_id, 1); // 1 for admin
            self::addUserToRanking($creator_id, $league_id);
            header('Location: /league?id=' . $league_id);
        }
        require_once '../views/liga/league_create.php';
    }

    public static function checkString($string) {
        return preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string);
    }
    private static function insertLeague($name, $description, $league_type, $creator_id, $invite_code)
    {
        $creation_date = date("Y-m-d H:i:s");
        $conn = dbConnect();
        $stmt = $conn->prepare('INSERT INTO Ligas (nome, descricao, tipo_liga, id_criador, data_criacao, codigo_convite) VALUES (:name, :description, :league_type, :creator_id, :creation_date, :invite_code)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':league_type', $league_type);
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $league_id = $_GET['id'];
                $user_id = $_SESSION['user']['id'];
                // Ensure the user is a member of the league
                if (!self::isUserMemberOfLeague($user_id, $league_id)) {
                    SessionController::setFlashMessage('access_error', 'Tu não és membro desta liga.');
                    header('Location: /error');
                    exit();
                }

                $invite_email = $_POST['email'];
                $leagueDetails = self::getLeagueInfo($league_id);

                $invitationSent = self::inviteToLeague($league_id, $leagueDetails['nome'], $invite_email);

                if ($invitationSent) {
                    SessionController::setFlashMessage('success', 'Convite enviado com sucesso para ' . $invite_email);
                } else {
                    SessionController::setFlashMessage('error', 'Ocorreu um erro ao enviar o convite. Por favor tente novamente.');
                }

                header("Location: /league?id=$league_id");
                exit();
            }

            SessionController::setFlashMessage('error', 'Por favor insira um e-mail válido.');
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();

        } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['id'])) {
                $league_id = $_GET['id'];
                $user_id = $_SESSION['user']['id'];
                $leagueDetails = self::getLeagueInfo($league_id);
                $joinRequests = self::getLeagueJoinRequests($league_id);
                // Ensure the league exists
                if (!$leagueDetails) {
                    SessionController::setFlashMessage('error', 'Liga não encontrada.');
                    header('Location: /error');
                    exit();
                }

                $isLeagueMember = self::isUserMemberOfLeague($user_id, $league_id);
                $isVisitor = false;
                // Verify if user is a member of the league
                if (!$isLeagueMember && $leagueDetails['tipo_liga'] == 'privada') {
                    SessionController::setFlashMessage('access_error', 'Tu não és membro desta liga/Liga privada.');
                    header('Location: /error');
                    exit();
                }

                // Verify if user is a visitor of the league
                if (!$isLeagueMember && $leagueDetails['tipo_liga'] == 'publica') {
                    $isVisitor = true;
                }

                // Get all league data
                $leagueGames = self::getLeagueGames($league_id);
                $leagueMembers = self::getLeagueMembers($league_id);

                // Get the last 3 games results for each member
                foreach ($leagueMembers as &$member) {
                    $gamesResults = self::getLeagueMemberLastGamesResults($league_id, $member['id']);

                    $last3Results = [];
                    foreach ($gamesResults as $game) {
                        if ($game['equipa_jogador'] == 1) {
                            // if player was in team 1
                            $last3Results[] = $game['score_equipa1'] > $game['score_equipa2'] ? 'V' : 'D';
                        } else {
                            // if player was in team 2
                            $last3Results[] = $game['score_equipa2'] > $game['score_equipa1'] ? 'V' : 'D';
                        }
                    }

                    // Guarantee that the array has 3 elements
                    $member['last_3_results'] = array_slice($last3Results, 0, 3);
                }
                unset($member);

                $openLeagueGames = self::getLeagueGames($league_id, GAME_STATUS_OPEN);
                $ongoingLeagueGames = self::getLeagueGames($league_id, GAME_STATUS_ONGOING);
                $inviteCode = self::getInviteCode($league_id);
                $lastFiveGames = self::lastGames($league_id, 5);
                $ranking = self::getPlayerRankings($league_id);

                // add W/L info to ranking array
                foreach ($ranking as &$rank) {
                    foreach ($leagueMembers as $member) {
                        if ($rank['id_utilizador'] == $member['id']) {
                            $rank['last_3_results'] = $member['last_3_results'];
                            break;
                        }
                    }
                }
                unset($rank);

                if ($leagueDetails['tipo_liga'] == 'publica') {
                    $leagueDetails['tipo'] = 'Pública';
                } else {
                    $leagueDetails['tipo'] = 'Privada';
                }

                require_once '../views/liga/league.php';
            } else {
                SessionController::setFlashMessage('error', 'Endereço Inválido');
                header('Location: /error');
            }
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
        $stmt = $conn->prepare('SELECT 1 FROM Membros_Liga WHERE id_utilizador = :user_id AND id_liga = :league_id');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }


    public static function getLeagueInfo($league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT id, nome, descricao, tipo_liga, id_criador, data_criacao FROM Ligas WHERE id = :league_id');
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
        $stmt = $conn->prepare('SELECT Utilizadores.nome_utilizador, Utilizadores.id, Utilizadores.avatar, 
        Membros_Liga.data_admissao 
        FROM Utilizadores 
        JOIN Membros_Liga ON Utilizadores.id = Membros_Liga.id_utilizador 
        WHERE Membros_Liga.id_liga = :league_id');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getLeagueMemberLastGamesResults($league_id, $player_id){
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT
            j.id AS jogo_id,
            jj.equipa AS equipa_jogador,
            j.team1_score AS score_equipa1,
            j.team2_score AS score_equipa2
        FROM Jogadores_Jogo AS jj
        JOIN Jogos AS j ON jj.id_jogo = j.id
        WHERE jj.id_utilizador = :player_id AND j.id_liga = :league_id AND j.status = 0
        ORDER BY j.data_hora DESC
        LIMIT 3;');
        $stmt->bindParam(':player_id', $player_id);
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
        $stmt = $conn->prepare('SELECT Ranking.id_utilizador, Utilizadores.nome_utilizador, 
       Utilizadores.avatar, Ranking.pontos as total_pontuacao, 
       Ranking.jogos_jogados, Ranking.jogos_ganhos as vitorias, 
       Ranking.jogos_perdidos as derrotas, COALESCE((Ranking.jogos_ganhos / NULLIF(Ranking.jogos_jogados, 0) * 100), 0) as win_rate
 FROM Ranking
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

        return $stmt->execute();
    }

    private static function addUserToRanking($user_id, $league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare("INSERT INTO Ranking (id_utilizador, id_liga, pontos, jogos_jogados, jogos_ganhos, jogos_perdidos) 
                            VALUES (:user_id, :league_id, 0, 0, 0, 0)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':league_id', $league_id);

        return $stmt->execute();
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
            self::checkLeagueCreator($league_id, $user_id);


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newLeagueName = $_POST['league-name'];
                $newLeagueDescription = $_POST['description'];
                $newLeagueType = $_POST['tipo_liga'];
                if (empty($newLeagueDescription) || empty($newLeagueName)) {
                    SessionController::setFlashMessage('error', 'Não pode ficar vazio.');
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit;
                }
                $typeUpdateStatus = self::updateLeagueType($league_id, $newLeagueType);
                $nameUpdateStatus = self::updateLeagueName($league_id, $newLeagueName);
                $descriptionUpdateStatus = self::updateLeagueDescription($league_id, $newLeagueDescription);

                if ($nameUpdateStatus && $descriptionUpdateStatus && $typeUpdateStatus) {
                    SessionController::setFlashMessage('league_settings_success', 'Informação da liga atualizada com sucesso.');
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
            $league_id = SessionController::getLeagueForDeletion();
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

    public static function checkLeagueCreator($league_id, $user_id)
    {
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

    public static function inviteToLeague($leagueId, $leagueName, $email)
    {
        $conn = dbConnect();

        // Generate a unique invitation code
        $invitationCode = bin2hex(random_bytes(16));

        // Insert into pending invites table
        $stmt = $conn->prepare("INSERT INTO Convites_Pendentes (id_liga, email, codigo_convite) VALUES (:league_id, :email, :invitation_code)");
        $stmt->bindParam(':league_id', $leagueId);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':invitation_code', $invitationCode);
        $stmt->execute();

        // Send the invitation email
        $mailer = new MailerController();
        $invitationLink = "https://liga-padel.pt/accept-invite?code=" . $invitationCode;

        return $mailer->sendLeagueInvitationEmail($email, $leagueName, $invitationLink);
    }


    public static function acceptInvitation()
    {
        // Check if the invitation code is provided
        if (!isset($_GET['code'])) {
            SessionController::setFlashMessage('error', 'Código de convite inválido.');
            header('Location: /error');
            exit();
        }

        $inviteCode = $_GET['code'];

        // Check if the user is logged in
        if (!isset($_SESSION['user'])) {
            // User is not logged in. Store the invite code in the session and redirect to the login page
            $_SESSION['invite_code'] = $inviteCode;
            header('Location: /login');
            exit();
        }

        // User is logged in. Process the invitation
        $userId = $_SESSION['user']['id'];

        if (self::processInvitation($userId, $inviteCode)) {
            // If the invitation is processed successfully, remove the invite code from the session
            unset($_SESSION['invite_code']);
            SessionController::setFlashMessage('success', 'Convite aceite com sucesso!');
            header('Location: /dashboard');
        } else {
            SessionController::setFlashMessage('error', 'Falha ao aceitar o convite.');
            header('Location: /error');
        }
    }

    public static function processInvitation($userId, $inviteCode)
    {
        $conn = dbConnect();

        // Get the invitation data
        $stmt = $conn->prepare("SELECT * FROM Convites_Pendentes WHERE codigo_convite = :invite_code");
        $stmt->bindParam(':invite_code', $inviteCode);
        $stmt->execute();

        $invite = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$invite) {
            // The invite code is not valid
            return false;
        }

        // Check if the invite is for this user
        if ($invite['email'] != $_SESSION['user']['email']) {
            // The invite is not for this user
            return false;
        }

        // The invite is valid and for this user. Add the user to the league
        $stmt = $conn->prepare("INSERT INTO Membros_Liga (id_liga, id_utilizador) VALUES (:league_id, :user_id)");
        $stmt->bindParam(':league_id', $invite['id_liga']);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        // Delete the invite
        $stmt = $conn->prepare("DELETE FROM Convites_Pendentes WHERE codigo_convite = :invite_code");
        $stmt->bindParam(':invite_code', $inviteCode);
        $stmt->execute();

        return true;
    }

    public static function getTotalLeagues()
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT COUNT(*) FROM Ligas');
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    private static function updateLeagueType($leagueId, $leagueType): bool
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('UPDATE Ligas SET tipo_liga = :league_type WHERE id = :league_id');
        $stmt->bindParam(':league_type', $leagueType);
        $stmt->bindParam(':league_id', $leagueId);

        return $stmt->execute();
    }

    public static function getLeagueType($league_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT tipo_liga FROM Ligas WHERE id = :league_id');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public static function publicLeagues()
    {
        SessionController::start();
        checkLoggedIn();

        $conn = dbConnect();
        $stmt = $conn->prepare('
    SELECT Ligas.id, Ligas.nome, Ligas.descricao, Ligas.data_criacao, COUNT(Membros_Liga.id_liga) AS membros_ativos 
    FROM Ligas 
    LEFT JOIN Membros_Liga ON Ligas.id = Membros_Liga.id_liga
    WHERE Ligas.tipo_liga = "publica"
    GROUP BY Ligas.id, Ligas.nome, Ligas.descricao, Ligas.data_criacao
');

        $stmt->execute();
        $openLeagues = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once '../views/liga/public_leagues.php';

    }


    public static function requestJoinLeague()
    {
        SessionController::start();
        checkLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $leagueId = $_GET['liga'];
            $leagueData = self::getLeagueInfo($leagueId);

            // Check if the league exists
            if (!$leagueData) {
                SessionController::setFlashMessage('error', 'Liga não encontrada.');
                header('Location: /error');
                exit();
            }

            // Check if the league is public
            if ($leagueData['tipo_liga'] != 'publica') {
                SessionController::setFlashMessage('error', 'Não podes pedir para entrar numa liga privada.');
                header('Location: /error');
                exit();
            }

            // Check if the user is already a member of the league
            if (self::isUserMemberOfLeague($leagueId, $_SESSION['user']['id'])) {
                SessionController::setFlashMessage('error', 'Já és membro desta liga.');
                header('Location: /error');
                exit();
            }

            // Check if the user has a pending request to join the league
            if (self::isUserPendingMemberOfLeague($leagueId, $_SESSION['user']['id'])) {
                SessionController::setFlashMessage('error', 'Já tens um pedido pendente para entrar nesta liga.');
                header('Location: /error');
                exit();
            }

            require '../views/liga/request_join_league.php';

        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $leagueAdmin = self::getLeagueAdmin($_POST['league_id']);
            $conn = dbConnect();
            $stmt = $conn->prepare('INSERT INTO Lista_Espera (id_utilizador, id_liga, data_pedido, status, mensagem) VALUES (:user_id, :league_id, NOW(), "pendente", :message)');
            $stmt->bindParam(':user_id', $_SESSION['user']['id']);
            $stmt->bindParam(':league_id', $_POST['league_id']);
            // need to sanitaze message here
            $stmt->bindParam(':message', $_POST['message']);
            $stmt->execute();
            NotificationController::create($leagueAdmin, 'Tens novos pedidos de adesão', '/league?id=' . $_POST['league_id']);
            SessionController::setFlashMessage('success', 'Pedido enviado com sucesso!');
            header('Location: /dashboard');
        } else {
            SessionController::setFlashMessage('error', 'Método não suportado.');
            header('Location: /error');
        }

    }

    private static function getLeagueAdmin($leagueId)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT id_utilizador FROM Membros_Liga WHERE id_liga = :league_id AND admin = 1');
        $stmt->bindParam(':league_id', $leagueId);
        $stmt->execute();

        return $stmt->fetchColumn();
    }


    private static function isUserPendingMemberOfLeague($leagueId, $userId)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT COUNT(*) FROM Lista_Espera WHERE id_liga = :league_id AND id_utilizador = :user_id AND status = "pendente"');
        $stmt->bindParam(':league_id', $leagueId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }


    private static function getLeagueJoinRequests($leagueId)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('
        SELECT Lista_Espera.id, Lista_Espera.id_utilizador, Lista_Espera.id_liga, Lista_Espera.data_pedido, Lista_Espera.status, Lista_Espera.mensagem, Utilizadores.nome_utilizador, Utilizadores.email
        FROM Lista_Espera
        INNER JOIN Utilizadores ON Lista_Espera.id_utilizador = Utilizadores.id
        WHERE Lista_Espera.id_liga = :league_id AND Lista_Espera.status = "pendente"
    ');
        $stmt->bindParam(':league_id', $leagueId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function isLeagueAdmin($leagueId, $userId)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT COUNT(*) FROM Membros_Liga WHERE id_liga = :league_id AND id_utilizador = :user_id AND admin = 1');
        $stmt->bindParam(':league_id', $leagueId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public static function acceptRequestJoinLeague()
    {
        SessionController::start();
        checkLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            SessionController::setFlashMessage('error', 'Método não suportado.');
            header('Location: /error');
            exit();
        }
        $requestId = $_POST['request_id'];
        $leagueId = $_POST['league_id'];
        $userId = $_POST['user_id'];

        if (!self::isLeagueAdmin($leagueId, $_SESSION['user']['id'])) {
            SessionController::setFlashMessage('error', 'Não tens permissões para aceitar pedidos de entrada nesta liga.');
            header('Location: /error');
            exit();
        }

        if(self::isUserMemberOfLeague($userId, $leagueId)) {
            SessionController::setFlashMessage('error', 'O utilizador já pertence à liga.');
            header('Location: /error');
            exit();
        }

        if (!self::addUserToLeague($userId, $leagueId)) {
            SessionController::setFlashMessage('error', 'Ocorreu um erro ao adicionar o utilizador à liga.');
            header('Location: /error');
            exit();
        }

        if (!self::addUserToRanking($userId, $leagueId)) {
            SessionController::setFlashMessage('error', 'Ocorreu um erro ao adicionar o utilizador ao ranking.');
            header('Location: /error');
            exit();
        }

        if (!self::acceptFromWaitList($requestId)) {
            SessionController::setFlashMessage('error', 'Ocorreu um erro ao aceitar o pedido.');
            header('Location: /error');
            exit();
        }

        NotificationController::create($userId, 'O teu pedido de adesão foi aceite', '/league?id=' . $leagueId);
        header("Location: /league?id=$leagueId");

    }

    private static function acceptFromWaitList($requestID)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('
            UPDATE Lista_Espera
            SET status = "aceite"
            WHERE id = :request_id
        ');
        $stmt->bindParam(':request_id', $requestID);

        return $stmt->execute();
    }

    private static function deleteFromWaitList($requestID)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('
            DELETE FROM Lista_Espera
            WHERE id = :request_id
        ');
        $stmt->bindParam(':request_id', $requestID);

        return $stmt->execute();
    }

    public static function rejectRequestJoinLeague()
    {
        SessionController::start();
        checkLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            SessionController::setFlashMessage('error', 'Método não suportado.');
            header('Location: /error');
            exit();
        }
        $requestId = $_POST['request_id'];
        $leagueId = $_POST['league_id'];

        if (!self::isLeagueAdmin($leagueId, $_SESSION['user']['id'])) {
            SessionController::setFlashMessage('error', 'Não tens permissões para rejeitar pedidos de entrada nesta liga.');
            header('Location: /error');
            exit();
        }

        if (!self::deleteFromWaitList($requestId)) {
            SessionController::setFlashMessage('error', 'Ocorreu um erro ao rejeitar o pedido.');
            header('Location: /error');
            exit();
        }

        header("Location: /league?id=$leagueId");
    }


    public static function leaveLeague(){
        SessionController::start();
        checkLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            SessionController::setFlashMessage('error', 'Método não suportado.');
            header('Location: /error');
            exit();
        }
        $leagueAdmin = self::getLeagueAdmin($_POST['league_id']);
        $leagueId = $_POST['league_id'];
        $userId = $_SESSION['user']['id'];
        $userInfo = UserController::getUserData($userId);

        if (!self::isUserMemberOfLeague($userId, $leagueId)) {
            SessionController::setFlashMessage('error', 'Não pertences a esta liga.');
            header('Location: /error');
            exit();
        }

        if (!self::removeUserFromLeague($userId, $leagueId)) {
            SessionController::setFlashMessage('error', 'Ocorreu um erro ao sair da liga.');
            header('Location: /error');
            exit();
        }

        if (!self::removeUserFromRanking($userId, $leagueId)) {
            SessionController::setFlashMessage('error', 'Ocorreu um erro ao sair do ranking.');
            header('Location: /error');
            exit();
        }

        if(!GameController::removePlayerFromGames($userId, $leagueId)){
            SessionController::setFlashMessage('error', 'Ocorreu um erro ao sair dos jogos.');
            header('Location: /error');
            exit();
        }

        NotificationController::create($leagueAdmin, $userInfo['nome_utilizador'] . ' saiu da liga', '/league?id=' . $leagueId);
        SessionController::setFlashMessage('success', 'Saiu da liga com sucesso.');
        header("Location: /dashboard");
    }

    private static function removeUserFromLeague($userId, $leagueId)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('
            DELETE FROM Membros_Liga
            WHERE id_utilizador = :user_id AND id_liga = :league_id
        ');
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':league_id', $leagueId);

        return $stmt->execute();
    }

    private static function removeUserFromRanking($userId, $leagueId)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare('
            DELETE FROM Ranking
            WHERE id_utilizador = :user_id AND id_liga = :league_id
        ');
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':league_id', $leagueId);

        return $stmt->execute();
    }
}



