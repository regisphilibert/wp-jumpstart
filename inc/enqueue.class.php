<?php

class jsEnqueue {

	public $items;
	public $default_items;
	public $enqueue_items;
	public $type;
	public $dir;
	function __construct($items = []){
		if(empty($items)){
			$this->items = $this->default_items;
		} else {
			$this->items = $items;
		}

		add_action('init', [$this, 'register']);
	
	}

	function register() {
		foreach($this->items as $handle => $item){
			$to_register = false;
			if(is_array($item)){
				$handle_name = $handle;
				$to_register = $item;
			} else {
				$handle_name = $item;
				if(isset($this->default_items[$handle_name])){
					$to_register = $this->default_items[$handle_name];
				}
			}
			if($to_register && $this->type == 'style' && $handle_name == 'default-style'){
				wp_deregister_style( 'default-style' );
			}
			if(!call_user_func('wp_' . $this->type . '_is', 'registered', $handle_name)){
				$saved_file = "0";
				$file_path = js_get_asset_path($this->dir . '/' . $to_register['filename']);
				if(file_exists($file_path)){
					$saved_file = filemtime($file_path);
				}
				call_user_func('wp_register_' . $this->type, 
					$handle_name,
					js_get_asset_uri($this->dir . '/'. $to_register['filename']),
					isset($to_register['deps']) ? $to_register['deps'] : false,
					'1.' . $saved_file,
					isset($to_register['in_footer']) ? $to_register['in_footer'] : false
				);
			}
		}
	}
	function enqueue($items = false){
		if(!$items){
			$this->enqueue_items = $this->items;
		} else {
			$this->enqueue_items = $items;
		}
		add_action('wp_enqueue_scripts', [$this, 'enqueue_handler']);
	}

	function enqueue_handler() {
		foreach($this->enqueue_items as $handle => $item){
			$handle_name = is_array($item) ? $handle : $item;
			if(call_user_func('wp_' . $this->type . '_is', $handle_name, 'registered') && !call_user_func('wp_' . $this->type . '_is', $handle_name)){
				call_user_func('wp_enqueue_' . $this->type, $handle_name);
			}
		}	
	}
}

class jsScripts extends jsEnqueue {
	function __construct($items = []) {
		$this->dir = 'js';
		$this->type = 'script';
		$this->default_items = [
			'main-script' => [
				'filename'=>'global.min.js',
				'deps' => ['jquery'],
				'in_footer' => true
			]
		];
		parent::__construct($items);
	}
}

class jsStyles extends jsEnqueue {
	function __construct($items = []) {
		$this->dir = 'css';
		$this->type = 'style';
		$this->default_items = [
			'default-style' => [
				'filename'=>'main.css',
			'deps' => false,
			]
		];
		parent::__construct($items);
	}
}
