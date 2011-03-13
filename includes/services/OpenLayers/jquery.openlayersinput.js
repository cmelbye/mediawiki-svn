/**
 * JavasSript for the OpenLayers form input of the Semantic Maps extension.
 * @see http://www.mediawiki.org/wiki/Extension:Semantic_Maps
 * 
 * @since 0.8
 * @ingroup SemanticMaps
 * 
 * @licence GNU GPL v3
 * @author Jeroen De Dauw <jeroendedauw at gmail dot com>
 */

(function( $ ){ $.fn.openlayersinput = function( mapDivId, options ) {
	
	var input = $( '<input />' ).attr( {
		'type': 'text',
		'name': options.inputname,
		'id': mapDivId + '_values',
		'value': semanticMaps.buildInputValue( options.locations ),
		'size': options.fieldsize
	} );
	
	var geofield = $( '<input />' ).attr( {
		'type': 'text',
		'id': mapDivId + '_geofield',
		'value': mediaWiki.msg( 'semanticmaps_enteraddresshere' ),
		'style': 'color: darkgray',
		'size': options.fieldsize
	} );
	
	geofield.focus( function() {
		if ( this.value == mediaWiki.msg( 'semanticmaps_enteraddresshere' ) ) {
			this.value = '';
			$( this ).css( 'color', '' );
		}
	} );
	
	geofield.blur( function() {
		if ( this.value == '' ) {
			this.value = mediaWiki.msg( 'semanticmaps_enteraddresshere' );
			$( this ).css( 'color', 'darkgray' );
		}
	} );
	
	var button = $( '<input />' ).attr( { 'type': 'submit', 'value': mediaWiki.msg( 'semanticmaps_lookupcoordinates' ) } );
	
	button.click( function() {
		geocodeAddress( $( '#' + mapDivId + '_geofield' ).attr( 'value' ) );
		return false;
	} );
	
	var mapDiv = $( '<div />' )
		.attr( {
			'id': mapDivId,
			'class': 'ui-widget ui-widget-content'
		} )
		.css( {
			'width': options.width,
			'height': options.height
		} );
	
	this.html( $( '<p />' ).append( input ) );
	
	if ( options.geonamesusername != '' ) {
		this.append( $( '<p />' ).append( geofield ).append( button ) );			
	}

	this.append( mapDiv );
	
	mapDiv.openlayers( mapDivId, options );	
	
	function geocodeAddress( address ) {
		$.getJSON(
			'http://api.geonames.org/searchJSON?callback=?',
			{
				'q': address,
				'username': options.geonamesusername,
				'formatted': 'true',
				'maxRows': 1
			},
			function( data ) {
				if ( data.totalResultsCount ) {
					if ( data.totalResultsCount > 0 ) {
						showLocation( new OpenLayers.LonLat( data.geonames[0].lng, data.geonames[0].lat ), address );
					}
					else {
						// TODO: notify no result
					}
				}
				else {
					// TODO: error
				}
			}
		);		
	}
	
	/**
	 * @param location: OpenLayers.LonLat object
	 */
	function showLocation( location, address ) {
		var markerLayer = mapDiv.map.getLayer('markerLayer');
		var markerCollection = markerLayer.markers;

		for ( var i = markerCollection.length - 1; i >= 0; i-- ) {
			markerLayer.removeMarker( markerCollection[i] );
		}
		
		location.transform( new OpenLayers.Projection( "EPSG:4326" ), new OpenLayers.Projection( "EPSG:900913" ) );
		
		markerLayer.addMarker(
			mapDiv.getOLMarker(
				markerLayer,
				{
					lonlat: location,
					text: '<b>' + address + '</b><hr />' + semanticMaps.dms( location.lat, location.lon ),
					title: address,
					icon: options.icon
				}
			)
		);
		
		mapDiv.map.panTo( location );
		
		$( '#' + mapDivId + '_values' ).attr( 'value', semanticMaps.buildInputValue( [ location ] ) ); 
	}
	
	return this;
	
}; })( jQuery );