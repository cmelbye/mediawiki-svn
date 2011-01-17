/**
* SwarmTransport loader
*/

/**
* Default player module configuration
*/
( function( mw ) {

	mw.addResourcePaths( {
		"mw.P2PNextTransport" : "mw.P2PTransport.js",
		"mw.EmbedPlayerSwarmVlc" : "mw.EmbedPlayerSwarmVlc.js"
	});

	mw.setDefaultConfig({
	 	/**
	 	* If P2PNextTransport should be enabled as video transport mechanism
	 	* Enabling P2PNextTransport loads mw.P2PNextTransport, vlc and swarmvlc embed if in IE.
	 	*/
 		'P2PNextTransport.Enable': false,

 		/**
 		* If the swarm transport plugin should be recommended if the user does not have it installed.
 		*/
 		'P2PNextTransport.Recommend' : false,

 		/**
 		* Swarm Lookup service url
 		*/
 		'SwarmTransport.TorrentLookupUrl': 'http://url2torrent.net/get/',
 		
 		/**
 		* TSwift Swarm Lookup service url
 		*/
 		'TSwiftTransport.TorrentLookupUrl': 'http://url2torrent.p2p-next.org/get/'
	});

	// Add the mw.SwarmTransport to the embedPlayer loader:
	$j( mw ).bind( 'LoaderEmbedPlayerUpdateRequest', function( event, playerElement, classRequest ) {
		// If the swarm transport is enabled add mw.SwarmTransport to the request.
		if( mw.getConfig( 'mw.P2PNextTransport.Enable' ) ) {
			
			if( $j.inArray( 'mw.P2PNextTransport', classRequest ) == -1 ) {
				classRequest.push( [ 'mw.P2PNextTransport' ]);
				// if IE / ActiveX
				// Look for swarm player:
				if( mw.EmbedTypes && mw.EmbedTypes.testActiveX( 'P2PNext.SwarmPlayer' ) ){
					// Add vlc and swarmVlc to request
					classRequest.push( 'mw.EmbedPlayerVlc' );
					classRequest.push( 'mw.EmbedPlayerSwarmVlc' );
				}
			}
		}
	});

})( window.mw );
