<?php
class phiAPI{
	private $args;

	public $endpoints;

	function __construct(){
		$this->args = $_GET;

		add_filter('template_redirect', [$this, 'template_redirect'], 1);
		
		if(API_KEY == "GENERATE_UNIQUE_KEY_PLEASE"){
		    add_action( 'admin_notices', [$this, 'admin_notice'] );    
		}
	}

	/* List your endpoint here. There should be the only public function of the class.

	/**
	 * Just an API test
	 * @return echo a simple text
	 */
	public function test(){
	    return $this->output("I want to die with my blue jeans on. - Andy Warhol");
	}

	/**
	 * Usage of arguments exemple
	 * @return the post data
	 */
	public function get_post() {
		$post = get_post($this->args['id']);
		return $this->output($post);
	}
 
 

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

	        if($action && method_exists($this, $action)){
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