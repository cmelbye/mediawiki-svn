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
	 * @see SMFormInput::getResourceModules
	 * 
	 * @since 0.8
	 * 
	 * @return array of string
	 */
	protected function getResourceModules() {
		return array_merge( parent::getResourceModules(), array( 'ext.sm.fi.openlayers' ) );
	}	
	
}
