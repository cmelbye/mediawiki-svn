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
	public function getMapHTML( array $params, Parser $parser ) {
		$mapName = $this->service->getMapId();
		
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
