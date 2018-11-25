<?php
/**
 * @desc This class should be used as a controller to control the different users authentication
 * methods
 *
 * @author  quadTech
 * @license quadTech
 */

class AuthController extends ApiController
{

    /**
     * @var class $model
     * @desc Address Model to describe an address entity
     */
    private $model;

    /**
     * @var string $model_name
     * @desc Address Model to describe an address entity class name
     */

    private $model_name;

    public function __construct() {
        preg_match("/(.+)Controller$/", get_class($this), $match);
        $this->model_name = $match[1] . "Model";
        if (class_exists($this->model_name)) {
            $this->model = new $this->model_name();
        } else {
            throw new Exception("Model does not exist.", 500);
        }
    }

    public function getAction($request) {
        if (!empty($request->url_elements[3])) {
            $query = (string)$request->url_elements[3];
            $username = (string)$request->url_elements[4];
            $pass = isset($request->url_elements[5]) ? (string)$request->url_elements[5] : "";
            if ($query == "login")
                // API/auth/{username}
                return $this->model->authenticateUser($username, $pass);
            elseif ($query == "username")
                // API/auth/{username}
                return $this->model->authenticateUsername($username);
            elseif ($query == "email")
                // API/auth/{username}
                return $this->model->authenticateEmail($username);
            else
                throw new Exception("Invalid username.", 400);
        }
    }

}