<?php

/**
 * Google Maps v3 form input class.
 *
 * @file SM_GoogleMaps3FormInput.php
 * @ingroup SemanticMaps
 *
 * @licence GNU GPL v3
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SMGoogleMaps3FormInput extends SMFormInput {
	
	/**
	 * @see SMFormInput::getInputHTML
	 * 
	 * @since 0.8
	 * 
	 * @param array $params
	 * @param Parser $parser
	 * @param string $mapName
	 * 
	 * @return string
	 */	
	protected function getInputHTML( array $params, Parser $parser, $mapName ) {
		$html = '';
		
		$html .= parent::getInputHTML( $params, $parser, $mapName );
		
		return $html;
	}
	
	/**
	 * @see SMFormInput::getResourceModules
	 * 
	 * @since 0.8
	 * 
	 * @return array of string
	 */
	protected function getResourceModules() {
		return array_merge( parent::getResourceModules(), array( 'ext.sm.fi.googlemaps3' ) );
	}	
	
}
