<?php

include_once 'models/BaseModel.php';

class Task extends BaseModel {
    public $id = NULL, $title = '', $type = '', $time = '', $place = '-', $finished = 0, $length = '', $comment = '', $user_id = '';
    
    protected static $attributes = ['title', 'type', 'time', 'place', 'finished', 'length', 'comment', 'user_id'];
    protected static $table = 'tasks';

    public function fill($array) {
        parent::fill($array);
        if (!$this->finished) {
            $this->finished = 0;
        }
    }

    public function validate() {
        if (!$this->type) {
            $this->error = 'Не указан тип';
            return False;
        }
        if (!$this->length) {
            $this->error = 'Не указана длительность';
            return False;
        }
        return True;
    }

    public static function getTasks($user_id, $from, $to, $finished) {
        if ($finished != 'any') {
            $sql = DB::connect()->prepare('SELECT * FROM tasks WHERE user_id=:user_id AND DATE(time)>=:from AND DATE(time)<=:to AND finished=:finished');
            $params = [
                ':user_id' => $user_id,
                ':from' => $from,
                ':to' => $to,
                ':finished' => $finished,
            ];
        } else {
            $sql = DB::connect()->prepare('SELECT * FROM tasks WHERE user_id=:user_id AND DATE(time)>=:from AND DATE(time)<=:to');
            $params = [
                ':user_id' => $user_id,
                ':from' => $from,
                ':to' => $to,
            ];
        }
        $sql->execute($params);
        return $sql->fetchAll();
    }
}