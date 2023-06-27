<?php

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . 'functions.php';

require_once '../core/Database.php';
require_once '../controllers/HomeController.php';
require_once '../controllers/UserController.php';
require_once '../controllers/LeagueController.php';
require_once '../controllers/GameController.php';
require_once '../controllers/Session.php';


$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$db = dbConnect();
session_start();
// Roteamento

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

    case '/user/updateEmail':
        UserController::changeEmail();
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
    case '/game_schedule':
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
    case '/profile':
        UserController::profile();
        break;

    // Página de configurações
    case '/settings':
        UserController::settings();
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        require '../views/404.php';
        break;
}
