<?php
class User
{
    private $conn;
    private $table = "Users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getUsers()
    {
        // Viết truy vấn SQL để lấy danh sách người dùng từ cơ sở dữ liệu
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function register($username, $password)
    {
        // Kiểm tra ràng buộc đầu vào
        $errors = [];
        if (empty($username)) {
            $errors['username'] = 'Username không được để trống';
        }
        if (empty($password)) {
            $errors['password'] = 'Password không được để trống';
        }

        if (count($errors) > 0) {
            return $errors;
        }

        //Truy van
        $query = "INSERT INTO " . $this->table . " (username, password) VALUES (:username, :password)";
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu & Gán dữ liệu vào câu lệnh
        $stmt->bindParam(':username', htmlspecialchars(strip_tags($username)));
        $stmt->bindParam(
            ':password',
            htmlspecialchars(strip_tags(password_hash($password, PASSWORD_DEFAULT)))
        );

        return $stmt->execute();
    }

    function login($username, $password)
    {
        // Kiểm tra ràng buộc đầu vào
        if (empty($username)) {
            return false;
        }
        if (empty($password)) {
            return false;
        }

        // Lấy thông tin người dùng từ cơ sở dữ liệu dựa trên tên đăng nhập
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kiểm tra xem người dùng có tồn tại không
        if (!$user) {
            return false;
        }
        // Kiểm tra mật khẩu
        if (!password_verify($password, $user['password'])) {
            return false;
        }
        // Trả về thông tin người dùng nếu xác thực thành công
        return $user;
    }
}
