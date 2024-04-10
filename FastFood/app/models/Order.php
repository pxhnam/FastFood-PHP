<?php
class Order
{
    private $conn;
    private $table = "Orders";

    public function __construct($db)
    {
        $this->conn = $db;
    }
    function get($search = '')
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE content LIKE :search';
        $searchTerm = '%' . $search . '%';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function create($product, $content)
    {
        $user_id = isset($_SESSION['User']) ? $_SESSION['User']['id'] : null;
        $total = 0;
        $query = "INSERT INTO " . $this->table . " (user_id, total, content) VALUES (:user_id, :total, :content)";
        $stmt = $this->conn->prepare($query);

        if (isset($_SESSION['Cart'])) {
            foreach ($_SESSION['Cart'] as $id => $quantity) {
                $total += $product->getTotal($id, $quantity);
            }
        }

        $stmt->bindValue(':user_id', htmlspecialchars(strip_tags($user_id)));
        $stmt->bindValue(':total', htmlspecialchars(strip_tags($total)));
        $stmt->bindValue(':content', htmlspecialchars(strip_tags($content)));
        $stmt->execute();
        return $this->conn->lastInsertId();
    }
}
