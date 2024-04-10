<?php
class Category
{
    private $conn;
    private $table = "Categories";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function get($search = '')
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE is_delete = false AND name LIKE :search';
        $searchTerm = '%' . $search . '%';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id AND is_delete = false';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function add($name)
    {
        if (empty($name)) {
            return 'Category name cannot be empty';
        }


        $query = "INSERT INTO " . $this->table . " (name) VALUES (:name)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':name', htmlspecialchars(strip_tags($name)));
        return $stmt->execute();
    }
    public function update($id, $name)
    {
        if (empty($name)) {
            return 'Category name cannot be empty';
        }

        $query = "UPDATE " . $this->table . " SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':name', htmlspecialchars(strip_tags($name)));
        return $stmt->execute();
    }
    public function remove($id)
    {
        $query = 'UPDATE ' . $this->table . ' SET is_delete = true WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
