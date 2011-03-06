<?php

/**
 * Class for handling the display_point(s) parser functions with Google Maps.
 *
 * @file Maps_GoogleMapsDispPoint.php
 * @ingroup MapsGoogleMaps
 *
 * @author Jeroen De Dauw
 */
final class MapsGoogleMapsDispPoint extends MapsBasePointMap {

	/**
	 * @see MapsBasePointMap::getMapHTML
	 */
	public function getMapHTML( array $params, Parser $parser, $mapName ) {
		return 
			$this->service->getOverlayOutput( $mapName, $params['overlays'], $params['controls'] )
			. parent::getMapHTML( $params, $parser, $mapName );
	}
	
}
