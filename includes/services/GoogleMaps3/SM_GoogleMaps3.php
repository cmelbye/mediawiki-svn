<?php

/**
 * This groupe contains all Google Maps v3 related files of the Semantic Maps extension.
 * 
 * @defgroup SMGoogleMaps3 Google Maps v3
 * @ingroup SMGoogleMaps3
 */

/**
 * This file holds the general information for the Google Maps v3 service.
 *
 * @since 0.8
 *
 * @file SM_GoogleMaps3.php
 * @ingroup SMGoogleMaps3
 *
 * @licence GNU GPL v3
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

$wgResourceModules['ext.sm.fi.googlemaps3'] = array(
	'dependencies' => array( 'ext.maps.googlemaps3', 'jquery.ui.button', 'jquery.ui.dialog' ),
	'localBasePath' => dirname( __FILE__ ),
	'remoteBasePath' => $smgScriptPath .  '/includes/services/GoogleMaps3',	
	'group' => 'ext.semanticmaps',
	'scripts' => array(
		'jquery.googlemapsinput.js',
		'ext.sm.googlemapsinput.js'
	),
	'messages' => array(
		'semanticmaps-forminput-remove',
		'semanticmaps-forminput-add',
		'semanticmaps-forminput-locations'
	)
);

$wgHooks['MappingServiceLoad'][] = 'smfInitGoogleMaps3';

function smfInitGoogleMaps3() {
	global $wgAutoloadClasses;
	
	$wgAutoloadClasses['SMGoogleMaps3FormInput'] = dirname( __FILE__ ) . '/SM_GoogleMaps3FormInput.php';
	
	MapsMappingServices::registerServiceFeature( 'googlemaps3', 'qp', 'SMMapPrinter' );
	MapsMappingServices::registerServiceFeature( 'googlemaps3', 'fi', 'SMGoogleMaps3FormInput' );
	
	return true;
}
