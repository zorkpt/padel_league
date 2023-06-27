<?php
const GAME_STATUS_OPEN = 1;
const GAME_STATUS_CLOSED = 0;
const GAME_STATUS_ONGOING = 2;
class LeagueController
{
    public static function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['league'];
            $description = $_POST['descricao'];
            $id_criador = $_SESSION['user']['id'];
            $data_criacao = date("Y-m-d H:i:s");

            // Gerar um código de convite único para a liga
            $invite_code = self::generateRandomInviteCode();

            if (empty($name) || empty($description)) {
                Session::setFlashMessage('league_error', 'Os campos não podem estar vazios');
                header('Location: /leagues/create');
                exit();
            }

            $conn = dbConnect();
            $stmt = $conn->prepare('INSERT INTO Ligas (nome, descricao, id_criador, data_criacao, codigo_convite) VALUES (:name, :description, :id_criador, :data_criacao, :invite_code )');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id_criador', $id_criador);
            $stmt->bindParam(':data_criacao', $data_criacao);
            $stmt->bindParam(':invite_code', $invite_code); // Adicione o código de convite na inserção da liga
            $stmt->execute();

            // Obtendo o ID da liga que acabamos de inserir
            $liga_id = $conn->lastInsertId();

            // Inserir o criador da liga como membro na tabela Membros_Liga
            $stmt = $conn->prepare('INSERT INTO Membros_Liga (id_utilizador, id_liga, admin) VALUES (:id_utilizador, :id_liga, :admin)');
            $stmt->bindParam(':id_utilizador', $id_criador);
            $stmt->bindParam(':id_liga', $liga_id);
            $stmt->bindValue(':admin', 1);  // Como o usuário é o criador da liga, ele será um admin
            $stmt->execute();

            header('Location: /dashboard'); // depois alterar para a nova liga criada quando essa view estiver a funcionar
        }

        require_once '../views/liga/league_create.php';
    }

// Função para gerar o código de convite aleatório
    public static function generateRandomInviteCode($length = 5) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function index()
    {
        require_once '../views/liga/league.php';
    }

    public static function getLeaguesUser($user_id)
    {
        $conn = dbConnect();
        $stmt = $conn->prepare(
            'SELECT Ligas.id, Ligas.nome, Ligas.descricao, Ligas.data_criacao, COUNT(Membros_Liga.id_utilizador) AS membros_ativos
        FROM Ligas
        LEFT JOIN Membros_Liga ON Ligas.id = Membros_Liga.id_liga
        WHERE Membros_Liga.id_utilizador = :user_id
        GROUP BY Ligas.id;'
        );
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function viewLeague()
    {
        if (isset($_GET['id'])) {
            $league_id = $_GET['id'];
            $user_id = $_SESSION['user']['id'];
            // Verify if user is a member of the league
            if (!self::isUserMemberOfLeague($user_id, $league_id)) {
                Session::setFlashMessage('access_error', 'Você não é um membro desta liga.');
                header('Location: /error');
                exit();
            }

            // Get the league details
            $leagueDetails = self::getLeagueInfo($league_id);

            // Get the League Games
            $leagueGames = self::getLeagueGames($league_id);
            // Get the league members
            $leagueMembers = self::getLeagueMembers($league_id);

            $openLeagueGames = self::getLeagueGames($league_id,GAME_STATUS_OPEN);

            $ongoingLeagueGames = self::getLeagueGames($league_id, GAME_STATUS_ONGOING);

            $inviteCode = self::getInviteCode($league_id);

            $lastFiveGames = self::lastGames($league_id);
            //Get the league rankings
            $ranking = self::getPlayerRankings($league_id);

            require_once '../views/liga/league.php';
        } else {
            header('Location: /error');
        }
    }

    public static function getInviteCode($league_id) {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT codigo_convite FROM Ligas WHERE id = :league_id');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    public static function lastGames($league_id){

        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT * FROM Jogos WHERE id_liga = :league_id ORDER BY data_hora DESC LIMIT 5');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($games as &$game) {
            $players = GameController::getPlayersInGame($game['id']);
            $game['players'] = $players;
        }

        return $game;
    }

    public static function isUserMemberOfLeague($user_id, $league_id)
    {
        // Consulte a base de dados para verificar se o usuário é um membro da liga
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT * FROM Membros_Liga WHERE id_utilizador = :user_id AND id_liga = :league_id');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        $membership = $stmt->fetch(PDO::FETCH_ASSOC);
        return $membership != false;  // retorna true se o usuário for membro, false caso contrário
    }
    public static function getLeagueInfo($league_id) {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT nome, descricao, id_criador, data_criacao FROM Ligas WHERE id = :league_id');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getLeagueMembers($league_id) {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT Utilizadores.nome_utilizador FROM Utilizadores JOIN Membros_Liga ON Utilizadores.id = Membros_Liga.id_utilizador WHERE Membros_Liga.id_liga = :league_id');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getLeagueGames($league_id, $status_filter = null) {
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

        $query .= ' GROUP BY Jogos.id ORDER BY Jogos.data_hora DESC';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':league_id', $league_id);

        if ($status_filter !== null) {
            $stmt->bindParam(':status_filter', $status_filter);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public static function getPlayerRankings($league_id) {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT Jogadores_Jogo.id_utilizador, Utilizadores.nome_utilizador, SUM(Jogadores_Jogo.pontuacao) as total_pontuacao FROM Jogadores_Jogo 
    JOIN Jogos ON Jogadores_Jogo.id_jogo = Jogos.id
    JOIN Utilizadores ON Jogadores_Jogo.id_utilizador = Utilizadores.id
    WHERE Jogos.id_liga = :league_id AND Jogos.status = "realizado"
    GROUP BY Jogadores_Jogo.id_utilizador
    ORDER BY total_pontuacao DESC');
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public static function joinLeague() {
        // Verificando o método da requisição HTTP
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Se for GET, mostrar o formulário para o usuário
            require_once '../views/league/join_form.php';
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Se for POST, processar a adesão à liga
            $invite_code = $_POST['invite_code'];

            // Conectando ao banco de dados
            $conn = dbConnect();

            // Buscando o id da liga através do código de convite
            $stmt = $conn->prepare("SELECT id FROM Ligas WHERE codigo_convite = :invite_code");
            $stmt->bindParam(':invite_code', $invite_code);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                // Se o código de convite não for válido, redirecionar o usuário de volta ao formulário com uma mensagem de erro
                Session::setFlashMessage('league_join_error', 'Código de convite inválido');
                header('Location: /league/join');
                exit();
            }

            $league_id = $result['id'];
            $user_id = $_SESSION['user']['id'];

            // Adicionando o usuário à liga
            $stmt = $conn->prepare("INSERT INTO Membros_Liga (id_utilizador, id_liga) VALUES (:user_id, :league_id)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':league_id', $league_id);
            $stmt->execute();

            // Redirecionando o usuário para a página da liga
            header("Location: /league/show?league_id=$league_id");
        }
    }

}



