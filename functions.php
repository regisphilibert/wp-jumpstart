<?php
/*********************************************
            THEME OPTIONS
*********************************************/

define(THEME_NAME, 'Phil WP'); //Used for options menu and other.
define(THEME_SHORTNAME, 'phil'); // To be used throughout theme.
define(THEME_ASSET_DIR, 'dist');
define(SITE_GA, 'XX-34343-UI');

define(BUNDLE_API, 1); // Activate API, you must generate a random key here : ./api/api-load.php:3
define(BUNDLE_SEO, 1); // Activate SEO. Then modify inc/custom-seo
define(BUNDLE_OPTIONS, 1); // Activate option page to be managed with ACF Pro
/* LOAD Jumpstart */
//require("jumpstart/_.php");

/* LOAD INC/ */

/* BUNDLES */
$bundles = ['api', 'seo', 'options'];
foreach($bundles as $bundle){
    $constant_name = "BUNDLE_" . strtoupper($bundle);
    if(defined($constant_name) && constant($constant_name)){
        require_once("bundles/$bundle/_.php");
    }
}

require("inc/init.php");


function go_jack(){
    return "go jack...";
}

/**
* Register your scripts directly in inc/scripts.php:phiScripts' __construct and manage enqueue conditions from here:
* class phiScripts takes array of script handles to enqueue
**/

new phiScripts([
    'main-script',
    'modernizr'=>[
        'filename' => 'modernizr.js',
        'deps'=>['main-script']
    ],
    'jquery-masonry'
]);
new phiStyles();

/**
 * Improve Wordpress' get_templart_part by allowing parsing of data
 * Depends on the phiPartial class.
 * @param  [type]  $slug         Name of the view
 * @param  boolean $name_or_data Name or Data param to avoid parsing 3 param just for data
 * @param  array   $data         Data to be parsed to view
 * @return [type]                Output the view.
 */
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

    $t = new phiPartial($data);
    $t->render($view);
}

/*////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
        LE THEME
////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////*/

/*********************************************
            LES MENUS
*********************************************/
function register_theme_menus() {
    register_nav_menus(
        array(
            'main-menu' => 'Menu principal',
        )
    );
}
add_action( 'init', 'register_theme_menus' );
/*********************************************
            SIDEBARS
*********************************************/
register_sidebar( array(
    'name'      => 'Sidebar Principal',
    'id'      => 'sidebar-main',
    'description'  => 'La principale Sidebar',
    'before_widget' =>  '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '',
    'after_title' => ''
    )
);

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails');
    //add_image_size('theme_default', 296, 246 , true);
}


 ?>