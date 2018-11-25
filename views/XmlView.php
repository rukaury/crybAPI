<?php
/**
 * @desc This class should be used as a to convert any page data in XML format
 *
 * @author  quadTech
 * @license quadTech
 */

class XmlView extends ApiView {
    public function render($content) {
        header('Content-Type: application/xml; charset=utf8');
        echo $this->generate_valid_xml_from_array($content);
        return true;
    }
    
    private function generate_xml_from_array($array, $node_name) {
    	$xml = '';
    	if (is_array($array) || is_object($array)) {
    		foreach ($array as $key=>$value) {
    			if (is_numeric($key)) {
    				$key = $node_name;
    			}
    
    			$xml .= '<' . $key . '>' . "\n" . $this->generate_xml_from_array($value, $node_name) . '</' . $key . '>' . "\n";
    		}
    	} else {
    		$xml = htmlspecialchars($array, ENT_QUOTES) . "\n";
    	}
    	return $xml;
    }
    
    private function generate_valid_xml_from_array($array, $node_block='nodes', $node_name='node') {
    	$xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
    	$xml .= '<' . $node_block . '>' . "\n";
    	$xml .= $this->generate_xml_from_array($array, $node_name);
    	$xml .= '</' . $node_block . '>' . "\n";
    	return $xml;
    }
}