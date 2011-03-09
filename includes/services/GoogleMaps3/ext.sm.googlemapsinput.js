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

jQuery(document).ready(function() {
	if ( true ) {
		for ( i in window.maps.googlemaps3_forminputs ) {
			jQuery( '#' + i + '_forminput' ).googlemapsinput( i, window.maps.googlemaps3_forminputs[i] );
		}
	}
	else {
		alert( mediaWiki.msg( 'maps-googlemaps3-incompatbrowser' ) );
		
		for ( i in window.maps.googlemaps3 ) {
			jQuery( '#' + i + '_forminput' ).text( mediaWiki.msg( 'maps-load-failed' ) );
		}
	}	
});
