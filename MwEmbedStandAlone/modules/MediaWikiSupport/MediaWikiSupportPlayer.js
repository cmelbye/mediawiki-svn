			



		// NOTE: Should could be moved to mediaWiki Api support module
		// only load from api if sources are empty:
		if ( _this.apiTitleKey && this.mediaElement.sources.length == 0) {
			// Load media from external data
			mw.log( 'EmbedPlayer::checkPlayerSources: loading apiTitleKey:' + _this.apiTitleKey );
			_this.loadSourceFromApi( function(){
				finishCheckPlayerSources();
			} );
			return ;