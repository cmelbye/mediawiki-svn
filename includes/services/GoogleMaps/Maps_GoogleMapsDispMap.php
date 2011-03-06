<?php

/**
 * Class for handling the display_map parser hook with Google Maps.
 *
 * @file Maps_GoogleMapsDispMap.php
 * @ingroup MapsGoogleMaps
 *
 * @author Jeroen De Dauw
 */
final class MapsGoogleMapsDispMap extends MapsBaseMap {
	
	/**
	 * @see MapsBaseMap::getMapHTML()
	 */
	public function getMapHTML( array $params, Parser $parser, $mapName ) {
		return 
			$this->service->getOverlayOutput( $mapName, $params['overlays'], $params['controls'] )
			. parent::getMapHTML( $params, $parser, $mapName );
	}
	
}
