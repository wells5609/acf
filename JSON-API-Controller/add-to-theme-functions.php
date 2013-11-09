<?php
/**
 *	NOTE: This code should go theme's functions.php file
**/


// Add a custom controller
add_filter('json_api_controllers', 'my_api_controllers');

function my_api_controllers($controllers) {

	// Corresponds to the class JSON_API_ACF_Controller
	$controllers[] = 'ACF';

	return $controllers;

}

// Register the source file for JSON_API_ACF_Controller
add_filter('json_api_acf_controller_path', 'acf_controller_path');

function acf_controller_path($default_path) {
	
	// use get_stylesheet_directory_uri() if child theme
	return get_template_directory() . '/api/controllers/acf-controller.php';

}



?>