<?php

/**
 * Retrieve the theme asset directory (Important: Is used by inc/class/enqueue.class.php)
 * @param  [type] $filename [description]
 * @return [type]           [description]
 */
function js_get_asset_path($filename){
    return get_stylesheet_directory() . "/" . THEME_ASSET_DIR . "/" . $filename;
}
/**
 * Retrieve the theme asset uri (Important: Is used by inc/class/enqueue.class.php)
 * @param  [type] $filename [description]
 * @return [type]           [description]
 */
function js_get_asset_uri($filename){
    return get_stylesheet_directory_uri() . "/" . THEME_ASSET_DIR . "/" . $filename;
}

/**
 * get_current_page_id
 * @return Renvoit l'ID du post/page/autre affiché.
 **/
if(!function_exists('get_current_page_id')){
    function get_current_page_id(){
        $url = explode('?', 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        return url_to_postid($url[0]);
    }
}

/*********************************************
            SHARING TOOLS
*********************************************/
if(!function_exists('shareFb')){
    function shareFb($url, $title){
        $title = urlencode($title);
        $url = urlencode(html_entity_decode($url, ENT_COMPAT, 'UTF-8'));
        return "https://www.facebook.com/sharer.php?u=$url&t=$title";
    }
}
if(!function_exists('shareTw')){
    function shareTw($url, $desc){
        if(strpos($desc, '#') !== false){
            $desc = str_replace('#', '%23', $desc);
        }
        //$desc = urlencode($desc);
        $url = urlencode(html_entity_decode($url, ENT_COMPAT, 'UTF-8'));
        return "https://twitter.com/home?status=$desc $url";
    }
}
if(!function_exists('shareEmail')){
    function shareEmail($subject, $body = false){
        $subject = rawurlencode($subject);
        $body = rawurlencode($body);
        return "mailto:?subject=$subject&amp;body=$body";

    }
}
if(!function_exists('shareGplus')){
    function shareGplus($url, $locale){
      return "https://plusone.google.com/_/+1/confirm?hl=$locale&url=$url";
    }
}
if(!function_exists('shareLinkedIn')){
    function shareLinkedIn($url, $title, $excerpt){
        $sitename = urlencode(get_bloginfo( 'name' ));
        return "http://www.linkedin.com/shareArticle?mini=true&url=$url&title=$title&summary=$excerpt&source=$sitename";
    }
}

/*********************************************
            PLACEHOLDING
*********************************************/
/**
 * randomName
 * @param $param : false|firstname|lastname, si false renvoit un nom complet sinon prénon ou nom.
 **/
if(!function_exists('randomName')){
    function randomName($param = false){
        $names = array("Carrie Gaul", "Wilbur Farney", "Dewayne Gandhi", "Tamra Proffit", "Dalene Rosebrook", "Jessica Lindgren", "Refugia Lacour", "Mathilda Resh", "Bess Lowry", "Tobi Rueda", "Trey Torpey", "Ginger Ohanlon", "Junko Botsford", "Elwanda Viers", "Temple Schlecht", "Twanna Allman", "Catherina Rochell", "Cleveland Bassin", "Hedwig Parm", "Mireya Morais");
        $firstnames = array();
        $lastnames = array();
        foreach($names as $name){
            $thisName = explode(' ', $name);
            if(!in_array($thisName[0], $firstnames)){
                $firstnames[] = $thisName[0];
            }
            if(!in_array($thisName[1], $lastnames)){
                $lastnames[] = $thisName[1];
            }
        }
        if($param == "firstname"){
            return $firstnames[mt_rand(0, count($firstnames)-1)];
        }
        elseif($param == "lastname"){
            return $lastnames[mt_rand(0, count($lastnames)-1)];
        }
        else{
            return $names[mt_rand(0, count($names)-1)];
        }
    }
}

/**
 * randomHex
 * @param $hastag : default > #.
 **/
function randomHex($hashtag = '#'){
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = $hashtag.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    return $color;
}

/*********************************************
            IMAGES AND ATTACHMENTS
*********************************************/
/**
 * img_orientation
 * Compare les hauteur et l'argeur d'une image pour conclure de son orientation (portait ou landscape)
 * @param $img_id : ID de l'attachment.
 * @return (string) portrait ou (string) landscape en fonction de l'orentation déduite.
 **/
if(!function_exists('img_orientation')){
    function img_orientation($img_id = false){
        global $post;
        $img_id = $img_id ? $img_id : get_post_thumbnail_id($post->ID);
        $img = wp_get_attachment_image_src($img_id, 'full');
        if($img[1] >= $img[2]){
            return "landscape";
        }
        else{
            return "portrait";
        }
    }
}

/**
 * get_theme_image
 * @param $filename : nom du fichier de l'image. $dir : nom du repertoire de stockage des images du thèmes.
 * @return Renvoit l'URL de l'image passé.
 **/
if(!function_exists('get_theme_image')){
    function get_theme_image($filename = false, $dir = false){
        if(!$dir){
            $dir = 'images';
            if(defined('PHI_IMAGES_DIR')){
                $dir = PHI_IMAGES_DIR;
            }
        }
        return $filename ? get_stylesheet_directory_uri().'/'.$dir.'/'.$filename : 'NO FILENAME SPECIFIED';
    }
}

/**
 * js_get_attachment_image_uri
 * @param $id : ID du attachment (obligatoire), $size : La taille de l'image a renvoyé
 * @return Renvoit l'URL de l'attachment passé dans la taille passée.
 **/
if(!function_exists('js_get_attachment_image_uri')){
    function js_get_attachment_image_uri($id, $size= "large"){
        $attachment = wp_get_attachment_image_src($id, $size);
        return $attachment[0];
    }
}

/**
 * js_get_page_by_template
 * @param  string $template  Name of template file without extension.
 * @param  bool $single if true, we only retrieve the first page found, false an array of matches.
 * @return [object] The post object of the page found or an array of matches container the post objects
 */
if(!function_exists('js_get_page_by_template')){
    function js_get_page_by_template($template, $single = false, $dir = 'templates'){
        $output = false;
        $pages = get_posts(array(
            'post_type'=>'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => $dir . '/' . $template . '.php',
            'suppress_filters'=>0
        ));
        foreach($pages as $page){
            if($single){
                return $page;
            }
            $output[] = $page;
        }
        return $output;
    }
}

/*********************************************
        FOR WPML PLUGIN
        http://wpml.org/
*********************************************/
/**
 * get_cat_ID_by_slug
 * @param $formats : un tableau contenant clé (language code) = > valeur (formats de date php)
 * @return le resultat de la fonction wordpress the_time() en ayant passé le format de date correspondant à la langue courante.
 * Si WPML n'est pas installé on renvoir simplement time() en ignorant les formats;
 **/
if(!function_exists('the_wpml_time')){
    function the_wpml_time($formats = false){
        global $post;
        if(!function_exists('icl_object_id')){
            return the_time();
        }
        if(!$formats){
            $formats = array('fr'=>'d/m/y', 'en'=>'m/d/y');
        }
        echo the_time($formats[ICL_LANGUAGE_CODE]);
    }
}

if(!function_exists('wpml_date')){
    function wpml_date($timestamp, $formats = false){
        if(!function_exists('icl_object_id')){
            return date('d/m/y', $date_string);
        }
        if(!$formats){
            $formats = array('fr'=>'d/m/y', 'en'=>'m/d/y');
        }
        return date($formats[ICL_LANGUAGE_CODE], $timestamp);
    }
}
/**
 * get_wpml_permalink
 * @param $id : ID de la page cherchée, $type : post_type (page, post, attachment etc..)
 * @return Si WPML est installé et que la page traduite existe, on renvoit l'url de la page en fonction de langue courante.
 * Si WPML n'est pas installé on renvoit simplement l'URL de la page passée.
 **/
if(!function_exists('get_wpml_permalink')){
    function get_wpml_permalink($id, $type = 'page'){
        if(!function_exists('icl_object_id')){
            return get_permalink($id);
        }
        else{
            return get_permalink(icl_object_id($id, $type, true));
        }
    }
}
/**
 * get_cat_ID_by_slug
 * @param $id : ID de la page cherché
 * @return Si WPML est installé et que la page traduite existe, on renvoit l'ID de la page en fonction de langue courante.
 * Si WPML n'est pas installé on revnoit simplement l'ID passé.
 **/
if(!function_exists('lang_get_page_id')){
    function lang_get_page_id($id){
        if( function_exists('icl_object_id')) {
            return icl_object_id($id,'page',true);
        } else {
            return $id;
        }
    }
}

/*********************************************
            PROTECTION
*********************************************/
/**
 * rot13_email
 * @param $email : L'email à protéger.
 * @return l'email encapsulé dans un span.add-email et les data-attr utilisé par le javascript.
 * ATTENTION : Si on encode en PHP, on décode en Javascript. S'assurer que les .add-email son décodé par le JS.
 **/
if(!function_exists('rot13_email')){
    function rot13_email($email){
        $email = explode('@', $email);
        return '<span class="add-email" data-user="'.str_rot13($email[0]).'" data-domain="'.str_rot13($email[1]).'"></span>';
    }
}

/*********************************************
            ACF
*********************************************/

/**
 * get a user field
 * @param  string $field   Field ID
 * @param  string|int $user_id The user ID
 * @return [type]          The value of the field
 */
if(!function_exists('get_user_field')){
    function get_user_field($field, $user_id){
        if(function_exists("get_field")){
            return get_field($field, 'user_'.$user_id) ? get_field($field, 'user_'.$user_id) : false;
        }
    }
}
if(!function_exists('the_user_field')){
    function the_user_field($field, $user_id, $locale){
        if(function_exists("get_field")){
            if($value = get_field($field, 'user_'.$user_id)){
                if($locale){
                    $langs = array('fr_FR'=>0, 'en_CA'=>1);
                    $split = explode('%|%', $value);
                    echo $split[$langs[$locale]];
                    return true;
                }
                else{
                    echo $value;
                    return true;
                }
            }
        }

    }
}

/**
 * get an image stored in an ACF field
 * @param  string  $field   ACF field id
 * @param  string  $size    wordpress size
 * @param  int $post_id post_id, default is false
 * @param  int $subfield false, default is false : shall we use get_sub_field() ?
 * @return string|boolean  url string or false if no field found
 */

if(!function_exists('get_field_image')){
    function get_field_image($field, $size = 'full', $post_id = false, $subfield = false){
        global $post;
        if(!function_exists('get_field')){
        	return false;
        }
        $post_id = $post_id ? $post_id : $post->ID;
        $image = $subfield ? get_sub_field($field, $post_id) : get_field($field, $post_id);
        if($image){
            if(is_array($image)){
                return $image['sizes'][$size] ? $image['sizes'][$size] : $image['url'];
            }
            else{
                return js_get_attachment_image_uri($image, $size);
            }
        }
        else{
            return false;
        }
    }
}

/**
 * get an image stored in an ACF sub field
 * @param  string  $subfield   ACF sub field id
 * @param  string  $size    wordpress size
 * @param  int $post_id post_id, default is false
 * @return string|boolean url string or false if no field found
 */

if(!function_exists('the_sub_field_image')){
    function the_sub_field_image($subfield, $size = 'full', $post_id = false){
        echo get_field_image($subfield, $size, $post_id, true);
    }
}
if(!function_exists('cl_acf_set_language')){
	function cl_acf_set_language() {
		if(!function_exists('get_field')){
			return false;
		}
	  return acf_get_setting('default_language');
}
}
if(!function_exists('get_global_option')){
	function get_global_option($name) {
		if(!function_exists('get_field')){
			return false;
		}
	    $option = get_field($name, 'option');
	    if(!$option){
	        add_filter('acf/settings/current_language', 'cl_acf_set_language', 100);
	        $option = get_field($name, 'option');
	        remove_filter('acf/settings/current_language', 'cl_acf_set_language', 100);
	    }
	    return $option;
	}
}
if(!function_exists('get_theme_option')){
	function get_theme_option($option){
	    if(function_exists('get_field')){
	        return get_global_option($option);
	    }
	    else{
	        return "ACF NOT INSTALLED :/";
	    }
	}
}
/*********************************************
            USERS
*********************************************/

/**
 * get_current_user_meta, allow to retrieve meta of the current user.
 * @param  string $key the meta key
 */
if(!function_exists('get_current_user_meta')){
    function get_current_user_meta($key){
        $current_user = wp_get_current_user();
        return get_user_meta( $current_user->ID, $key, true );
    }
}