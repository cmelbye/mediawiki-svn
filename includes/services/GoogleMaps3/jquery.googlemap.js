(function( $ ){ $.fn.googlemaps = function( options ) {

	var centre = new google.maps.LatLng(-34.397, 150.644);
	
	var map = new google.maps.Map( this.get( 0 ), {
		zoom: options.zoom,
		mapTypeId: eval( options.type ),
		center: centre
	} );

	return this;
	
}; })( jQuery );