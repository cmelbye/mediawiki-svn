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
$protocol = ( isset( $_SERVER['HTTPS'] ) )? 'https' : 'http'; 
$wgServer = $protocol . '://' . $_SERVER['SERVER_NAME'] .  dirname( $_SERVER['SCRIPT_NAME'] ) . '/';

// By default set $wgScriptPath to empty
$wgScriptPath = '';

// Default Load Script path
$wgLoadScript = $wgServer . $wgScriptPath . 'load.php';

// If we should use simple php file cache infront of resource loader 
// helps performance in situations where you don't reverse proxy the resource loader.  
$mwUsePoorManSquidProxy = true;

// The list of enabled modules 
$wgMwEmbedEnabledModules = array();
// By default we enable every module in the "modules" folder
// Modules are registered after localsettings.php to give a chance 
// for local configuration to override the set of enabled modules
$d = dir( realpath( dirname( __FILE__ ) )  . '/../modules' );	
while (false !== ($entry = $d->read())) {
	if( substr( $entry, 0, 1 ) != '.' ){
		$wgMwEmbedEnabledModules[] = $entry;
	}
}

// $wgMwEmbedModuleConfig allow setting of any mwEmbed configuration variable 
// ie $wgMwEmbedModuleConfig['ModuleName.Foo'] = 'bar';
// For list of configuration variables see the .conf file in any given mwEmbed module
$wgMwEmbedModuleConfig = array();

/**
 * Client-side resource modules. Extensions should add their module definitions
 * here. The mwEmbed
 *
 * Example:
 *   $wgResourceModules['ext.myExtension'] = array(
 *      'scripts' => 'myExtension.js',
 *      'styles' => 'myExtension.css',
 *      'dependencies' => array( 'jquery.cookie', 'jquery.tabIndex' ),
 *      'localBasePath' => dirname( __FILE__ ),
 *      'remoteExtPath' => 'MyExtension',
 *   );
 */
$wgResourceModules = array();

/* Default skin can be any jquery based skin */
$wgDefaultSkin = 'redmond';

// If the resource loader is in 'debug mode'
$wgResourceLoaderDebug = false;


/**
 * Maximum time in seconds to cache resources served by the resource loader
 */
$wgResourceLoaderMaxage = array(
	'versioned' => array(
		// Squid/Varnish but also any other public proxy cache between the client and MediaWiki
		'server' => 30 * 24 * 60 * 60, // 30 days
		// On the client side (e.g. in the browser cache).
		'client' => 30 * 24 * 60 * 60, // 30 days
	),
	'unversioned' => array(
		'server' => 5 * 60, // 5 minutes
		'client' => 5 * 60, // 5 minutes
	),
);

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
