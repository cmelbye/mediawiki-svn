<?php

/**
 * Class for handling geographical SMW queries.
 * 
 * @since 0.7.3
 * 
 * @ingroup SemanticMaps
 * @file SM_QueryHandler.php
 * 
 * @author Jeroen De Dauw
 */
class SMQueryHandler {
	
	protected $queryResult;
	protected $outputmode;
	
	protected $locations = false;
	
	// TODO: add system to properly handle query parameters
	public $template = false;
	public $icon = '';
	
	/**
	 * Make a separate link to the title or not?
	 * 
	 * @since 0.7.3
	 * 
	 * @var boolean
	 */
	public $titleLinkSeperate;
	
	/**
	 * Should link targets be made absolute (instead of relative)?
	 * 
	 * @since 0.8
	 * 
	 * @var boolean
	 */
	protected $linkAbsolute;
	
	/**
	 * The text used for the link to the page (if it's created). $1 will be replaced by the page name. 
	 * 
	 * @since 0.8
	 * 
	 * @var string
	 */
	protected $pageLinkText;	
	
	/**
	 * Constructor.
	 * 
	 * @since 0.7.3
	 * 
	 * @param SMWQueryResult $queryResult
	 * @param integer $outputmode
	 */
	public function __construct( SMWQueryResult $queryResult, $outputmode, $linkAbsolute = false, $pageLinkText = '$1', $titleLinkSeperate = false ) {
		$this->queryResult = $queryResult;
		$this->outputmode = $outputmode;
		
		$this->linkAbsolute = $linkAbsolute;
		$this->pageLinkText = $pageLinkText;
		$this->titleLinkSeperate = $titleLinkSeperate;
	}
	
	/**
	 * Gets the query result as a list of locations.
	 * 
	 * @since 0.7.3
	 * 
	 * @return array of MapsLocation
	 */	
	public function getLocations() {
		if ( $this->locations === false ) {
			$this->locations = $this->findLocations();
		}
		
		return $this->locations;
	}
	
	/**
	 * Gets the query result as a list of locations.
	 * 
	 * @since 0.7.3
	 * 
	 * @return array of MapsLocation
	 */		
	protected function findLocations() {
		$locations = array();
		
		while ( ( $row = $this->queryResult->getNext() ) !== false ) {
			$locations = array_merge( $locations, $this->handleResultRow( $row ) );
		}

		return $locations;
	}
	
	/**
	 * Returns the locations found in the provided result row.
	 * 
	 * TODO: split up this method if possible (!)
	 * TODO: fix template handling
	 * TODO: clean up link type handling
	 * 
	 * @since 0.7.3
	 * 
	 * @param array $row Array of SMWResultArray
	 * 
	 * @return array of MapsLocation
	 */
	protected function handleResultRow( array /* of SMWResultArray */ $row ) {
		global $wgUser, $smgUseSpatialExtensions, $wgTitle;
		
		$locations = array();
		
		$skin = $wgUser->getSkin();
		
		$title = '';
		$text = '';
		$lat = '';
		$lon = '';
		
		$coords = array();
		$label = array();		
		
		// Loop throught all fields of the record.
		foreach ( $row as $i => $resultArray ) {
			/* SMWPrintRequest */ $printRequest = $resultArray->getPrintRequest();
			
			// Loop throught all the parts of the field value.
			while ( ( /* SMWDataValue */ $object = $resultArray->getNextObject() ) !== false ) {		
				if ( $object->getTypeID() == '_wpg' && $i == 0 ) {
					if ( !$this->titleLinkSeperate && $this->linkAbsolute ) {
						$title = Html::element(
							'a',
							array( 'href' => $object->getTitle()->getFullUrl() ),
							$object->getTitle()->getText()
						);
					}
					else {
						$title = $object->getLongText( $this->outputmode, $skin );
					}
					
					if ( $this->titleLinkSeperate ) {
						$text .= Html::element(
							'a',
							array( 'href' => $object->getTitle()->getFullUrl() ),
							str_replace( '$1', $object->getTitle()->getText(), $this->params['pagelinktext'] ) 
						) . '<br />';
					}					
				}
				
				if ( $object->getTypeID() != '_geo' && $i != 0 ) {
					if ( $this->linkAbsolute ) {
						$t = Title::newFromText( $printRequest->getHTMLText( NULL ), SMW_NS_PROPERTY );
						
						if ( $t->exists() ) {
							
							$propertyName = $propertyName = Html::element(
								'a',
								array( 'href' => $t->getFullUrl() ),
								$printRequest->getHTMLText( NULL )
							);
						}
						else {
							$propertyName = $printRequest->getHTMLText( NULL );
						}						
					}
					else {
						$propertyName = $printRequest->getHTMLText( $skin );
					}
					
					if ( $propertyName != '' ) $propertyName .= ': ';
					
					if ( $this->linkAbsolute ) {
						$hasPage = $object->getTypeID() == '_wpg';
						
						if ( $hasPage ) {
							$t = Title::newFromText( $object->getLongText( $this->outputmode, NULL ), NS_MAIN );
							$hasPage = $t->exists();
						}
						
						if ( $hasPage ) {
							$propertyValue = Html::element(
								'a',
								array( 'href' => $t->getFullUrl() ),
								$object->getLongText( $this->outputmode, NULL )
							);
						}
						else {
							$propertyValue = $object->getLongText( $this->outputmode, NULL );
						}						
					}
					else {
						$propertyValue = $object->getLongText( $this->outputmode, $skin );
					}
								
					$text .= $propertyName . $propertyValue . '<br />';
				}
		
				if ( $printRequest->getMode() == SMWPrintRequest::PRINT_PROP && $printRequest->getTypeID() == '_geo' ) {
					$coords[] = $object->getDBkeys();
				}
			}
		}
		
		foreach ( $coords as $coord ) {
			if ( count( $coord ) >= 2 ) {
				if ( $smgUseSpatialExtensions ) {
					// TODO
				}
				else {
					list( $lat, $lon ) = $coord; 
				}
				
				if ( $lat != '' && $lon != '' ) {
					$icon = $this->getLocationIcon( $row );

					$location = new MapsLocation();
					
					$location->setCoordinates( array( $lat, $lon ) );
					
					if ( $location->isValid() ) {
						$location->setTitle( $title );
						$location->setText( $text );
						$location->setIcon( $icon );
	
						$locations[] = $location;						
					}
				}
			}	
		}	
		
		return $locations;
	}
	
	/**
	 * Get the icon for a row.
	 *
	 * @since 0.7.3
	 *
	 * @param array $row
	 * 
	 * @return string
	 */
	protected function getLocationIcon( array $row ) {
		$icon = '';
		$legend_labels = array();
		
		// Look for display_options field, which can be set by Semantic Compound Queries
        // the location of this field changed in SMW 1.5
		$display_location = method_exists( $row[0], 'getResultSubject' ) ? $row[0]->getResultSubject() : $row[0];
		
		if ( property_exists( $display_location, 'display_options' ) && is_array( $display_location->display_options ) ) {
			$display_options = $display_location->display_options;
			if ( array_key_exists( 'icon', $display_options ) ) {
				$icon = $display_options['icon'];

				// This is somewhat of a hack - if a legend label has been set, we're getting it for every point, instead of just once per icon	
				if ( array_key_exists( 'legend label', $display_options ) ) {
									
					$legend_label = $display_options['legend label'];
					
					if ( ! array_key_exists( $icon, $legend_labels ) ) {
						$legend_labels[$icon] = $legend_label;
					}
				}
			}
		} // Icon can be set even for regular, non-compound queries If it is, though, we have to translate the name into a URL here
		elseif ( $this->icon != '' ) {
			$icon = MapsMapper::getImageUrl( $this->icon );
		}

		return $icon;
	}	
	
}
