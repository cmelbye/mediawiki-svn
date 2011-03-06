<?php

/**
 * Abstract class MapsBasePointMap provides the scafolding for classes handling display_point(s)
 * calls for a specific mapping service. It inherits from MapsMapFeature and therefore forces
 * inheriting classes to implement sereveral methods.
 *
 * @file Maps_BasePointMap.php
 * @ingroup Maps
 *
 * @author Jeroen De Dauw
 */
class MapsBasePointMap {
	
	/**
	 * @var iMappingService
	 */
	protected $service;
	
	protected $markerData = array();
	
	public function __construct( iMappingService $service ) {
		$this->service = $service;
	}
	
	/**
	 * Returns the specific parameters by first checking if they have been initialized yet,
	 * doing to work if this is not the case, and then returning them.
	 * 
	 * @return array
	 */
	public final function getSpecificParameterInfo() {
		if ( $this->specificParameters === false ) {
			$this->specificParameters = array();
			$this->initSpecificParamInfo( $this->specificParameters );
		}
		
		return $this->specificParameters;
	}
	
	/**
	 * Initializes the specific parameters.
	 * 
	 * Override this method to set parameters specific to a feature service comibination in
	 * the inheriting class.
	 */
	protected function initSpecificParamInfo( array &$parameters ) {
	}	
	
	/**
	 * Handles the request from the parser hook by doing the work that's common for all
	 * mapping services, calling the specific methods and finally returning the resulting output.
	 *
	 * @param array $params
	 * @param Parser $parser
	 * 
	 * @return html
	 */
	public final function renderMap( array $params, Parser $parser ) {
		$this->handleMarkerData( $params );
		
		$mapName = $this->service->getMapId();
		
		$output = $this->getMapHTML( $params, $parser, $mapName ) . $this->getJSON( $params, $parser, $mapName );
		
		global $wgTitle;
		if ( $wgTitle->isSpecialPage() ) {
			global $wgOut;
			$this->service->addDependencies( $wgOut );
		}
		else {
			$this->service->addDependencies( $parser );			
		}
		
		return $output;
	}
	
	/**
	 * Returns the HTML to display the map.
	 * 
	 * @since 0.8
	 * 
	 * @param array $params
	 * @param Parser $parser
	 * @param string $mapName
	 * 
	 * @return string
	 */
	protected function getMapHTML( array $params, Parser $parser, $mapName ) {
		return Html::element(
			'div',
			array(
				'id' => $mapName,
				'style' => "width: {$params['width']}; height: {$params['height']}; background-color: #cccccc; overflow: hidden;",
			),
			wfMsg( 'maps-loading-map' )
		);
	}		
	
	/**
	 * Returns the JSON with the maps data.
	 *
	 * @since 0.8
	 *
	 * @param array $params
	 * @param Parser $parser
	 * @param string $mapName
	 * 
	 * @return string
	 */	
	protected function getJSON( array $params, Parser $parser, $mapName ) {
		$object = $this->getJSONObject( $params, $parser );
		
		if ( $object === false ) {
			return '';
		}
		
		return Html::inlineScript(
			MapsMapper::getBaseMapJSON( $this->service->getName() )
			. "maps.{$this->service->getName()}.{$mapName}=" . json_encode( $object ) . ';'
		);
	}
	
	/**
	 * Returns a PHP object to encode to JSON with the map data.
	 *
	 * @since 0.8
	 *
	 * @param array $params
	 * @param Parser $parser
	 * 
	 * @return mixed
	 */	
	protected function getJSONObject( array $params, Parser $parser ) {
		return $params;
	}	
	
	/**
	 * Converts the data in the coordinates parameter to JSON-ready objects.
	 * These get stored in the locations parameter, and the coordinates on gets deleted.
	 * 
	 * @since 0.8
	 * 
	 * @param array &$params
	 */
	protected function handleMarkerData( array &$params ) {
		global $wgTitle;

		$parser = new Parser();			
		$iconUrl = MapsMapper::getImageUrl( $params['icon'] );
		$params['locations'] = array();

		foreach ( $params['coordinates'] as $location ) {
			if ( $location->isValid() ) {
				$jsonObj = $location->getJSONObject( $params['title'], $params['label'], $iconUrl );
				
				$jsonObj['title'] = strip_tags( $parser->parse( $jsonObj['title'], $wgTitle, new ParserOptions() )->getText() );
				$jsonObj['text'] = $parser->parse( $jsonObj['text'], $wgTitle, new ParserOptions() )->getText();
				
				$params['locations'][] = $jsonObj;				
			}
		}
		
		unset( $params['coordinates'] );
	}

}
