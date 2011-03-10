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
	this.html(
		$( '<div />' ).css( {
			'display': 'none'
		} ).append( $( '<input />' ).attr( { 'type': 'text', 'name': options.inputname, 'id': mapDivId + '_values' } ) )
	);
	
	updateInputValue( buildInputValue( options.locations ) );
	
	var table = $( '<table />' ).attr( { 'class' : 'mapinput' } );
	
	for ( i in options.locations ) {
		table.append( 
			'<tr><td>' +
				locationToDMS( options.locations[i].lat, options.locations[i].lon ) +
			'</td><td>' + 
				'<button>' + mediaWiki.msg( 'semanticmaps-forminput-remove' ) + '</button>' + 
			'</td></tr>'
		);
	}
	
	table.append(
		'<tr><td width="300px">' +
			'<input type="text" class="text ui-widget-content ui-corner-all" />' +
		'</td><td>' + 
			'<button>' + mediaWiki.msg( 'semanticmaps-forminput-add' ) + '</button>' +
		'</td></tr>'
			
	);
	
	this.append(
		table
	);
	
	/*
	this.append(
		$( '<div />' )
			.attr( {
				'id': mapDivId,
				'width': options.width,
				'height': options.height
			} )		
	);
	*/	
	
	$( "button", ".mapinput" ).button();
	
	//$('#' + mapDivId).googlemaps( options ).resizable();
	
	function locationToDMS ( lat, lon ) { // TODO: i18n
		return Math.abs( lat ) + '° ' + ( lat < 0 ? 'S' : 'N' ) + ', ' + Math.abs( lon ) + '° ' + ( lon < 0 ? 'W' : 'E' );
	}
	
	function updateInputValue( value ) {
		$( '#' + mapDivId + '_values' ).text( value );
	}
	
	function buildInputValue( locations ) {
		var dms = [];
		
		for ( i in locations ) {
			dms.push( locationToDMS( locations[i].lat, locations[i].lon ) );
		}
		
		return dms.join( '; ' );
	}
	
	this.attr( { 'width': options.width, 'height': options.height } );
	
	return this;
	
}; })( jQuery );