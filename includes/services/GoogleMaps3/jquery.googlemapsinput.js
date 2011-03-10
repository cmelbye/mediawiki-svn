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
	this.attr( { 'class': "ui-widget" } ).css( { 'width': 'auto' } );
	
	this.html(
		$( '<div />' ).css( {
			'display': 'none'
		} ).append( $( '<input />' ).attr( { 'type': 'text', 'name': options.inputname, 'id': mapDivId + '_values' } ) )
	);
	
	updateInputValue( buildInputValue( options.locations ) );
	
	var table = $( '<table />' ).attr( { 'class' : 'mapinput ui-widget ui-widget-content' } );
	
	table.append(
		'<thread><tr class="ui-widget-header "><th colspan="2">' + mediaWiki.msg( 'semanticmaps-forminput-locations' ) + '</th></tr></thead><tbody>'
	);
	
	this.append( table );
	
	for ( i in options.locations ) {
		appendTableRow( i, options.locations[i].lat, options.locations[i].lon );
	}
	
	var rowNr = options.locations.length;
	
	table.append(
		'<tr id="' + mapDivId + '_addrow"><td width="300px">' +
			'<input type="text" class="text ui-widget-content ui-corner-all" width="95%" />' +
		'</td><td>' + 
			'<button id="' + mapDivId + '_addbutton">' + mediaWiki.msg( 'semanticmaps-forminput-add' ) + '</button>' +
		'</td></tr></tbody>'
	);
	
	this.append(
		$( '<div />' )
			.attr( {
				'id': mapDivId,
				'class': 'ui-widget ui-widget-content'
			} )
			.css( {
				'width': options.width,
				'height': options.height
			} )
			.googlemaps( options )
	);
	
	$( "#" + mapDivId + '_addbutton' ).button().click( onAddButtonClick );
	
	function onAddButtonClick() {
		var addRow = $( '#' + mapDivId + '_addrow' );
		
		addRow.remove();
		appendTableRow( rowNr, 0, 0 ); // TODO
		table.append( addRow );
		$( "#" + mapDivId + '_addbutton' ).button().click( onAddButtonClick );
		rowNr++;
		
		updateInput();
		return false;		
	}
	
	function onRemoveButtonClick() {
		$( '#' + mapDivId + '_row_' + $( this ).attr( 'rowid' ) ).remove();
		updateInput();
		return false;		
	}
	
	//$('#' + mapDivId);
	
	function appendTableRow( i, lat, lon ) {
		table.append(
			'<tr id="' + mapDivId + '_row_' + i + '"><td>' +
				locationToDMS( lat, lon ) +
			'</td><td>' + 
				'<button class="forminput-remove" rowid="' + i + '" id="' + mapDivId + '_addbutton_' + i + '">' +
					mediaWiki.msg( 'semanticmaps-forminput-remove' ) +
				'</button>' + 
			'</td></tr>'
		);
		
		$( "#" + mapDivId + '_addbutton_' + i ).button().click( onRemoveButtonClick );
	}
	
	function locationToDMS ( lat, lon ) { // TODO: i18n
		return Math.abs( lat ) + '° ' + ( lat < 0 ? 'S' : 'N' ) + ', ' + Math.abs( lon ) + '° ' + ( lon < 0 ? 'W' : 'E' );
	}
	
	function updateInput() {
		var locations = [];
		
		//$( '' ).each();
		
		updateInputValue( buildInputValue( locations ) );
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