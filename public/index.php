<?php
require '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . 'functions.php';
require_once '../core/Database.php';
require_once '../controllers/HomeController.php';
require_once '../controllers/UserController.php';
require_once '../controllers/LeagueController.php';
require_once '../controllers/GameController.php';
require_once '../controllers/SessionController.php';
require_once '../controllers/NotificationController.php';
require_once '../controllers/MailerController.php';


$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$db = dbConnect();
session_start();
// Routing

switch ($request_uri[0]) {
    // Página inicial
    case '/':
        HomeController::index();
        break;

    // Página de registro
    case '/register':
        UserController::register();
        break;

    // Página de login
    case '/login':
        UserController::login();
        break;

    case '/logout':
        UserController::logout();
        break;

    case '/user/updatePassword':
        UserController::changePassword();
        break;

    case '/user/forgot-password':
        UserController::forgotPassword();
        break;

    case '/user/redefine-password':
        UserController::redefinePassword();
        break;


    case '/user/updateEmail':
        UserController::changeEmail();
        break;

    case '/user/updateAvatar':
        UserController::updateAvatar();
        break;


    // Página do painel
    case '/dashboard':
        UserController::dashboard();
        break;

    // Página de criação da liga
    case '/leagues/create':
        LeagueController::create();
        break;

    // Página da liga
    case '/league':
        LeagueController::viewLeague();
        break;

    case '/league/join':
        LeagueController::joinLeague();
        break;

    // Página de agendamento do jogo
    case '/game/schedule':
        GameController::schedule();
        break;

    // Página do jogo
    case '/game':
        GameController::show();
        break;

    case '/game/create':
        GameController::addGame();
        break;

    case '/game/subscribe':
        GameController::subscribe();
        break;

    case '/game/unsubscribe':
        GameController::unsubscribe();
        break;

    case '/game/lock':
        GameController::handleLockGame();
        break;

    case '/game/register_results':
        GameController::registerResults();
        break;

    case '/game/submit_results':
        GameController::submitResults();
        break;

    case '/game/finish':
        GameController::finishGame();
        break;


    // Página do perfil do usuário
    case '/settings':
        UserController::settings();
        break;

    // Página de configurações
    case '/profile':
        UserController::profile();
        break;

    // Errors
    case '/error';
        HomeController::error();
        break;

    case '/notification/read':
        NotificationController::handleReadNotification();
        break;

    case '/league/settings';
        LeagueController::settings();
        break;

    case '/league/confirm-delete';
        LeagueController::confirmDelete();
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        require '../views/404.php';
        break;
}
