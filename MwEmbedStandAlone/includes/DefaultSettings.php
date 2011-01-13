<?php 
/**
 * This file stores default settings for Kaltura html5 client library "mwEmbed".
 * 
 *  DO NOT MODIFY THIS FILE. Instead modify LocalSettings.php in the parent mwEmbd directory. 
 * 
 */

// The default cache directory
$wgScriptCacheDirectory = realpath( dirname( __FILE__ ) ) . '/cache';


/**
 * Guess at URL to resource loader load.php 
 */
$wgResourceLoaderUrl = ( isset( $_SERVER['HTTPS'] ) )? 'https' : 'http'; 
$wgResourceLoaderUrl.= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PATH_INFO'] . '/load.php';

// The list of enabled modules 
$wgMwEmbedEnabledModules = array();

// By default we enable every module in the "modules" folder
// We include /modules/{moduleName}/{moduleName}.php after localsettings.php to give a chance 
// for local configuration to override the set of enabled modules

$d = dir( realpath( dirname( __FILE__ ) )  . '/../modules' );	
while (false !== ($entry = $d->read())) {
	if( substr( $entry, 0, 1 ) != '.' ){
		$wgMwEmbedEnabledModules[] = $entry;
	}
}

/*********************************************************
 * Default Kaltura Configuration: 
 * TODO move kaltura configuration to KalturaSupport module ( part of ResourceLoader update ) 
 ********************************************************/

// The default Kaltura service url:
$wgKalturaServiceUrl = 'http://www.kaltura.com';

// Default Kaltura CDN url: 
$wgKalturaCDNUrl = 'http://cdn.kaltura.com';

// Default Kaltura service url:
$wgKalturaServiceBase = '/api_v3/index.php?';

// Default expire time for ui conf api queries in seconds 
$wgKalturaUiConfCacheTime = 600;




/*********************************************************
 * Include local settings override:
 ********************************************************/
$wgLocalSettingsFile = realpath( dirname( __FILE__ ) ) . '/../LocalSettings.php';

if( is_file( $wgLocalSettingsFile ) ){
	require_once( $wgLocalSettingsFile );
}


?>
