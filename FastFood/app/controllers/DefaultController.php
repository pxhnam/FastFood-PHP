<?php

class DefaultController
{
    private $User;
    private $db;
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->User = new User($this->db);
    }
    public function Index()
    {
        include_once 'app/views/defaults/index.php';
    }
    public function Login()
    {
        Authenticate::LoggedIn();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include 'app/views/defaults/login.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $result = $this->User->login($username, $password);
            if (is_array($result)) {
                $_SESSION['User'] = array(
                    'id' => $result['id'],
                    'username' => $result['username'],
                    'role' => $result['role']
                );
                header('Location: /');
            } else {
                $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
                include 'app/views/defaults/login.php';
            }
        }
    }
    public function Register()
    {
        Authenticate::LoggedIn();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include 'app/views/defaults/register.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $result = $this->User->register($username, $password);

            if (is_array($result)) {
                $errors = $result;
                include 'app/views/defaults/register.php';
            } else header('Location: /login');
        }
    }
    public function Logout()
    {
        unset($_SESSION['User']);
        header('Location: /');
        exit;
    }
    public function Error()
    {
        include_once 'app/views/errors/404.php';
    }
}
