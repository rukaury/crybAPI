<?php
/**
 * @desc This class should be used as a model to control the a single Address
 * information
 *
 * @author  quadTech
 * @license quadTech
 */

class AddressesModel
{
    public $pid;
    public $street;
    public $house_number;
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
        $query = "INSERT INTO address (pid, house_number, street, city, state, country, apartment) VALUES (:pid, 
:house_number, :street, :city, :state, :country, :apartment)";
        $pdo = DB::get()->prepare($query);
        $pdo->execute(array(
            ':pid' 	=> $this->pid,
            ':house_number' 	=> $this->house_number,
            ':street' 	=> $this->street,
            ':city'		=> $this->city,
            ':state'		=> $this->state,
            ':country'		=> $this->country,
            ':apartment'		=> $this->apartment
        ));

        if ($pdo->rowCount() > 0) {
            return $this->pid;
        }
        else
            throw new Exception("No address was created.", 404);
    }

    public function updateAddress() {
        $pdo = DB::get()->prepare("UPDATE address SET house_number = :house_number, street = :street, country = :country, state = :state, 
apartment = :apartment, city = :city 
WHERE pid =
 :id");
        $pdo->execute(array(
            ':house_number' 	=> $this->house_number,
            ':street' 	=> $this->street,
            ':apartment'		=> $this->apartment
        ));

        if ($pdo->rowCount() > 0) {
            return $this;
        }
        else
            throw new Exception("No address was updated.", 404);
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