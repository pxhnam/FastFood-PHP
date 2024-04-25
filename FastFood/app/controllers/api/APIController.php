<?php
header('Content-Type: application/json');
class ApiController
{
    private $User;
    private $Product;
    private $Category;
    private $Information;
    private $Order;
    private $db;
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->User = new User($this->db);
        $this->Product = new Product($this->db);
        $this->Category = new Category($this->db);
        $this->Information = new Information($this->db);
        $this->Order = new Order($this->db);
    }
    public function getProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $product = $this->Product->getById($id);
            echo json_encode($product);
        }
    }

    public function getProducts()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = $_POST["search"] ?? '';
            $page = $_POST["page"] ?? '';
            $pageSize = $_POST["pageSize"] ?? '';
            $totalPages = ceil($this->Product->count($search) / $pageSize);
            $products = $this->Product->get($search, ($page - 1) * $pageSize, $pageSize);
            echo json_encode([
                'success' => true,
                'products' => $products,
                'totalPages' => $totalPages
            ]);
        }
    }
    public function getCategories()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = $_POST["search"] ?? '';
            $page = $_POST["page"] ?? '';
            $pageSize = 10;
            $totalPages = ceil($this->Category->count($search) / $pageSize);
            $categories = $this->Category->get($search, ($page - 1) * $pageSize, $pageSize);
            echo json_encode([
                'success' => true,
                'categories' => $categories,
                'totalPages' => $totalPages
            ]);
            return;
        }
    }
    public function getOrders()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = $_POST["search"] ?? '';
            $page = $_POST["page"] ?? '';
            $pageSize = 10;
            $totalPages = ceil($this->Order->count($search) / $pageSize);
            $orders = $this->Order->get($search, ($page - 1) * $pageSize, $pageSize);
            echo json_encode([
                'success' => true,
                'orders' => $orders,
                'totalPages' => $totalPages
            ]);
        }
    }
    public function getSex()
    {
        $genders = $this->User->getSexEnumValues();
        echo json_encode(['success' => true, 'genders' => $genders]);
    }
    public function getCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST["id"] ?? '';
            $category = $this->Category->getById($id);
            if ($category) {
                echo json_encode(['success' => true, 'category' => $category]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy category']);
            }
        }
    }
    public function updateCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST["id"] ?? '';
            $name = $_POST["name"] ?? '';
            if ($id) {
                $result = $this->Category->update($id, $name);
                if (is_string($result)) {
                    echo json_encode(['success' => false, 'message' => $result]);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Update thành công']);
                }
            } else {
                $result = $this->Category->add($name);
                if (is_string($result)) {
                    echo json_encode(['success' => false, 'message' => $result]);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Add thành công']);
                }
            }
        }
    }
    public function removeCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST["id"] ?? '';
            if ($id) {
                $category = $this->Category->remove($id);
                if ($category) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Xóa loại sản phẩm thành công.'
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy']);
            }
        }
    }
    public function removeProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST["id"] ?? '';
            if ($id) {
                $product = $this->Product->remove($id);
                if ($product) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Xóa sản phẩm thành công.'
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy']);
            }
        }
    }

    public function getCarts()
    {
        $products = [];
        if (isset($_SESSION['Cart'])) {
            foreach ($_SESSION['Cart'] as $id => $quantity) {
                $product = $this->Product->getById($id);
                if ($product) {
                    $product->quantity = $quantity;
                    $products[] = $product;
                }
            }
        }
        echo json_encode($products);
    }
    function getInformation()
    {
        if (isset($_SESSION['User'])) {
            $info = $this->Information->get($_SESSION['User']['id']);
            if ($info) {
                echo json_encode(['success' => true, $info]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Bạn chưa từng nhập địa chỉ trước đó.'
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
        }
    }
    function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['Cart'])) {
                $id_info = $_POST["id"] ?? '';
                $name = $_POST["name"] ?? '';
                $phone_number = $_POST["phone_number"] ?? '';
                $address = $_POST["address"] ?? '';
                if (empty(trim($name))) {
                    echo json_encode([
                        'success' => false,
                        'type' => 'warning',
                        'message' => 'Vui lòng nhập tên người nhận.'
                    ]);
                    return;
                }
                if (empty(trim($phone_number))) {
                    echo json_encode([
                        'success' => false,
                        'type' => 'warning',
                        'message' => 'Số điện thoại không được để trống.'
                    ]);
                    return;
                }
                if (is_numeric($phone_number) && strlen(trim($phone_number)) < 10) {
                    echo json_encode([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Vui lòng nhập số điện thoại hợp lệ.'
                    ]);
                    return;
                }
                if (strlen(trim($address)) < 15) {
                    echo json_encode([
                        'success' => false,
                        'type' => 'warning',
                        'message' => 'Vui lòng nhập địa chỉ hợp lệ.'
                    ]);
                    return;
                }
                try {
                    $pdo = new PDO("mysql:host=localhost;dbname=FastFood;charset=utf8mb4", "root", "");
                    $pdo->beginTransaction();
                    $totalAmount = 0;
                    if (isset($_SESSION['Cart'])) {
                        foreach ($_SESSION['Cart'] as $id => $quantity) {
                            $totalAmount += $this->Product->getById($id)->price * $quantity;
                        }
                    }
                    if ($id_info == 0) {
                        if (isset($_SESSION['User'])) {
                            $stmt = $pdo->prepare('INSERT INTO Informations (phone_number, address, user_id) VALUES (?, ?, ?)');
                            $stmt->execute([$phone_number, $address, $_SESSION['User']['id']]);
                        }
                    }

                    $stmt = $pdo->prepare('INSERT INTO Orders (user_id, total, content) VALUES (?, ?, ?)');
                    $stmt->execute([
                        $_SESSION['User']['id'] ?? null,
                        $totalAmount,
                        $name . ' - ' . $phone_number . ' - ' . $address
                    ]);
                    $order = $pdo->lastInsertId();

                    $stmt = $pdo->prepare('INSERT INTO Order_Details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
                    foreach ($_SESSION['Cart'] as $id => $quantity) {
                        $stmt->execute([$order, $id, $quantity, $this->Product->getById($id)->price]);
                    }

                    $pdo->commit();
                    unset($_SESSION['Cart']);
                    echo json_encode([
                        'success' => true,
                        'type' => 'success',
                        'message' => 'Đơn hàng của bạn đã được tạo'
                    ]);
                    exit;
                } catch (PDOException $ex) {
                    $pdo->rollBack();
                    echo "Có lỗi xảy ra : " . $ex->getMessage();
                    echo json_encode(['success' => false, 'type' => 'error']);
                    return;
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'type' => 'info',
                    'message' => 'Giỏ hàng trống'
                ]);
            }
        }
    }

    function order()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $order = $this->Order->getById($id);
            echo json_encode(['success' => true, 'order' => $order]);
        }
    }
}
