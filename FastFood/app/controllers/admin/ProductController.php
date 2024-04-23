<?php
class ProductController
{
    private $Product;
    private $Category;
    private $db;
    private $path = 'app/views/admin/products/';
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->Product = new Product($this->db);
        $this->Category = new Category($this->db);
    }

    public function Index()
    {
        include_once $this->path . 'index.php';
    }
    public function Create()
    {
        $categories = $this->Category->getList();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include $this->path . 'create.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';
            $category = $_POST['category'] ?? '';
            $description = $_POST['description'] ?? '';

            if (isset($_FILES["image"])) {
                $image = Uploader::uploadImage($_FILES["image"]);
                if ($image) {
                    $result = $this->Product->create($name, $image, $description, $price, $category);

                    if (is_array($result)) {
                        $errors = $result;
                        include $this->path . 'create.php';
                    } else {
                        header('Location: /admin/product');
                    }
                } else {
                    $errors['image'] = 'Error uploading file.';
                    include $this->path . 'create.php';
                }
            }
        }
    }
    public function Update($id)
    {
        try {
            $product = $this->Product->getById($id);
            if ($product) {
                $categories = $this->Category->getList();
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    include $this->path . 'update.php';
                } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $name = $_POST['name'] ?? '';
                    $price = $_POST['price'] ?? '';
                    $category = $_POST['category'] ?? '';
                    $description = $_POST['description'] ?? '';

                    if (isset($_FILES["image"])) {
                        $image = Uploader::uploadImage($_FILES["image"]);
                        if (!$image) {
                            $image = $product->image;
                        }
                        $result = $this->Product->update($id, $name, $image, $description, $price, $category);

                        if (is_array($result)) {
                            $errors = $result;
                            include $this->path . 'update.php';
                        } else {
                            header('Location: /admin/product');
                        }
                    }
                }
            } else  header('Location: /admin/error');
        } catch (\Throwable $th) {
        }
    }
}
