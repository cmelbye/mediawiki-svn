/**
* TimedText loader.
*/
// Scope everything in "mw" ( keeps the global namespace clean )
( function( mw, $ ) {
	
	// Merge in timed text related attributes:
	mw.mergeConfig( 'EmbedPlayer.SourceAttributes', [
  	   'srclang',
	   'category'
	]);
	
	/**
	* Check if the video tags in the page support timed text
	* this way we can add our timed text libraries to the player
	* library request.
	*/

	// Update the player loader request with timedText library if the embedPlayer
	// includes timedText tracks.
	$( mw ).bind( 'EmbedPlayerUpdateDependencies', function( event, playerElement, classRequest ) {
		if( mw.isTimedTextSupported( playerElement ) ) {
			classRequest = $j.merge( classRequest, ['mw.TimedText'] );
		}
	} );
	
	// On new embed player check if we need to add timedText
	$( mw ).bind( 'EmbedPlayerNewPlayer', function( event, embedPlayer ){
		if( mw.isTimedTextSupported( embedPlayer) ){
			if( ! embedPlayer.timedText && mw.TimedText ) {
				embedPlayer.timedText = new mw.TimedText( embedPlayer );
			}
		}
	});
	
	/**
	 * Check if we should load the timedText interface or not.
	 *
	 * Note we check for text sources outside of
	 */
	mw.isTimedTextSupported = function( embedPlayer ) {
		if( mw.getConfig( 'TimedText.ShowInterface' ) == 'always' ) {
			return true;
		}
		// Check for timed text  sources or api
		if ( 
			(
				$( embedPlayer ).attr('apititlekey')
				||  
				$( embedPlayer ).attr('apiTitleKey' )
			)
			|| 
			( embedPlayer.mediaElement && embedPlayer.mediaElement.textSourceExists() )	
			||
			$( embedPlayer ).find( 'track' ).length != 0
		) {
			return true;
		} else {
			return false;
		}
	};	

} )( window.mediaWiki, window.jQuery );