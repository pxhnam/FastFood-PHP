<?php
class CategoryController
{

    public function __construct()
    {
        Authorize::isAdmin();
    }
    public function Index()
    {
        include_once 'app/views/admin/categories/index.php';
    }
}
