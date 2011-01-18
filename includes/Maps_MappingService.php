<?php

/**
 * Base class for mapping services. Deriving classes hold mapping service specific 
 * information and functionality, which can be used by any mapping feature.
 * 
 * @since 0.6.3
 * 
 * @file Maps_MappingService.php
 * @ingroup Maps
 * 
 * @author Jeroen De Dauw
 */
abstract class MapsMappingService implements iMappingService {
	
	/**
	 * The internal name of the service.
	 * 
	 * @since 0.6.3
	 * 
	 * @var string
	 */
	protected $serviceName;
	
	/**
	 * A list of aliases for the internal name.
	 * 
	 * @since 0.6.3
	 * 
	 * @var array
	 */
	protected $aliases;
	
	/**
	 * A list of features that support the service, used for validation and defaulting.
	 * 
	 * @since 0.6.3
	 * 
	 * @var array
	 */
	protected $features;
	
	/**
	 * A list of names of resource modules to add.
	 * 
	 * @since 0.7.3
	 * 
	 * @var array
	 */	
	protected $resourceModules = array();
	
	/**
	 * Constructor. Creates a new instance of MapsMappingService.
	 * 
	 * @since 0.6.3
	 * 
	 * @param string $serviceName
	 * @param array $aliases
	 */
	function __construct( $serviceName, array $aliases = array() ) {
		$this->serviceName = $serviceName;
		$this->aliases = $aliases;
	}
	
	/**
	 * @see iMappingService::addParameterInfo
	 * 
	 * @since 0.7
	 * 
	 * @param array $parameterInfo
	 */	
	public function addParameterInfo( array &$parameterInfo ) {
	}
	
	/**
	 * @see iMappingService::createMarkersJs
	 * 
	 * @since 0.6.5
	 */
	public function createMarkersJs( array $markers ) {
		return '[]';
	}		
	
	/**
	 * @see iMappingService::addFeature
	 * 
	 * @since 0.6.3
	 */
	public function addFeature( $featureName, $handlingClass ) {
		$this->features[$featureName] = $handlingClass;
	}
	
	/**
	 * @see iMappingService::addDependencies 
	 * 
	 * @since 0.6.3
	 */
	public final function addDependencies( &$parserOrOut ) {
		// Only add a head item when there are dependencies.
		if ( $parserOrOut instanceof Parser ) {
			$parserOrOut->getOutput()->addModules( $this->getResourceModules() );
		} 
		else if ( $parserOrOut instanceof OutputPage ) { 
			$parserOrOut->addModules( $this->getResourceModules() );
		}
	}
	
	/**
	 * @see iMappingService::getName
	 * 
	 * @since 0.6.3
	 */	
	public function getName() {
		return $this->serviceName;
	}
	
	/**
	 * @see iMappingService::getFeature
	 * 
	 * @since 0.6.3
	 */
	public function getFeature( $featureName ) {
		return array_key_exists( $featureName, $this->features ) ? $this->features[$featureName] : false;
	}
	
	/**
	 * @see iMappingService::getFeatureInstance
	 * 
	 * @since 0.6.6
	 */
	public function getFeatureInstance( $featureName ) {
		$className = $this->getFeature( $featureName );
		
		if ( $className === false || !class_exists( $className ) ) {
			throw new Exception( 'Could not create a mapping feature class instance' );
		}
		
		return new $className( $this );
	}	
	
	/**
	 * @see iMappingService::getAliases
	 * 
	 * @since 0.6.3
	 */
	public function getAliases() {
		return $this->aliases;
	}
	
	/**
	 * @see iMappingService::hasAlias
	 * 
	 * @since 0.6.3
	 */
	public function hasAlias( $alias ) {
		return in_array( $alias, $this->aliases );
	}
	
	/**
	 * Returns the resource modules that need to be loaded to use this mapping service.
	 * 
	 * @since 0.7.3
	 * 
	 * @return array of string
	 */
	protected function getResourceModules() {
		return $this->resourceModules;
	}
	
	/**
	 * Add one or more names of resource modules that should be loaded.
	 * 
	 * @since 0.7.3
	 * 
	 * @param mixed $modules Array of string or string
	 */
	public function addResourceModules( $modules ) {
		$this->resourceModules = array_merge( $this->resourceModules, (array)$modules );
	}
	
}