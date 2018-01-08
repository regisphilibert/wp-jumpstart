<?php
class jsTemplate{

	private $template;

	public $dir = "views";

	function __construct($template = false, $layout = 'layout'){
		$this->template = $template;
		include __DIR__  . "/../" . $this->dir . "/" . $layout . ".php";
	}

	function content() {
	    global $post;

		if($this->template){
			$view = $this->dir . "/".  $this->template;
			if(locate_template([$view . "php"])) {
				get_template_part($view);
				return;
			}
		}
		$type = str_replace(THEME_SHORTNAME . '_', '', $post->post_type);
		if(!is_dir(get_stylesheet_directory() . "/" . $this->dir . "/" . $type)){ 
			$type = "default"; 
		}
		if(is_archive() || is_home()){
			$layout = "archive";
		} 
		elseif(is_404()){
			$type = "default";
			$layout = "404";
		}
		else {
			$layout = "single";
		}

		$view = $this->dir . '/' . $type . '/';

		// Does this page has a "custom template" assigned to it, and if so, try and locate it.
		$path = get_page_template_slug();

		if($path && locate_template([$view . 'templates/' . basename($path)])){
			$this->template = basename($path, '.php');
			$view .= 'templates/' . $this->template;
		}
		if($this->template){
			// We located a "custom template" and adjuste the view path
			
		} else {
			$view .= $layout;
		}

		$view_file = $view . '.php';
		//ardump($view_file);
	    if(locate_template([$view_file])) {
	        get_template_part($view);
	    }
	}
}