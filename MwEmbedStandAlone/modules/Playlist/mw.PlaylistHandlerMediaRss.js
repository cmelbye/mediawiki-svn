mw.PlaylistHandlerMediaRss = function( Playlist ){
	return this.init( Playlist );
}

mw.PlaylistHandlerMediaRss.prototype = {
	// Set the media rss namespace
	mediaNS: 'http://search.yahoo.com/mrss/',

	// If playback should continue to the next clip on clip complete
	autoContinue: true,

	init: function ( Playlist ){
		this.playlist = Playlist;
	},

	/**
	 * load the playlist source file with a callback
	 */
	loadPlaylist: function( callback ){
		var _this = this;
		// check if we already have the $rss loaded
		if( this.$rss ){
			callback( this.$rss );
			return ;
		}

		// Show an error if a cross domain request:
		if( ! mw.isLocalDomain( this.getSrc() ) ) {
			mw.log("Error: trying to get cross domain playlist source: " + this.getSrc() );
		}

		// Note this only works with local sources
		$j.get( mw.absoluteUrl( this.getSrc() ), function( data ){
			_this.$rss = $( data );
			callback( _this.$rss );
		});
	},
	hasMultiplePlaylists: function(){
		return false;
	},
	getSrc: function(){
		return this.playlist.src;
	},
	// Get clip count
	getClipCount: function(){
		if( !this.$rss ){
			mw.log("Error no rss to count items" );
		}
		return this.$rss.find('item').length;
	},

	getClipSources: function( clipIndex, callback ){
		var _this = this;
		var $item = $( this.$rss.find('item')[ clipIndex ] );
		var clipSources = [];
		$j.each( $item.get(0).getElementsByTagNameNS( _this.mediaNS, 'content' ), function( inx, mediaContent){
			if( $( mediaContent ).get(0).nodeName == 'media:content' ){
				clipSource = {}
				if( $( mediaContent ).attr('url' ) ){
					clipSource.src = $( mediaContent ).attr('url' );
				}
				if( $( mediaContent ).attr('type' ) ){
					clipSource.type = $( mediaContent ).attr('type' );
				}
				if( $( mediaContent ).attr( 'duration' ) ) {
					clipSource.durationHint = $( mediaContent ).attr('duration' );
				}
				clipSources.push( clipSource );
			}
		});
		callback( clipSources );
	},

	applyCustomClipData: function( embedPlayer, clipIndex ){
		return {};
	},

	getClipList: function(){
		return this.$rss.find('item');
	},
	/**
	* Get an items poster image ( return missing thumb src if not found )
	*/
	getClipPoster: function ( clipIndex ){
		var $item = this.$rss.find('item').eq( clipIndex );
		var mediaThumb = $item.get(0).getElementsByTagNameNS( this.mediaNS, 'thumbnail' );
		mw.log( 'mw.PlaylistMediaRss::getClipPoster: ' + $( mediaThumb ).attr('url' ) );
		if( mediaThumb && $( mediaThumb ).attr('url' ) ){
			return $( mediaThumb ).attr('url' );
		}

		// return missing thumb url
		return mw.getConfig( 'imagesPath' ) + 'vid_default_thumb.jpg';
	},
	/**
	* Get an item title from the $rss source
	*/
	getClipTitle: function( clipIndex ){
		var $item = this.$rss.find('item').eq( clipIndex ) ;
		var mediaTitle = $item.get(0).getElementsByTagNameNS( this.mediaNS, 'title' );
		if( mediaTitle ){
			return $( mediaTitle ).text();
		}
		mw.log("Error could not find title for clip: " + clipIndex );
		return gM('mwe-mediarss-untitled');
	},

	getClipDuration: function ( clipIndex ) {
		// return the first found media duration
		var $item = this.$rss.find('item').eq( clipIndex ) ;
		var itemDuration = 0;
		$( $item.get(0).getElementsByTagNameNS( this.mediaNS, 'content' ) ).each( function( inx, mediaContent ){
			if( $( mediaContent ).attr( 'duration' ) ) {
				itemDuration = $( mediaContent ).attr( 'duration' );
				// end for loop
				return false;
			}
		});
		return mw.seconds2npt( itemDuration )
	}
}