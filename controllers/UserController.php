<?php

class UserController
{
    public static function register()
    {
        if(isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }

        $emailFromInvite = self::getEmailFromInvite();

        if ($emailFromInvite) {
            $_SESSION['old']['email'] = $emailFromInvite;
        }


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm-password'];
            $avatar = $_FILES['avatar'];
            $created_date = date("Y-m-d H:i:s");

            // store username and email to fill the form in case of fail
            $_SESSION['old']['username'] = $username;
            $_SESSION['old']['email'] = $email;

            if (empty($username)) {
                SessionController::setFlashMessage('username', "Nome de usuário é obrigatório.");
            } else if(strlen($username) < 3) {
                SessionController::setFlashMessage('username', "Nome de usuário deve ter pelo menos 3 caracteres.");
            } else {
                $userExists = self::checkUserExists($username);
                if($userExists) {
                    SessionController::setFlashMessage('username', "Nome de usuário já está em uso.");
                }
            }
            if(empty($email)){
                SessionController::setFlashMessage('email', "E-mail é obrigatorio");
            }else {
                $emailExists = self::checkEmailExists($email);
                if($emailExists){
                    SessionController::setFlashMessage('email', "E-mail já está em uso.");
                }
            }

            if(empty($password)) {
                SessionController::setFlashMessage('password', "Senha é obrigatória.");
            }elseif ($password != $confirmPassword){
                SessionController::setFlashMessage('password', 'As senhas não são iguais.');
            }

            list($avatarDestination, $avatarErrors) = self::handleAvatarUpload($avatar);

            if (!empty($avatarErrors)) {
                foreach ($avatarErrors as $error) {
                    SessionController::setFlashMessage('avatar', $error);
                }
            }
            // Verify there are no errors and insert user on db
            if (!SessionController::hasFlash('username') && !SessionController::hasFlash('email') && !SessionController::hasFlash('password') && !SessionController::hasFlash('avatar')) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $conn = dbConnect();

                $stmt = $conn->prepare("INSERT INTO Utilizadores (nome_utilizador, email, password_hash, avatar, data_registo) VALUES (:username, :email, :password, :avatar, :data_registo)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':avatar', $avatarDestination);
                $stmt->bindParam(':data_registo', $created_date);

                $stmt->execute();
                $newUserId = $conn->lastInsertId();  // Get the ID of the newly created user

                // Log the user in
                $_SESSION['user'] = [
                    'id' => $newUserId,
                    'nome_utilizador' => $username,
                    'email' => $email,
                    'avatar' => $avatarDestination,
                ];

                $mailer = new MailerController();
                $mailer->sendWelcomeEmail($email, $username);

                // Check if there's an invitation code in the session
                if (isset($_SESSION['invite_code'])) {
                    // There's an invitation code. Redirect to the accept invitation page
                    header('Location: /accept-invite?code=' . $_SESSION['invite_code']);
                    exit();
                } else {
                    header('Location: /dashboard');
                    exit;
                }
            }
        }

        require_once '../views/user/register.php';
    }


    public static function checkUserExists($username)
    {
        $conn = dbConnect();

        $stmt = $conn->prepare("SELECT * FROM Utilizadores WHERE nome_utilizador = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Return true if same name is found
        return $stmt->rowCount() > 0;
    }

    public static function checkEmailExists($email)
    {
        $conn = dbConnect();

        $stmt = $conn->prepare("SELECT * FROM Utilizadores WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Return true if same mail is found
        return $stmt->rowCount() > 0;
    }

    public static function updateAvatar()
    {
        checkLoggedIn();

        try {
            list($avatarPath, $errors) = self::handleAvatarUpload($_FILES['avatar']);

            if (!empty($errors)) {
                throw new Exception(implode(", ", $errors));
            }

            // Update the avatar in the database
            $conn = dbConnect();
            $stmt = $conn->prepare("UPDATE Utilizadores SET avatar = :avatar WHERE id = :id");
            $stmt->bindParam(':avatar', $avatarPath);
            $stmt->bindParam(':id', $_SESSION['user']['id']);
            $stmt->execute();

            // Update the avatar in the session
            $_SESSION['user']['avatar'] = $avatarPath;

            // Set a success message
            SessionController::setFlashMessage('success_avatar_message', 'O avatar foi atualizado com sucesso!');

        } catch (Exception $e) {
            // Set an error message
            SessionController::setFlashMessage('avatar', $e->getMessage());
        }

        header('Location: /settings');
        exit;
    }


    public static function handleAvatarUpload($avatar)
    {
        $errors = [];
        $avatarDestination = "/uploads/default.png"; // Valor padrão se não houver upload

        // Verifique se um arquivo foi realmente enviado
        if (isset($avatar) && $avatar['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($avatar['error'] !== UPLOAD_ERR_OK) {
                $errors['avatar'] = 'Problema no upload do avatar';
            } else {
                $avatarName = $avatar['name'];
                $avatarTmpName = $avatar['tmp_name'];
                $avatarSize = $avatar['size'];

                $avatarExt = explode('.', $avatarName);
                $avatarActualExt = strtolower(end($avatarExt));

                $allowed = array('jpg', 'jpeg', 'png');

                if (!in_array($avatarActualExt, $allowed)) {
                    $errors['avatar'] = "Apenas são permitidos arquivos jpg, jpeg e png!";
                }

                if ($avatarSize > 5000000) {
                    $errors['avatar'] = "O arquivo é demasiado grande!";
                }

                if (empty($errors)) {
                    $avatarNameNew = uniqid('', true) . "." . $avatarActualExt;
                    $avatarDestination = '/uploads/' . $avatarNameNew;
                    $absolutePath = $_SERVER['DOCUMENT_ROOT'] . $avatarDestination;
                    move_uploaded_file($avatarTmpName, $absolutePath);
                }
            }
        }

        // avatar path and errors
        return [$avatarDestination, $errors];
    }

    public static function login()
    {
        if(isLoggedIn()) {
            // Check if there's an invite code in the session or GET params
            if (isset($_GET['invite_code'])) {
                // There's an invitation code. Redirect to the accept invitation page
                header('Location: /accept-invite?codigo=' . $_GET['invite_code']);
                exit();
            } else {
                header('Location: /dashboard');
                exit;
            }
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $_SESSION['old']['username'] = $username;


            $conn = dbConnect();
            $stmt = $conn->prepare('SELECT id, nome_utilizador, password_hash, email, avatar FROM Utilizadores WHERE nome_utilizador = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            unset($user['password']);

            if ($user && password_verify($password, $user['password_hash'])) {
                SessionController::start();
                $_SESSION['user'] = $user;

                // Check if there's an invite code in the session or GET params
                if (isset($_GET['invite_code'])) {
                    // There's an invitation code. Redirect to the accept invitation page
                    header('Location: /accept-invite?codigo=' . $_GET['invite_code']);
                    exit();
                } else {
                    header('Location: /dashboard');
                    exit;
                }

            } else {
                $errors['login'] = 'Nome de usuário ou senha incorretos.';
                $_SESSION['errors'] = $errors;
            }
        }

        require_once '../views/user/login.php';
    }


    public static function changePassword()
    {
        checkLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $old_password = $_POST['old_password'];
            $new_password = $_POST['new_password'];
            $confirm_new_password = $_POST['confirm_new_password'];

            if ($new_password != $confirm_new_password) {
                SessionController::setFlashMessage('password', 'As senhas não são iguais');
                header("Location: /settings");
                exit();
            }

            $conn = dbConnect();
            $stmt = $conn->prepare('SELECT password_hash FROM Utilizadores where  id = :id');
            $stmt->bindParam(':id', $_SESSION['user']['id']);
            $stmt->execute();

            $user = $stmt->fetch();

            if (!password_verify($old_password, $user['password_hash'])) {
                SessionController::setFlashMessage('password', 'A senha antiga está incorreta.');
                header("Location: /settings");
                exit();
            }


            $newPasswordHash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('UPDATE Utilizadores SET password_hash = :password where id = :id');
            $stmt->bindParam(':id', $_SESSION['user']['id']);
            $stmt->bindParam(':password', $newPasswordHash);
            $stmt->execute();

            SessionController::setFlashMessage('success_password_message', 'Senha alterada com sucesso.');
            header('Location: /settings');

        }

    }

    public static function changeEmail()
    {

        checkLoggedIn();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newMail = $_POST['email'];

            if(!filter_var($newMail, FILTER_VALIDATE_EMAIL) || empty($newMail)) {
                SessionController::setFlashMessage('email','Formato de e-mail inválido.');
                header('Location: /settings');
                exit();
            }

            $conn = dbConnect();
            $stmt = $conn->prepare('SELECT email FROM Utilizadores where email = :email');
            $stmt->bindParam(':email', $newMail);
            $stmt->execute();
            $existingEmail = $stmt->fetch();

            if ($existingEmail) {
                SessionController::setFlashMessage('email', 'Este e-mail já está em uso.');
                header('Location: /settings');
                exit();
            }

            $stmt = $conn->prepare('UPDATE Utilizadores SET email = :email where id = :id');
            $stmt->bindParam(':id', $_SESSION['user']['id']);
            $stmt->bindParam(':email', $newMail);
            $stmt->execute();

            $_SESSION['user']['email'] = $newMail;

            SessionController::setFlashMessage('success_mail_message', 'E-mail alterado com sucesso.');
            header('Location: /settings');

        }
    }


    public static function verifyPassword($user_id, $input_password) {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT password_hash FROM Utilizadores WHERE id = :user_id');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($input_password, $user['password_hash'])) {
            return false;
        }

        return true;
    }

    public  static function getDaysFromLastGame($user_id){
        $lastGameDate = GameController::getLastUserGameDate($user_id);
        if(!$lastGameDate){
            return 0;
        }
        $lastGameDateTime = new DateTime($lastGameDate);
        $currentDateTime = new DateTime();
        $interval = $currentDateTime->diff($lastGameDateTime);
        return $interval->format('%a');
    }

    public static function dashboard()
    {
        checkLoggedIn();
        $user_id = $_SESSION['user']['id'];
        $leagues = LeagueController::getLeaguesUser($user_id);
        $user = self::getUserData($user_id);
        $daysSinceLastGame =  self::getDaysFromLastGame($user_id);
        $lastGames = GameController::getPlayerGames($user_id);

        require_once '../views/user/dashboard.php';
    }

    public static function settings()
    {
        checkLoggedIn();
        require_once BASE_PATH . '/views/user/settings.php';
    }

    public static function profile()
    {
        checkLoggedIn();

        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $user_id = $_GET['id'];

            // username query
            $conn = dbConnect();
            $stmt = $conn->prepare('SELECT nome_utilizador, avatar from Utilizadores where id = :id');
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();

            $user_name = $stmt->fetch(PDO::FETCH_ASSOC);

            // scores
            $stmt = $conn->prepare('SELECT jogos_jogados, jogos_ganhos FROM Ranking where id_utilizador = :id');
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();

            $score = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($score !== false && isset($score['jogos_jogados']) && isset($score['jogos_ganhos'])) {
                if ($score['jogos_jogados'] > 0) {
                    $win_loss_ratio = ($score['jogos_ganhos'] / $score['jogos_jogados']) * 100;
                }
            } else {
                $score = [];
                $score['jogos_ganhos'] = 0;
                $score['jogos_jogados'] = 0;
                $win_loss_ratio = 0;
            }


            // leagues
            $leagues = LeagueController::getLeaguesUser($user_id);
            foreach ($leagues as $key => $league) {
                $player_ranking = LeagueController::getPlayerRankingInLeague($league['id'], $user_id);
                if(isset($player_ranking['rank'])) {
                    $leagues[$key]['ranking'] = $player_ranking['rank'];
                    $leagues[$key]['points'] = $player_ranking['total_pontuacao'];
                }
            }


        }
        require_once BASE_PATH . '/views/user/profile.php';
    }



    public static function getUserData($user_id) {
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT nome_utilizador, avatar FROM Utilizadores WHERE id = :user_id');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByEmail($email){
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT * FROM Utilizadores WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user) {
            return null;
        }

        return $user;
    }

    public static function logout()
    {
        SessionController::start();
        session_destroy();
        header('Location: /');
        exit;
    }

    public static function forgotPassword() {
        if(isset($_POST['email'])) {

            $email = $_POST['email'];

            $user = self::getByEmail($email);

            if($user === null) {
                SessionController::setFlashMessage('resetPassword', 'Não encontramos nenhum usuário com esse email.');
                header('Location: ' . '/user/forgot-password');
                exit();
            }

            $resetToken = bin2hex(random_bytes(50));

            self::updateUserResetToken($user,$resetToken);

            $resetLink = 'https://liga-padel.pt/user/redefine-password?token=' . $resetToken;
            $mailer = new MailerController();
            $mailer->sendPasswordResetEmail($email, $resetLink);

            SessionController::setFlashMessage('success', 'Enviamos um email com um link de redefinição de senha.');
            header('Location: /login');
            exit;
        }
        require_once BASE_PATH . 'views/user/forgot_password.php';
    }

    public static function updateUserResetToken($user, $resetToken){
        $resetExpires = date('Y-m-d H:i:s', time() + 3600); // expires in 1 hour

        $conn = dbConnect();
        $stmt = $conn->prepare('UPDATE Utilizadores SET passwordResetToken = :reset_token, passwordResetExpires = :reset_expires WHERE email = :user_email');
        $stmt->bindParam(':reset_token', $resetToken);
        $stmt->bindParam(':reset_expires', $resetExpires);
        $stmt->bindParam(':user_email', $user->email);
        $stmt->execute();
    }

    public static function redefinePassword() {
        if(isset($_POST['password'])) {

        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $conn = dbConnect();
        $stmt = $conn->prepare('UPDATE Utilizadores SET password_hash = :new_password, passwordResetToken = NULL, passwordResetExpires = NULL WHERE email = :email');
        $stmt->bindParam(':new_password', $newPassword);
        $stmt->bindParam(':email', $_SESSION['resetEmail']);
        $stmt->execute();

        unset($_SESSION['resetEmail']);
        SessionController::setFlashMessage('resetPassword', 'A sua senha foi atualizada com sucesso.');
        header('Location: /login');
        exit();

        }elseif(isset($_GET['token'])) {
            $token = $_GET['token'];

            $conn = dbConnect();
            $stmt = $conn->prepare('SELECT email, passwordResetExpires from Utilizadores where passwordResetToken = :token');
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify is token is valid
            if ($user === false || $user['passwordResetExpires'] < date('Y-m-d H:i:s')) {
                SessionController::setFlashMessage('error', 'O token é inválido ou expirou.');
                header('Location: /error');
                exit();
            }

            $_SESSION['resetEmail'] = $user['email'];
            SessionController::setFlashMessage('changePassword', 'Por favor introduz uma nova senha');
            require_once BASE_PATH . 'views/user/redefine_password.php';

        } else {
            SessionController::setFlashMessage('error', 'Endereço Inválido');
            header('Location /error');
            exit();
        }
    }

    public static function getEmailFromInvite() {
        if (!isset($_SESSION['invite_code'])) {
            return null;
        }

        $inviteCode = $_SESSION['invite_code'];

        $conn = dbConnect();
        $stmt = $conn->prepare("SELECT email FROM Convites_Pendentes WHERE codigo_convite = :invite_code");
        $stmt->bindParam(':invite_code', $inviteCode);
        $stmt->execute();

        $invite = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($invite) {
            // Return the email from the invite
            return $invite['email'];
        } else {
            // No invite found
            return null;
        }
    }

}

