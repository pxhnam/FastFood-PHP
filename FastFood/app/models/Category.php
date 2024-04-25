<?php
class Category
{
    private $conn;
    private $table = "Categories";

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getList()
    {
        try {
            $query = "SELECT * FROM $this->table WHERE is_delete = false";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
        }
    }
    public function get($search, $offset, $limit)
    {
        try {
            $query = "SELECT * FROM $this->table WHERE is_delete = false AND name LIKE :search LIMIT :offset, :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':search', '%' . $search . '%');
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
        }
    }

    public function getById($id)
    {
        try {
            $query = "SELECT * FROM $this->table WHERE id = :id AND is_delete = false";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
        }
    }

    function count($search)
    {
        try {
            $query = "SELECT COUNT(*) AS total FROM $this->table WHERE is_delete = false AND name LIKE :search";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':search', '%' . $search . '%');
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $ex) {
        }
    }

    public function add($name)
    {
        if (empty($name)) {
            return 'Category name cannot be empty';
        }

        try {
            $query = "INSERT INTO $this->table (name) VALUES (:name)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':name', htmlspecialchars(strip_tags($name)));
            return $stmt->execute();
        } catch (PDOException $ex) {
        }
    }

    public function update($id, $name)
    {
        if (empty($name)) {
            return 'Category name cannot be empty';
        }

        try {
            $query = "UPDATE $this->table SET name = :name WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindValue(':name', htmlspecialchars(strip_tags($name)));
            return $stmt->execute();
        } catch (PDOException $ex) {
        }
    }
    public function remove($id)
    {
        try {
            $query = "UPDATE $this->table SET is_delete = true WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $ex) {
        }
    }
}
