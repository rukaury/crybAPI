<?php
/**
 * @desc This class should be used as a controller to control the different property(s) information
 * requests of the API
 *
 * @author  quadTech
 * @license quadTech
 */

class PropertiesController
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
     @uri	/Properties
     @verb	GET
     @desc	Get a list of Properties
     */
    /*
     @uri	/Properties/{id}
     @verb	GET
     @desc	Get one property
     */
    public function getAction($request) {
        if (!empty($request->url_elements[3])) {
            $property_query = $request->url_elements[3];
            if ($property_query != 'options' && $property_query != 'owner'){
                // crybAPI/properties/{id}
                return $this->model->getProperty((int)$property_query);
            }
            elseif ($property_query == 'options'){
                // crybAPI/properties/options/{city}/{type}/{#of_bedrooms}/{#of_bathrooms}/{minPrice}/{maxPrice}
                $property_options = array();
                if(isset($request->url_elements[4]) && $request->url_elements[4] != 'none'){
                    $property_options['city'] = $request->url_elements[4] ;
                }
                if(isset($request->url_elements[5]) && $request->url_elements[5] != 'none'){
                    $property_options['type'] = $request->url_elements[5];
                }
                if(isset($request->url_elements[6]) && (int)$request->url_elements[6] != 0){
                    $property_options['num_of_bedrooms'] = $request->url_elements[6];
                }
                if(isset($request->url_elements[7]) && (int)$request->url_elements[7] != 0){
                    $property_options['num_of_bathrooms'] = $request->url_elements[7];
                }
                if(isset($request->url_elements[8]) && (int)$request->url_elements[8] != 0){
                    $property_options['minPrice'] = $request->url_elements[8];
                }
                if(isset($request->url_elements[9]) && (int)$request->url_elements[9] != 0){
                    $property_options['maxPrice'] = $request->url_elements[9];
                }
                return $this->model->getPropertiesByOptions($property_options);
            }
            elseif ($property_query == 'owner'){
                // crybAPI/properties/owner/{id}
                //}/{maxPrice}
                return $this->model->getPropertiesByOwner((int)$request->url_elements[4]);
            }
            else
                throw new Exception("Invalid property info.", 400);
        } else {
            // crybAPI/Properties
            return $this->model->getProperties();
        }
    }

    /*
     @uri	/Properties
     @verb	POST
     @desc	Create one property
     */
    public function postAction($request) {
        // crybAPI/Properties
        $this->model = Helper::cast($request->body->property, $this->model_name);
        if ($this->model->uid && $this->model->p_type && $this->model->num_of_bedrooms &&
            $this->model->num_of_bathrooms && $this->model->price && $this->model->description)
            return $this->model->createProperty();
        else
            throw new Exception("Invalid or missing property object in request.", 400);
    }

    /*
     @uri	/Properties
     @verb	PUT
     @desc	Update one property
     */
    public function putAction($request) {
        // crybAPI/Properties
        $this->model = Helper::cast($request->body->property, $this->model_name);
        if ($this->model->pid and $this->model->bid){
            return $this->model->updatePropertyBid();
        }
        else if ($this->model->is_deleted){
            return $this->model->unDeleteProperty();
        }
        else if ($this->model->pid){
            return $this->model->updateProperty();
        }
        else{
            throw new Exception("Invalid or missing property object in request.", 400);
        }
    }

    /*
     @uri	/Properties/{id}
     @verb	DELETE
     @desc	Delete one property
     */
    public function deleteAction($request) {
        // crybAPI/Properties/{id}
        if (($request->url_elements[3])) {
            $property_id = (int)$request->url_elements[3];
            if ($property_id)
                return $this->model->deleteProperty($property_id);
            else
                throw new Exception("Invalid property ID.", 400);
        } else {
            throw new Exception("Missing property ID.", 400);
        }
    }
}