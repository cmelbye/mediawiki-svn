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
	
	var updateButton = $( '<button />' ).text( mediaWiki.msg( 'semanticmaps-updatemap' ) );
	
	updateButton.click( function() {
		var locations = coord.split( $( '#' + mapDivId + '_values' ).attr( 'value' ) );
		var location = coord.parse( locations[0] );
		
		if ( location !== false ) {
			projectAndShowLocation( new OpenLayers.LonLat( location.lon, location.lat ), '' );
		}
		
		return false;
	} );
	
	input.keypress( function( event ) {
		if ( event.which == '13' ) {
			updateButton.click();
		}
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
	
	var geoButton = $( '<button />' ).text( mediaWiki.msg( 'semanticmaps_lookupcoordinates' ) );
	
	geoButton.click( function() {
		geocodeAddress( $( '#' + mapDivId + '_geofield' ).attr( 'value' ) );
		return false;
	} );
	
	geofield.keypress( function( event ) {
		if ( event.which == '13' ) {
			geoButton.click();
		}
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
	
	this.html( $( '<p />' ).append( input ).append( updateButton ) );
	
	if ( options.geonamesusername != '' ) {
		this.append( $( '<p />' ).append( geofield ).append( geoButton ) );			
	}

	this.append( mapDiv );
	
	mapDiv.openlayers( mapDivId, options );	
	
	var clickControl = new (OpenLayers.Class(OpenLayers.Control, {				
		defaultHandlerOptions: {
			'single': true,
			'double': false,
			'pixelTolerance': 0,
			'stopSingle': false,
			'stopDouble': false
		},

		initialize: function(options) {
			this.handlerOptions = OpenLayers.Util.extend(
				{}, this.defaultHandlerOptions
			);
			OpenLayers.Control.prototype.initialize.apply(
				this, arguments
			); 
			this.handler = new OpenLayers.Handler.Click(
				this, {
					'click': this.trigger
				}, this.handlerOptions
			);
		}, 

		trigger: function(e) {
			showLocation( mapDiv.map.getLonLatFromViewPortPx(e.xy), 'Click' ); // TODO
		}

	}))();
	mapDiv.map.addControl( clickControl );
	clickControl.activate();
	
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
	
	function projectAndShowLocation( location, title ) {
		location.transform( new OpenLayers.Projection( "EPSG:4326" ), new OpenLayers.Projection( "EPSG:900913" ) );
		showLocation( location, title );
	}
	
	/**
	 * @param {OpenLayers.LonLat} location
	 */
	function showLocation( location, title ) {
		var markerLayer = mapDiv.map.getLayer('markerLayer');
		var markerCollection = markerLayer.markers;

		for ( var i = markerCollection.length - 1; i >= 0; i-- ) {
			markerLayer.removeMarker( markerCollection[i] );
		}
		
		var normalProjectionLocation = new OpenLayers.LonLat( location.lon, location.lat );
		normalProjectionLocation.transform( new OpenLayers.Projection( "EPSG:900913" ), new OpenLayers.Projection( "EPSG:4326" ) );		
		
		var text = coord.dms( normalProjectionLocation.lat, normalProjectionLocation.lon );
		
		if ( title != '' ) {
			text = '<b>' + title + '</b><hr />' + text;
		}
		
		markerLayer.addMarker(
			mapDiv.getOLMarker(
				markerLayer,
				{
					lonlat: location,
					text: text,
					title: title,
					icon: options.icon
				}
			)
		);
		
		mapDiv.map.panTo( location );

		$( '#' + mapDivId + '_values' ).attr( 'value', semanticMaps.buildInputValue( [ normalProjectionLocation ] ) ); 
	}
	
	return this;
	
}; })( jQuery );