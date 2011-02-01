/**
 * mwEmbed jQuery utility functions that are too small for their own file
 */ 

( function( $ ) {
		
	/**
	 * Set a given selector html to the loading spinner:
	 */
	$.fn.loadingSpinner = function( ) {
		if ( this ) {
			$( this ).html(
				$( '<div />' )
					.addClass( "loadingSpinner" )
			);
		}
		return this;
	};
	/**
	 * Add an absolute overlay spinner useful for cases where the
	 * element does not display child elements, ( images, video )
	 */
	$.fn.getAbsoluteOverlaySpinner = function(){
		var pos = $( this ).offset();				
		var posLeft = (  $( this ).width() ) ? 
			parseInt( pos.left + ( .5 * $( this ).width() ) -16 ) : 
			pos.left + 30;
			
		var posTop = (  $( this ).height() ) ? 
			parseInt( pos.top + ( .5 * $( this ).height() ) -16 ) : 
			pos.top + 30;
		
		var $spinner = $('<div />')
			.loadingSpinner()				
			.css({
				'width' : 32,
				'height' : 32,
				'position': 'absolute',
				'top' : posTop + 'px',
				'left' : posLeft + 'px'
			});
		$('body').append( $spinner	);
		return $spinner;
	};
			
	
	/**
	 * Shortcut to a themed button Should be depreciated for $.button
	 * bellow
	 */
	$.btnHtml = function( msg, styleClass, iconId, opt ) {
		if ( !opt )
			opt = { };
		var href = ( opt.href ) ? opt.href : '#';
		var target_attr = ( opt.target ) ? ' target="' + opt.target + '" ' : '';
		var style_attr = ( opt.style ) ? ' style="' + opt.style + '" ' : '';
		return '<a href="' + href + '" ' + target_attr + style_attr +
			' class="ui-state-default ui-corner-all ui-icon_link ' +
			styleClass + '"><span class="ui-icon ui-icon-' + iconId + '" ></span>' +
			'<span class="btnText">' + msg + '</span></a>';
	};
	
	// Shortcut to generate a jQuery button 
	var mw_default_button_options = {
		// The class name for the button link
		'class' : '',
		
		// The style properties for the button link
		'style' : { },
		
		// The text of the button link
		'text' : '',
		
		// The icon id that precedes the button link:
		'icon' : 'carat-1-n' 
	};
	
	$.button = function( options ) {
		var options = $j.extend( {}, mw_default_button_options, options);
		
		// Button:
		var $button = $('<a />')			
			.attr('href', '#')
			.addClass( 'ui-state-default ui-corner-all ui-icon_link' );
		// Add css if set:
		if( options.css ) {
			$button.css( options.css );
		}
							
		if( options['class'] ) {
			$button.addClass( options['class'] );
		}	
						
		
		// return the button: 
		$button.append(
				$('<span />').addClass( 'ui-icon ui-icon-' + options.icon ),
				$('<span />').addClass( 'btnText' )	
				.text( options.text )
		)
		.buttonHover(); // add buttonHover binding;		
		if( !options.text ){
			$button.css('padding', '1em');
		}
		return $button;
	};
	
	// Shortcut to bind hover state
	$.fn.buttonHover = function() {
		$( this ).hover(
			function() {
				$( this ).addClass( 'ui-state-hover' );
			},
			function() {
				$( this ).removeClass( 'ui-state-hover' );
			}
		);
		return this;
	};
	
	/**
	 * Resize a dialog to fit the window
	 * 
	 * @param {Object}
	 *            options horizontal and vertical space ( default 50 )
	 */
	$.fn.dialogFitWindow = function( options ) {
		var opt_default = { 'hspace':50, 'vspace':50 };
		if ( !options )
			var options = { };
		options = $j.extend( opt_default, options );
		$( this.selector ).dialog( 'option', 'width', $( window ).width() - options.hspace );
		$( this.selector ).dialog( 'option', 'height', $( window ).height() - options.vspace );
		$( this.selector ).dialog( 'option', 'position', 'center' );
			// update the child position: (some of this should be pushed
			// up-stream via dialog config options
		$( this.selector + '~ .ui-dialog-buttonpane' ).css( {
			'position':'absolute',
			'left':'0px',
			'right':'0px',
			'bottom':'0px'
			} );
		};
		
} )( jQuery );