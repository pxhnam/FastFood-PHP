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
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsername($username)
    {
        $query = "SELECT * FROM $this->table WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':username', htmlspecialchars(strip_tags($username)));
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    public function getById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', htmlspecialchars(strip_tags($id)));
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    function register($first_name, $last_name, $username, $password, $day, $month, $year, $gender)
    {
        $errors = [];
        if (empty($first_name)) {
            $errors['first_name'] = 'First name không được để trống';
        }
        if (empty($last_name)) {
            $errors['last_name'] = 'Last name không được để trống';
        }
        if (empty($username)) {
            $errors['username'] = 'Username không được để trống';
        } else {
            $existingUser = $this->getUsername($username);
            if ($existingUser) {
                $errors['username'] = 'Username đã tồn tại';
            }
        }
        if (empty($password)) {
            $errors['password'] = 'Password không được để trống';
        }
        if (empty($day)) {
            $errors['day'] = 'Day không được để trống';
        }
        if (empty($month)) {
            $errors['month'] = 'Month không được để trống';
        }
        if (empty($year)) {
            $errors['year'] = 'Year không được để trống';
        }
        if (empty($gender)) {
            $errors['gender'] = 'Gender không được để trống';
        }
        if (!Validator::isValidDate($day, $month, $year)) {
            $errors['all'] = 'Ngày sinh không hợp lệ';
        }

        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO $this->table (username, password, first_name, last_name, sex, day, month, year) VALUES (:username, :password, :first_name, :last_name, :sex, :day, :month, :year)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':username', htmlspecialchars(strip_tags($username)));
        $stmt->bindValue(
            ':password',
            htmlspecialchars(strip_tags(password_hash($password, PASSWORD_DEFAULT)))
        );
        $stmt->bindValue(':first_name', htmlspecialchars(strip_tags($first_name)));
        $stmt->bindValue(':last_name', htmlspecialchars(strip_tags($last_name)));
        $stmt->bindValue(':sex', htmlspecialchars(strip_tags($gender)));
        $stmt->bindValue(':day', htmlspecialchars(strip_tags($day)));
        $stmt->bindValue(':month', htmlspecialchars(strip_tags($month)));
        $stmt->bindValue(':year', htmlspecialchars(strip_tags($year)));

        return $stmt->execute();
    }

    function login($username, $password)
    {
        if (empty($username)) return false;
        if (empty($password)) return false;

        $query = "SELECT * FROM $this->table WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return false;
        if (!password_verify($password, $user['password'])) return false;

        return $user;
    }
    public function getSexEnumValues()
    {
        $query = "SHOW COLUMNS FROM $this->table WHERE Field = 'sex'";
        $stmt = $this->conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        preg_match_all("/'(.*?)'/", $row['Type'], $matches);
        $enum_values = $matches[1];

        return $enum_values;
    }
    public function changePassword($old_password, $new_password, $confirm_password)
    {
        $errors = [];

        if (empty($old_password)) {
            $errors['old_password'] = 'Old Password không được để trống';
        }
        if (empty($new_password)) {
            $errors['new_password'] = 'New Password không được để trống';
        }
        if (empty($confirm_password)) {
            $errors['confirm_password'] = 'Confirm Password không được để trống';
        }
        if ($new_password !== $confirm_password) {
            $errors['confirm_password'] = 'Confirm Password không khớp với New Password';
        }
        $id = $_SESSION['User']['id'];

        $user = $this->getById($id);
        if (!$user) {
            $errors['all'] = 'Không tìm thấy người dùng';
            return $errors;
        }

        if (!password_verify($old_password, $user['password'])) {
            $errors['all'] = 'Mật khẩu cũ không đúng';
            return $errors;
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $query = "UPDATE $this->table SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':password', $hashed_password);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}
