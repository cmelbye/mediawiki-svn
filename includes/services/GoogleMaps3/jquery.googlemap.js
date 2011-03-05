(function( $ ){ $.fn.googlemaps = function( options ) {

	var mapOptions = {
		//disableDefaultUI: true,
		zoom: options.zoom,
		mapTypeId: eval( options.type ),
	};
	
	mapOptions.center = new google.maps.LatLng(-34.397, 150.644);
	
	mapOptions.panControl = $.inArray( 'pan', options.controls ) != -1;
	mapOptions.zoomControl = $.inArray( 'zoom', options.controls ) != -1;
	mapOptions.mapTypeControl = $.inArray( 'type', options.controls ) != -1;
	mapOptions.scaleControl = $.inArray( 'scale', options.controls ) != -1;
	mapOptions.streetViewControl = $.inArray( 'streetview', options.controls ) != -1;
	
	mapOptions.zoomControlOptions = { style: eval( options.zoomstyle ) }
	mapOptions.mapTypeControlOptions = { style: eval( options.typestyle ) }	
	
	

	var map = new google.maps.Map( this.get( 0 ), mapOptions );

	return this;
	
}; })( jQuery );