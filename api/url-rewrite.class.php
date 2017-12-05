<?php
class phiApiRewrite {
	private $rules;
	private $query_vars;
	function __construct(){
		$this->rules = [
			'api/([^/]*?)/([^/]*?)$' => 'index.php?api_key=$matches[1]&api_action=$matches[2]'
		];

		$this->query_vars = [
	        'api_action',
	        'api_key',
		];

		add_action( 'wp_loaded', [$this, 'flush_rules'] );
		add_filter( 'rewrite_rules_array', [$this, 'insert_rewrite_rules'] );
		add_filter( 'query_vars',[$this, 'insert_query_vars'] );
	}

	function flush_rules() {
	    $rules = get_option( 'rewrite_rules' );
	    $rewrite = 0;
	    foreach($this->rules as $regex=>$rules){
	      if(!isset($rules[$regex])){
	        $rewrite = 1;
	      }
	      if($rewrite){
	            global $wp_rewrite;
	            $wp_rewrite->flush_rules();
	      }
	    }
	}
	function insert_rewrite_rules( $rules ) {
	    $newrules = $this->rules;
	    return $newrules + $rules;
	}
	function insert_query_vars( $vars ) {
	    $vars = array_merge($vars, $this->query_vars);
	    return $vars;
	}
}
new phiApiRewrite;