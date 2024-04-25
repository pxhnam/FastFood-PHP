<?php
class OrderDetail
{
    private $conn;
    private $table = "Order_Details";

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function create($order_id, $product_id, $quantity, $price)
    {
        try {

            $query = "INSERT INTO $this->table (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindValue(':order_id', htmlspecialchars(strip_tags($order_id)));
            $stmt->bindValue(':product_id', htmlspecialchars(strip_tags($product_id)));
            $stmt->bindValue(':quantity', htmlspecialchars(strip_tags($quantity)));
            $stmt->bindValue(':price', htmlspecialchars(strip_tags($price)));
            return $stmt->execute();
        } catch (PDOException $ex) {
        }
    }
}
