<?php
/*********************************************
            THEME OPTIONS
*********************************************/

define(THEME_NAME, 'Phil WP'); //Utilisé pour les nom de page d'options et autres.
define(THEME_SHORTNAME, 'phil'); // Utilise comme prefix des tables d'options et autre references code.
define(PHI_THEME_OPTIONS, 1); // Activate an option page to be used along ACF.
define(THEME_ASSET_DIR, 'dist');
define(API, 0); // Activate API, you must generate a random key here : ./api/api-load.php:3

/* LOAD Jumpstart */
require("jumpstart/jumpstart_functions.php");

/* LOAD INC/ */

require("inc/init.php");

/* LOAD API ? */
if(API){
    require_once("api/api-load.php");
}

function go_jack(){
    return "go jack...";
}
/**
 * TEMPLATING
 */

function js_get_template($type = 'content', $name = false){
    global $post;
    if(!$name){
        if(is_front_page()){
            $name = 'index';
        }
        else{
            $name = str_replace('js_', '', get_post_type());
            if(is_archive()){
            $type = "archive";
            }
            if(is_singular()){
                $type = "single";
            }
        }
    }
    if(locate_template(['views/' . $type . '-' . $name . '.php'])) {
        get_template_part( 'views/'.$type, $name);
    } else{
        get_template_part( 'views/js_default');
    }
    
}
//We add the option page of the theme (using ACF);
if(PHI_THEME_OPTIONS){
    new phiOptions();
}

/**
* Register your scripts directly in inc/scripts.php:phiScripts' __construct and manage enqueue conditions from here:
* class phiScripts takes array of script handles to enqueue
**/
$scripts_to_enqueue = ['main-script'];
new phiScripts($scripts_to_enqueue);

function js_top(){
    get_template_part( 'views/js_top' );
}
function js_bottom(){
    get_template_part( 'views/js_bottom' );
}

function add_class_to_body_class($classes = ""){
    $classes[] = THEME_SHORTNAME;
    return $classes;
}
add_filter('body_class', 'add_class_to_body_class');


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
/*********************************************
            ADD TO HEAD
*********************************************/
//Pour ajouter des balise ou des variables javascript dans le head, décommenter pour activer.
//add_action( 'wp_head', 'theme_add_to_head' );
function theme_add_to_head(){
    global $post;
    //Pour un site responsive :
    echo '<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">';
    //Des variables JS
    echo "<script>";
    echo "var x='y'";
    echo "</script>";
}

//On register le style nous même (celui à la racine) pour lui ajouter le ver= que l'on veut, Ici c'est un chiffre + . + timestamp de dernière sauvegarde du ficiher.
wp_deregister_style( 'default-style' );

function register_theme_styles(){
    $file_saved = filemtime(get_template_directory()."/dist/css/main.css");
    wp_enqueue_style( 'default-style', get_template_directory_uri()."/dist/css/main.css", false, '1.'.$file_saved, $media = 'all' );
}
add_action('wp_enqueue_scripts', 'register_theme_styles');

 ?>