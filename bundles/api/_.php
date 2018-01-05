<?php
if(!defined('API_KEY')){
	define(API_KEY, 'GENERATE_UNIQUE_KEY_PLEASE');
}

if(!defined('API_PAGE')){
	define(API_PAGE, 1);
}

require_once(__DIR__ . '/Api.class.php');
require_once(__DIR__ . '/Rewrite.class.php');
if(API_PAGE){
	require_once(__DIR__ . '/Page.class.php');
	require_once(__DIR__ . '/Scripts.class.php');
}
