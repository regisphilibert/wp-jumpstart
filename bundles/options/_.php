<?php
class phiOptions {

	public $params;

	function __construct($params = []) {
		$default_params = [
	        'page_title'=> THEME_NAME . ' Options',
	        'menu_title'=> THEME_NAME . ' Options',
	        'menu_slug' => THEME_SHORTNAME . '_options'
	    ];
    	$this->params = array_merge($default_params, $params);
	    if(function_exists('acf_add_options_page')){
	        acf_add_options_page($this->params);
	    } else {
	    	add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	    }
		
	}

	function admin_menu() {
		add_menu_page(
			$this->params['page_title'],
			$this->params['menu_title'],
			'manage_options',
			$this->params['menu_slug'],
			array(
				$this,
				'temporary_options_page'
			)
		);
	}

	function temporary_options_page() { ?>
		<h2><?php echo $this->params['page_title']; ?></h2>
		<p>
			You need to install ACF Pro to manage this page.
		</p>
	<?php }
}

new phiOptions;