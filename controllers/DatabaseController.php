<?php

namespace controllers;

use Jajo\JSONDB;

class DatabaseController
{
    private $database;

    public function __construct()
    {
        $this->database = new JSONDB('databases');
    }

    public function insertRow(array $user)
    {
        $this->database->insert('users.json', $user);
    }

    public function select(array $user)
    {
        $users = $this->database->select('*')
            ->from('users.json')
            ->where(['login' => $user['login']])
            ->get();
        if ($users) {
            return $users;
        } else {
            return false;
        }
    }

    public function uniqueCheck(array $user, string $field1 = null, string $field2 = null)
    {
        $users = $this->database->select($field1, $field2)
            ->from('users.json')
            ->where([$field1 => $user[$field1], $field2 => $user[$field2]])
            ->get();
        if ($users) {
            return $users;
        } else {
            return false;
        }
    }

    public function update(array $fieldsWithArgs, array $whereClause)
    {
        $users = $this->database->update($fieldsWithArgs)
            ->from('users.json')
            ->where($whereClause)
            ->trigger();
        if ($users) {
            return $users;
        } else {
            return false;
        }
    }

    public function delete(array $whereClause)
    {
        $users = $this->database->delete()
            ->from('users.json')
            ->where($whereClause)
            ->trigger();
    }
}
