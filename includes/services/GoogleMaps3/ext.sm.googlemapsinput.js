/**
 * JavasSript for the Google Maps v3 form input in the Semantic Maps extension.
 * @see http://www.mediawiki.org/wiki/Extension:Semantic_Maps
 * 
 * @since 0.8
 * @ingroup SemanticMaps
 * 
 * @licence GNU GPL v3
 * @author Jeroen De Dauw <jeroendedauw at gmail dot com>
 */

function buildInputValue( locations ) {
	var dms = [];
	
	for ( i in locations ) {
		dms.push( locationToDMS( locations[i].lat, locations[i].lon ) );
	}
	
	return dms.join( '; ' );
}

function locationToDMS ( lat, lon ) { // TODO: i18n
	return Math.abs( lat ) + '° ' + ( lat < 0 ? 'S' : 'N' ) + ', ' + Math.abs( lon ) + '° ' + ( lon < 0 ? 'W' : 'E' );
}

jQuery(document).ready(function() {
	if ( false ) {
		for ( i in window.maps.googlemaps3_forminputs ) {
			jQuery( '#' + i + '_forminput' ).googlemapsinput( i, window.maps.googlemaps3_forminputs[i] );
		}
	}
	else {
		alert( mediaWiki.msg( 'maps-googlemaps3-incompatbrowser' ) );
		
		for ( i in window.maps.googlemaps3_forminputs ) {
			jQuery( '#' + i + '_forminput' )
				.html( $( '<input />' )
					.attr( { 'name': i, 'value': buildInputValue( window.maps.googlemaps3_forminputs[i].locations ) } )
				);
		}
	}	
});
