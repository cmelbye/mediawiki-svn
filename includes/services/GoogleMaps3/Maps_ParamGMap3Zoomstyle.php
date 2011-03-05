<?php

/**
 * Parameter manipulation ensuring the value is a Google Maps v3 zoom control style.
 * 
 * @since 0.8
 * 
 * @file Maps_ParamGMap3Zoomstyle.php
 * @ingroup Maps
 * @ingroup ParameterManipulations
 * @ingroup MapsGoogleMaps3
 * 
 * @author Jeroen De Dauw
 */
class MapsParamGMap3Zoomstyle extends ItemParameterManipulation {
	
	/**
	 * Constructor.
	 * 
	 * @since 0.7
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * @see ItemParameterManipulation::doManipulation
	 * 
	 * @since 0.7
	 */	
	public function doManipulation( &$value, Parameter $parameter, array &$parameters ) {
		$value = 'google.maps.ZoomControlStyle.' . strtoupper( $value );
	}
	
}