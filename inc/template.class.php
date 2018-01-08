<?php
class jsTemplate{

	private $template;

	function __construct($template = false){
	
		$this->template = $template;

		$this->top();
		$this->content();
		$this->bottom();
	}

	function top() {
		get_template_part( 'views/top' );
	}

	function bottom() {
		get_template_part( 'views/bottom' );
	}

	function content() {
	    global $post;

		$type = str_replace(THEME_SHORTNAME . '_', '', $post->post_type);

		if(is_archive() || is_home()){
			$layout = "archive";
		} else {
			$layout = "single";
		}

		$view = 'views/' . $type . '/';

		$path = get_page_template_slug();
		if($path && locate_template([$view . 'templates/' . basename($path)])){
			$this->template = basename($path, '.php');
		}
		
		if($this->template){
			$view .= 'templates/' . $this->template;
		} else {
			$view .= $layout;
		}

		$view_file = $view . '.php';
	    if(locate_template([$view_file])) {
	        get_template_part($view);
	    } else{
	        get_template_part( 'views/default');
	    }
	}
}