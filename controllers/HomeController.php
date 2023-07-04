<?php

class HomeController {
    public static function index() {
        if(isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        require_once '../views/home/home.php';
    }

    public static function error() {
        $errors = [];
        $errors = SessionController::getFlash('access_error');

        require_once  BASE_PATH . 'views/error.php';
    }
}

