<?php

namespace controllers;

class SignInController
{
    private $db;
    public $accept;
    public $errors = [];

    public function __construct(array $user, $database)
    {
        $this->db = $database;
        if ($userFromDB = $this->db->select($user)[0]) {
            $salt = $userFromDB['salt'];
            $hash = $userFromDB['password'];

            $password = md5($salt . $user['password']);

            if ($password == $hash) {
                $_SESSION['auth'] = true;
                $_SESSION['login'] = $userFromDB['login'];
                $this->accept = true;
            } else {
                $this->errors['password'] = 'incorrect password';
                $this->accept = false;
            }
        } else {
            $this->errors['login'] = 'incorrect login';
            $this->accept = false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getAccept()
    {
        return $this->accept;
    }
}
