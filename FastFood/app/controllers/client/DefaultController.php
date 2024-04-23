<?php

class DefaultController
{
    private $User;
    private $db;
    private $path = 'app/views/client/defaults/';
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->User = new User($this->db);
    }
    public function Index()
    {
        include_once $this->path . 'index.php';
    }
    public function Password()
    {
        Authenticate::RequiredLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include $this->path . 'password.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old_password = $_POST['old_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            try {
                $result = $this->User->changePassword($old_password, $new_password, $confirm_password);
                if (is_array($result)) {
                    $errors = $result;
                    include $this->path . 'password.php';
                } else {
                    $success = 'Thay đổi mật khẩu thành công.';
                    include $this->path . 'password.php';
                }
            } catch (\Throwable $th) {
                include $this->path . 'password.php';
                exit;
            }
        }
    }
    public function Login()
    {
        Authenticate::LoggedIn();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include $this->path . 'login.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            try {
                $result = $this->User->login($username, $password);
            } catch (\Throwable $th) {
                include $this->path . 'login.php';
                exit;
            }
            if (is_array($result)) {
                $_SESSION['User'] = array(
                    'id' => $result['id'],
                    'username' => $result['username'],
                    'role' => $result['role']
                );
                echo "<script>alert('Đăng Nhập Thành Công');location.href='/login'</script>";
            } else {
                $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
                include $this->path . 'login.php';
            }
        }
    }
    public function Register()
    {
        Authenticate::LoggedIn();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include $this->path . 'register.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $day = $_POST['day'] ?? '';
            $month = $_POST['month'] ?? '';
            $year = $_POST['year'] ?? '';
            $gender = $_POST['gender'] ?? '';

            try {
                $result = $this->User->register($first_name, $last_name, $username, $password, $day, $month, $year, $gender);
            } catch (\Throwable $th) {
                include $this->path . 'register.php';
                exit;
            }

            if (is_array($result)) {
                $errors = $result;
                include $this->path . 'register.php';
            } else echo "<script>alert('Đăng Ký Thành Công');location.href='/login'</script>";
        }
    }
    public function Logout()
    {
        unset($_SESSION['User']);
        header('Location: /');
    }
    public function Error()
    {
        include_once 'app/views/client/errors/404.php';
    }
}
