<?php

class BaseController {

    protected $request;

    public function __construct($request) {
        $this->request = $request;
    }

    public function response() {
        return $this->request->create_response();
    }

    public function error($error) {
        $title = 'Ошибка';
        include 'views/error.php';
    }

}