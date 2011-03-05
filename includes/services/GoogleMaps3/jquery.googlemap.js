/**
 * JavasSript for Google Maps v3 maps in the Maps extension.
 * @see http://www.mediawiki.org/wiki/Extension:Maps
 * 
 * @author Jeroen De Dauw <jeroendedauw at gmail dot com>
 */

(function( $ ){ $.fn.googlemaps = function( options ) {

	var mapOptions = {
		disableDefaultUI: true,
		mapTypeId: eval( options.type ),
	};
	
	// Map controls
	mapOptions.panControl = $.inArray( 'pan', options.controls ) != -1;
	mapOptions.zoomControl = $.inArray( 'zoom', options.controls ) != -1;
	mapOptions.mapTypeControl = $.inArray( 'type', options.controls ) != -1;
	mapOptions.scaleControl = $.inArray( 'scale', options.controls ) != -1;
	mapOptions.streetViewControl = $.inArray( 'streetview', options.controls ) != -1;

	// Map control styles
	mapOptions.zoomControlOptions = { style: eval( options.zoomstyle ) }
	mapOptions.mapTypeControlOptions = { style: eval( options.typestyle ) }	

	// Create the map.
	var locations = options.locations;

	var map = new google.maps.Map( this.get( 0 ), mapOptions );

	var markers = []; 
	
	// Add the markers.
	for ( var i = options.locations.length - 1; i >= 0; i-- ) {
		var location = options.locations[i];
		
		var marker = new google.maps.Marker( {
			map: map,
			position: new google.maps.LatLng( location.lat , location.lon ),
			title: location.title
		} );
		
		this.openWindow = false;
		
		if ( location.text != '' ) {
			marker.text = location.text;
			google.maps.event.addListener( marker, 'click', function() {
				if ( this.openWindow !== false ) {
					this.openWindow.close();
				}
				this.openWindow = new google.maps.InfoWindow( { content: this.text } );
				this.openWindow.closeclick = function() {
					marker.openWindow = false;
				};
				this.openWindow.open( map, this );					
			} );			
		}
		
		markers.push( marker );
	}
	
	var bounds;
	
	if ( options.centre === false || options.zoom === false ) {
		bounds = new google.maps.LatLngBounds();
		
		for ( var i = markers.length - 1; i >= 0; i-- ) {
			bounds.extend( markers[i].getPosition() );
		}
		
		map.fitBounds( bounds );
	}
	
	map.setCenter(
		options.centre === false ?
			bounds.getCenter() : new google.maps.LatLng( options.centre.lat , options.centre.lon )
	);
	
	if ( options.autoinfowindows ) {
		for ( var i = markers.length - 1; i >= 0; i-- ) {
			markers[i].openWindow = new google.maps.InfoWindow( { content: markers[i].text } );
			markers[i].openWindow.closeclick = function() {
				markers[i].openWindow = false;
			};			
			markers[i].openWindow.open( map, markers[i] );	
		}		
	}
	
	return this;
	
}; })( jQuery );