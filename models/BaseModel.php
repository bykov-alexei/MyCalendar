<?php

include_once 'classes.php';

function array_get($array, $key, $default=NULL) {
    $array = (array)$array;
    if (isset($array[$key]) && $array[$key] != '') {
        return $array[$key];
    }
    return $default;
}

class BaseModel {
    public $id;

    protected static $attributes = [];
    protected static $table;
    protected $error;

    public function validate() {
        return true;
    }

    public function save() {
        if ($this->validate()) {
            $sql = DB::connect()->prepare('INSERT INTO `' . static::$table . '` (`' . implode('`, `', static::$attributes) . '`) VALUES (:' . implode(', :', static::$attributes) . ');');
            $data = [];
            foreach (static::$attributes as $attribute) {
                $data[$attribute] = $this->$attribute;
            }
            $sql->execute($data);
            return $sql->rowCount() === 1;
        }
        return false;
    }

    public function update() {
        if ($this->id && $this->validate()) {
            $set = [];
            foreach (static::$attributes as $attribute) {
                $set[] = '`' . $attribute . '` = :' . $attribute;
            }
            $sql = DB::connect()->prepare('UPDATE `' . static::$table . '` SET ' . implode(', ', $set) . ' WHERE id = :id LIMIT 1;');
            $data = [];
            foreach (static::$attributes as $attribute) {
                $data[$attribute] = $this->$attribute;
            }
            $data['id'] = $this->id;

            $sql->execute($data);
            var_dump($this);
            return $sql->errorInfo();
        }
        return false;
    }

    public function delete() {
        if ($this->id && $this->validate()) {
            $sql = DB::connect()->prepare('DELETE FROM `' . static::$table . '` WHERE id = :id LIMIT 1;');
            $data = [];
            $data['id'] = $this->id;
            $sql->execute($data);
            return $sql->errorInfo();
        }
        return false;
    }

    public function fill($array) {
        foreach (static::$attributes as $attribute) {
            $value = array_get($array, $attribute);
            if ($value) {
                $this->$attribute = $value;
            }
        }
    }

    public static function get_all() {
        $sql = DB::connect()->prepare('SELECT * FROM `' . static::$table . '`;');
        $sql->execute();

        $objects = [];

        while ($object = $sql->fetchObject(static::class)) {
            $objects[] = $object;
        }

        return $objects;
    }

    public static function getById($id) {
        $sql = DB::connect()->prepare('SELECT * FROM `' . static::$table . '` WHERE id = :id LIMIT 1;');
        $sql->execute(['id' => $id]);
        $object = $sql->fetchObject(static::class);
        return $object;
    }

    public function errorInfo() {
        return $this->error;
    }
}