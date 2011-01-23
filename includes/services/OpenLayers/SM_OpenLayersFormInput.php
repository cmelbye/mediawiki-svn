<?php

/**
 * Class for OpenLayers form inputs.
 * 
 * @file SM_OpenLayersFormInput.php
 * @ingroup SMOpenLayers
 * 
 * @author Jeroen De Dauw
 */
class SMOpenLayersFormInput extends SMFormInput {
	
	/**
	 * @see SMFormInput::getEarthZoom
	 * 
	 * @since 0.6.5
	 */
	protected function getEarthZoom() {
		return 1;
	}	
	
	/**
	 * @see MapsMapFeature::addFormDependencies()
	 */
	protected function addFormDependencies() {
		global $wgOut;
		global $smgScriptPath, $smgStyleVersion;
		
		$this->service->addDependency( Html::linkedScript( "$smgScriptPath/includes/services/OpenLayers/SM_OpenLayersForms.js?$smgStyleVersion" ) );
		$this->service->addDependencies( $wgOut );
	}
	
	/**
	 * @see MapsMapFeature::addSpecificMapHTML
	 */
	public function addSpecificMapHTML() {
		return Html::element(
			'div',
			array(
				'id' => $this->service->getMapId( false ),
				'style' => "width: $this->width; height: $this->height; background-color: #cccccc; overflow: hidden;",
			),
			wfMsg( 'maps-loading-map' )
		);
	}
	
}
