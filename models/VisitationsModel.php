<?php
/**
 * Created by PhpStorm.
 * User: rukau
 * Date: 2018-11-28
 * Time: 16:48
 */

class VisitationsModel
{
    public $pid;
    public $uid;

    public function getUserVisitations($uid) {
        $pdo = DB::get()->prepare("SELECT * FROM visitation where uid = :uid");
        $pdo->execute(array('uid' => $uid));
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'VisitationsModel');

        $visitations = array();
        if (count($result)) {
            foreach($result as $row)
                array_push($visitations, $row);
            return $visitations;
        } else
            throw new Exception("No users found.", 204);
    }

    public function getAllVisitations() {
        $pdo = DB::get()->prepare("SELECT * FROM visitation");
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'VisitationsModel');

        $visitations = array();
        if (count($result)) {
            foreach($result as $row)
                array_push($visitations, $row);
            return $visitations;
        } else
            throw new Exception("No users found.", 204);
    }

    public function createVisitation() {
        $pdo = DB::get()->prepare("SELECT :pid, :uid FROM visitation");
        $pdo->execute(array(
            ':uid' 	=> $this->uid,
            ':pid' 	=> $this->pid
        ));

        if ($pdo->rowCount() == 0) {
            $pdo = DB::get()->prepare("INSERT INTO visitation (pid,uid) VALUES (:pid, :uid)");
            $pdo->execute(array(
                ':uid' 	=> $this->uid,
                ':pid' 	=> $this->pid
            ));

            if ($pdo->rowCount() == 1) {
                return $this;
            }
        }
        else
            throw new Exception("No visitation was created.", 500);
    }

    public function updateVisitation() {
        $pdo = DB::get()->prepare("UPDATE user SET first_name = :first_name, last_name = :last_name, email = :email WHERE id = :id");
        $pdo->execute(array(
            ':uid'			=> $this->uid,
            ':pid' 	=> $this->pid
        ));

        if ($pdo->rowCount() > 0) {
            return $this;
        }
        else
            throw new Exception("No visitation was updated.", 200);
    }

    public function deleteVisitation($uid, $pid) {
        $pdo = DB::get()->prepare("DELETE FROM visitation WHERE uid = :uid and pid = :pid");
        $pdo->execute(array(
            ':uid'			=> $uid,
            ':pid'			=> $pid
        ));

        if ($pdo->rowCount() > 0) {
            $this->pid = $pid;
            $this->uid = $uid;
            return $this;
        }
        else
            throw new Exception("No visitation was deleted.", 400);
    }
}