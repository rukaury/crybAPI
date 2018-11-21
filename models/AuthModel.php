<?php
/**
 * Created by PhpStorm.
 * User: rukau
 * Date: 2018-10-30
 * Time: 22:47
 */

class AuthModel extends ApiModel
{

    public function authenticateUser($email, $pass) {
        $pdo = DB::get()->prepare("SELECT uid, isOwner FROM customer WHERE email = :email and pass = :pass");
        $pdo->execute(array('email' => $email, 'pass' => $pass));
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'AuthModel');
        if (count($result) == 1)
            return $result[0];
        else
            throw new Exception("No user found.", 400);
    }

    public function authenticateUsername($username) {
        $pdo = DB::get()->prepare("SELECT uid FROM customer WHERE username = :username");
        $pdo->execute(array('username' => $username));
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'AuthModel');
        $success = 1;
        if (count($result) == 1)
            return $success;
        else
            throw new Exception("No user found.", 400);
    }

    public function authenticateEmail($email) {
        $pdo = DB::get()->prepare("SELECT uid FROM customer WHERE email = :email");
        $pdo->execute(array('email' => $email));
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'AuthModel');
        $success = 1;
        if (count($result) == 1)
            return $success;
        else
            throw new Exception("No user found.", 400);
    }

}