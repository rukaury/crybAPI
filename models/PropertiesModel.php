<?php
/**
 * Created by PhpStorm.
 * property: rukau
 * Date: 2018-11-10
 * Time: 22:33
 */

class PropertiesModel
{
    public $pid;
    public $uid;
    public $type;
    public $num_of_bedrooms;
    public $num_of_bathrooms;
    public $num_of_other_rooms;
    public $description;

    public function getProperties() {
        $pdo = DB::get()->prepare("SELECT * FROM property as p, address as a where p.pid = a.pid");
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'PropertiesModel');
        $properties = array();
        if (count($result)) {
            foreach($result as $row)
                array_push($properties, $row);
            return $properties;
        } else
            throw new Exception("Page not found.", 204);
    }

    public function getPropertiesByOwner($owner_id) {
        $pdo = DB::get()->prepare("SELECT * FROM property as p, address as a WHERE p.uid = :id and p.pid = a.pid");
        $pdo->execute(array('id' => $owner_id));
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'PropertiesModel');

        if (count($result) > 0)
            return $result;
        else
            throw new Exception("Page not found.", 404);
    }

    public function getProperty($property_id) {
        $pdo = DB::get()->prepare("SELECT * FROM property as p, address as a WHERE p.pid = :id and p.pid = a.pid");
        $pdo->execute(array('id' => $property_id));
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'PropertiesModel');

        if (count($result) == 1)
            return $result;
        else
            throw new Exception("Page not found.", 404);
    }

    public function getPropertiesByOptions($property_options) {
        $sqlQuery = "SELECT * FROM property as p, address as a WHERE p.pid = a.pid";
        foreach ($property_options as $key => $value) {
            if($key == 'minPrice')
                $sqlQuery .= " and price >= " . $value;
            elseif($key == 'maxPrice')
                $sqlQuery .= " and price <= "  . $value;
            elseif($key == 'num_of_bedrooms')
                $sqlQuery .= " and " . $key . " >= " . $value;
            elseif($key == 'num_of_bathrooms')
                $sqlQuery .= " and " . $key . " >= " . $value;
            else
                $sqlQuery .= " and " . $key . " = '" . $value . "'";
        }
        $pdo = DB::get()->prepare($sqlQuery);
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'PropertiesModel');

        if (count($result) > 0)
            return $result;
        else
            throw new Exception("Page not found.", 404);
    }

    public function getPropertiesHtmlView($property_options) {
        $sqlQuery = "SELECT * FROM property as p, address as a, photo as ph WHERE p.pid = a.pid and p.pid = ph.pid";
        foreach ($property_options as $key => $value) {
            if($key == 'minPrice')
                $sqlQuery .= " and price >= " . $value;
            elseif($key == 'maxPrice')
                $sqlQuery .= " and price <= "  . $value;
            elseif($key == 'num_of_bedrooms')
                $sqlQuery .= " and " . $key . " >= " . $value;
            elseif($key == 'num_of_bathrooms')
                $sqlQuery .= " and " . $key . " >= " . $value;
            else
                $sqlQuery .= " and " . $key . " = '" . $value . "'";
        }
        $pdo = DB::get()->prepare($sqlQuery);
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'PropertiesModel');

        if (count($result) > 0){
            $html = "<div class='col-lg-4 col-md-6 d-flex'>\n";
            $html .= "<div class='col-lg-4 col-md-6 d-flex'>\n";
            return $result;
        }
        else
            throw new Exception("Page not found.", 404);
    }

    public function createProperty() {
        $pdo = DB::get()->prepare("INSERT INTO property (uid, type, num_of_bedrooms, num_of_bathrooms, num_of_other_rooms, price, area, description) VALUES (:uid, :type, :num_of_bedrooms, :num_of_bathrooms, :num_of_other_rooms, :price, :area, :description");
        $pdo->execute(array(
            ':uid' 	=> $this->uid,
            ':type' 	=> $this->type,
            ':num_of_bedrooms'		=> $this->num_of_bedrooms,
            ':num_of_bathrooms'		=> $this->num_of_bathrooms,
            ':num_of_other_rooms'		=> $this->num_of_other_rooms,
            ':price'		=> $this->price,
            ':area'		=> $this->area,
            ':description'		=> $this->description
        ));

        if ($pdo->rowCount() > 0) {
            $this->pid = DB::lastInsertId('id');
            return $this;
        }
        else
            throw new Exception("Page not found..", 404);
    }

    public function updateProperty() {
        $pdo = DB::get()->prepare("UPDATE property SET first_name = :first_name, last_name = :last_name, email = :email, sex = :sex, type = :type, pass = :pass, description = :description, uid = :uid WHERE pid = :id");
        $pdo->execute(array(
            ':id'			=> $this->pid,
            ':uid' 	=> $this->uid,
            ':type' 	=> $this->type,
            ':num_of_bedrooms'		=> $this->num_of_bedrooms,
            ':num_of_bathrooms'		=> $this->num_of_bathrooms,
            ':num_of_other_rooms'		=> $this->num_of_other_rooms,
            ':price'		=> $this->price,
            ':area'		=> $this->area,
            ':description'		=> $this->description
        ));

        if ($pdo->rowCount() > 0) {
            return $this;
        }
        else
            throw new Exception("Page not found..", 404);
    }

    public function deleteProperty($property_id) {
        $pdo = DB::get()->prepare("DELETE FROM property WHERE pid = :id");
        $pdo->execute(array(
            ':id' => $property_id
        ));

        if ($pdo->rowCount() > 0) {
            $this->pid = $property_id;
            return $this;
        }
        else
            throw new Exception("Page not found..", 404);
    }
}