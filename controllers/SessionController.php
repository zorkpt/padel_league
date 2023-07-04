<?php

class SessionController
{
    public static function start()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function setFlashMessage($key, $message)
    {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key)
    {
        self::start();
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return [];
    }

    public static function hasFlash($key)
    {
        self::start();
        return isset($_SESSION['flash'][$key]);
    }



}