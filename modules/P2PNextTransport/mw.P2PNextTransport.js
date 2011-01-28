/**
 * Add the messages text:
 */

mw.includeAllModuleMessages();

/**
* Define P2PNextTransport object:
*/
mw.P2PNextTransport = {
	// The transport objects
	transportObjects: null,
	
	addPlayerHooks: function(){
		var _this = this;
		
		// Add the P2PNextTransport player to available player types:
		$j( mw ).bind( 'EmbedPlayerManagerReady', function( event ) {	
			$.each( _this.getTransportObjects(), function(na, transportObject ){
				
				// Add the transportObject playerType
				mw.EmbedTypes.getMediaPlayers().defaultPlayers[ transportObject.mime ] = [ transportObject.playerLib ]; 
				
				// Build the Transport Player
				var transportPlayer = new mediaPlayer( transportObject.name + 'TransportPlayer', [ transportObject.mime ], transportObject.playerLib );
				
				// Add the P2PNextTransport "player"
				mw.EmbedTypes.getMediaPlayers().addPlayer( transportPlayer );
			});
		});

		
		// Bind some hooks to every player:
		$j( mw ).bind( 'newEmbedPlayerEvent', function( event, embedPlayer ) {
			// Setup the "embedCode" binding to swap in an updated url
			$j( embedPlayer ).bind( 'checkPlayerSourcesEvent', function( event, callback ) {
				// Add binding for source selection preference  
				$j( embedPlayer.mediaElement ).bind('AutoSelectSource', function( event, playableSources ){
					// Check for swift and swarm ( prefer swift )
					$j.each( playableSources, function(inx, source){						
						if( source.mimeType == 'video/swiftTransport'){
							embedPlayer.mediaElement.selectedSource = source;
							// break loop do no more checks
							return false;
						}
						if( source.mimeType == 'video/swarmTransport'){
							embedPlayer.mediaElement.selectedSource = source;
							// continue looking for swift
							return true;
						}
					});					
				});
				
				
				// Confirm P2PNextTransport add-on is available 

				if( _this.getTransportObjects().length ){
					// Add the swarm source
					_this.addTransportSources( embedPlayer, function( status ){						
						// Check if the status returned true 
						if( status ){
							// Update the source if paused
							if( embedPlayer.paused ) {
								
								// Re setup sources
								embedPlayer.setupSourcePlayer();
							}
						}
					});
				}
				// Don't block on swarm request, directly do the callback
				callback();
			} );

			// Check if we have a "recommend" binding and provide an xpi install link
			mw.log('P2PNextTransport::bind:addControlBindingsEvent');
			$j( embedPlayer ).bind( 'addControlBindingsEvent', function(){
				if( mw.getConfig( 'P2PNextTransport.Recommend' ) && _this.getPluginLibrary() ){
					embedPlayer.controlBuilder.doWarningBindinng(
						'recommendSwarmTransport',
						_this.getRecomendSwarmMessage()
					);
				}
			});

		} );

	},
	// Gets what transport objects that holds all the custom methods per the transport type
	//	returns [] empty array if no transport object is found. 
	getTransportObjects: function(){
		if( this.transportObjects  != null ){
			return this.transportObjects;
		}
		this.transportObjects = [];
		// Build the transport object: 
		var baseSwarmObj =  {
			'name'  : 'swarm',
			'mime': 'video/swarmTransport',
			'playerLib' : 'Native',
			'lookupUrl' : mw.getConfig( 'P2PNextTransport.SwarmLookupUrl' ),
			'protocol' : 'tribe://'
		};
		if( typeof window['swarmTransport'] != 'undefined' ){
			this.transportObjects.push( baseSwarmObj );
		}
		
		// Look for vlc based swarm player:
		try{
			if( mw.EmbedTypes.testActiveX( 'P2PNext.SwarmPlayer' ) ){
				this.transportObjects.push(  
					$j.extend( {}, baseSwarmObj, {						
						'playerLib': 'SwarmVlc'
					}) 
				);
			}
		} catch (e ){
			mw.log(" Error:: P2PNextTransport:testActiveX( 'P2PNext.SwarmPlayer' failed ");
		}
		
		// if tswift is supported it presently "overrides" 'swarm'
		if( typeof window['tswiftTransport'] != 'undefined' ){
			this.transportObjects.push(
				$j.extend( {}, baseSwarmObj, {
					'name' : 'swift',
					'mime': 'video/swiftTransport',
					'lookupUrl' : mw.getConfig( 'P2PNextTransport.SwiftLookupUrl' ),
					'protocol' : 'tswift://'
				})
			);
		}	
		return this.transportObjects;
	},
	
	addTransportSources: function( embedPlayer, callback ) {
		var _this = this;

		// xxx todo: also grab the WebM source if supported.
		var source = embedPlayer.mediaElement.getSources( 'video/ogg' )[0];
		if( ! source ){
			mw.log("Warning: addSwarmSource: could not find video/ogg source to generate torrent from");
			callback();
			return ;
		}
		$.each( this.getTransportObjects(), function( inx, transportObject ){
			// Setup function to run in context based on callback result
			$j.getJSON(
				transportObject.lookupUrl + '?jsonp=?',
				{
					'url' : mw.absoluteUrl( source.getSrc() )
				},
				function( data ){									
					// Check if the torrent is ready:
					if( !data || !data.torrent || !data.swift  ) {
						mw.log( "P2PNext: ( " + transportObject.lookupUrl + " ) Not ready ");
						callback( false );
						return ;
					}					
					var transportSrc = '';					
					// Set the source via transportObject type
					if( transportObject.name == 'swift'){
						transportSrc = data.swift;
						
					} else {
						transportSrc = transportObject.protocol + data.torrent;
					}
					embedPlayer.mediaElement.tryAddSource(
						$j('<source />')
						.attr( {
							'type' : transportObject.mime,
							'title': gM('mwe-' + transportObject.name + 'transport-stream-ogg' ),
							'src': transportSrc,
							// Set default if "swift"
							'default' :true 
						} )
						.get( 0 )
					);
					callback( true );
				}				
			);
		})
	},

	getRecomendSwarmMessage: function(){
		//xxx an xpi link would be nice ( for now just link out to the web site )
		return gM( 'mwe-swarmtransport-recommend', 'http://wikipedia.p2p-next.org/download/' );
	}

};

// Add player bindings for swarm Transport
mw.P2PNextTransport.addPlayerHooks();