<?php 
/**
 * Legacy entry point to resource loader 
 */
// Set the request variables: 
$_GET['modules'] = 'startup';
$_GET['only'] = 'scripts';
include_once( 'load.php' );