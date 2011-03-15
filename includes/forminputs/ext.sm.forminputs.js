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
	
	this.buildInputValue = function( locations ) {
		var floats = [];
		
		for ( i in locations ) {
			floats.push( coord.float( locations[i].lat, locations[i].lon ) );
		}
		
		return floats.join( '; ' );
	};
	
} )( jQuery );
