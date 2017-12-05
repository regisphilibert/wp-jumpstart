<?php

class phiScripts {

	private $scripts;
	private $to_enqueue;
	function __construct($to_enqueue = []){
		$this->scripts = [
			'main-script' => [
		        'filename'=>'global.min.js',
		        'deps' => ['jquery'],
		        'in_footer' => true
			]
		];
		
		$this->to_enqueue = $to_enqueue;

		add_action('init', [$this, 'register_scripts']);
		
		add_action('wp_enqueue_scripts', [$this, 'add_scripts_to_page']);
	}

	function register_scripts() {
		foreach($this->scripts as $key => $script){
			$saved_file = filemtime(phi_get_asset_path('js/' . $script[1]));
		    wp_register_script(
		        $key,
		        phi_get_asset_uri('js/' . $script['filename']),
		        $script['deps'],
		       	'1.' . $saved_file,
		        $script['in_footer']
		    );
		}
	}
	
	function add_scripts_to_page() {
		foreach($this->to_enqueue as $script){
			if(isset($this->scripts[$script])){
				wp_enqueue_script( $script );
			}
			
		}
	}
}