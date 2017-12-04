<?php

$action = get_query_var('api_action');
$key = get_query_var('api_key');
$args = explode('/',get_query_var('js_api_args'));

if($action && function_exists('api__' . $action)){
    call_user_func_array('api__' . $action, $args);
}

/**
 * API FUNCTIONS (must be prepended by api__ )
 */

/**
 * Just an API test
 * @return echo a simple text
 */
function api__test(){
    arquick("I want to die with my blue jeans on. - Andy Warhol");
}