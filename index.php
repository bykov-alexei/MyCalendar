<?php

include_once 'bootstrap.php';

include_once 'classes.php';

$title = 'Войти';
$req = new Request;
$method = $req->method();
switch ($method) {
    case 'GET': 
        include 'views/auth.php';
    break;
}