	

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
	
	$( mw ).bind( 'EmbedPlayerNewPlayer', function( embedPlayer ){
		mw.addMwPlayerHooks( embedPlayer );
	});
	
	mw.addMwPlayerHooks = function( embedPlayer ){
		// extend the embedPlayer with MediaWikiSupportPlayer
		embedPlayer = $.extend( embedPlayer, MediaWikiSupportPlayer);
		
		// Add LoadSources binding:
		$( embedPlayer ).bind('CheckPlayerSourcesEvent', function(event, callback){
			embedPlayer.loadSources( embedPlayer, callback );
		});
		
		
		
		
		// NOTE: Should could be moved to mediaWiki Api support module
		// only load from api if sources are empty:
		if ( _this.apiTitleKey && this.mediaElement.sources.length == 0 ) {
			// Load media from external data
			mw.log( 'EmbedPlayer::checkPlayerSources: loading apiTitleKey:' + _this.apiTitleKey );
			_this.loadSourceFromApi( function(){
				finishCheckPlayerSources();
			} );
			return ;
		}
	};	
	
	// MediaWikiSupportPlayer methods:
	// We don't directly extend the player because we use the bind trigger model, 
	// so that multiple interfaces can extend the player where needed. 
	MediaWikiSupportPlayer = {	
		/**
		 * @param {Object} embedPlayer the player load sources for
		 * @param {function} the function called once sources are loaded 
		 */
		'loadSources' : function( embedPlayer, callback){
			var _this = this;
			
			if( ! $( embedPlayer).attr( 'data-mwtitle') ){
				mw.log( 'Error MediaWikiSupportPlayer:: no mwtitle');
				return false;
			} else {
				var apiTitleKey = $( embedPlayer).attr( 'data-mwtitle');
			}

			// Set local apiProvider via config if not defined
			if( ! $( embedPlayer ).attr('data-mwprovider') ){
				var apiProvider = mw.getConfig( 'EmbedPlayer.ApiProvider' );
			} else {
				var apiProvider = $( embedPlayer ).attr('data-mwprovider');
			}

			// Setup the request
			var request = {
				'prop': 'imageinfo',
				// In case the user added File: or Image: to the apiKey:
				'titles': 'File:' + unescape( apiTitleKey ).replace( /^(File:|Image:)/ , '' ),
				'iiprop': 'url|size|dimensions|metadata',
				'iiurlwidth': _this.width,
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
				}	else {
					callback( false );
					return ;
				}
				// Make sure we have imageinfo:
				if( ! page.imageinfo || !page.imageinfo[0] ){
					callback( false );
					return ;
				}
				var imageinfo = page.imageinfo[0];

				// Set the poster
				_this.poster = imageinfo.thumburl;

				// Add the media src
				_this.mediaElement.tryAddSource(
					$j('<source />')
					.attr( 'src', imageinfo.url )
					.get( 0 )
				);

				// Set the duration
				if( imageinfo.metadata[2]['name'] == 'length' ) {
					_this.duration = imageinfo.metadata[2]['value'];
				}

				// Set the width height
				// Make sure we have an accurate aspect ratio
				if( imageinfo.height != 0 && imageinfo.width != 0 ) {
					_this.height = parseInt( _this.width * ( imageinfo.height / imageinfo.width ) );
				}

				// Update the css for the player interface
				$j( _this ).css( 'height', _this.height);

				callback();
			});
		}
	};
			
} )( window.mediaWiki, window.jQuery );