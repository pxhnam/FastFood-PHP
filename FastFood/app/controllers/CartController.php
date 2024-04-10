<?php
class CartController
{
    public function __construct()
    {
    }

    public function index()
    {
        include_once 'app/views/carts/index.php';
    }

    // Hàm thêm sản phẩm vào giỏ hàng
    function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST["id"] ?? '';
            $quantity = $_POST["quantity"] ?? '';

            if (!empty($id) && !empty($quantity) && is_numeric($id) && is_numeric($quantity)) {
                if (!isset($_SESSION['Cart'])) {
                    $_SESSION['Cart'] = array();
                }

                if (isset($_SESSION['Cart'][$id])) {
                    $_SESSION['Cart'][$id] += $quantity;
                } else {
                    $_SESSION['Cart'][$id] = $quantity;
                }
                header('Content-Type: application/json');
                echo json_encode(
                    array(
                        'success' => true,
                        'count' => count($_SESSION['Cart'])
                    )
                );
            }
        }
    }
    function setQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST["id"] ?? '';
            $quantity = $_POST["quantity"] ?? '';
            if (!empty($id) && !empty($quantity) && is_numeric($id) && is_numeric($quantity)) {
                $quantity = $quantity <= 0 ?  1 : $quantity;
                if (isset($_SESSION['Cart'][$id])) {
                    $_SESSION['Cart'][$id] = $quantity;
                }
            }
        }
    }
    // Hàm xóa một sản phẩm khỏi giỏ hàng
    function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST["id"] ?? '';

            if (!empty($id) && is_numeric($id)) {
                if (isset($_SESSION['Cart'][$id])) {
                    unset($_SESSION['Cart'][$id]);
                }
            }
        }
    }
    function count()
    {
        header('Content-Type: application/json');
        echo json_encode(
            array(
                'success' => true,
                'count' => count($_SESSION['Cart'])
            )
        );
    }

    public function clear()
    {
        unset($_SESSION['Cart']);
    }
}
