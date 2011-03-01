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
		$output = $this->service->getOverlayOutput( $mapName, $params['overlays'], $params['controls'] );
		
		return $output . Html::element(
			'div',
			array(
				'id' => $mapName,
				'style' => "width: {$params['width']}; height: {$params['height']}; background-color: #cccccc; overflow: hidden;",
			),
			wfMsg( 'maps-loading-map' )
		);			
	}
	
}
