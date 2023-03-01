<?php

namespace controllers;

class SignUpController
{
    private $db;
    public $accept;
    public $errors;

    public function __construct(array $user, $database)
    {
        $this->db = $database;

        if ($this->validateFields($user)) {
            $salt = $this->generateSalt();
            $user['password'] = md5($salt . $user['password']);
            $user['salt'] = $salt;
            unset($user['confirmPassword']);
            $this->db->insertRow($user);

            $_SESSION['auth'] = true;
            $_SESSION['login'] = $user['login'];
            $this->accept = true;
        } else {
            $this->accept = false;
        }
    }

    private function generateSalt()
    {
        $salt = '';
        $saltLength = 8;

        for ($i = 0; $i < $saltLength; $i++) {
            $salt .= chr(mt_rand(33, 126));
        }

        return $salt;
    }

    private function validateFields(array $user)
    {
        switch (true) {
            case strlen($user['login']) < 6:
                $this->errors['login'] = 'invalid login: min 6 characters';
                return false;
            case stristr($user['login']," "):
                $this->errors['login'] = 'invalid login: can\'t containt spaces';
                return false;
            case strlen($user['password']) < 6 || preg_match('#[a-z0-9]+#', $user['password']) != 1 || stristr($user['password']," "):
                $this->errors['password'] = 'invalid password: min 6 characters and must contain only letters and digits';
                return false;
            case !filter_var($user['email'], FILTER_VALIDATE_EMAIL):
                $this->errors['email'] = 'invalid email';
                return false;
            case strlen($user['name']) < 2 || preg_match("#[A-z]+#", $user['name']) != 1 || stristr($user['name']," "):
                $this->errors['name'] = 'name: min 2 characters and must contain only letters';
                return false;
            case $this->db->uniqueCheck($user, 'login', 'email'):
                $this->errors['unique'] = 'not unique email or login';
                return false;
            case $user['password'] != $user['confirmPassword']:
                $this->errors['match'] = 'passwords don\'t match';
                return false;
            default:
                return true;
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
