<?php
    $GLOBALS['js_msg']['en'] = array(
        "Bonjour tout le monde"=>"Hello everyone",
    );
    if(!function_exists("js_get_loc_strings")){
        function js_get_loc_strings(){
            return $GLOBALS['js_msg'];
        }
    }
    if(!function_exists("_js")){
        function _js($string){
            $lang_code = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'fr';
            return isset($GLOBALS['js_msg'][$lang_code][$string]) ? $GLOBALS['js_msg'][$lang_code][$string] : $string;
        }
    }
?>