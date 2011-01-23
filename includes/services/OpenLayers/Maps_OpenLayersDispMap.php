<?php

/**
 * Class for handling the display_map parser hook with OpenLayers.
 *
 * @file Maps_OpenLayersDispMap.php
 * @ingroup MapsOpenLayers
 *
 * @author Jeroen De Dauw
 */
class MapsOpenLayersDispMap extends MapsBaseMap {
	
	/**
	 * @see MapsBaseMap::getJSONObject
	 *
	 * @since 0.7.3
	 *
	 * @param array $params
	 * @param Parser $parser
	 * 
	 * @return mixed
	 */	
	protected function getJSONObject( array $params, Parser $parser ) {
		global $wgLang;
		$params['langCode'] = $wgLang->getCode();
		$params['mapId'] = $this->service->getMapId( false ); 
		return $params;
	}	
	
	/**
	 * @see MapsBaseMap::getMapHTML
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
