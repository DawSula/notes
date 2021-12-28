<?php

declare(strict_types=1);

namespace App\Model;

use PDO;


class UserModel extends AbstractModel
{

    public function checkPasswords(array $params): bool
    {

        return ($params['password'] === $params['repeatedPassword']);
    }


    public function checkIfAllSet(array $params): bool
    {
        foreach ($params as $value) {
            if ($value === "") {
                return false;
            }
        }
        return true;
    }

    public function checkifCorrect(array $params): bool
    {
        $name =  $this->conn->quote($params['name']);
        $password = $this->conn->quote($params['password']);

        $query = "SELECT * FROM users WHERE name=$name";
        $result = $this->conn->query($query);

        $result = $result->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return false;
        }
        if (!(password_verify($password, $result['password']))) {
            return false;
        } else {
            return true;
        }
    }

    public function checkIfExist(array $params): bool
    {
        $name =  $this->conn->quote($params['name']);

        $query = "SELECT count(id) as x FROM users WHERE name=$name";
        $result = $this->conn->query($query);

        $result = $result->fetch(PDO::FETCH_ASSOC);

        if ((int)$result['x'] === 0) {
            return false;
        } else {
            return true;
        }
    }
    public function checkIfStrongPassword(array $params): bool
    {
        $password = $params['password'];

        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            return false;
        }else {
            return true;
        }
    }

    public function userRegister(array $params): void
    {

        $name =  $this->conn->quote($params['name']);
        $password = $this->conn->quote($params['password']);
        $hashPass = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (name, password) VALUES ($name,'$hashPass')";
        $this->conn->exec($query);
    }

    public function startSession($params)
    {
        $name =  $this->conn->quote($params['name']);

        $query = "SELECT id FROM users WHERE name=$name";
        $result = $this->conn->query($query);

        $result = $result->fetch(PDO::FETCH_ASSOC);

        $_SESSION['id'] = $result['id'];
        $_SESSION['start'] = true;
    }
}
