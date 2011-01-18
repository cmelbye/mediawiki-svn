<?php

/**
 * Class for handling the display_point(s) parser functions with Yahoo! Maps.
 *
 * @file Maps_YahooMapsDispPoint.php
 * @ingroup MapsYahooMaps
 *
 * @author Jeroen De Dauw
 */
class MapsYahooMapsDispPoint extends MapsBasePointMap {
	
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