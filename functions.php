<?php
/*********************************************
            THEME OPTIONS
*********************************************/

define(THEME_NAME, 'Jumpstart'); //Used for options menu and other.
define(THEME_SHORTNAME, 'js'); // prefix to be used throughout theme.
define(THEME_ASSET_DIR, 'dist'); // For use throughout your code, including existing helper functions and asset registering. (Please update Gruntfile.js accordingly)
define(SITE_GA, 'XX-34343-UI');

load_theme_textdomain( THEME_SHORTNAME, get_template_directory() . '/languages' );

define(BUNDLE_API, 1); // Activate API, you must generate a random key here : ./api/api-load.php:3
define(BUNDLE_SEO, 1); // Activate SEO. Then modify inc/custom-seo
define(BUNDLE_OPTIONS, 1); // Activate option page to be managed with ACF Pro

/* BUNDLES */
$bundles = ['api', 'seo', 'options'];
foreach($bundles as $bundle){
    $constant_name = "BUNDLE_" . strtoupper($bundle);
    if(defined($constant_name) && constant($constant_name)){
        require_once("bundles/$bundle/_.php");
    }
}

/* Load php includes from the inc/ folder */
require("inc/init.php");

/*********************************************
            REGISTERING SCRIPTS / STYLES
*********************************************/
/**
 * phiScripts and phiStyles are classes extending for a common ones, therefor they work the same way.
 * You need first to create an instance (constructor deals with registering)
 * And then launch its enqueue() method which deals with enqueuing.
 * 
 * For the registering constructor, pass as parameter an array containing either:
 * handlename => for preregistered wordpress script/style or default scripts (registered by inc/walker/scripts.class.php)
 * arrays => with the script/style expected parameters
 *
 * For the enqueue() method, parameters takes handles to be enqueued, so you can build the array on template conditions. If no parameter, every scripts passed in constructor will be enqueued
 */
$scripts = new jsScripts([
    'main-script',
    'jquery-ui-core',
    'plugins'=>[
        'filename' => 'plugins.min.js',
        'deps'=>['main-script']
    ],
]);
$scripts->enqueue();

$styles = new jsStyles([
    'default-style' => [
        'filename'=>'main.css',
        'deps' => false,
    ],
    'admin-phil' => [
        'filename'=>'admin/phi-admin.css',
    ]
]);
$styles->enqueue();

/**
 * Improve Wordpress' get_templart_part by allowing parsing of data
 * Depends on the phiPartial class.
 * @param  [type]  $slug         Name of the view
 * @param  boolean $name_or_data Name or Data param to avoid parsing 3 param just for data
 * @param  array   $data         Data to be parsed to view
 * @return [type]                Output the view.
 */
if(!function_exists('get_template_include')){
    function get_template_include($slug, $name_or_data = false, $data = []) {
        $name = false;
        if(is_array($name_or_data)){
            $data = $name_or_data;
        } 
        if(is_string($name_or_data)){
            $name = $name_or_data;
        }
        $view = $slug;
        if($name) {
            $view .= '-' . $name;
        }

        $t = new jsPartial($data);
        $t->render($view);
    }
}
/*********************************************
            MENUS ?
*********************************************/
function register_theme_menus() {
    register_nav_menus(
        array(
            'main-menu' => 'Main Menu',
        )
    );
}
add_action( 'init', 'register_theme_menus' );
/*********************************************
            SIDEBARS ? (who needs those these days)
*********************************************/
register_sidebar( array(
    'name'      => 'Main Sidebar',
    'id'      => 'sidebar-main',
    'description'  => 'The main sidebar',
    'before_widget' =>  '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '',
    'after_title' => ''
    )
);
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails');
    // Add other support here
    //add_image_size('theme_default', 296, 246 , true);
}
/**
 * The following functions are safe call to a plugin which display debug data
 */

/**
 * Function from ardump to add sticky element on body with debug info.
 * @param [string/array] $data The sticky to add to body
 */
function addSticky($data){
    if(class_exists('Sticky')){
        global $StickyData;
        new Sticky($StickyData, $data);
    } else {
        return;
    }
}

/**
 * ardump Output some nice dump for 'debug users' only.
 * @param  [string|array] $content The content to output in the dump
 * @return nothing
 */
function ardump($content, $title = false) {
    if(class_exists('jsAlert')){
        $alert = new jsAlert;
        $alert->ardump($content, $title);
    } else {
        return;
    }
}
addSticky("page | logged in as gunther");
addSticky(get_locale());
/**
 * arquick Output a simple string for 'debug users' only.
 * @param  [string|array] $content The content to output in the dump
 * @return nothing
 */
function arquick($content) {
    if(class_exists('jsAlert')){
        $alert = new jsAlert;
        $alert->arquick($content);
    } else {
        return;
    }
}

/**
 * is_debug_user Check if user is a For Your Eyes Only debug user.
 * @param  int|array|object $user Wordpress' user object or id, default to current user.
 * @return boolean          true if user is among debug users, false if not.
 */
function is_debug_user($user = false){
    if(class_exists('jsComponent')){
        $t = new jsComponent;
        return $t->is_debug_user($user);
    }
    return false;
}
