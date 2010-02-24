/**
* skin js allows you to override contrlBuilder html/class output
*/

mw.addMessages( {
	"mwe-credit-title" : "Title: $1"
} );

var kskinConfig = {

	// The parent class for all kskin css: 
	playerClass: 'k-player',
	
	// Display time string length
	longTimeDisp: false,
	
	// Options are handled internally
	external_options: false,
	
	// Volume control layout is horizontal
	volume_layout: 'horizontal',
	
	// Skin "kskin" is specific for wikimedia we have an
	// api Title key so the "credits" menu item can be showed.
	supportedMenuItems: {
		'credits': true
	},
	
	// Extends base components with kskin specific options:
	components: {
		'playButtonLarge' : {
			'h' : 55
		},		
		'options': {
			'w':50,
			'o':function() {
				return $j( '<div />' )
					.attr( 'title',  gM( 'mwe-player_options' ) )
					.addClass( "ui-state-default ui-corner-bl rButton k-options" )
					.append( 
						$j( '<span />' )
						.text(  gM( 'mwe-menu_btn' ) )
					)
			}
		},
		'volumeControl':{
			'w':40
		},		
		// No kalturaAttribution component for kSkin ( its integrated into the credits screen ) 
		'kalturaAttribution' : false,
		
		// Time display: 
		'timeDisplay': {
			'w':45
		},	
		
		'optionsMenu': {
			'w' : 0,
			'o' : function( ctrlObj ) {
				var embedPlayer = ctrlObj.embedPlayer;				
				
				$menuOverlay = $j( '<div />')					
					.addClass( 'k-menu ui-widget-content' )
					.css( {
						'width' :  ctrlObj.getOverlayWidth(),
						'height' :  ctrlObj.getOverlayHeight()
					} );
					
				// Setup menu offset ( if player height <  getOverlayHeight )
				// This displays the menu outside of the player on small embeds	
				if ( embedPlayer.getPlayerHeight() <  ctrlObj.getOverlayHeight() ) {
				
					$menuOverlay.css( 'top', parseInt( embedPlayer.getPlayerHeight() + ctrlObj.getControlBarHeight() ) + 'px' );
					
					// Special common overflow hack for thumbnail display of player 								
					$j( embedPlayer ).parents( '.thumbinner' ).css( 'overflow', 'visible' );
				}
				$menuBar = $j( '<ul />' )
					.addClass( 'k-menu-bar' );
					
				// Output menu item containers: 
				for ( var menuItem in ctrlObj.supportedMenuItems ) {
					$menuBar.append( 
						$j( '<li />') 
						// Add the menu item class:
						.addClass( 'k-' + menuItem + '-btn' )
						.attr( 'rel', menuItem )
						.append(
							$j( '<a />' )
							.attr( {  
								'title' : gM( 'mwe-' + menuItem ),
								'href' : '#'
							})
						)							
					);
				}
				
				// Add the menuBar to the menuOverlay
				$menuOverlay.append( $menuBar );
				
				var $menuScreens = $j( '<div />' )
					.addClass( 'k-menu-screens' )
					.css( {
						'width' : (  ctrlObj.getOverlayWidth() - 75 ), 
						'height' : ( ctrlObj.getOverlayHeight() - ctrlObj.getControlBarHeight() )
					} )
				for ( var menuItem in ctrlObj.supportedMenuItems ) {
					$menuScreens.append(
						$j( '<div />' )
						.addClass( 'menu-screen menu-' + menuItem )
					);	
				}
				
				// Add the menuScreens to the menuOverlay
				$menuOverlay.append( $menuScreens );
				
				return $menuOverlay;	
				
			}
		}
	},
	
	/**
	* Get minimal width for interface overlay
	*/
	getOverlayWidth: function(){
		return ( this.embedPlayer.getPlayerWidth() < 200 )? 200 : this.embedPlayer.getPlayerWidth();
	},	
	
	/**
	* Get minimal height for interface overlay
	*/
	getOverlayHeight: function(){
		return ( this.embedPlayer.getPlayerHeight() < 160 )? 160 : this.embedPlayer.getPlayerHeight();
	},
	
	/**
	* Adds the skin Control Bindings
	*/
	addSkinControlBindings: function() {
		var embedPlayer = this.embedPlayer;
		var _this = this;		
		
		// Set up control bar pointer
		this.$playerTarget = embedPlayer.$interface;
		// Set the menu target: 
		_this.$kmenu = _this.$playerTarget.find( '.k-menu' );
		 		
		// Options menu display:			
		this.$playerTarget.find( '.k-options' )
		.unbind()
		.click( function() {
			if ( _this.$playerTarget.find( '.k-menu' ).length == 0 ) {
				
				// Stop the player if it does not support overlays:
				if ( !embedPlayer.supports['overlays'] ){
					embedPlayer.stop();
				}
				
				// Add the menu binding
				_this.addMeunBinding();
			}
			_this.$kmenu = _this.$playerTarget.find( '.k-menu' );
			if ( _this.$kmenu.is( ':visible' ) ) {
				_this.closeMenuOverlay( $j( this ) );
			} else {
				_this.showMenuOverlay( $j( this ) );
			}
		} );
		
	},
	
	/**
	* Close the menu overlay
	*/
	closeMenuOverlay: function( ) {
		var $optionsMenu = this.$playerTarget.find( '.k-options' );
		this.$kmenu.fadeOut( "fast", function() {
			$optionsMenu.find( 'span' )
				.text ( gM( 'mwe-menu_btn' ) );
		} );
		this.$playerTarget.find( '.play-btn-large' ).fadeIn( 'fast' );
	},
	
	/**
	* Show the menu overlay
	*/
	showMenuOverlay: function( $ktxt ) {
		var $optionsMenu = this.$playerTarget.find( '.k-options' );
		this.$kmenu.fadeIn( "fast", function() {
			$optionsMenu.find( 'span' )
				.text ( gM( 'mwe-close_btn' ) );
		} );
		this.$playerTarget.find( '.play-btn-large' ).fadeOut( 'fast' );
	},
	
	/**
	* Adds binding for the options menu
	*
	* @param {Object} $tp Target video container for 
	*/
	addMeunBinding: function() {
		var _this = this;
		var embedPlayer = this.embedPlayer;
		// Set local player target pointer:
		var $playerTarget = embedPlayer.$interface;
		
		// Check if k-menu already exists:
		if ( $playerTarget.find( '.k-menu' ).length != 0 )
			return false;
		
		// Add options menu to top of player target children: 
		$playerTarget.prepend(
			_this.getComponent( 'optionsMenu' )			
		);		
		
		// By default its hidden:
		$playerTarget.find( '.k-menu' ).hide();
			
		// Add menu-items bindings:  
		for ( var menuItem in _this.supportedMenuItems ) {
			$playerTarget.find( '.k-' + menuItem + '-btn' ).click( function( ) {
				
				// Grab the context from the "clicked" menu item
				var mk = $j( this ).attr( 'rel' );
				
				// hide all menu items	        	
				$targetItem = $playerTarget.find( '.menu-' + mk );	
				
				// call the function showMenuItem
				_this.showMenuItem(	mk );
				
				// Hide the others 
				$playerTarget.find( '.menu-screen' ).hide();
				
				// Show the target menu item:
				$targetItem.fadeIn( "fast" );
				 
				// Don't follow the # link
				return false;
			} );
		}
	},
	
	/**
	* Shows a selected menu_item
	*
	* NOTE: this should be merged with parent ctrlBuilder optionMenuItems 
	* binding mode
	* 
	* @param {String} menu_itme Menu item key to display
	*/
	showMenuItem:function( menuItem ) {	
		var embedPlayer = this.embedPlayer;
		//handle special k-skin specific display; 
		switch( menuItem ){
			case 'credits':
				this.showCredits();			
			break;
			case 'playerSelect':
				embedPlayer.$interface.find( '.menu-playerSelect').html(  
					this.getPlayerSelect()
				);		
			break; 
			case 'download' :
				embedPlayer.$interface.find( '.menu-download').text( 
					gM('mwe-loading_txt' ) 
				);
				// Call show download with the target to be populated
				this.showDownload(
					embedPlayer.$interface.find( '.menu-download') 
				);
			break;
			case 'share':
				embedPlayer.$interface.find( '.menu-share').html(
					this.getShare()
				);
			break;				
		}		
	},
	
	/**
	* Show the credit screen (presently specific to kaltura skin )
	*/  
	showCredits: function() {
		// Set up the shortcuts:	
		var embedPlayer = this.embedPlayer;
		var _this = this;	
		var $target = embedPlayer.$interface.find( '.menu-credits' );

		$target.empty().append( 
			$j('<h2 />')
			.text( gM( 'mwe-credits' ) ),
			$j('<div />')
			.addClass( "credits_box ui-corner-all" )
			.loadingSpinner()
		);

		if( mw.getConfig( 'kalturaAttribution' ) == true ){
			$target.append( 
				$j( '<div />' )
				.addClass( 'k-attribution' )
				.attr({
					'title': gM('mwe-kaltura-platform-title')
				})
				.click( function( ) {
					window.location = 'http://kaltura.com';
				})
			);
		}
		
		if( !embedPlayer.apiTitleKey ){
			$target.find('.credits_box').text(
				'Error: no title key to grab credits with' 
			);
			return ;
		}
		
		_this.getCredits();					
	},
	
	/**
	 * Issues a request to populate the credits box
	 */
	getCredits: function(){
		// Setup shortcuts:
		var embedPlayer = this.embedPlayer;
		var _this = this;	
		var $target = embedPlayer.$interface.find( '.menu-credits' );
		
		var api_url = mw.getApiProviderURL( embedPlayer.apiProvider );
		var fileTitle = 'File:' + embedPlayer.apiTitleKey.replace(/File:|Image:/, '');
		
		// Get the image info
		var request = { 
			'prop' : 'imageinfo',
			'titles' : fileTitle,
			'iiprop' : 'url'		
		};
		var articleUrl = '';
		mw.getJSON( api_url, request, function( data ){			
			if ( data.query.pages ) {
				for ( var i in data.query.pages ) {
					var imageProps = data.query.pages[i];
					// Check properites for "missing" 
					if( imageProps.imageinfo && imageProps.imageinfo[0] && imageProps.imageinfo[0].descriptionurl ){					
						// Found page 
						$target.find( '.credits_box' ).html(
							_this.doCreditLine( imageProps.imageinfo[0].descriptionurl )
						);				
					}else{
						// missing page  descriptionurl
						$target.find( '.credits_box' ).text(
							'Error: title key: ' + embedPlayer.apiTitleKey + ' not found' 
						);						
					}
				}
			}
		} );				
	},
	
	/**
	* Build a clip credit from the resource wikiText page
	*
	* NOTE: in the future this should parse the resource page template
	* 
	* @parm {String} wikiText Resource wiki text page contents
	*/
	doCreditLine: function ( articleUrl ){
		var embedPlayer = this.embedPlayer;
		
		// Get the title str 
		var titleStr = embedPlayer.apiTitleKey.replace(/_/g, ' ');		
		
		var imgWidth = ( this.getOverlayWidth() < 250 )? 45 : 90;
		
		// @@FIXME Do a quick check for source line:
		return $j( '<div/>' ).addClass( 'creditline' )
			.append(
				$j('<a/>').attr({
					'href' : articleUrl,
					'title' :  titleStr
				}).html( 
					$j('<img/>').attr( {
						'border': 0, 
						'src' : embedPlayer.thumbnail						
					} ).css( {
						'width' : imgWidth,
						'height': parseInt( imgWidth * ( embedPlayer.height / embedPlayer.width ) )
					} )
				)
			)
			.append(			
				$j('<span>').html( 
					gM( 'mwe-credit-title' ,  
						// We use a div container to easialy get at the built out link
						$j('<div>').html( 
							$j('<a/>').attr({
								'href' : articleUrl,
								'title' :  titleStr
							}).text( titleStr )
						).html()
					)
				)
			);
	}
};
