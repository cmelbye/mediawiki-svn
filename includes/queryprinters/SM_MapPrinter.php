<?php

/**
 * Abstract class that provides the common functionality for all map query printers.
 *
 * @file SM_MapPrinter.php
 * @ingroup SemanticMaps
 *
 * @author Jeroen De Dauw
 */
abstract class SMMapPrinter extends SMWResultPrinter {
	
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
	protected abstract function getMapHTML( array $params, Parser $parser, $mapName );	
	
	/**
	 * Returns the name of the service to get the correct mapping service object.
	 * 
	 * @since 0.6.3
	 * 
	 * @return string
	 */
	protected abstract function getServiceName();
	
	/**
	 * @var iMappingService
	 */
	protected $service;	
	
	protected $fatalErrorMsg = false;
	
	protected $parameters;
	
	/**
	 * Constructor.
	 * 
	 * @param $format String
	 * @param $inline
	 * @param $service iMappingService
	 */
	public function __construct( $format, $inline, /* iMappingService */ $service = null ) {
		// TODO: this is a hack since I can't find a way to pass along the service object here when the QP is created in SMW.
		if ( $service == null ) {
			$service = MapsMappingServices::getServiceInstance( $this->getServiceName() );
		}
		
		$this->service = $service;
	}

	/**
	 * (non-PHPdoc)
	 * @see SMWResultPrinter::readParameters()
	 */
	protected function readParameters( /* array */ $params, $outputmode ) {
		parent::readParameters( $params, $outputmode );

		$parameterInfo = SMQueryPrinters::getParameterInfo();
		$this->service->addParameterInfo( $parameterInfo );
		
		$validator = new Validator( $this->getName(), false );
		$validator->setParameters( $mapProperties, $parameterInfo );
		$validator->validateParameters();
		
		$fatalError  = $validator->hasFatalError();
		
		if ( $fatalError === false ) {
			$this->parameters = $validator->getParameters( false );
		}
		else {
			$this->fatalErrorMsg =
				'<span class="errorbox">' .
				htmlspecialchars( wfMsgExt( 'validator-fatal-error', 'parsemag', $fatalError->getMessage() ) ) . 
				'</span>';			
		}	
	}	
	
	/**
	 * Builds up and returns the HTML for the map, with the queried coordinate data on it.
	 *
	 * @param SMWQueryResult $res
	 * @param $outputmode
	 * 
	 * @return array
	 */
	public final function getResultText( /* SMWQueryResult */ $res, $outputmode ) {
		if ( $this->fatalErrorMsg !== false ) {
			global $wgParser;
			$mapName = $this->service->getMapId();
			
			$queryHandler = SMQueryHandler( $res, $outputmode, $this->parameters );
			$locations = $queryHandler->getLocations();
			
			return $this->getMapHTML( $this->parameters, $wgParser, $mapName ) . $this->getJSON( $this->parameters, $wgParser, $mapName );
		}
		else {
			return $this->fatalErrorMsg;
		}
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
	 * Adds the static locations (specified via the staticlocations parameter) to the map.
	 * 
	 * TODO
	 * 
	 * @since 0.7
	 */
	protected function addStaticLocations() {
		global $wgTitle;
		
		// New parser object to render popup contents with.
		$parser = new Parser();			
		
		$this->title = $parser->parse( $this->title, $wgTitle, new ParserOptions() )->getText();
		$this->label = $parser->parse( $this->label, $wgTitle, new ParserOptions() )->getText();
		
		// Each $location is an array containg the coordinate set as first element, possibly followed by meta data. 
		foreach ( $this->staticlocations as $location ) {
			$markerData = MapsCoordinateParser::parseCoordinates( array_shift( $location ) );
			
			if ( !$markerData ) continue;
			
			$markerData = array( $markerData['lat'], $markerData['lon'] );
			
			if ( count( $location ) > 0 ) {
				// Parse and add the point specific title if it's present.
				$markerData['title'] = $parser->parse( $location[0], $wgTitle, new ParserOptions() )->getText();
				
				if ( count( $location ) > 1 ) {
					// Parse and add the point specific label if it's present.
					$markerData['label'] = $parser->parse( $location[1], $wgTitle, new ParserOptions() )->getText();
					
					if ( count( $location ) > 2 ) {
						// Add the point specific icon if it's present.
						$markerData['icon'] = $location[2];
					}
				}
			}
			
			// If there is no point specific icon, use the general icon parameter when available.
			if ( !array_key_exists( 'icon', $markerData ) ) {
				$markerData['icon'] = $this->icon;
			}
			
			if ( $markerData['icon'] != '' ) {
				$markerData['icon'] = MapsMapper::getImageUrl( $markerData['icon'] );
			}
			
			// Temporary fix, will refactor away later
			// TODO
			$markerData = array_values( $markerData );
			if ( count( $markerData ) < 5 ) {
				if ( count( $markerData ) < 4 ) {
					$markerData[] = '';
				}				
				$markerData[] = '';
			} 
			
			$this->locations[] = $markerData;
		}		
	}	
	
	/**
	 * Reads the parameters and gets the query printers output.
	 * 
	 * @param SMWQueryResult $results
	 * @param array $params
	 * @param $outputmode
	 * 
	 * @return array
	 */
	public final function getResult( /* SMWQueryResult */ $results, /* array */ $params, $outputmode ) {
		// Skip checks, results with 0 entries are normal.
		$this->readParameters( $params, $outputmode );
		
		return $this->getResultText( $results, SMW_OUTPUT_HTML );
	}

	/**
	 * Returns the internationalized name of the mapping service.
	 * 
	 * @return string
	 */
	public final function getName() {
		return wfMsg( 'maps_' . $this->service->getName() );
	}
	
	/**
	 * Returns a list of parameter information, for usage by Special:Ask and others.
	 * 
	 * @return array
	 */
    public function getParameters() {
    	global $egMapsMapWidth, $egMapsMapHeight;
    	
        $params = parent::exportFormatParameters();
        
        $params[] = array( 'name' => 'zoom', 'type' => 'int', 'description' => wfMsg( 'semanticmaps_paramdesc_zoom' ) );
        $params[] = array( 'name' => 'width', 'type' => 'int', 'description' => wfMsgExt( 'semanticmaps_paramdesc_width', 'parsemag', $egMapsMapWidth ) );
        $params[] = array( 'name' => 'height', 'type' => 'int', 'description' => wfMsgExt( 'semanticmaps_paramdesc_height', 'parsemag', $egMapsMapHeight ) );

        return $params;
    }	
	
}