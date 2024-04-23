<?php
class AdminController
{
    private $dir = 'app/views/admin/home/';
    public function index()
    {
        include_once $this->dir . 'index.php';
    }
    public function Error()
    {
        include_once 'app/views/admin/errors/404.php';
    }
}
