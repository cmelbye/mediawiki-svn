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
		$validator->setParameters( $params, $parameterInfo );
		$validator->validateParameters();
		
		$fatalError  = $validator->hasFatalError();
		
		if ( $fatalError === false ) {
			$this->parameters = $validator->getParameterValues();
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
		if ( $this->fatalErrorMsg === false ) {
			global $wgParser;
			
			$params = $this->parameters;
			
			$queryHandler = new SMQueryHandler( $res, $outputmode, $params );
			//$queryHandler->setText(  );
			
			$this->handleMarkerData( $params, $queryHandler->getLocations() );
			
			$mapName = $this->service->getMapId();
			
			return array(
				$this->getMapHTML( $params, $wgParser, $mapName ) . $this->getJSON( $params, $wgParser, $mapName ),
				'noparse' => true, 
				'isHTML' => true
			);
		}
		else {
			return $this->fatalErrorMsg;
		}
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
	 * @param array $queryLocations
	 */
	protected function handleMarkerData( array &$params, array $queryLocations ) {
		global $wgTitle;

		$parser = new Parser();			
		$iconUrl = MapsMapper::getImageUrl( $params['icon'] );
		$params['locations'] = array();

		foreach ( array_merge( $params['staticlocations'], $queryLocations ) as $location ) {
			if ( $location->isValid() ) {
				$jsonObj = $location->getJSONObject( $params['title'], $params['label'], $iconUrl );
				
				$jsonObj['title'] = strip_tags( $jsonObj['title'] );
				
				$params['locations'][] = $jsonObj;				
			}
		}
		
		unset( $params['staticlocations'] );
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
    	
        $params = parent::getParameters();
        
		$paramDescs = SMQueryPrinters::getParameterInfo();
		$this->service->addParameterInfo( $paramDescs ); 

		foreach ( $paramDescs as $paramDesc ) {
			$param = array(
				'name' => $paramDesc->getName(),
				'type' => $this->getMappedParamType( $paramDesc->getType() ),
				'description' => $paramDesc->getDescription() ? $paramDesc->getDescription() : '',
				'default' => $paramDesc->isRequired() ? '' : $paramDesc->getDefault()
			);
			
	        foreach ( $paramDesc->getCriteria() as $criterion ) {
	    		if ( $criterion instanceof CriterionInArray ) {
	    			$param['values'] = $criterion->getAllowedValues();
	    			$param['type'] = $paramDesc->isList() ? 'enum-list' : 'enumeration';
	    			break;
	    		}
	    	} 

	    	$params[] = $param;
		}

        return $params;
    }
    
    /**
     * Takes in an element of the Parameter::TYPE_ enum and turns it into an SMW type (string) indicator.
     * 
     * @since 0.8
     * 
     * @param Parameter::TYPE_ $type
     * 
     * @return string
     */
    protected function getMappedParamType( $type ) {
    	static $typeMap = array(
    		Parameter::TYPE_STRING => 'string',
    		Parameter::TYPE_BOOLEAN => 'boolean',
    		Parameter::TYPE_CHAR => 'int',
    		Parameter::TYPE_FLOAT => 'int',
    		Parameter::TYPE_INTEGER => 'int',
    		Parameter::TYPE_NUMBER => 'int',
    	);
    	
    	return $typeMap[$type];
    }
	
}