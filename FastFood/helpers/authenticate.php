<?php
class Authenticate
{
    public static function LoggedIn()
    {
        if (isset($_SESSION['User'])) {
            header('Location: /');
            exit;
        }
    }
    public static function RequiredLogin()
    {
        if (!isset($_SESSION['User'])) {
            header('Location: /Login');
            exit;
        }
    }
}
