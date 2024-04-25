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
        try {

            $query = "SELECT * FROM $this->table WHERE content LIKE :search LIMIT :offset, :limit";
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

            $query = "SELECT * FROM $this->table o INNER JOIN Order_Details od ON o.id = od.order_id INNER JOIN Products p on od.product_id = p.id WHERE o.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
        }
    }

    function count($search)
    {
        try {

            $query = "SELECT COUNT(*) AS total FROM $this->table WHERE content LIKE :search";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':search', '%' . $search . '%');
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $ex) {
        }
    }
}
