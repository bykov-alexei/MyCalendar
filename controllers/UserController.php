<?php

include_once 'controllers/BaseController.php';
include_once 'models/User.php';

class UserController extends BaseController {
    
    public function signIn() {
        $user = new User;
        $user->fill($this->request->post());
        if (!$user->validate()) {
            $this->response()->status(406)->json(['message' => $user->errorInfo()]);
            return;
        }
        $dbUser = User::getByLogin($user->login);
        if (!$dbUser) {
            $this->response()->json(['message' => 'Такой пользователь не зарегестрирован']);
        } else {
            if ($dbUser->password !== $user->password) {
                $this->response()->json(['message' => 'Неверный пароль']);    
            } else {
                $this->response()->cookie('token', $user->getHash())->
                                    status(202)->
                                    redirect('/tasks.php?list');
            }
        }
    }

    public function signUp() {
        $user = new User;
        $user->fill($this->request->post());
        if (!$user->validate()) {
            $this->response()->status(406)->json(['message' => $user->errorInfo()]);
            return;
        }
        $dbUser = User::getByLogin($user->login);
        if ($dbUser) {
            $this->response()->json(['message' => 'Такой пользователь уже зарегестрирован']);
        } else {
            $user->save();
            $this->response()->cookie('token', $user->getHash())->
                                    status(201)->
                                    redirect('/tasks.php?list');
        }
    }
}