<?php

/*********************************************
            ACF 5 PRO
*********************************************/
function my_admin_error_notice() {
    $acf_included_data = get_plugin_data( get_stylesheet_directory() . '/' . JUMPSTART_DIR . '/deps/acf-pro/acf.php');
    $class = "updated jumpstart";
    $message = "<strong>Jumpstart says :</strong> The awesome Advanced Custom Fields {$acf_included_data['Version']} by the even more awesome {$acf_included_data['AuthorName']} is presently included in Jumpstart.<br>
    To be able to update it on your own you need to purchase it here <a href='{$acf_included_data['PluginURI']}'>{$acf_included_data['PluginURI']}</a>";
    echo"<div class=\"$class\"> <p>$message</p></div>";
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(! is_plugin_active('advanced-custom-fields-pro/acf.php') ){
    add_action( 'admin_notices', 'my_admin_error_notice' );
    require_once( get_stylesheet_directory() . '/' . JUMPSTART_DIR . '/deps/acf-pro/acf.php' );
    require_once( get_stylesheet_directory() . '/' . JUMPSTART_DIR . '/deps/acf-pro/api/api-helpers.php');
    add_filter('acf/settings/path', 'my_acf_settings_path');
    add_filter('acf/settings/dir', 'my_acf_settings_dir');
    add_filter('acf_settings', 'my_acf_settings');
}

function my_acf_settings_path( $path ) {
    // update path
    $path = get_stylesheet_directory() . '/' . JUMPSTART_DIR . '/deps/acf-pro/';
    // return
    return $path;
}

function my_acf_settings_dir( $dir ) {
    // update path
    $dir = get_stylesheet_directory_uri() . '/' . JUMPSTART_DIR . '/deps/acf-pro/';
    // return
    return $dir;
}

if(!acf_group_exists("RP Tools") || 1==1){
    require_once(get_stylesheet_directory() . '/' . JUMPSTART_DIR . '/deps/acf_rp_fields.php');
}
if(function_exists('acf_add_options_page')){
  acf_add_options_page(array(
    'title'=>'Jumpstart',
    'parent' => 'options-general.php',
    'capability' => 'manage_options'
  ));
}
define( 'ACF_LITE' , get_artools_option('acf_menu') );

?>