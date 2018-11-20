<?php
/**
 * Created by PhpStorm.
 * User: rukau
 * Date: 2018-11-10
 * Time: 22:41
 */

class PhotosController
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
     @uri	/Photos
     @verb	GET
     @desc	Get a list of property photos
     */
    /*
     @uri	/Photos/{id}
     @verb	GET
     @desc	Get all property photos from property
     */
    public function getAction($request) {
        if (!empty($request->url_elements[3])) {
            $property_id = (int)$request->url_elements[3];
            if (is_int($property_id) && $request->url_elements[4] == 'allData')
                // API/Photos/{id}
                return $this->model->getPropertyPhotos($property_id);
            elseif (is_int($property_id) && $request->url_elements[4] == 'number')
                // API/Photos/{id}
                return $this->model->getPropertyPhotosNumber($property_id);
            else
                throw new Exception("Invalid property ID.", 400);
        } else {
            // API/Photos
            return $this->model->getAllPropertyPhotos();
        }
    }

    /*
     @uri	/Photos
     @verb	POST
     @desc	Create one PropertyPhoto
     */
    public function postAction($request) {
        // API/Photos
        $this->model = Helper::cast($request->body->property, $this->model_name);
        if ($this->model->first_name && $this->model->last_name && $this->model->email)
            return $this->model->createPropertyPhoto();
        else
            throw new Exception("Invalid or missing property object in request.", 400);
    }

    /*
     @uri	/PropertyPhotos
     @verb	PUT
     @desc	Update one property
     */
    public function putAction($request) {
        // API/propertyPhotos
        $this->model = Helper::cast($request->body->property, $this->model_name);
        if ($this->model->id)
            return $this->model->updatePropertyPhoto();
        else
            throw new Exception("Invalid or missing property object in request.", 400);
    }

    /*
     @uri	/PropertyPhotos/{id}
     @verb	DELETE
     @desc	Delete one property
     */
    public function deleteAction($request) {
        // API/propertyPhotos/{id}
        if (($request->url_elements[3])) {
            $property_id = (int)$request->url_elements[3];
            if ($property_id)
                return $this->model->deletePropertyPhoto($property_id);
            else
                throw new Exception("Invalid property ID.", 400);
        } else {
            throw new Exception("Missing property ID.", 400);
        }
    }
}