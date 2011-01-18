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

	protected function initSpecificParamInfo( array &$parameters ) {
	}
	
	/**
	 * @see MapsBaseMap::addSpecificMapHTML
	 */
	public function addSpecificMapHTML( Parser $parser ) {
		$mapName = $this->service->getMapId();
		
		$this->service->addOverlayOutput( $this->output, $mapName, $this->overlays, $this->controls );

		return Html::element(
			'div',
			array(
				'id' => $mapName,
				'style' => "width: $this->width; height: $this->height; background-color: #cccccc; overflow: hidden;",
			),
			wfMsg( 'maps-loading-map' )
		);
	}
	
}