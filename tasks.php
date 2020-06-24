<?php

include_once 'bootstrap.php';
include_once 'classes.php';
include_once 'controllers/TaskController.php';

$req = new Request;
$controller = new TaskController($req);

switch ($req->method()) {
    case 'GET':
        $controller->view();
    break;
    
    case 'PUT':
        $controller->put();
    break;

    case 'DELETE':
        $controller->delete();
    break;
}