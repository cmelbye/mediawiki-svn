<?php 

	// Register all the EmbedPlayer modules 
	return array(
			"mw.EmbedPlayer" => array( 
				'scripts' => array( 
					"resources/mw.EmbedPlayer.js", 
					"skins/mw.PlayerControlBuilder.js",
				),
				'dependencies' => array(
					// jQuery dependencies: 
					'jquery.hoverIntent',
					'jquery.cookie',
					'jquery.ui.mouse',
					'$.fn.menu',
					'mw.style.jquerymenu',
					'$.ui.slider'					
				),
				'styles' => "skins/mw.style.EmbedPlayer.css",
				'messageFile' => 'EmbedPlayer.i18n.php',			
			),
				
			"mw.EmbedPlayerKplayer"	=> array( 'scripts'=> "resources/mw.EmbedPlayerKplayer.js" ),
			"mw.EmbedPlayerGeneric"	=> array( 'scripts'=> "resources/mw.EmbedPlayerGeneric.js" ),
			"mw.EmbedPlayerJava" => array( 'scripts'=> "resources/mw.EmbedPlayerJava.js"),
			"mw.EmbedPlayerNative"	=> array( 'scripts'=> "resources/mw.EmbedPlayerNative.js" ),
			
			"mw.EmbedPlayerVlc" => array( 'scripts'=> "resources/mw.EmbedPlayerVlc.js" ),
			
			"mw.IFramePlayerApiServer" => array( 'scripts' => "resources/mw.IFramePlayerApiServer.js" ),
			"mw.IFramePlayerApiClient" => array( 'scripts' => "resources/mw.IFramePlayerApiClient.js" ),
		
			"mw.PlayerSkinKskin" => array( 	'scripts' => "skins/kskin/mw.PlayerSkinKskin.js",
											'styles' => "skins/kskin/mw.style.PlayerSkinKskin.css"),
			
			"mw.PlayerSkinMvpcf" => array( 	'scripts'=> "skins/mvpcf/mw.PlayerSkinMvpcf.js", 
											'styles'=> "skins/mvpcf/mw.style.PlayerSkinMvpcf.css"),
	);

?>