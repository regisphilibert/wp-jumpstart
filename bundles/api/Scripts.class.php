<?php

class jsApiScripts {

	function __construct(){
		if(is_admin()){
		    add_action( 'admin_enqueue_scripts', [$this, 'scripts']);
		    add_action('admin_enqueue_scripts', [$this, 'styles']);
		}
	}

	function scripts(){
    	wp_enqueue_script( 'api-script', get_stylesheet_directory_uri().'/bundles/api/js/api.min.js', false, '1.0', true);
    	if(wp_script_is('js-alert-script', 'registered')){
    		wp_enqueue_script('js-alert-script');
    	}
		
	}

	function styles(){
	    wp_enqueue_style( 'api-style', get_stylesheet_directory_uri()."/bundles/api/css/api.css", false, '1.0', $media = 'all' );
    	if(wp_style_is('js-alert-style', 'registered')){
    		wp_enqueue_style('js-alert-style');
    	}
	}

}

new jsApiScripts;