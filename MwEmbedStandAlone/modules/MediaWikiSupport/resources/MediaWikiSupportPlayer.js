
( function( mw, $ ) {
	
	/** 
	 * Merge in the default video attributes supported by embedPlayer:
	 */
	mw.mergeConfig( 'EmbedPlayer.Attributes', {
		// A apiTitleKey for looking up subtitles, credits and related videos
		"data-mwtitle" : null,
	
		// The apiProvider where to lookup the title key
		"data-mwprovider" : null
	});
	
	// Add mediaWiki player support to target embedPlayer 
	$( mw ).bind( 'EmbedPlayerNewPlayer', function( embedPlayer ){
		mw.addMediaWikiPlayerSupport( embedPlayer );
	});
	
	/**
	 * Master function to add mediaWiki support to embedPlayer 
	 */
	mw.addMediaWikiPlayerSupport = function( embedPlayer ){
		
		/**
		 * Loads mediaWiki sources for a given embedPlayer
		 * @param {function} callback Function called once player sources have been added 
		 */
		function loadPlayerSources( callback ){
			if( ! $( embedPlayer).attr( 'data-mwtitle') ){
				mw.log( 'Error MediaWikiSupportPlayer:: no mwtitle');
				callback( false );
				return false;
			} else {
				var apiTitleKey = $( embedPlayer).attr( 'data-mwtitle');
			}
			// Set local apiProvider via config if not defined
			var apiProvider = $( embedPlayer ).attr('data-mwprovider');
			if( !apiProvider ){
				apiProvider = mw.getConfig( 'EmbedPlayer.ApiProvider' );
			}

			// Setup the request
			var request = {
				'prop': 'imageinfo',
				// In case the user added File: or Image: to the apiKey:
				'titles': 'File:' + unescape( apiTitleKey ).replace( /^(File:|Image:)/ , '' ),
				'iiprop': 'url|size|dimensions|metadata',
				'iiurlwidth': embedPlayer.getWidth(),
				'redirects' : true // automatically resolve redirects
			};

			// Run the request:
			mw.getJSON( mw.getApiProviderURL( apiProvider ), request, function( data ){
				if ( data.query.pages ) {
					for ( var i in data.query.pages ) {
						if( i == '-1' ) {
							callback( false );
							return ;
						}
						var page = data.query.pages[i];
					}
				} else {
					callback( false );
					return ;
				}
				// Make sure we have imageinfo:
				if( ! page.imageinfo || !page.imageinfo[0] ){
					callback( false );
					return ;
				}
				var imageinfo = page.imageinfo[0];
				
				// TODO these should call public methods rather than update internals: 
				
				// Update the poster
				embedPlayer.poster = imageinfo.thumburl;

				// Add the media src
				embedPlayer.mediaElement.tryAddSource(
					$('<source />')
					.attr( 'src', imageinfo.url )
					.get( 0 )
				);

				// Set the duration
				if( imageinfo.metadata[2]['name'] == 'length' ) {
					embedPlayer.duration = imageinfo.metadata[2]['value'];
				}

				// Set the width height
				// Make sure we have an accurate aspect ratio
				if( imageinfo.height != 0 && imageinfo.width != 0 ) {
					embedPlayer.height = parseInt( embedPlayer.width * ( imageinfo.height / imageinfo.width ) );
				}

				// Update the css for the player interface
				$( embedPlayer ).css( 'height', _this.height);

				callback();
			});
		}
		
		/**
		 * Adds embedPlayer Bindings
		 */
		$( embedPlayer ).bind('CheckPlayerSourcesEvent', function(event, callback){
			loadPlayerSources( callback );
		});
	};
		
} )( window.mediaWiki, window.jQuery );