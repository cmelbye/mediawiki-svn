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
	 * @see MapsBaseMap::addSpecificMapHTML
	 */
	public function addSpecificMapHTML( Parser $parser ) {
		return Html::element(
			'div',
			array(
				'id' => $this->service->getMapId(),
				'style' => "width: $this->width; height: $this->height; background-color: #cccccc; overflow: hidden;"
			),
			null
		);
	}
	
}