/**
 * JavasSript for Google Maps v3 maps in the Maps extension.
 * @see http://www.mediawiki.org/wiki/Extension:Maps
 * 
 * @author Jeroen De Dauw <jeroendedauw at gmail dot com>
 */

(function( $ ){ $.fn.googlemaps = function( options ) {

	var mapOptions = {
		disableDefaultUI: true,
		zoom: options.zoom,
		mapTypeId: eval( options.type ),
	};
	
	// TODO
	mapOptions.center = new google.maps.LatLng(-34.397, 150.644);
	
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
	var map = new google.maps.Map( this.get( 0 ), mapOptions );

	// Add the markers.
	for ( var i = options.locations.length - 1; i >= 0; i-- ) {
		var location = options.locations[i];
		var marker = new google.maps.Marker({
			map: map,
			position: new google.maps.LatLng( location.lat , location.lon ),
			title: location.title
		});
	}
	
	if ( options.centre === false ) {
		
	}
	else {
		map.setCenter( new google.maps.LatLng( options.centre.lat , options.centre.lon ) );
	}
	
	return this;
	
}; })( jQuery );