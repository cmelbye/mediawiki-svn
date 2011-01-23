<?php

/**
 * Class for handling the display_point(s) parser functions with Google Maps v3.
 *
 * @file Maps_GoogleMaps3DispPoint.php
 * @ingroup MapsGoogleMaps3
 *
 * @author Jeroen De Dauw
 */
final class MapsGoogleMaps3DispPoint extends MapsBasePointMap {
	
	/**
	 * @see MapsBasePointMap::getMapHTML()
	 */
	public function getMapHTML( array $params, Parser $parser ) {
		return Html::element(
			'div',
			array(
				'id' => $this->service->getMapId(),
				'style' => "width: {$params['width']}; height: {$params['height']}; background-color: #cccccc; overflow: hidden;",
			),
			wfMsg( 'maps-loading-map' )
		);
	}
	
}
