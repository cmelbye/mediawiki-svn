<?php 

	// Register all the EmbedPlayer modules 
	return array(
			"mw.EmbedPlayer" => array( 
				'scripts' => array( 
					"mw.EmbedPlayer.js", 
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
	);

?>