/**
 * JavasSript for the Semantic Maps extension.
 * @see http://www.mediawiki.org/wiki/Extension:Semantic_Maps
 * 
 * @since 0.8
 * @ingroup SemanticMaps
 * 
 * @licence GNU GPL v3
 * @author Jeroen De Dauw <jeroendedauw at gmail dot com>
 */

window.semanticMaps = new ( function( $ ) {
	
	this.buildInputValue = function ( locations ) {
		var dms = [];
		
		for ( i in locations ) {
			dms.push( this.locationToDMS( locations[i].lat, locations[i].lon ) );
		}
		
		return dms.join( '; ' );
	};
	
	this.dms = function ( lat, lon ) { // TODO: i18n
		return Math.abs( lat ) + '° ' + ( lat < 0 ? 'S' : 'N' ) + ', ' + Math.abs( lon ) + '° ' + ( lon < 0 ? 'W' : 'E' );
	};
	
	this.locationToDMS = this.dms;
	
} )( jQuery );