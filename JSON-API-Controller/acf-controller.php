<?php
/*
Controller name: ACF
Controller description: Returns Advanced Custom Fields.
*/

/**
 *	NOTE: This file should be located in your theme directory: "../api/controllers/acf-controller.php"
**/

class JSON_API_ACF_Controller {
	
	public function get_fields() {
		
		global $json_api;
		$fields = $json_api->query->fields;
		$post_id = $json_api->query->post_id;
		
		$this->check_fields_and_id(true, $post_id);
		
		if ( ! $fields || 'all' === $fields || "*" === $fields ) {
			$field_objects = get_fields($post_id);			
		}
		else {
			
			$fieldArray = explode(',', $fields);
			
			$field_objects = array();
			
			foreach($fieldArray as $field) :
				
				$field_objects[$field] = get_field($field, $post_id);
				
			endforeach;
			
		}
				
		return array(
			'post_id' => $post_id,
			'results' => $field_objects,
		);
	}
	
	public function get_field_objects() {
		
		global $json_api;
		
		$fields = $json_api->query->fields;
		$post_id = $json_api->query->post_id;
		
		$this->check_fields_and_id(true, $post_id);
		
		if ( ! $fields || 'all' === $fields || "*" === $fields ) {
			$field_objects = get_field_objects($post_id);			
		}
		else {
						
			$fieldArray = explode(',', $fields);
			
			$field_objects = array();
			
			foreach($fieldArray as $field) :
				
				$field_objects[] = get_field_object($field, $post_id);
				
			endforeach;
		}
		
		return array(
			'post_id' => $post_id,
			'results' => $field_objects,
		);
		
	}
	
	private function check_fields_and_id($fields, $post_id) {
		
		global $json_api;
		
		if ( ! $fields ) {	
			return $json_api->error("Include a 'fields' in your request, fool.");
		}
		if ( ! $post_id ) {
			return $json_api->error("Include 'post_id' in your request, fool.");	
		}	
	}
	
	
}


?>
