<?php

/**
 * Class for handling the display_point(s) parser functions with OpenLayers.
 *
 * @file Maps_OpenLayersDispPoint.php
 * @ingroup MapsOpenLayers
 *
 * @author Jeroen De Dauw
 */
class MapsOpenLayersDispPoint extends MapsBasePointMap {
	
	/**
	 * @see MapsBaseMap::addSpecificMapHTML
	 */
	public function addSpecificMapHTML( Parser $parser ) {
		return Html::element(
			'div',
			array(
				'id' => $this->service->getMapId(),
				'style' => "width: $this->width; height: $this->height; background-color: #cccccc; overflow: hidden;",
			),
			wfMsg( 'maps-loading-map' )
		);
	}

}