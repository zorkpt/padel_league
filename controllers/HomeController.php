<?php

class HomeController {
    public static function index() {

        $latestMembers = UserController::getLastestMembers();
        $totalGames = GameController::getTotalGames();
        $totalLeagues = LeagueController::getTotalLeagues();

        if(isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        require_once '../views/home/home.php';
    }

    public static function error() {

        require_once  BASE_PATH . 'views/error.php';
    }
}

