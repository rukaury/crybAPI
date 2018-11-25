<?php
/**
 * @desc This class should be used as a to convert any page data in JSON format
 *
 * @author  quadTech
 * @license quadTech
 */

class JsonView extends ApiView {
    public function render($content) {
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($content);
        return true;
    }
}