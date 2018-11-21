<?php
/**
 * Created by PhpStorm.
 * User: rukau
 * Date: 2018-11-10
 * Time: 21:42
 */

class CustomersModel
{
    public $uid;
    public $first_name;
    public $last_name;
    public $sex;
    public $pass;
    public $username;
    public $isOwner;
    public $email;

    public function getCustomers() {
        $pdo = DB::get()->prepare("SELECT * FROM customer");
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'CustomersModel');

        $users = array();
        if (count($result)) {
            foreach($result as $row)
                array_push($users, $row);
            return $users;
        } else
            throw new Exception("No users found.", 204);
    }

    public function getCustomer($user_id) {
        $pdo = DB::get()->prepare("SELECT * FROM customer WHERE uid = :id");
        $pdo->execute(array('id' => $user_id));
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'CustomersModel');

        if (count($result) == 1)
            return $result;
        else
            throw new Exception("No user found.", 204);
    }

    public function createCustomer() {
        $pdo = DB::get()->prepare("INSERT INTO customer (first_name, last_name, email, sex, isOwner, username, pass) VALUES 
(:first_name, 
:last_name, 
:email, :sex, :isOwner, :username, :pass)");
        $pdo->execute(array(
            ':first_name' 	=> $this->first_name,
            ':last_name' 	=> $this->last_name,
            ':email'		=> $this->email,
            ':sex'		=> $this->sex,
            ':isOwner'		=> $this->isOwner,
            ':username'		=> $this->username,
            ':pass'		=> $this->pass
        ));

        if ($pdo->rowCount() > 0) {
            $this->uid = DB::lastInsertId('id');
            return $this->uid;
        }
        else
            throw new Exception("No user was created.", 500);
    }

    public function updateCustomer() {
        $pdo = DB::get()->prepare("UPDATE customer SET first_name = :first_name, last_name = :last_name, email = :email, sex = :sex,
 username = :username, pass = :pass
 WHERE id = :id");
        $pdo->execute(array(
            ':id'			=> $this->uid,
            ':first_name' 	=> $this->first_name,
            ':last_name' 	=> $this->last_name,
            ':email'		=> $this->email,
            ':sex'		=> $this->sex,
            ':username'		=> $this->username,
            ':pass'		=> $this->pass
        ));

        if ($pdo->rowCount() > 0) {
            return $this;
        }
        else
            throw new Exception("No user was updated.", 200);
    }

    public function deleteCustomer($user_id) {
        $pdo = DB::get()->prepare("DELETE FROM customer WHERE id = :id");
        $pdo->execute(array(
            ':id'			=> $user_id
        ));

        if ($pdo->rowCount() > 0) {
            $this->uid = $user_id;
            return $this;
        }
        else
            throw new Exception("No customer was deleted.", 400);
    }

}