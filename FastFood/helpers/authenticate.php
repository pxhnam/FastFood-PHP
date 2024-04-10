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
}
