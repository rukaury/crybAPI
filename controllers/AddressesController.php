<?php
/**
 * @desc This class should be used as a controller to control the different address(es) information
 * requests of the API
 *
 * @author  quadTech
 * @license quadTech
 */

class AddressesController
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

    /*
     @uri	/Addresses
     @verb	GET
     @desc	Function should retrieve all or 1 address.
     */
    /*
     @uri	/Addresses/{id}
     @verb	GET
     @desc	Get one address by querying the URI
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
        if ($this->model->pid && $this->model->house_number && $this->model->street &&
            $this->model->country && $this->model->state && $this->model->city)
            return $this->model->createAddress();
        else
            throw new Exception("Invalid or missing address object in request.", 404);
    }

    /*
     @uri	/Addresses
     @verb	PUT
     @desc	Update one address
     */
    public function putAction($request) {
        // API/Addresses
        $this->model = Helper::cast($request->body->address, $this->model_name);
        if ($this->model->pid)
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