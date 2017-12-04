<?php
/**
* Jumpstart
* Version 1.2
*/

define(JUMPSTART_DIR, "jumpstart");
define(ACF_VER, 5);
//This functions goes here to be used with load.php

require_once("tool-box.php");

function acf_group_exists($group_title){
    $args = array(
        'post_type'=>'acf',
        'post_status'=>'publish',
        'name'=>'acf_'.sanitize_title($group_title)
    );
    $group = get_posts($args);
    //print_r($group);
    return !empty($group) ? current($group) : false;
}
require_once("deps/load.php");
require_once("classes/seo.php");
/**
*
* Permet d'afficher une options du plugin en renseignant directement son "nom" sans le prefix du plugin.
* @param @option : Le nom de l'option
* @return La valeur de l'option en question ou
* - toutes les options du plugin si @option n'est pas renseignée ou
* - faux si l'option n'est pas trouvée.
*
*/
function get_artools_option($option = false){
    if(function_exists('get_field')){
        return get_global_option('rptools_'.$option);
    }
    $options = get_option('artools');
    if($option){
        return $options['artools'.'_'.$option] != '' ? $options['artools'.'_'.$option] : false ;
    }
    else{
        return empty($options) ? (object)$options : false;
    }
}

function get_rptools_option($option){
    if(function_exists('get_field')){
        return get_global_option('rptools_'.$option);
    }
}

function get_global_option($name) {
    $option = get_field($name, 'option');
    if(!$option){
        add_filter('acf/settings/current_language', 'cl_acf_set_language', 100);
        $option = get_field($name, 'option');
        remove_filter('acf/settings/current_language', 'cl_acf_set_language', 100);
    }
    return $option;
}

/**
 * [add_rptools_options description]
 * @param array  $params     http://www.advancedcustomfields.com/resources/acf_add_options_page/
 * @param string $capability deprecated
 */
function add_rptools_options($params = array(), $capability = 'manage_options'){
    if(function_exists('acf_add_options_page')){
        $default_params = array(
            'page_title'=> THEME_NAME . ' Options',
            'menu_title'=> THEME_NAME . ' Options',
            'menu_slug' => THEME_SHORTNAME . '_options'
        );
        $params = array_merge($default_params, $params);
        acf_add_options_page($params);
    }
}
/*********************************************
            DEBUG ACTIONS
*********************************************/

/**
 * artools_add_js_var
 * Où on ajoute des variables récupérable en javascript.
 * Ajouter les variables à l'array en début de fonction.
 * @return void
 *
 * @author
 **/
function rptools_add_js_var(){
    $debug_user = is_debug_user() ? 1 : 0;
    $js_vars = array(
        'rptools'=>1,
        'debug_user'=>$debug_user,
        'display_debug'=>get_artools_option('display_debug') ? 1 : 0,
    );
    if($debug_user){
        global $current_user;
        get_currentuserinfo();
        $user_infos = (array) $current_user->data;
        //$user_infos = json_encode($user_infos);
        $js_vars['user_id'] = $user_infos['ID'];
    }
    echo '<script>';
    foreach($js_vars as $k=>$v){
        echo 'var '.$k.'='.$v.';';
    }
    echo '</script>';
}
add_action( 'wp_head', 'rptools_add_js_var', 10, 1 );

//SCRIPT

function js_move_jquery_to_footer()
{
    if (!is_admin())
    {
        wp_deregister_script('jquery');
        wp_register_script('jquery', '/wp-includes/js/jquery/jquery.js', FALSE, '1.11.0', TRUE);
        wp_enqueue_script('jquery');
    }
}
add_action('init', 'js_move_jquery_to_footer');

wp_register_script(
    'rptools-core-script',
    get_stylesheet_directory_uri()."/".JUMPSTART_DIR.'/js/rptools.script-min.js',
    false,
    '1.0',
    true
);
wp_register_script(
    'rptools-admin-script',
    get_stylesheet_directory_uri()."/".JUMPSTART_DIR.'/js/rptools.admin.js',
    array('jquery'),
    '1.0',
    true
);
wp_register_script(
    'rptools-modernizr',
    get_stylesheet_directory_uri()."/".JUMPSTART_DIR.'/js/libs/modernizr.dev.js',
    false,
    '2.6.2',
    false
);
wp_register_style(
    'rptools-bootstrap',
    get_stylesheet_directory_uri()."/".JUMPSTART_DIR.'/css/bootstrap.custom.css',
    false,
    "1"
);
wp_register_style(
    'rptools-style',
    get_stylesheet_directory_uri()."/".JUMPSTART_DIR.'/css/style.css',
    false,
    "1"
);
add_action('wp_enqueue_scripts','rptools_scripts');

function rptools_scripts(){
    if(!is_admin()){
        wp_enqueue_script('rptools-core-script');
    }
    if(get_artools_option('modernizr_dev')){
        wp_enqueue_script('rptools-modernizr');
    }
}
function admin_rptools_scripts(){
    //wp_enqueue_script('rptools-core-script');
    wp_enqueue_script( 'rptools-admin-script' );
}
add_action( 'admin_enqueue_scripts', 'admin_rptools_scripts' );

add_action('wp_enqueue_scripts','rptools_style');
function rptools_style(){
    if(is_debug_user()){
        wp_enqueue_style('rptools-style');
    }
    if(get_artools_option('bootstrap_css') && is_debug_user() && !is_admin()){
        wp_enqueue_style( 'rptools-bootstrap' );
    }
}
if(is_admin()){
    add_action('admin_enqueue_scripts', 'rptools_custom_admin_script_css');
}
function rptools_custom_admin_script_css() {
    wp_enqueue_style( 'rptools-bootstrap' );
    wp_enqueue_style('rptools-style');
    wp_enqueue_style( 'admin_css', (get_template_directory_uri() ."/".JUMPSTART_DIR.'/css/admin.css'), false, '1.0.0' );
}
/*********************************************
      AJAX
*********************************************/
function add_ajax_library(){
    $html = '<script type="text/javascript">';
    $html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
    $html .= '</script>';
    echo $html;
}
if(get_artools_option('ajax_on')){
    add_action('wp_head', 'add_ajax_library');
}

/*********************************************
            ADD CLASSES TO BODY
*********************************************/
function rptools_add_class_to_body_class($classes = ""){
    if(is_debug_user()){
        $classes[] = 'jumpstart-debug-user';
    }
    if(is_debug_mode()){
        $classes[] = 'jumpstart-debug-mode';
    }
    return $classes;
}
add_filter('body_class', 'rptools_add_class_to_body_class');

/**
 *
 * Display a message/array or a list of messages/arrays, only for the users checked as Debug Users
 *
 * @alert       The message or an array of messages to display
 * @type        Type of the alert : normal | warning | danger
 * @title       A title to display.
 * @return      Return a print out of the alert message
 *
 */

if(!function_exists('prod_alert')){
    function prod_alert($alerts, $type = false, $title = false, $content = 'string'){
        global $prod_alert;
        $title = $title ? $title : "Informations debug (ne seront pas visible en production)";
        $prod_alert[] = $alert;
        if(is_string($alerts)){
            $messages[] = $alerts;
        }
        else{
            $messages = $alerts;
        }
        if(is_debug_user()){
            echo "<div class='debug-alert ".$type."'>";
            echo "<nav><a class='expend-alert'>< - ></a> | <a class='close-alert'>X</a></nav>";
            echo "<div class='debug-alert-title'>$title</div>";
            echo "<div class='debug-alert-content'>";
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $backtrace = $backtrace[1];
            echo "<div class='backtrace'>".$backtrace['file'].":".$backtrace['line']."</div>";
            if($content == 'string'){
                $i=1;
                foreach($messages as $alert){
                    echo $alert;
                    echo '<hr>';
                }

            }
            else{
                echo "<pre>";
                print_r($alerts);
                echo "</pre>";
            }
            echo "</div></div>";
        }
    }
}

function archo($alerts, $type = false, $title = false){
    prod_alert($alerts, $type, $title, 'string');
}

function ardump($alerts, $type = false, $title = false){
    prod_alert($alerts, $type, $title, 'array');
}

function arquick($message){
    if(is_debug_user()){
        echo "<pre>";
        if(is_array($message) || is_object($message)){
            print_r($message);
        }
        else{
            echo $message;
        }
        echo "</pre>";
    }
}
/**
 * is_debug_mode
 * Verifie que le mode debug est activé.
 * @param neant.
 * @return true ou false
 *
 **/
if(!function_exists('is_debug_mode')){
    function is_debug_mode(){
        return get_artools_option('display_debug') ? 1 : 0;
    }
}
/**
 * is_debug_user
 * Verifie que l'utilisateur fait parti des "utilisateurs debug" de RP Tools.
 * @param $user = l'utilisateur à vérifier, une ID ou un objet user wordpress. Par défaut = L'utilisateur courant.
 * @return true ou false
 *
 **/
if(!function_exists('is_debug_user')){
    function is_debug_user($user = false){
        if(!$user){
            global $current_user;
            get_currentuserinfo();
            $user = $current_user->ID;
        }
        else{
            if(is_object($user)){
                $user = $user->ID;
            }
            else{
                $user = $user;
            }
        }
        $debug_users = get_artools_option('debug_users');
        if(!$debug_users){
            return false;
        }
        foreach($debug_users as $d_user){
            if($d_user['ID'] == $user){
                return true;
            }
        }
        return false;
    }
}
/**
 * get_debug_users
 * Verifie que l'on a bien dans les options, au moins 1 "utilisateur debug" de RP Tools Tools coché.
 * @return true ou false
 *
 **/
function get_debug_users(){
    $debug_users = get_artools_option('debug_users');
    return $debug_users;
}
/**
 * has_debug_users
 * Verifie que l'on a bien dans les options, au moins 1 "utilisateur debug" de RP Tools coché.
 * @return true ou false
 *
 **/
function has_debug_users(){
    $debug_users = get_artools_option('debug_users');
    return $debug_users ? true : false;
}

/*Adding some custom to the option page*/
function option_page(){
    ?>
    <em>You were not supposed to see this but now that you did...</em> <br>
    <strong>This is Jumpstart</strong>, a simple integrated kind of plugin, kind of boilerplate by <u>and</u> for <a href="http://regisphilibert.com">Régis Philibert</a><br>
    Version is <strong>1.3</strong><br>
    All of this would be so lame without <strong><a target="_blank" href="http://www.advancedcustomfields.com/">Advanced Custom Field</a></strong><br>
    <br>
    <br>
    <?php
    die();
}

if(is_admin()){
    add_action( 'wp_ajax_option_page_info', 'option_page');
}

/**
 * COOKIE MANAGER
 */

define(JS_STORAGE, 'js_storage__');

function js_add_cookie($key, $value, $expire_after = 86400){
    $js_storage = JS_STORAGE;
    if(is_array($value)){
        $value = json_encode($value);
    }
    return setcookie($js_storage . $key, $value, time() + $expire_after, "/");
}
function js_get_cookie($key = false){
    if($key){
        return $_COOKIE[JS_STORAGE . $key];
    }
    else{
        foreach($_COOKIE as $k => $v){
            $strpos = strpos($k, JS_STORAGE);
            if($strpos !== false){
                $output[substr($k, $strpos + strlen(JS_STORAGE))] = $v;
            }
        }
        return $output;

    }
}
function js_remove_cookie($key = false){
    if($key){
        setcookie(JS_STORAGE . $key, false, time() - 1, '/');
        unset($_COOKIE[JS_STORAGE . $key ]);
    }
    else{
        foreach($_COOKIE as $k => $v){
            $strpos = strpos($k, JS_STORAGE);
            if($strpos !== false){
                setcookie($k, false, time() - 1, '/');
                unset($_COOKIE[$key]);
            }
        }
        return $output;
    }
}

?>
