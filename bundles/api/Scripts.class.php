<?php

class phiApiScripts {

	function __construct(){
		if(is_admin()){
		    add_action( 'admin_enqueue_scripts', [$this, 'scripts']);
		    add_action('admin_enqueue_scripts', [$this, 'styles']);
		}
	}

	function scripts(){
    	wp_enqueue_script( 'api-script', get_stylesheet_directory_uri().'/api/js/api.min.js', false, '1.0', true);
	}

	function styles(){
	    wp_enqueue_style( 'api-style', get_stylesheet_directory_uri()."/api/css/api.css", false, '1.0', $media = 'all' );
	}

}

new phiApiScripts;