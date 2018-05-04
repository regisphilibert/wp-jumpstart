<?php
class jsPartial{

	private $vars;

	public function __construct($data){
		if($data){
			$this->vars = $data;
		}
	}

	public function render($view, $dir = "views/includes/"){
		$ext = ".php";
		$dot_position = strrpos($view, '.');
		// An extension is already present in the $view
	    if ( $dot_position ){
	    	$ext = "";
	    }
		$path = $dir . $view . $ext;
		if(locate_template([$path])){
			$this->load_template(get_stylesheet_directory() . "/" . $path);
		}
	}

	public function get($key = false){
		if(!$key){
			return $this->vars;
		}
		if(isset($this->vars[$key])){
			return $this->vars[$key];
		}
		return false;
	}

	public function say($key){
		if($this->get($key)){
			echo $this->vars[$key];
		}
	}

	public function load_template($_template_file){
    	global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;
 
	    if ( is_array( $wp_query->query_vars ) ) {
	        extract( $wp_query->query_vars, EXTR_SKIP );
	    }
	 
	    if ( isset( $s ) ) {
	        $s = esc_attr( $s );
	    }
	
	    require( $_template_file );
	}

}