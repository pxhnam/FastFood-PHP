<?php
class Information
{
    private $conn;
    private $table = "Informations";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function get($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :id ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function create($phone_number, $address)
    {
        //Truy van
        $query = "INSERT INTO " . $this->table . " (phone_number, address, user_id) VALUES (:phone_number, :address, :user_id)";
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu & Gán dữ liệu vào câu lệnh

        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':user_id', $_SESSION['User']['id']);

        return $stmt->execute();
    }
}
