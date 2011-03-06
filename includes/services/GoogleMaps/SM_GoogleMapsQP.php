<?php
/**
 * A query printer for maps using the Google Maps API.
 *
 * @file SM_GoogleMapsQP.php
 * @ingroup SMGoogleMaps
 *
 * @author Robert Buzink
 * @author Yaron Koren
 * @author Jeroen De Dauw
 */
class SMGoogleMapsQP extends SMMapPrinter {
	
	/**
	 * @see SMMapPrinter::getServiceName
	 */	
	protected function getServiceName() {
		return 'googlemaps2';
	}
	
	/**
	 * @see SMMapPrinter::getMapHTML
	 */
	protected function getMapHTML( array $params, Parser $parser, $mapName ) {
		return 
			$this->service->getOverlayOutput( $mapName, $params['overlays'], $params['controls'] )
			. parent::getMapHTML( $params, $parser, $mapName );
	}
	
	/**
	 * Returns type info, descriptions and allowed values for this QP's parameters after adding the
	 * specific ones to the list.
	 * 
	 * @return array
	 */
    public function getParameters() {
        $params = parent::getParameters();
        
        $allowedTypes = array_keys( MapsGoogleMaps::$mapTypes );
        
        $params[] = array( 'name' => 'controls', 'type' => 'enum-list', 'description' => wfMsg( 'semanticmaps_paramdesc_controls' ), 'values' => MapsGoogleMaps::getControlNames() );
        $params[] = array( 'name' => 'types', 'type' => 'enum-list', 'description' => wfMsg( 'semanticmaps_paramdesc_types' ), 'values' => $allowedTypes );
        $params[] = array( 'name' => 'type', 'type' => 'enumeration', 'description' => wfMsg( 'semanticmaps_paramdesc_type' ), 'values' => $allowedTypes );
        $params[] = array( 'name' => 'overlays', 'type' => 'enum-list', 'description' => wfMsg( 'semanticmaps_paramdesc_overlays' ), 'values' => MapsGoogleMaps::getOverlayNames() );
        $params[] = array( 'name' => 'autozoom', 'type' => 'enumeration', 'description' => wfMsg( 'semanticmaps_paramdesc_autozoom' ), 'values' => array( 'on', 'off' ) );
        
        return $params;
    }
	
}
