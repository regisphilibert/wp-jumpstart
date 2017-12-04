<?php
/*********************************************
            LA PETITE BOÎTE À OUTIL
*********************************************/

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


/**
 * get_years
 * @param $query une query_post wordpress.
 * @return Renvoit un tableau clé (année) => valeur(année).
 **/
if(!function_exists('get_years')){
    function get_years($query){
        $posts = get_posts($query);
        $output = array();
        foreach($posts as $post){
            $output[date('Y', strtotime($post->post_date))] = date('Y', strtotime($post->post_date));
        }
        return $output;
    }
}

/**
 * rptools_sort_menu
 * À utiliser pour ordonner liste d'objet en fonction de leur valeur menu_order, pratique pour trier un retour de posts, pages etc...
 **/
if(!function_exists('rptools_sort_menu')){
    function rptools_sort_menu($a, $b) {
    if ($a->menu_order == $b->menu_order) {
        return 0;
    }
    return ($a->menu_order < $b->menu_order) ? -1 : 1;
    }
}

/*********************************************
            OUTILS DE PARTAGES
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
      BUILDING THE Open Graph Object
*********************************************/
if(!function_exists('og_object')){
    function og_object($id = false, $output = false, $thumb_size = "medium"){
        global $post;
        $id = !$id ? $post->ID : $id;
        $thumb_id = get_post_thumbnail_id($id);
        $this_post = get_post($post->ID);
        $og_title = $this_post->post_title;
        $og_description = $this_post->post_excerpt != '' ? $this_post->post_excerpt : strip_tags($this_post->post_content);
        if($thumb_id = get_post_thumbnail_id()){
            $og_image = get_post($thumb_id);
            $og_image =  wp_get_attachment_image_src( $thumb_id , $thumb_size );
            $og_image = $og_image[0];
        }
        elseif( 'attachment' == get_post_type($id)){
            if(wp_attachment_is_image( $id )){
                $og_image = wp_get_attachment_image_src( $id , $thumb_size );
                $og_image = $og_image[0];
            }
            else{
                $og_image = false;
            }
        }
        else{
            $og_image = false;
        }
        $og = array();
        $og['title'] = $og_title;
        $og['description'] = $og_description;
        $og['image'] = $og_image;
        $og['url'] = get_permalink($id);
        $og['type'] = "website";
        $og['site_name'] = get_bloginfo('name');
        $og['app_id'] = "293476984098241";
        return !$output ? (object) $og : $og[$output];
    }
}

/*********************************************
            GENERALS
*********************************************/

/**
 * Dump the current $wp_query
 * @param  string $what which key to dump
 * @return dump       true on dump
 */

if(!function_exists('ardump_query')){
    function ardump_query($what = 'query_vars'){
        global $wp_query;
        ardump($wp_query->$what);
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
            CATEGORIES
*********************************************/
/**
 * get_terms_in_posts
 * Pour récupéréer les term associés à une liste de post.
 * @param $posts_ids : une ID de post ou une liste sous forme de tableau, $taxonomy : slug de la taxonomy ou une liste de taxonomy sous forme de tableau, $output : info a renvoyr, par défaut on renvoit les objets complet.
**/
if(!function_exists('get_terms_in_posts')){
    function get_terms_in_posts($posts_ids, $taxonomy, $output = 'object'){
        $cats = wp_get_object_terms($posts_ids, $taxonomy);
        foreach($cats as $category){
            $categories[$category->term_id] = $output != 'object' ? $category->$output : $category;
        }
        return !empty($categories) ? $categories : false;
    }
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
            if(defined('JS_IMAGES_DIR')){
                $dir = JS_IMAGES_DIR;
            }
        }
        return $filename ? get_stylesheet_directory_uri().'/'.$dir.'/'.$filename : 'NO FILENAME SPECIFIED';
    }
}
/**
 * get_template_image
 * @param $filename : nom du fichier de l'image. $dir : nom du repertoire de stockage des images du thèmes parent.
 * @return Renvoit l'URL de l'image passé.
 **/
if(!function_exists('get_template_image')){
    function get_template_image($filename = false, $dir = false){
        if(!$dir){
            $dir = 'images';
            if(defined('JS_IMAGES_DIR')){
                $dir = JS_IMAGES_DIR;
            }
        }
        return $filename ? get_template_directory_uri().'/'.$dir.'/'.$filename : 'NO FILENAME SPECIFIED';
    }
}
/**
 * get_next_attachment
 * @param $id : ID de l'attachment (obligatoire si hors de la boucle)
 * @return Renvois l'object du sibling qui suit l'attachment passé
 **/
if(!function_exists('get_next_attachment')){
    function get_next_attachment($id = false){
        global $post;
        $id = !$id ? $post->ID : $id;
        return get_siblings_attachment($id, "next");
    }
}

/**
 * get_previous_attachment
 * @param $id : ID de l'attachment (obligatoire si hors de la boucle)
 * @return Renvois l'object du sibling qui précède l'attachment passé
 **/
if(!function_exists('get_previous_attachment')){
    function get_previous_attachment($id = false){
        global $post;
        $id = !$id ? $post->ID : $id;
        return get_siblings_attachment($id, "previous");
    }
}

/**
 * get_siblings_attachment
 * @param $id : ID de l'attachment (obligatoire si hors de la boucle), @output : "all, next ou autre = prev.
 * @return Tableau contenant tout les attachments siblings ordonné par menu_order.
 **/
if(!function_exists('get_siblings_attachment')){
    function get_siblings_attachment($id = false, $output = "all"){
        global $post;
        $id = !$id ? $post->ID : $id;
        $post_parent = get_post_parent($id);
        $post_siblings = get_children( array(
            'post_parent' => $post_parent,
            'post_type'   => 'attachment',
            'numberposts' => -1,
            'post_status' => 'inherit')
        );
        if($post->menu_order != 0){
            uasort($post_siblings ,"rptools_sort_menu");
        }
        else{
            ksort($post_siblings);
        }
        if($output == "all"){
            return $post_siblings;
        }
        else{
            while(key($post_siblings) !== $id) next($post_siblings);
            if($output == "next"){
                return next($post_siblings);
            }
            else{
                return prev($post_siblings);
            }
        }
    }
}

/**
 * rp_get_attachment_image_src
 * @param $id : ID du attachment (obligatoire), $size : La taille de l'image a renvoyé
 * @return Renvoit l'URL de l'attachment passé dans la taille passée.
 **/
if(!function_exists('rp_get_attachment_image_src')){
    function rp_get_attachment_image_src($id, $size= "large"){
        $attachment = wp_get_attachment_image_src($id, $size);
        return $attachment[0];
    }
}

/**
 * get_post_parent
 * @param $id : ID du post (obligatoire si hors de la boucle)
 * @return Renvoit l'ID du post parent du post passé.
 **/
if(!function_exists('get_post_parent')){
    function get_post_parent($id = false){
        global $post;
        $id = !$id ? $post->ID : $id;
        $post_parent = get_post($id);
        return $post_parent->post_parent;
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

/**
 * shortenIt
 * @param $string : Une chaîne de caractères, $charlengh : La taile maximale avant troncature, $ending : comment finir la chaîne apres troncature.
 * @return Renvoit la chaîne passée tronquée au nombre de caractère passé dans $charlength ou la chaîne entière si plus petite que $charlength
 **/
if(!function_exists('shortenIt')){
    function shortenIt($string, $charlengh = int, $ending = "..."){
        $lengh = strpos($string," ")!==false ? $charlengh - strlen($ending) : ($charlengh/2) - strlen($ending);
        if(strlen($string) > $lengh){
        return mb_substr($string, 0, $lengh).$ending;
        }
        else{
            return $string;
        }
    }
}
/**
 * pluralIt
 * @param $single : La chaîne au singulier, $plural : La chaîne au plurie, $number : Le nombre qui décidera la pluralité.
 * @return Renvoit la chaîne singulière ou plurielle en fonction du nombre.
 **/
if(!function_exists('pluralIt')){
    function pluralIt($single, $plural, $number){
        return $number > 1 ? $plural : $single;
    }
}
/*********************************************
            LES CATEGORIES
*********************************************/
/* Exemple d'objet de catégorie.
stdclass object
(
    [term_id] => 8
    [name] => coool
    [slug] => coool
    [term_group] => 0
    [term_taxonomy_id] => 8
    [taxonomy] => category
    [description] =>
    [parent] => 6
    [count] => 3
    [cat_id] => 8
    [category_count] => 3
    [category_description] =>
    [cat_name] => coool
    [category_nicename] => coool
    [category_parent] => 6
)
*/
/**
 * rp_get_category_children
 * @param $parent_slug : Le slug de la catégorie dont on cherche les catégories enfantes.
 * @return Renvoit les catégories enfantes sous formes d'objet.
 **/
if(!function_exists('rp_get_category_children')){
    function rp_get_category_children($parent_slug = 'main-categories'){
        $cats = get_categories(array('parent'=>get_cat_ID_by_slug($parent_slug)));
        return $cats;
    }
}
/**
 * get_cat_ID_by_slug
 * @param $slug : le slug de la catégorie cherchée.
 * @return l'ID de la catégorie chercheé.
 **/
if(!function_exists('get_cat_ID_by_slug')){
    function get_cat_ID_by_slug($slug){
        $cat = get_category_by_slug($slug);
        return $cat->cat_ID;
    }
}

/*************************************************************************************************************************************************
            FOR PLUGINS !
*************************************************************************************************************************************************/

/*********************************************
      FOR TYPES PLUGIN
      http://wordpress.org/plugins/types/
*********************************************/

/**
 * get_type_meta
 * @param $post_id : ID du post, @meta_slug : Le slug ou ID de la meta type sans le prefix wpcf.
 * @return valeur de la meta Type
 **/
if(!function_exists('get_type_meta')){
    function get_type_meta($post_id, $meta_slug){
        return get_post_meta($post_id, 'wpcf-'.$meta_slug, true);
    }
}

/**
 * get_type_children : On ne fait qu'appeler la fonction types_children_posts apres avoir vérifé qu'elle existe.
 * @param $post_type : Type des posts enfant a appeler, $args : parametres à ajouter a la query sous form d'array.
 * @return un tableau d'objet de post
 **/
if(!function_exists('get_types_children')){
    function get_types_children($post_type, $args = false){
        return function_exists(types_child_posts) ? types_child_posts($post_type, $args) : 'Types must be installed';
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
if(!function_exists('get_author_id')){
    function get_author_id(){
        global $post;
        return get_the_author_meta('ID');
    }
}
if(!function_exists('the_localized_time')){
    function the_localized_time($formats = false){
        global $post;
        $default = array('en_CA'=>'F jS', 'fr_FR'=>'d F');
        $formats = !$formats ? $default : $formats;
        return the_time($formats[get_locale()]);
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
 * get user email, allow to retrieve default user email meta or ACF email meta.
 * @param  int|string $user_id the wordpress user id
 * @param  string $acf_field the ACF field id
 * @return string          the email of the user as a string
 */
function get_user_email($user_id, $acf_field = 'user-alt-email'){
    if(function_exists('get_field')){
        return get_field($acf_field, 'user_'.$user_id) ? get_field($acf_field, 'user_'.$user_id) : get_the_author_meta( 'user_email', $user_id );

    }
    else{
        return get_the_author_meta( 'user_email', $user_id );
    }
}

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
        $post_id = $post_id ? $post_id : $post->ID;
        $image = $subfield ? get_sub_field($field, $post_id) : get_field($field, $post_id);
        if($image){
            if(is_array($image)){
                return $image['sizes'][$size] ? $image['sizes'][$size] : $image['url'];
            }
            else{
                return rp_get_attachment_image_src($image, $size);
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

/**
 * Retrieve ACF field value from self or closest ancestor with value.
 * @param  [string]  $field   [name of acf field]
 * @param  boolean|int|string $post_id [le post->ID]
 * @return [misc]           [acf field value of self or closest ancestors with acf field]
 */

if(!function_exists('get_parent_field')){
    function get_parent_field($field, $post_id = false){
        global $post;
        $post_id = $post_id ? $post_id : $post->ID;
        if($output = get_field($field, $post_id)){
            return $output;
        }
        else{
            $parents = get_post_ancestors($post_id);
            foreach($parents as $parent){
                if($output = get_field($field, $parent)){
                    return $output;
                }
            }
        }
        return false;
    }
}

/**
 * Echo ACF field value from self or closest ancestor with value.
 * Must be used within the loop;
 * @param  [string]  $field   [name of acf field]
 * @return [misc]           [echo acf field value of self or closest ancestors with acf field]
 */
if(!function_exists('the_parent_field')){
    function the_parent_field($field){
        global $post;
        echo get_parent_field($field, $post->ID);
    }
}

/**
 * Advanced Custom Fields Options function
 * Always fetch an Options field value from the default language
 */
function cl_acf_set_language() {
  return acf_get_setting('default_language');
}

function get_theme_option($option){
    if(function_exists('get_field')){
        return get_global_option($option);
    }
    else{
        return "ACF NOT INSTALLED :/";
    }
}
?>
