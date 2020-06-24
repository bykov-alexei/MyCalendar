<?php

include_once 'controllers/BaseController.php';
include_once 'models/Task.php';
include_once 'models/User.php';

class TaskController extends BaseController {
    public static $lengthes = [
        'Менее 10 мин', '1 час', 'Несколько часов', 'Весь день',
    ];
    public static $types = [
        'Встреча', 'Звонок', 'Совещание', 'Дело',
    ];

    public function view() {
        if ($user = $this->getUser()) {
            if (array_key_exists('list', $this->request->get())) {
                $title = 'Задачи';
                include 'views/list.php';
            
            } else if (array_key_exists('edit', $this->request->get())) {
                $title = 'Задача';
                $task = new Task;
                if (array_key_exists('id', $this->request->get())) {
                    if ($this->checkAccess()) {
                        $task = Task::getById($this->request->get()['id']);
                    } else {
                        $this->error("Нет прав для редактирования этой задачи");
                        return;
                    }
                }
                include 'views/task.php';
            } else {
                $this->get();
            }
        }
    }

    public function put() {
        if ($user = $this->getUser()) {
            $data = $this->request->post();
            $task = new Task;
            $task->fill($data);
            if (!$task->validate()) {
                $this->response()->status(406)->json(['message' => $task->errorInfo()]);
            }
            if ($data['id']) {
                $task->id = $data['id'];
                if ($task->user_id == $user->id) {
                    var_dump($task);
                    $task->update();
                    $this->response()->status(202)->redirect('/tasks.php?list');
                } else {
                    $this->response()->status(403)->json(['message' => 'Нет прав на изменение этой задачи']);
                }
            } else {
                $task->user_id = $user->id;
                $res = $task->save();
                if ($res) {
                    $this->response()->status(201)->redirect('/tasks.php?list');
                }
            }
        }
    }

    public function get() {
        if ($user = $this->getUser()) {
            $user_id = $user->id;
            $from = $this->request->get()['from'];
            $to = $this->request->get()['to'];
            $finished = $this->request->get()['option'];
            $tasks = Task::getTasks($user_id, $from, $to, $finished);
            $this->response()->status(200)->json($tasks);
        }
    }

    public function delete() {
        $user = User::getByToken($_COOKIE['token']);
        $user_id = $user->id;
        $task = Task::getById($this->request->post()['id']);
        var_dump($task);
        if (!$task) {
            $this->response()->status(200);
        } else if ($task->user_id != $user_id) {
            $this->response()->status(403)->json(['message' => 'Нет прав для удаления этой задачи']);
        } else {
            $task->delete();
            $this->response()->status(200);
        }
    }

    public function getUser() {
        $token = $_COOKIE['token'] ?? NULL;
        $user = User::getByToken($token);
        if (!$user) {
            $this->response()->redirect('/index.php');
            return NULL;
        }
        return $user;
    }

    public function checkAccess() {
        $task = Task::getById($this->request->get()['id']);
        $user = User::getByToken($_COOKIE['token']);
        if (!$task) {
            return false;
        }
        if (!$user) {
            return false;
        }
        return ($task->user_id === $user->id);
    }
}