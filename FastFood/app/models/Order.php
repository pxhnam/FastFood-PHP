<?php
class Order
{
    private $conn;
    private $table = "Orders";

    public function __construct($db)
    {
        $this->conn = $db;
    }
    function get($search, $offset, $limit)
    {
        $query = "SELECT * FROM $this->table WHERE content LIKE :search LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':search', '%' . $search . '%');
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getById($id)
    {
        $query = "SELECT * FROM $this->table o INNER JOIN Order_Details od ON o.id = od.order_id INNER JOIN Products p on od.product_id = p.id WHERE o.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function count($search)
    {
        $query = "SELECT COUNT(*) AS total FROM $this->table WHERE content LIKE :search";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':search', '%' . $search . '%');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    function create($product, $content)
    {
        $user_id = isset($_SESSION['User']) ? $_SESSION['User']['id'] : null;
        $total = 0;
        $query = "INSERT INTO  $this->table (user_id, total, content) VALUES (:user_id, :total, :content)";
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
