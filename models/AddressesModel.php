<?php
/**
 * Created by PhpStorm.
 * User: rukau
 * Date: 2018-11-10
 * Time: 22:17
 */

class AddressesModel
{
    public $pid;
    public $street;
    public $number;
    public $country;
    public $state;
    public $city;
    public $apartment;

    public function getAddresses() {
        $pdo = DB::get()->prepare("SELECT * FROM address");
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'AddressesModel');

        $addresses = array();
        if (count($result)) {
            foreach($result as $row)
                array_push($addresses, $row);
            return $addresses;
        } else
            throw new Exception("No addresses found.", 204);
    }

    public function getAddress($property_id) {
        $pdo = DB::get()->prepare("SELECT * FROM address WHERE pid = :id");
        $pdo->execute(array('id' => $property_id));
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'AddressesModel');

        if (count($result) == 1)
            return $result;
        else
            throw new Exception("No address found.", 204);
    }

    public function createAddress() {
        $pdo = DB::get()->prepare("INSERT INTO address (number, street, country, state, apartment, city) VALUES (:number, :street, 
:country, :state, : apartment, :city)");
        $pdo->execute(array(
            ':number' 	=> $this->first_name,
            ':street' 	=> $this->last_name,
            ':country'		=> $this->email,
            ':state'		=> $this->state,
            ':city'		=> $this->city,
            ':apartment'		=> $this->apt
        ));

        if ($pdo->rowCount() > 0) {
            $this->id = DB::lastInsertId('id');
            return $this;
        }
        else
            throw new Exception("No address was created.", 500);
    }

    public function updateAddress() {
        $pdo = DB::get()->prepare("UPDATE address SET number = :number, street = :street, country = :country, state = :state, 
apartment = :apartment, city = :city 
WHERE pid =
 :id");
        $pdo->execute(array(
            ':id'			=> $this->pid,
            ':number' 	=> $this->first_name,
            ':street' 	=> $this->last_name,
            ':country'		=> $this->email,
            ':state'		=> $this->state,
            ':apartment'		=> $this->apt,
            ':city'	=> $this->city
        ));

        if ($pdo->rowCount() > 0) {
            return $this;
        }
        else
            throw new Exception("No address was updated.", 200);
    }

    public function deleteAddress($property_id) {
        $pdo = DB::get()->prepare("DELETE FROM address WHERE pid = :id");
        $pdo->execute(array(
            ':id'			=> $property_id
        ));

        if ($pdo->rowCount() > 0) {
            $this->pid = $property_id;
            return $this;
        }
        else
            throw new Exception("No address was deleted.", 400);
    }
}