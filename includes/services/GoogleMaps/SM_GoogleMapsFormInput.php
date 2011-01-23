<?php

/**
 * Class for Google Maps v2 form inputs.
 * 
 * @file SM_GoogleMapsFormInput.php
 * @ingroup SMGoogleMaps
 * 
 * @author Jeroen De Dauw
 * @author Robert Buzink
 * @author Yaron Koren
 */
class SMGoogleMapsFormInput extends SMFormInput {

	/**
	 * @see SMFormInput::getEarthZoom
	 * 
	 * @since 0.6.5
	 */
	protected function getEarthZoom() {
		return 1;
	}	
	
	/**
	 * @see SMFormInput::getShowAddressFunction
	 * 
	 * @since 0.6.5
	 */	
	protected function getShowAddressFunction() {
		global $egGoogleMapsKey;
		return $egGoogleMapsKey == '' ? false : 'showGAddress';	
	}
	
	/**
	 * @see smw/extensions/SemanticMaps/FormInputs/SMFormInput#addFormDependencies()
	 */
	protected function addFormDependencies() {
		global $wgOut;
		global $smgScriptPath, $smgStyleVersion;

		$this->service->addDependency( Html::linkedScript( "$smgScriptPath/includes/services/GoogleMaps/SM_GoogleMapsForms.js?$smgStyleVersion" ) );
		$this->service->addDependencies( $wgOut );
	}
	
	/**
	 * @see MapsMapFeature::getMapHTML
	 */
	public function getMapHTML() {
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