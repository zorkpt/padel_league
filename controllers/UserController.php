<?php

class UserController
{
    public static function register()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $created_date = date("Y-m-d H:i:s");

            if (empty($username)) {
                $errors['username'] = 'Campo obrigatório';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email)) {
                $errors['email'] = 'E-Mail Inválido';
            }
            if (empty($password)) {
                $errors['password'] = 'Campo obrigatório';
            }

            if (empty($errors)) {


                $hashed_password = password_hash($password, PASSWORD_DEFAULT);


                $conn = dbConnect();

                $stmt = $conn->prepare("INSERT INTO Utilizadores (nome_utilizador, email, password_hash, data_registo) VALUES (:username, :email, :password, :data_registo)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam('data_registo', $created_date);

                $stmt->execute();


                header('Location: /login');
            } else {
                $_SESSION['errors'] = $errors;
            }


        }

        require_once '../views/user/register.php';
    }

    public static function login()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $conn = dbConnect();
            $stmt = $conn->prepare('SELECT id, nome_utilizador, password_hash, email FROM Utilizadores WHERE nome_utilizador = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch();
            unset($user['password']);


            if ($user && password_verify($password, $user['password_hash'])) {

                session_start();
                $_SESSION['user'] = $user;

                header('Location: /');
                exit;
            } else {
                $errors['login'] = 'Nome de usuário ou senha incorretos.';
                $_SESSION['errors'] = $errors;
            }
        }

        require_once '../views/user/login.php';
    }

    public static function changePassword()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $old_password = $_POST['old_password'];
            $new_password = $_POST['new_password'];
            $confirm_new_password = $_POST['confirm_new_password'];

            if ($new_password != $confirm_new_password) {
                Session::setFlashMessage('password', 'As senhas não são iguais');
                header("Location: /settings");
                exit();
            }

            $conn = dbConnect();
            $stmt = $conn->prepare('SELECT password_hash FROM Utilizadores where  id = :id');
            $stmt->bindParam(':id', $_SESSION['user']['id']);
            $stmt->execute();

            $user = $stmt->fetch();

            if (!password_verify($old_password, $user['password_hash'])) {
                Session::setFlashMessage('password', 'A senha antiga está incorreta.');
                header("Location: /settings");
                exit();
            }


            $newPasswordHash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('UPDATE Utilizadores SET password_hash = :password where id = :id');
            $stmt->bindParam(':id', $_SESSION['user']['id']);
            $stmt->bindParam(':password', $newPasswordHash);
            $stmt->execute();

            Session::setFlashMessage('success_message', 'Senha alterada com sucesso.');
            header('Location: /settings');

        }

    }

    public static function changeEmail()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newMail = $_POST['email'];

            if(!filter_var($newMail, FILTER_VALIDATE_EMAIL) || empty($newMail)) {
                Session::setFlashMessage('email','Formato de e-mail inválido.');
                header('Location: /settings');
                exit();
            }

            $conn = dbConnect();
            $stmt = $conn->prepare('SELECT email FROM Utilizadores where email = :email');
            $stmt->bindParam(':email', $newMail);
            $stmt->execute();
            $existingEmail = $stmt->fetch();

            if ($existingEmail) {

                Session::setFlashMessage('email', 'Este e-mail já está em uso.');
                header('Location: /settings');
                exit();
            }


            $stmt = $conn->prepare('UPDATE Utilizadores SET email = :email where id = :id');
            $stmt->bindParam(':id', $_SESSION['user']['id']);
            $stmt->bindParam(':email', $newMail);
            $stmt->execute();

            $_SESSION['user']['email'] = $newMail;

            Session::setFlashMessage('success_mail_message', 'E-mail alterado com sucesso.');
            header('Location: /settings');

        }
    }

    public static function dashboard()
    {
        $user_id = $_SESSION['user']['id'];
        $leagues = LeagueController::getLeaguesUser($user_id);
        require_once '../views/user/dashboard.php';
    }

    public static function settings()
    {
        if (!isLoggedIn()) {
            Session::setFlashMessage('login', 'Faz Login para ver esta página');
            header('Location: /login');
            exit;
        }
        require_once BASE_PATH . '/views/user/settings.php';
    }



    public static function profile()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $user_id = $_GET['id'];

            // username query
            $conn = dbConnect();
            $stmt = $conn->prepare('SELECT nome_utilizador from Utilizadores where id = :id');
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();

            $user_name = $stmt->fetch(PDO::FETCH_ASSOC);

            // scores
            $stmt = $conn->prepare('SELECT jogos_jogados, jogos_ganhos FROM Ranking where id_utilizador = :id');
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();

            $score = $stmt->fetch(PDO::FETCH_ASSOC);

            if($score['jogos_jogados'] > 0) {
                $win_loss_ratio = ($score['jogos_ganhos'] / $score['jogos_jogados']) * 100;
            }else{
                $win_loss_ratio = 0;
            }


            // leagues
            $leagues = LeagueController::getLeaguesUser($user_id);

        }
        require_once BASE_PATH . '/views/user/profile.php';
    }

    public static function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }

}

