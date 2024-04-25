<?php
class Product
{
    private $conn;
    private $table = "Products";

    function __construct($db)
    {
        $this->conn = $db;
    }

    function get($search, $offset, $limit)
    {
        try {

            $query = "SELECT p.*, c.name AS category_name FROM $this->table p INNER JOIN categories c ON p.category_id = c.id WHERE p.is_delete = false AND p.name LIKE :search LIMIT :offset, :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':search', '%' . $search . '%');
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
        }
    }
    function getById($id)
    {
        try {

            $query = "SELECT p.*, c.name AS category_name FROM $this->table p INNER JOIN categories c ON p.category_id = c.id WHERE p.id = :id AND p.is_delete = false";

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

    function update($name, $image, $description, $price, $category, $id = null)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Product name cannot be empty';
        }
        if (empty($image)) {
            $errors['image'] = 'Image cannot be empty';
        }
        if (empty($description)) {
            $errors['description'] = 'Description cannot be empty';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Invalid price for the product';
        }
        if (!is_numeric($category) || $category <= 0) {
            $errors['category'] = 'Invalid category';
        }

        if (count($errors) > 0) {
            return $errors;
        }
        try {
            if ($id) {
                $query = "UPDATE $this->table SET name = :name, image = :image, description = :description, price = :price, category_id = :category_id WHERE id = :id";
                $stmt = $this->conn->prepare($query);
            } else {
                $query = "INSERT INTO $this->table (name, image, description, price, category_id) VALUES (:name, :image, :description, :price, :category_id)";
                $stmt = $this->conn->prepare($query);
            }

            $id && $stmt->bindParam(':id', $id);
            $stmt->bindValue(':name', htmlspecialchars(strip_tags($name)));
            $stmt->bindValue(':image', htmlspecialchars(strip_tags($image)));
            $stmt->bindValue(':description', htmlspecialchars(strip_tags($description)));
            $stmt->bindValue(':price', htmlspecialchars(strip_tags($price)));
            $stmt->bindValue(':category_id', htmlspecialchars(strip_tags($category)));

            return $stmt->execute();
        } catch (PDOException $ex) {
        }
    }
    function remove($id)
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
