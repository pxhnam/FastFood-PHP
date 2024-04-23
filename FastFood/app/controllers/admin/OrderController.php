<?php
class OrderController
{
    public function __construct()
    {
        Authorize::isAdmin();
    }
    public function Index()
    {
        include_once 'app/views/admin/orders/index.php';
    }
}
