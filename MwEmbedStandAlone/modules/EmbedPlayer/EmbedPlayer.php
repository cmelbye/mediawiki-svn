<?php 

	// Register all the EmbedPlayer modules in the $wgResourceModules global array
	return array( 
		'resources' => array(
			"mw.EmbedPlayer" => array( 
				'scripts' => array( "mw.EmbedPlayer.js", "skins/mw.PlayerControlBuilder.js" ),
				'styles' => "skins/mw.style.EmbedPlayer.css",
				'messageFile' => 'EmbedPlayer.i18n.php'
			),
				
			"mw.EmbedPlayerKplayer"	=> array( 'scripts'=> "mw.EmbedPlayerKplayer.js" ),
			"mw.EmbedPlayerGeneric"	=> array( 'scripts'=> "mw.EmbedPlayerGeneric.js" ),
			"mw.EmbedPlayerJava" => array( 'scripts'=> "mw.EmbedPlayerJava.js"),
			"mw.EmbedPlayerNative"	=> array( 'scripts'=> "mw.EmbedPlayerNative.js" ),
			
			"mw.EmbedPlayerVlc" => array( 'scripts'=> "mw.EmbedPlayerVlc.js" ),
			
			"mw.IFramePlayerApiServer" => array( 'scripts' => "mw.IFramePlayerApiServer.js" ),
			"mw.IFramePlayerApiClient" => array( 'scripts' => "mw.IFramePlayerApiClient.js" ),
		
			"mw.PlayerSkinKskin" => array( 	'scripts' => "skins/kskin/mw.PlayerSkinKskin.js",
											'styles' => "skins/kskin/mw.style.PlayerSkinKskin.css"),
			
			"mw.PlayerSkinMvpcf" => array( 	'scripts'=> "skins/mvpcf/mw.PlayerSkinMvpcf.js", 
											'styles'=> "skins/mvpcf/mw.style.PlayerSkinMvpcf.css"),
		),
		// Set any default configuration
		'config' => array(
			// If the player controls should be overlaid on top of the video ( if supported by playback method)
			// can be set to false per embed player via overlayControls attribute
			'EmbedPlayer.OverlayControls' => true,
	
			// If the iPad should use html controls ( can't use fullscreen or control volume, 
			// but lets you support overlays ie html controls ads etc. )
			'EmbedPlayer.EnableIpadHTMLControls'=> false, 
			
			'EmbedPlayer.LibraryPage'=> 'http://www.kaltura.org/project/HTML5_Video_Media_JavaScript_Library',
	
			// A default apiProvider ( ie where to lookup subtitles, video properties etc )
			// NOTE: Each player instance can also specify a specific provider
			"EmbedPlayer.ApiProvider" => "local",
	
			// What tags will be re-written to video player by default
			// Set to empty string or null to avoid automatic video tag rewrites to embedPlayer
			"EmbedPlayer.RewriteTags" => "video,audio,playlist",
	
			// Default video size ( if no size provided )
			"EmbedPlayer.DefaultSize" => "400x300",
	
			// If the video player should attribute kaltura
			"EmbedPlayer.KalturaAttribution" => true,
	
			// The attribution button
			'EmbedPlayer.AttributionButton' => array(
				'title' => 'Kaltura html5 video library',
			 	'href' => 'http://www.kaltura.org/project/HTML5_Video_Media_JavaScript_Library',
				// Style icon to be applied
				'class' => 'kaltura-icon',
				// An icon image url ( should be a 12x12 image or data url )
				'iconurl' => false
			),
			
			// If the player should wait for metadata like video size and duration, before trying to draw
			// the player interface. 
			'EmbedPlayer.WaitForMeta' => true,
			
			// Set the browser player warning flag displays warning for non optimal playback
			"EmbedPlayer.ShowNativeWarning" => true,
	
			// If fullscreen is global enabled.
			"EmbedPlayer.EnableFullscreen" => true,
	
			// If mwEmbed should use the Native player controls
			// this will prevent video tag rewriting and skinning
			// useful for devices such as iPad / iPod that
			// don't fully support DOM overlays or don't expose full-screen
			// functionality to javascript
			"EmbedPlayer.NativeControls" => false,
	
			// If mwEmbed should use native controls on mobile safari
			"EmbedPlayer.NativeControlsMobileSafari" => true,
	
	
			// The z-index given to the player interface during full screen ( high z-index )
			"EmbedPlayer.fullScreenZIndex" => 999998,
	
			// The default share embed mode ( can be "object" or "videojs" )
			//
			// "iframe" will provide a <iframe tag pointing to mwEmbedFrame.php
			// 		Object embedding should be much more compatible with sites that
			//		let users embed flash applets
			// "videojs" will include the source javascript and video tag to
			//	 	rewrite the player on the remote page DOM
			//		Video tag embedding is much more mash-up friendly but exposes
			//		the remote site to the mwEmbed javascript and can be a xss issue.
			"EmbedPlayer.ShareEmbedMode" => 'iframe',
	
			// Default player skin name
			"EmbedPlayer.SkinName" => "mvpcf",
	
			// Number of milliseconds between interface updates
			'EmbedPlayer.MonitorRate' => 250,
	
			// If the embedPlayer should accept arguments passed in from iframe postMessages calls
			'EmbedPlayer.EnalbeIFramePlayerServer' => false,
			
			// If embedPlayer should support server side temporal urls for seeking options are 
			// flash|always|none default is support for flash only.
			'EmbedPlayer.EnableURLTimeEncoding' => 'flash',
			
			// The domains which can read and send events to the video player
			'EmbedPLayer.IFramePlayer.DomainWhiteList' => '*',
			
			// If the iframe should send and receive javascript events across domains via postMessage 
			'EmbedPlayer.EnableIframeApi' => true,
			
			/**
			 * The base source attribute checks also see:
			 * http://dev.w3.org/html5/spec/Overview.html#the-source-element
			 */
			'EmbedPlayer.SourceAttributes' => array(
				// source id
				'id',
			
				// media url
				'src',
			
				// Title string for the source asset
				'title',
			
				// boolean if we support temporal url requests on the source media
				'URLTimeEncoding',
			
				// Media has a startOffset ( used for plugins that
				// display ogg page time rather than presentation time
				'startOffset',
			
				// A hint to the duration of the media file so that duration
				// can be displayed in the player without loading the media file
				'durationHint',
			
				// Media start time
				'start',
			
				// Media end time
				'end',
			
				// If the source is the default source
				'default',
				
				// Title of the source
				'title',
				
				// titleKey ( used for api lookups TODO move into mediaWiki specific support
				'titleKey'
			),
			/** 
			 * Merge in the default video attributes supported by embedPlayer:
			 */
			'EmbedPlayer.Attributes' => array(
				/*
				 * Base html element attributes:
				 */
		
				// id: Auto-populated if unset
				"id" => null,
		
				// Width: alternate to "style" to set player width
				"width" => null,
		
				// Height: alternative to "style" to set player height
				"height" => null,
		
				/*
				 * Base html5 video element attributes / states also see:
				 * http://www.whatwg.org/specs/web-apps/current-work/multipage/video.html
				 */
		
				// Media src URI, can be relative or absolute URI
				"src" => null,
		
				// Poster attribute for displaying a place holder image before loading
				// or playing the video
				"poster" => null,
		
				// Autoplay if the media should start playing
				"autoplay" => false,
		
				// Loop attribute if the media should repeat on complete
				"loop" => false,
		
				// If the player controls should be displayed
				"controls" => true,
		
				// Video starts "paused"
				"paused" => true,
		
				// ReadyState an attribute informs clients of video loading state:
				// see: http://www.whatwg.org/specs/web-apps/current-work/#readystate
				"readyState" => 0,
		
				// Loading state of the video element
				"networkState" => 0,
		
				// Current playback position
				"currentTime" => 0,
		
				// Previous player set time
				// Lets javascript use $j('#videoId').get(0).currentTime = newTime;
				"previousTime" => 0,
		
				// Previous player set volume
				// Lets javascript use $j('#videoId').get(0).volume = newVolume;
				"previousVolume" => 1,
		
				// Initial player volume:
				"volume" => 0.75,
		
				// Caches the volume before a mute toggle
				"preMuteVolume" => 0.75,
		
				// Media duration: Value is populated via
				// custom durationHint attribute or via the media file once its played
				"duration" => null,
		
				// Mute state
				"muted" => false,
		
				/**
				 * Custom attributes for embedPlayer player: (not part of the html5
				 * video spec)
				 */
		
				// Default video aspect ratio
				'videoAspect' => '4:3',
		
				// Start time of the clip
				"start" => 0,
		
				// End time of the clip
				"end" => null,
		
				// A apiTitleKey for looking up subtitles, credits and related videos
				// TODO move to data-mwTitleKey and into mediaWikiSupport module
				"apiTitleKey" => null,
		
				// The apiProvider where to lookup the title key
				// TODO move to data-mwApiProvider and into mediaWikiSupport module
				"apiProvider" => null,
		
				// If the player controls should be overlaid
				// ( Global default via config EmbedPlayer.OverlayControls in module
				// loader.js)
				"overlaycontrols" => true,
		
				// Attribute to use 'native' controls
				"usenativecontrols" => false,
		
				// If the player should include an attribution button:
				'attributionbutton' => true,
		
				// ROE url ( for xml based metadata )
				// also see: http://wiki.xiph.org/ROE
				"roe" => null,
		
				// If serving an ogg_chop segment use this to offset the presentation
				// time
				// ( for some plugins that use ogg page time rather than presentation
				// time )
				"startOffset" => 0,
		
				// Source page for media asset ( used for linkbacks in remote embedding
				// )
				"linkback" => null,
		
				// If the download link should be shown
				"download_link" => true,
		
				// Content type of the media
				"type" => null
			),
		),
	);


?>