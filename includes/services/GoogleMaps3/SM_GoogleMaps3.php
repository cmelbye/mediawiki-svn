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
 * @file SM_GoogleMaps3.php
 * @ingroup SMGoogleMaps3
 *
 * @author Jeroen De Dauw
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

$wgHooks['MappingServiceLoad'][] = 'smfInitGoogleMaps';

function smfInitGoogleMaps() {
	global $wgAutoloadClasses;
	
	$wgAutoloadClasses['SMGoogleMaps3QP'] = dirname( __FILE__ ) . '/SM_GoogleMaps3QP.php';
	
	MapsMappingServices::registerServiceFeature( 'googlemaps3', 'qp', 'SMGoogleMaps3QP' );
	
	return true;
}
