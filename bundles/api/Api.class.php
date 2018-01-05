<?php
class phiAPI{
	private $args;

	/**
	 * ADDING METHODS
	 * 1/ Add the method to the class.
	 * 2/ Add the method name to the protected variable $endpoints below
	 */
	protected $endpoints = [
		"test", "get_post"
	];

	function __construct(){
		$this->args = $_GET;

		add_filter('template_redirect', [$this, 'template_redirect'], 1);
		
		if(API_KEY == "GENERATE_UNIQUE_KEY_PLEASE"){
		    add_action( 'admin_notices', [$this, 'admin_notice'] );    
		}
	}
	
	/**
	 * METHODS
	 */
	
	/**
	 * Just an API test
	 * @return echo a simple text
	 */
	public function test(){
	    return $this->output(["quote"=>"I want to die with my blue jeans on. - Andy Warhol"]);
	}

	/**
	 * Usage of arguments exemple
	 * @return the post data
	 */
	public function get_post() {
		$post = get_post($this->args['id']);
		return $this->output($post);
	}
 
 	
	/**
	 * output a REST type json response or a printable outout depending on context.
	 * @param  [array|string]  $content The output to be displayed
	 * @param  boolean $error   Will output REST type error response with content
	 * @return [type]           nothing
	 */
	private function output($content, $error = false){
		if(!$error){
			$output = [
				'data'=>$content
			];
		} else {
			$output = [
				'error'=> [
					'message'=>$content,
					'status_code'=>404
				]
			];
		}
		if($this->args['ui']){
			if(function_exists('ardump')){
				ardump($output);
			} else {
				echo '<pre>';
				echo print_r($output);
				echo '</pre>';
			}
			
			die();
		}
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		echo json_encode($output);
		die();
	}

	function template_redirect(){
	    global $wp_query;
	    if (isset($wp_query->query_vars['api_action']) && $wp_query->query_vars['api_key'] == API_KEY) {
	        $action = $wp_query->query_vars['api_action'];
	        $key = $wp_query->query_vars['api_key'];

	        if($action && method_exists($this, $action) && in_array($action, $this->endpoints)){
	            $this->$action($args);
	        } else {
	            $this->output("Method '$action()' does not exist");
	        }
	    }
	}

	function admin_notice() {
	    ?>
	    <div class="notice notice-error is-dismissible">
	        <p><span class="dashicons dashicons-carrot"></span> You're have yet to create a unique key for the <?php echo THEME_NAME ?> API</p>
	    </div>
	    <?php
	}

}
new phiApi;