<?php
/**
 * @desc This class should be used as a controller to control the different customer information
 * requests of the API
 *
 * @author  quadTech
 * @license quadTech
 */

class CustomersController
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
     @uri	/Customers
     @verb	GET
     @desc	Get a list of Customers
     */
    /*
     @uri	/Customers/{id}
     @verb	GET
     @desc	Get one customer
     */
    public function getAction($request) {
        if (!empty($request->url_elements[3])) {
            $customer_id = (int)$request->url_elements[3];
            if ($customer_id)
                // API/Customers/{id}
                return $this->model->getCustomer($customer_id);
            else
                throw new Exception("Invalid Customer ID.", 400);
        } else {
            // API/Customers
            return $this->model->getCustomers();
        }
    }

    /*
     @uri	/Customers
     @verb	POST
     @desc	Create one customer
     */
    public function postAction($request) {
        // API/Customers
        $this->model = Helper::cast($request->body->customer, $this->model_name);
        if ($this->model->first_name && $this->model->last_name && $this->model->email)
            return $this->model->createCustomer();
        else
            throw new Exception("Invalid or missing customer object in request.", 400);
    }

    /*
     @uri	/Customers
     @verb	PUT
     @desc	Update one customer
     */
    public function putAction($request) {
        // API/Customers
        $this->model = Helper::cast($request->body->customer, $this->model_name);
        if ($this->model->id)
            return $this->model->updateCustomer();
        else
            throw new Exception("Invalid or missing customer object in request.", 400);
    }

    /*
     @uri	/Customers/{id}
     @verb	DELETE
     @desc	Delete one customer
     */
    public function deleteAction($request) {
        // API/Customers/{id}
        if (($request->url_elements[3])) {
            $customer_id = (int)$request->url_elements[3];
            if ($customer_id)
                return $this->model->deleteCustomer($customer_id);
            else
                throw new Exception("Invalid customer ID.", 400);
        } else {
            throw new Exception("Missing customer ID.", 400);
        }
    }
}