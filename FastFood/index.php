<?php
session_start();
require_once 'config/database.php';
foreach (glob("app/models/*.php") as $filename) {
    require_once $filename;
}
foreach (glob("helpers/*.php") as $filename) {
    require_once $filename;
}

$url = $_SERVER['REQUEST_URI'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

# $controllerName = isset($url[1]) && $url[1] !== '' ? ucfirst($url[1]) . 'Controller' : 'DefaultController';
# $action = isset($url[2]) && $url[2] !== '' ? $url[2] : 'index';

if (isset($url[1]) && $url[1] === 'admin') {
    #Admin
    Authorize::isAdmin();
    $controllerName = isset($url[2]) && $url[2] !== '' ? ucfirst($url[2]) . 'Controller' : 'AdminController';
    $action = isset($url[3]) && $url[3] !== '' ? $url[3] : 'index';
    if (!file_exists('app/controllers/admin/' . $controllerName . '.php')) {
        $controllerName = 'AdminController';
        $action = $url[2];
    }

    require_once 'app/controllers/admin/' . $controllerName . '.php';
    $controller = new $controllerName();

    if (!method_exists($controller, $action)) {
        header('Location: /admin/error');
    }

    call_user_func_array([$controller, $action], array_slice($url, 4));
} elseif (isset($url[1]) && $url[1] === 'api') {
    #API
    $controllerName = isset($url[1]) && $url[1] !== '' ? ucfirst($url[1]) . 'Controller' : 'APIController';
    $action = isset($url[2]) && $url[2] !== '' ? $url[2] : 'index';
    if (!file_exists('app/controllers/api/' . $controllerName . '.php')) {
        die('Not found controller');
    }

    require_once 'app/controllers/api/' . $controllerName . '.php';
    $controller = new $controllerName();

    if (!method_exists($controller, $action)) {
        die('Not found action');
    }
    $controller->$action();
} else {
    #Client
    $controllerName = isset($url[1]) && $url[1] !== '' ? ucfirst($url[1]) . 'Controller' : 'DefaultController';
    $action = isset($url[2]) && $url[2] !== '' ? $url[2] : 'index';
    if (!file_exists('app/controllers/client/' . $controllerName . '.php')) {
        $controllerName = 'DefaultController';
        $action = $url[1];
    }

    require_once 'app/controllers/client/' . $controllerName . '.php';
    $controller = new $controllerName();

    if (!method_exists($controller, $action)) {
        header('Location: /error');
    }

    call_user_func_array([$controller, $action], array_slice($url, 3));
}
