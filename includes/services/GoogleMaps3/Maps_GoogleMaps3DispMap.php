<?php

/**
 * Class for handling the display_map parser hook with Google Maps v3.
 *
 * @ingroup MapsGoogleMaps3
 *
 * @author Jeroen De Dauw
 */
final class MapsGoogleMaps3DispMap extends MapsBaseMap {
	
	/**
	 * @see MapsBaseMap::getMapHTML()
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