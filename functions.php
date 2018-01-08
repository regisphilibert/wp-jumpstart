<?php
/*********************************************
            THEME OPTIONS
*********************************************/

define(THEME_NAME, 'Jumpstart'); //Used for options menu and other.
define(THEME_SHORTNAME, 'js'); // prefix to be used throughout theme.
define(THEME_ASSET_DIR, 'dist'); // For use throughout your code, including existing helper functions and asset registering. (Please update Gruntfile.js accordingly)
define(SITE_GA, 'XX-34343-UI');

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
addSticky("homepage");
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

// Register Custom Post Type
function custom_post_type() {

    $labels = array(
        'name'                  => _x( 'Testees', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Testee', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Post Types', 'text_domain' ),
        'name_admin_bar'        => __( 'Post Type', 'text_domain' ),
        'archives'              => __( 'Item Archives', 'text_domain' ),
        'attributes'            => __( 'Item Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
        'all_items'             => __( 'All Items', 'text_domain' ),
        'add_new_item'          => __( 'Add New Item', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Item', 'text_domain' ),
        'edit_item'             => __( 'Edit Item', 'text_domain' ),
        'update_item'           => __( 'Update Item', 'text_domain' ),
        'view_item'             => __( 'View Item', 'text_domain' ),
        'view_items'            => __( 'View Items', 'text_domain' ),
        'search_items'          => __( 'Search Item', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
        'items_list'            => __( 'Items list', 'text_domain' ),
        'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Testee', 'text_domain' ),
        'description'           => __( 'Post Type Description', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'test_type', $args );

}
add_action( 'init', 'custom_post_type', 0 );

 ?>