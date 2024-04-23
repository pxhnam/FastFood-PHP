<?php
class Authorize
{
    public static function isAdmin()
    {
        if (!isset($_SESSION['User'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['User']['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
    }
}
