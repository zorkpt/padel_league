<?php

class HomeController {
    public static function index() {
        if(isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        require_once '../views/home/home.php';
    }
}

