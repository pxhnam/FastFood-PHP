<?php
class CategoryController
{

    public function __construct()
    {
        Authorize::isRole('admin');
    }
    public function Index()
    {
        include_once 'app/views/categories/index.php';
    }
}
