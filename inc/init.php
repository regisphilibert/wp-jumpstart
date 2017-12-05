<?php 
// The helper functions
require('helpers.php');

//The walkers for the various WP Menus we used around the site
require('walkers/phil.php');

//Registering scripts
require('scripts.php');

require('theme-options.php');

// The file expending from Jumpstart SEO class.
if(class_exists('jsSEO')){
	require('seo.php');	
}
