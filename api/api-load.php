<?php

define(API_KEY, 'GENERATE_UNIQUE_KEY_PLEASE');

function admin_notice__js_api_key() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><span class="dashicons dashicons-carrot"></span> You're have yet to create a unique key for the <?php echo THEME_NAME ?> API</p>
    </div>
    <?php
}
if(API_KEY == "GENERATE_UNIQUE_KEY_PLEASE"){
    add_action( 'admin_notices', 'admin_notice__js_api_key' );    
}


function api_script(){
    wp_enqueue_script( 'api-script', get_stylesheet_directory_uri().'/api/js/api.min.js', false, '1.0', true);
}
function api_style(){
    wp_enqueue_style( 'api-style', get_stylesheet_directory_uri()."/api/css/api.css", false, '1.0', $media = 'all' );
}

if(is_admin()){
    add_action( 'admin_enqueue_scripts', 'api_script' );
    add_action('admin_enqueue_scripts', 'api_style');
}

function load_api_option_page(){
    get_template_part('api/api-page');
}

add_action('admin_menu', 'api_menu');
function api_menu() {
   add_menu_page( THEME_NAME . ' API', THEME_NAME . ' API', 'manage_options', THEME_SHORTNAME . '-api.php', 'load_api_option_page', 'dashicons-carrot', 90 );
}

/*********************************************
        API URL REWRITING
*********************************************/
add_action( 'wp_loaded','api_flush_rules' );
add_filter( 'rewrite_rules_array','api_insert_rewrite_rules' );
add_filter( 'query_vars','api_insert_query_vars' );

function apm_get_rules(){
    return array(
        'api/([^/]*?)/([^/]*?)$' => 'index.php?api_key=$matches[1]&api_action=$matches[2]',
        'api/([^/]*?)/([^/]*?)/([^*]*?)$' => 'index.php?api_key=$matches[1]&api_action=$matches[2]&js_api_args=$matches[3]'
    );
}

function api_flush_rules() {
    $rules = get_option( 'rewrite_rules' );
    $rewrite = 0;
    foreach(apm_get_rules() as $regex=>$rules){
      if(!isset($rules[$regex])){
        $rewrite = 1;
      }
      if($rewrite){
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
      }
    }
}
function api_insert_rewrite_rules( $rules ) {
    $newrules = apm_get_rules();
    return $newrules + $rules;
}
function api_insert_query_vars( $vars ) {
    array_push($vars,
        'api_action',
        'api_key',
        'js_api_args'
    );
    return $vars;
}

add_filter('template_include', 'api_template', 1, 1);
function api_template($template)
{
    global $wp_query;
    if (isset($wp_query->query_vars['api_action']) && $wp_query->query_vars['api_key'] == API_KEY) {
        return dirname(__FILE__) . '/api-functions.php';
    }
    return $template;
}

?>