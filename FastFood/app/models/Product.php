<?php
class Product
{
    private $conn;
    private $table = "Products";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getProducts($search)
    {
        // Viết truy vấn SQL để lấy danh sách người dùng từ cơ sở dữ liệu
        $query = 'SELECT p.*, c.name AS category_name FROM ' . $this->table . ' p INNER JOIN categories c ON p.category_id = c.id WHERE p.is_delete = false AND p.name LIKE :search';
        //
        $searchTerm = '%' . $search . '%';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductById($id)
    {
        $query = 'SELECT p.*, c.name AS category_name FROM ' . $this->table . ' p INNER JOIN categories c ON p.category_id = c.id' . ' WHERE p.id = :id AND p.is_delete = false';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function getTotal($id, $quantity)
    {
        $query = 'SELECT price FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->price * $quantity;
    }
    public function getPrice($id)
    {
        $query = 'SELECT price FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->price;
    }

    public function create($name, $image, $description, $price, $category)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Product name cannot be empty';
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

        // Truy vấn tạo sản phẩm mới
        $query = "INSERT INTO " . $this->table . " (name, image, description, price, category_id) VALUES (:name, :image, :description, :price, :category_id)";
        $stmt = $this->conn->prepare($query);

        // Gán dữ liệu vào câu lệnh
        $stmt->bindValue(':name', htmlspecialchars(strip_tags($name)));
        $stmt->bindValue(':image', htmlspecialchars(strip_tags($image)));
        $stmt->bindValue(':description', htmlspecialchars(strip_tags($description)));
        $stmt->bindValue(':price', htmlspecialchars(strip_tags($price)));
        $stmt->bindValue(':category_id', htmlspecialchars(strip_tags($category)));
        // Thực thi câu lệnh
        return $stmt->execute();
    }

    public function update($id, $name, $image, $description, $price, $category)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Product name cannot be empty';
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

        // Truy vấn cập nhật sản phẩm
        $query = "UPDATE " . $this->table . " SET name = :name, image = :image, description = :description, price = :price, category_id = :category_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Gán dữ liệu vào câu lệnh
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':name', htmlspecialchars(strip_tags($name)));
        $stmt->bindValue(':image', htmlspecialchars(strip_tags($image)));
        $stmt->bindValue(':description', htmlspecialchars(strip_tags($description)));
        $stmt->bindValue(':price', htmlspecialchars(strip_tags($price)));
        $stmt->bindValue(':category_id', htmlspecialchars(strip_tags($category)));

        // Thực thi câu lệnh
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
