<?php
session_start();
require_once 'config/database.php';
require_once 'helpers/authorize.php';
require_once 'helpers/authenticate.php';
foreach (glob("app/models/*.php") as $filename) {
    require_once $filename;
}

$url = $_SERVER['REQUEST_URI'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

$controllerName = isset($url[1]) && $url[1] !== '' ? ucfirst($url[1]) . 'Controller' : 'DefaultController';

$action = isset($url[2]) && $url[2] !== '' ? $url[2] : 'index';

if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    $controllerName = 'DefaultController';
    $action = $url[1];
}

require_once 'app/controllers/' . $controllerName . '.php';

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    $action = 'error';
}

call_user_func_array([$controller, $action], array_slice($url, 3));
