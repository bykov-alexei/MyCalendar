<?php

include_once 'models/BaseModel.php';

class User extends BaseModel {
    public $login, $password;

    protected static $attributes = ['login', 'password'];
    protected static $table = 'users';

    public function __construct() {
        $login = '';
        $password = '';
    }

    public function getHash() {
        return md5(strval($this->login) . strval($this->password));
    }

    public static function getByLogin($login) {
        $db = DB::connect();
        $sql = $db->prepare('SELECT login, password FROM users WHERE login=:login');
        $sql->execute([
            ':login' => $login,
        ]);
        return $sql->fetchObject(static::class);
    }

    public static function getByToken($token) {
        $db = DB::connect();
        $sql = $db->prepare('SELECT id, login, password FROM users WHERE MD5(CONCAT(login, password))=:token');
        $res = $sql->execute([
            ':token' => $token,
        ]);
        return $sql->fetchObject(static::class);
    }

    public function validate() {
        if (!preg_match('/^\w{4,}$/u', $this->login)) {
            $this->error = "Логин долженсодержать минимум 4 символа";
            return false;
        } 
        if (!preg_match('/\S{4,}/u', $this->password)) {
            $this->error = "Пароль должен содержать минимум 4 непробельных символа";
            return false;
        }
        return true;
    }
}