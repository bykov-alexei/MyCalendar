<?php

include_once 'bootstrap.php';
include_once 'classes.php';
include_once 'controllers/UserController.php';

$req = new Request;
$controller = new UserController($req);
switch ($req->method()) {
    
    case 'POST':
        $controller->signIn();
    break;

    case 'PUT':
        $controller->signUp();
    break;
}