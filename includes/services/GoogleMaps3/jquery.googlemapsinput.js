/**
 * JavasSript for the Google Maps v3 form input of the Semantic Maps extension.
 * @see http://www.mediawiki.org/wiki/Extension:Semantic_Maps
 * 
 * @since 0.8
 * @ingroup SemanticMaps
 * 
 * @licence GNU GPL v3
 * @author Jeroen De Dauw <jeroendedauw at gmail dot com>
 */

(function( $ ){ $.fn.googlemapsinput = function( mapDivId, options ) {

	var self = this;
	
	this.showCoordinate = function( coordinate ) {
		this.mapDiv.removeMarkers();
		coordinate.icon = '';
		coordinate.title = '';
		coordinate.text = coord.dms( coordinate.lat, coordinate.lon );
		this.mapDiv.addMarker( coordinate );
	};
	
	this.geocodeAddress = function( address ) {
		
	};
	
	this.mapforminput( mapDivId, options, { canGeocode: true } );
	
	this.mapDiv.googlemaps( options );	
	
	google.maps.event.addListener( this.mapDiv.map, 'click', function( event ) {
		var location = { lat: event.latLng.lat(), lon: event.latLng.lng() };
		self.mapDiv.map.panTo( event.latLng );
		self.showCoordinate( location );
		self.input.attr( 'value', semanticMaps.buildInputValue( [ location ] ) );
	} );	
	
	return this;
	
}; })( jQuery );