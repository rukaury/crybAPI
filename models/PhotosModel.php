<?php
/**
 * @desc This class should be used as a model to control the a single property photo
 * information
 *
 * @author  quadTech
 * @license quadTech
 */

class PhotosModel
{
    public $pid;
    public $type;
    public $file_name;
    public $old_name;

    public function getAllPropertyPhotos() {
        $pdo = DB::get()->prepare("SELECT * FROM photo");
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'PhotosModel');

        $photos = array();
        if (count($result)) {
            foreach($result as $row)
                array_push($photos, $row);
            return $photos;
        } else
            throw new Exception("An error has occured.", 500);
    }

    public function getPropertyPhotos($property_id) {
        $pdo = DB::get()->prepare("SELECT * FROM photo WHERE pid = :id");
        $pdo->execute(array('id' => $property_id));
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'PhotosModel');

        if (count($result) > 1)
            return $result;
        else
            throw new Exception("An error has occured.", 500);
    }

    public function getPropertyPhotosNumber($property_id) {
        $pdo = DB::get()->prepare("SELECT COUNT(*) FROM photo WHERE pid = :id");
        $pdo->execute(array('id' => $property_id));
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'PhotosModel');

        if (count($result) == 1)
            return $result;
        else
            throw new Exception("An error has occured.", 500);
    }

    public function createPropertyPhoto() {
        $pdo = DB::get()->prepare("INSERT INTO photo (pid, type, file_name) VALUES (:pid, :type , :file_name)");
        $pdo->execute(array(
            ':pid' 	=> $this->pid,
            ':type' => $this->type,
            ':file_name' => $this->file_name
        ));

        if ($pdo->rowCount() > 0) {
            $this->pid = DB::lastInsertId('id');
            return $this;
        }
        else
            throw new Exception("An error has occured.", 500);
    }

    public function updatePropertyPhoto() {
        $pdo = DB::get()->prepare("UPDATE photo SET type = :type, file_name = :new_file_name WHERE pid = :id and file_name = :old_file_name");
        $pdo->execute(array(
            ':id'			=> $this->pid,
            ':type' 	=> $this->type,
            ':new_file_name' => $this->file_name,
            ':old_file_name' => $this->old_name
        ));

        if ($pdo->rowCount() > 0) {
            return $this;
        }
        else
            throw new Exception("An error has occured.", 500);
    }

    public function deletePropertyPhoto($property_id, $file_name) {
        $pdo = DB::get()->prepare("DELETE FROM user WHERE pid = :id and file_name = :name");
        $pdo->execute(array(
            ':id' => $property_id,
            ':name' => $file_name
        ));

        if ($pdo->rowCount() > 0) {
            $this->pid = $property_id;
            return $this;
        }
        else
            throw new Exception("An error has occured.", 500);
    }
}