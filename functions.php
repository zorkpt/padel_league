<?php

session_start();
function dd($value){
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

function view($path, $attributes = []) {
    extract($attributes);
    require base_path('Views/' . $path);
}

function base_path($path){
    return BASE_PATH . $path;
}

function uriIs($value) {
    return $_SERVER['REQUEST_URI'] === $value;
}

function isLoggedIn() {
    return isset($_SESSION['user']['id']);
}