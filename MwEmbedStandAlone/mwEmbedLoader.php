<?php 
/**
 * Legacy entry point to resource loader 
 */
// Set the request variables: 
$_REQUEST['modules'] = 'startup';
$_REQUEST['only'] = 'scripts';
include_once( 'load.php' );