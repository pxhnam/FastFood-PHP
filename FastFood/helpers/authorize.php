<?php
class Authorize
{
    public static function isRole($requiredRole)
    {
        if (!isset($_SESSION['User'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['User']['role'] !== $requiredRole) {
            header('Location: /');
            exit;
        }
    }
}
