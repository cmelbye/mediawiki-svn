/**
* EmbedPlayer loader
*/
( function( mw, $ ) {
	/**
	* Add a DOM ready check for player tags
	*
	* We use SetupInterface so other functions that depend on the interface can 
	* wait for the IntefacesReady event
	*/
	$( mw ).bind( 'SetupInterface', function( event, callback ){
		
		// Allow modules to do tag rewrites as well: 
		var doModuleTagRewrites = function(){			
			$(mw).triggerQueueCallback( 'LoadeRewritePlayerTags', callback );
		}
		// Check if we have tags to rewrite: 
		if( $( mw.getConfig( 'EmbedPlayer.RewriteTags' )  ).length ) {
			var rewriteElementCount = 0;

			// Rewrite the embedPlayer EmbedPlayer.RewriteTags :
			$( mw.getConfig( 'EmbedPlayer.RewriteTags' ) )
				.embedPlayer( doModuleTagRewrites );
		} else {
			doModuleTagRewrites();
		}
	});
	
	/**
	* Add the mwEmbed jQuery loader wrapper 
	*/
	$.fn.embedPlayer = function( readyCallback ){
		var _this = this;
		
		if( this.selector ){
			var playerSelect = this.selector;
		} else {
			var playerSelect = this;
		}
		
		// Hide videonojs class
		$( '.videonojs' ).hide();

		
		// Set up the embed video player class request: (include the skin js as well)
		var dependencySet = [
			'mw.EmbedPlayer'
		];
		
		// Add PNG fix code needed:
		if ( $.browser.msie && $.browser.version < 7 ) {
			dependencySet.push( 'jquery.pngFix' );
		}
		
		// Guess at playback system for 90+% of users the browser indicates playback mode: 
		// NOTE: this does not affect a given playback library being loaded on-demand later. 
		if( ( $.browser.msie && $.browser.version < 9 ) || $.browser.safari ) {
			dependencySet.push( 'mw.EmbedPlayerJava' );
		}
		// If video tag is supported add native lib:
		if( document.createElement('video').canPlayType && !$.browser.safari) {
			dependencySet.push( 'mw.EmbedPlayerNative' )
		}

		
		// Check if the iFrame player api is enabled and we have a parent iframe url: 
		// TODO we might want to move the iframe api to a separate module
		if ( mw.getConfig('EmbedPlayer.EnableIframeApi') 
				&& 
			mw.getConfig( 'EmbedPlayer.IframeParentUrl' ) 
		){
			dependencySet.push('mw.EmbedPlayerNative');
			dependencySet.push('$.postMessage');
			dependencySet.push('mw.IFramePlayerApiServer');
		}
		
		// Allow modules to update the set of dependencies: 
		var rewriteElementCount = 0;
		$.each( playerSelect, function(inx, playerElement){
			
			// Assign an the element an ID ( if its missing one )
			if ( $( playerElement ).attr( "id" ) == '' ) {
				$( playerElement ).attr( "id", 'v' + ( rewriteElementCount++ ) );
			}
			
			// Add an overlay loader
			$( playerElement )
				.getAbsoluteOverlaySpinner()
				.attr('id', 'loadingSpinner_' + $( element ).attr('id') )
				.addClass( 'playerLoadingSpinner' );
			
			// Add core "skin/interface" loader			
			var skinString = $( playerElement ).attr( 'class' );
			if( ! skinString || $.inArray( skinName.toLowerCase(), mw.validSkins ) == -1 ){
				skinName = mw.getConfig( 'EmbedPlayer.DefaultSkin' );
			}
			skinName = skinName.toLowerCase();
			
			// Add the skin to the request
			var skinCaseName = skinName.charAt(0).toUpperCase() + skinName.substr(1);
			dependencySet.push( 'mw.PlayerSkin' + skinCaseName );
			
			// Allow other modules update the dependencies
			$j( mw ).trigger( 'EmbedPlayerUpdateDependencies',
					[ playerElement, dependencySet ] );
		});
		
		// Do the request and process the playerElements with updated dependency set
		mediaWiki.loader.using( dependencySet, function(){
			// EmbedPlayer should be ready: 
		});
	};

} )( mediaWiki, jQuery );
