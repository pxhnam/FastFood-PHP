<?php
class CartController
{
    private $path;
    public function __construct()
    {
    }

    public function index()
    {
        include_once 'app/views/client/carts/index.php';
    }

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
        $count = 0;
        if (isset($_SESSION['Cart'])) {
            $count = count($_SESSION['Cart']);
        }
        header('Content-Type: application/json');
        echo json_encode(
            array(
                'success' => true,
                'count' =>  $count
            )
        );
    }

    public function clear()
    {
        unset($_SESSION['Cart']);
    }
}
