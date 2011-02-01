
// Wrap in mediaWiki 
( function( mw ) {

	// Add MediaWikiSupportPlayer dependency on players with apiTitleKey 
	$( mw ).bind( 'EmbedPlayerUpdateDependencies', function( event, embedPlayer, dependencySet ){
		if( $j( embedPlayer ).attr( 'data-mwtitle' ) ){
			//dependencySet.push( 'MediaWikiSupportPlayer' );
		}
	});
	
} )( window.mediaWiki );