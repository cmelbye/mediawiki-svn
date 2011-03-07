<?php
/**
 * A query printer for maps using the Google Maps API.
 *
 * @file SM_GoogleMapsQP.php
 * @ingroup SMGoogleMaps
 *
 * @author Robert Buzink
 * @author Yaron Koren
 * @author Jeroen De Dauw
 */
class SMGoogleMapsQP extends SMMapPrinter {
	
	/**
	 * @see SMMapPrinter::getServiceName
	 */	
	protected function getServiceName() {
		return 'googlemaps2';
	}
	
	/**
	 * @see SMMapPrinter::getMapHTML
	 */
	protected function getMapHTML( array $params, Parser $parser, $mapName ) {
		return 
			$this->service->getOverlayOutput( $mapName, $params['overlays'], $params['controls'] )
			. parent::getMapHTML( $params, $parser, $mapName );
	}
	
}
