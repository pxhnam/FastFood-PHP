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
        try {
            $query = "SELECT * FROM $this->table WHERE user_id = :id ORDER BY id DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
        }
    }
    function create($phone_number, $address)
    {
        try {
            $query = "INSERT INTO $this->table (phone_number, address, user_id) VALUES (:phone_number, :address, :user_id)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindValue(':phone_number', htmlspecialchars(strip_tags($phone_number)));
            $stmt->bindValue(':address', htmlspecialchars(strip_tags($address)));
            $stmt->bindParam(':user_id', $_SESSION['User']['id']);

            return $stmt->execute();
        } catch (PDOException $ex) {
        }
    }
}
