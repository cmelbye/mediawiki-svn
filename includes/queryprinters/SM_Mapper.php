<?php

/**
 * General map query printer class.
 *
 * @file SM_Mapper.php
 * @ingroup SemanticMaps
 *
 * @author Jeroen De Dauw
 */
final class SMMapper {
	
	/**
	 * @var SMMapPrinter
	 */
	protected $queryPrinter;
	
	/**
	 * @since 0.8
	 * 
	 * @var boolean
	 */
	protected $isMapFormat;
	
	/**
	 * Constructor.
	 * 
	 * @param $format String
	 * @param $inline
	 */
	public function __construct( $format, $inline ) {
		global $egMapsDefaultServices;

		$this->isMapFormat = $format == 'map';
		
		// TODO: allow service parameter to override the default
		// Note: if this is allowed, then the getParameters should only return the base parameters.
		if ( $this->isMapFormat ) $format = $egMapsDefaultServices['qp'];
		
		// Get the instance of the service class.
		$service = MapsMappingServices::getValidServiceInstance( $format, 'qp' );
		
		// Get an instance of the class handling the current query printer and service.
		$QPClass = $service->getFeature( 'qp' );	
		$this->queryPrinter = new $QPClass( $format, $inline, $service );
	}
	
	/**
	 * Intercept calls to getName, so special behaviour for the map format can be implemented.
	 * 
	 * @since 0.8
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->isMapFormat ? wfMsg( 'maps_map' ) : $this->queryPrinter->getName();
	}
	
	/**
	 * SMW thinks this class is a SMWResultPrinter, and calls methods that should
	 * be forewarded to $this->queryPrinter on it.
	 * 
	 * @since 0.8
	 * 
	 * @param string $name
	 * @param array $arguments
	 */
	public function __call( $name, array $arguments ) {
		return call_user_func_array( array( $this->queryPrinter, $name ), $arguments );
	}
	
}
