<?php
/**
 * @desc This class should be used as a template of any type of controller. It is not used in our
 * API.
 *
 * @author  quadTech
 * @license quadTech
 */

class VisitationsController extends ApiController
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
     @uri	/visitations
     @verb	GET
     @desc	Get a list of visitations
     */
    /*
     @uri	/visitations/{uid}
     @verb	GET
     @desc	Get users visitation
     */
    public function getAction($request) {
        if (!empty($request->url_elements[3])) {
            $user_id = (int)$request->url_elements[3];
            if ($user_id)
                // API/visitations/{id}
                return $this->model->getUserVisitations($user_id);
            else
                throw new Exception("Invalid User ID.", 400);
        } else {
            // API/users
            return $this->model->getAllVisitations();
        }
    }

    /*
     @uri	/visitation
     @verb	POST
     @desc	Create one visitation
     */
    public function postAction($request) {
        // API/users
        $this->model = Helper::cast($request->body->visitation, $this->model_name);
        if ($this->model->uid && $this->model->pid)
            return $this->model->createVisitation();
        else
            throw new Exception("Invalid or missing user object in request.", 400);
    }

    /*
     @uri	/visitations
     @verb	PUT
     @desc	Update one visitation
     */
    public function putAction($request) {
        // API/users
        $this->model = Helper::cast($request->body->visitation, $this->model_name);
        if ($this->model->id)
            return $this->model->updateVisitation();
        else
            throw new Exception("Invalid or missing user object in request.", 400);
    }

    /*
     @uri	/visitations/{uid, pid}
     @verb	DELETE
     @desc	Delete one visitation
     */
    public function deleteAction($request) {
        // API/users/{id}
        if (($request->url_elements[3])) {
            $uid = (int)$request->url_elements[3];
            $pid = (int)$request->url_elements[3];
            if ($uid and $pid)
                return $this->model->deleteVisitation($uid, $pid);
            else
                throw new Exception("Invalid User ID.", 400);
        } else {
            throw new Exception("Missing User ID.", 400);
        }
    }
}