// Scope everything in "mw" ( keeps the global namespace clean ) 
( function( mw, $ ) {
	
	mw.addResourcePaths({
		"mw.AdTimeline" : "mw.AdTimeline.js",
		"mw.AdLoader" : "mw.AdLoader.js",
		"mw.VastAdParser" : "mw.VastAdParser.js"
	});
	
	mw.addModuleLoader('AdSupport', function(){
		return [ 'mw.MobileAdTimeline', 'mw.AdLoader', 'mw.VastAdParser' ];
	});
	
	mw.setDefaultConfig({
		'AdSupport.XmlProxyUrl' : mw.getMwEmbedPath() + 'modules/AdSupport/simplePhpXMLProxy.php'
	});
	
	// Ads have to communicate with parent iframe to support companion ads.
	// ( we have to add them for all players since checkUiConf is done on the other side of the
	// iframe proxy )
	$( mw ).bind( 'AddIframePlayerBindings', function( event, exportedBindings){
		// Add the updateCompanionTarget binding to bridge iframe
		exportedBindings.push( 'updateCompanionTarget' );
	});
	
	// Add the updateCompanion binding to new iframeEmbedPlayers
	$( mw ).bind( 'newIframePlayerClientSide', function( event, playerProxy ){
		$( playerProxy ).bind( 'updateCompanionTarget', function( event, companionObject) {
			// NOTE: security wise we should try and "scrub" the html for script tags
			$('#' + companionObject.elementid ).html( 
					companionObject.html
			)
		});
	});

} )( window.mediaWiki, window.jQuery );