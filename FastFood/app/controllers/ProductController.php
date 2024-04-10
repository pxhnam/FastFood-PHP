<?php
class ProductController
{
    private $Product;
    private $Category;
    private $db;
    public function __construct()
    {
        Authorize::isRole('admin');
        $this->db = (new Database())->getConnection();
        $this->Product = new Product($this->db);
        $this->Category = new Category($this->db);
    }

    public function Index()
    {
        include_once 'app/views/products/index.php';
    }
    public function Create()
    {
        $categories = $this->Category->get();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include 'app/views/products/create.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';
            $category = $_POST['category'] ?? '';
            $description = $_POST['description'] ?? '';

            // Xử lý tải lên hình ảnh đại diện
            if (isset($_FILES["image"])) {
                $image = $this->uploadImage($_FILES["image"]);
                if ($image) {
                    // Lưu đường dẫn của hình ảnh đại diện vào CSDL
                    $result = $this->Product->create($name, $image, $description, $price, $category);

                    if (is_array($result)) {
                        // Có lỗi, hiển thị lại form với thông báo lỗi
                        $errors = $result;
                        include 'app/views/products/create.php';
                    } else {
                        // Không có lỗi, chuyển hướng ve trang chu hoac trang danh sach
                        header('Location: /product');
                    }
                } else {
                    $errors['image'] = 'Error uploading file.';
                    include 'app/views/products/create.php';
                }
            }
        }
    }
    public function Update($id)
    {
        $product = $this->Product->getProductById($id);
        $categories = $this->Category->get();
        if ($product) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                include 'app/views/products/update.php';
            } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'] ?? '';
                $price = $_POST['price'] ?? '';
                $category = $_POST['category'] ?? '';
                $description = $_POST['description'] ?? '';

                if (isset($_FILES["image"])) {
                    $image = $this->uploadImage($_FILES["image"]);
                    if (!$image) {
                        $image = $product->image;
                    }
                    $result = $this->Product->update($id, $name, $image, $description, $price, $category);

                    if (is_array($result)) {
                        $errors = $result;
                        include 'app/views/products/update.php';
                    } else {
                        header('Location: /product');
                    }
                }
            }
        } else echo 'Not Found';
    }

    public function uploadImage($file)
    {
        // Check if a file was uploaded
        if (!empty($file["tmp_name"]) && is_uploaded_file($file["tmp_name"])) {
            $targetDirectory = "uploads/";
            $targetFile = $targetDirectory . basename($file["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Kiểm tra xem file có phải là hình ảnh thực sự hay không
            $check = getimagesize($file["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }

            // Kiểm tra kích thước file
            if ($file["size"] > 500000) { // Ví dụ: giới hạn 500KB
                $uploadOk = 0;
            }

            // Kiểm tra định dạng file
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $uploadOk = 0;
            }

            // Kiểm tra nếu $uploadOk bằng 0
            if ($uploadOk == 0) {
                return false;
            } else {
                // Đổi tên tệp hợp lệ và di chuyển tệp vào thư mục đích
                $newFileName = uniqid() . '_' . basename($file["name"]); // Đổi tên tệp để tránh xung đột tên
                $targetFile = $targetDirectory . $newFileName;

                if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                    return $newFileName;
                } else {
                    return false;
                }
            }
        } else {
            // No file uploaded, return false or handle as needed
            return false;
        }
    }
}
