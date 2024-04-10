<?php
class DefaultController
{

    public function __construct()
    {
    }
    public function Index()
    {
        include_once 'app/views/errors/404.php';
    }
}
