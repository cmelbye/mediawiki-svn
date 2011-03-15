/**
 * JavasSript for the form inputs of the Semantic Maps extension.
 * @see http://www.mediawiki.org/wiki/Extension:Semantic_Maps
 * 
 * @since 0.8
 * @ingroup SemanticMaps
 * 
 * @licence GNU GPL v3
 * @author Jeroen De Dauw <jeroendedauw at gmail dot com>
 */

(function( $ ){ $.fn.mapforminput = function( mapDivId, options ) {
	
	var self = this;
	
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
			self.showCoordinate( location );
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
		self.geocodeAddress( $( '#' + mapDivId + '_geofield' ).attr( 'value' ) );
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
	this.mapDiv = mapDiv;
	
	this.html( $( '<p />' ).append( input ).append( updateButton ) );
	
	if ( options.geonamesusername != '' ) {
		this.append( $( '<p />' ).append( geofield ).append( geoButton ) );			
	}

	this.append( mapDiv );
	
	return this;
	
}; })( jQuery );