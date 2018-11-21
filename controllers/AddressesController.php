<?php
/**
 * Created by PhpStorm.
 * User: rukau
 * Date: 2018-11-10
 * Time: 22:12
 */

class AddressesController
{
    private $model;
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

    /*
     @uri	/Addresses
     @verb	GET
     @desc	Get a list of Addresses
     */
    /*
     @uri	/Addresses/{id}
     @verb	GET
     @desc	Get one address
     */
    public function getAction($request) {
        if (!empty($request->url_elements[3])) {
            $property_id = (int)$request->url_elements[3];
            if ($property_id)
                // API/Addresses/{id}
                return $this->model->getAddress($property_id);
            else
                throw new Exception("Invalid property ID.", 400);
        } else {
            // API/Addresses
            return $this->model->getAddresses();
        }
    }

    /*
     @uri	/Addresses
     @verb	POST
     @desc	Create one address
     */
    public function postAction($request) {
        // API/Addresses
        $this->model = Helper::cast($request->body->address, $this->model_name);
        if ($this->model->first_name && $this->model->last_name && $this->model->email)
            return $this->model->createAddress();
        else
            throw new Exception("Invalid or missing address object in request.", 400);
    }

    /*
     @uri	/Addresses
     @verb	PUT
     @desc	Update one address
     */
    public function putAction($request) {
        // API/Addresses
        $this->model = Helper::cast($request->body->address, $this->model_name);
        if ($this->model->id)
            return $this->model->updateAddress();
        else
            throw new Exception("Invalid or missing address object in request.", 400);
    }

    /*
     @uri	/Addresses/{id}
     @verb	DELETE
     @desc	Delete one address
     */
    public function deleteAction($request) {
        // API/Addresses/{id}
        if (($request->url_elements[3])) {
            $property_id = (int)$request->url_elements[3];
            if ($property_id)
                return $this->model->deleteAddress($property_id);
            else
                throw new Exception("Invalid address ID.", 400);
        } else {
            throw new Exception("Missing address ID.", 400);
        }
    }

}