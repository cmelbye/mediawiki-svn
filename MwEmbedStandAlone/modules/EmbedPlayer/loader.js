/**
* EmbedPlayer loader
*/
/**
* Default player module configuration
*/
( function( mw, $ ) {
	/**
	* Check the current DOM for any tags in "EmbedPlayer.RewriteTags"
	*/
	mw.documentHasPlayerTags = function() {
		var rewriteTags = mw.getConfig( 'EmbedPlayer.RewriteTags' );
		if( $( rewriteTags ).length != 0 ) {
			return true;
		}
		return false;
	};

	/**
	* Add a DOM ready check for player tags
	*
	* We use mw.addSetupHook instead of mw.ready so that
	* mwEmbed player is setup before any other mw.ready calls
	*/
	$( mw ).bind( 'SetupInterface', function( event, callback ){
		
		mw.log( 'Loader::EmbedPlayer:rewritePagePlayerTags:' + mw.documentHasPlayerTags() );
		
		// Allow modules to do tag rewrites as well: 
		var doModuleTagRewrites = function(){			
			$(mw).triggerQueueCallback( 'LoadeRewritePlayerTags', callback );
		}
		
		if( mw.documentHasPlayerTags() ) {
			var rewriteElementCount = 0;

			// Set each player to loading ( as early on as possible )
			$( mw.getConfig( 'EmbedPlayer.RewriteTags' ) ).each( function( index, element ){

				// Assign an the element an ID ( if its missing one )
				if ( $( element ).attr( "id" ) == '' ) {
					$( element ).attr( "id", 'v' + ( rewriteElementCount++ ) );
				}
				// Add an absolute positioned loader
				$( element )
					.getAbsoluteOverlaySpinner()
					.attr('id', 'loadingSpinner_' + $( element ).attr('id') )
					.addClass( 'playerLoadingSpinner' );

			});
			// Load the embedPlayer module ( then run queued hooks )
			mw.load( 'EmbedPlayer', function ( ) {
				// Rewrite the EmbedPlayer.RewriteTags with the
				$( mw.getConfig( 'EmbedPlayer.RewriteTags' ) ).embedPlayer( doModuleTagRewrites );
			})
		} else {
			doModuleTagRewrites();
		}
	});
	
	/**
	* Add the module loader function:
	*/
	mw.addModuleLoader( 'EmbedPlayer', function() {
		var _this = this;
		// Hide videonojs class
		$( '.videonojs' ).hide();

		// Set up the embed video player class request: (include the skin js as well)
		var dependencyRequest = [
			[
				'mw.EmbedPlayer'
			],
			[
			 	'mw.PlayerControlBuilder',
				'$.fn.hoverIntent',
				'mw.style.EmbedPlayer',
				'$.cookie',
				// Add JSON lib if browsers does not define "JSON" natively
				'JSON',
				'$.ui',
				'$.widget'
			],
			[
				'$.ui.mouse',
				'$.fn.menu',
				'mw.style.jquerymenu',
				'$.ui.slider'
			]
		];

		// Pass every tag being rewritten through the update request function
		$( mw.getConfig( 'EmbedPlayer.RewriteTags' ) ).each( function(inx, playerElement) {
			mw.embedPlayerUpdateLibraryRequest( playerElement, dependencyRequest[ 1 ] )
		} );

		// Add PNG fix code needed:
		if ( $.browser.msie && $.browser.version < 7 ) {
			dependencyRequest[0].push( '$.fn.pngFix' );
		}

		// Do short detection, to avoid extra player library request in ~most~ cases.
		//( If browser is firefox include native, if browser is IE include java )
		if( $.browser.msie ) {
			dependencyRequest[0].push( 'mw.EmbedPlayerJava' );
		}

		// Safari gets slower load since we have to detect ogg support
		if( !!document.createElement('video').canPlayType && !$.browser.safari ) {
			dependencyRequest[0].push( 'mw.EmbedPlayerNative' )
		}
		// Check if the iFrame player api is enabled and we have a parent iframe url: 
		if ( mw.getConfig('EmbedPlayer.EnableIframeApi') 
				&& 
			mw.getConfig( 'EmbedPlayer.IframeParentUrl' ) 
		){
			dependencyRequest[0].push('mw.EmbedPlayerNative');
			dependencyRequest[0].push('$.postMessage');
			dependencyRequest[0].push('mw.IFramePlayerApiServer');
		}

		// Return the set of libs to be loaded
		return dependencyRequest;
	} );

	/**
	 * Takes a embed player element and updates a request object with any
	 * dependent libraries per that tags attributes.
	 *
	 * For example a player skin class name could result in adding some
	 *  css and javascirpt to the player library request.
	 *
	 * @param {Object} playerElement The tag to check for library dependent request classes.
	 * @param {Array} dependencyRequest The library request array
	 */
	mw.embedPlayerUpdateLibraryRequest = function(playerElement, dependencyRequest ){
		var skinName = $( playerElement ).attr( 'class' );
		// Set playerClassName to default if unset or not a valid skin
		if( ! skinName || $.inArray( skinName.toLowerCase(), mw.validSkins ) == -1 ){
			skinName = mw.getConfig( 'EmbedPlayer.SkinName' );
		}
		skinName = skinName.toLowerCase();
		// Add the skin to the request
		var skinCaseName = skinName.charAt(0).toUpperCase() + skinName.substr(1);
		// The skin js:
		if( $.inArray( 'mw.PlayerSkin' + skinCaseName, dependencyRequest ) == -1 ){
			dependencyRequest.push( 'mw.PlayerSkin' + skinCaseName );
		}
		// The skin css
		if( $.inArray( 'mw.style.PlayerSkin' + skinCaseName, dependencyRequest ) == -1 ){
			dependencyRequest.push( 'mw.style.PlayerSkin' + skinCaseName );
		}

		// Allow extension to extend the request.
		$( mw ).trigger( 'LoaderEmbedPlayerUpdateRequest',
				[ playerElement, dependencyRequest ] );
	}

} )( mediaWiki, jQuery );
