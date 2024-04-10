<?php
class OrderController
{
    // private $db;
    public function __construct()
    {
        // $this->db = (new Database())->getConnection();
        // $this->User = new User($this->db);
        Authorize::isRole('admin');
    }
    public function Index()
    {
        include_once 'app/views/orders/index.php';
    }
}
