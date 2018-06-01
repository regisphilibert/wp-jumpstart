<?php 

// The registering of Post Types and Taxonomies
require('register.php');

// The helper functions
require('helpers.php');

// The templating functions
require('template.class.php');

require('partial.class.php');

//The walkers for the various WP Menus we used around the site
require('walkers/phil.php');

//Registering scripts
require('enqueue.class.php');
// The file custom SEO class extending  phiSEO class.
if(class_exists('phiSEO')){
	require('custom-seo.php');	
}