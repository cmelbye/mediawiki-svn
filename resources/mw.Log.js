// dependencies: [ mw ] 

( function( mw, $j ) {

	function pad( d, n ) {
		var s = d.toString(); return s.length == n ? s : pad( '0' + s, n );
	}

	/**
	 * Log a string msg to the console
	 * 
	 * @param {String} string String to output to console
	 */
	mw.log = function( s, level ) {
		
		if ( typeof level === 'undefined' ) {
			level = mw.log.INFO;
		}

		if ( level > mw.log.level ) {
			return;
		}	

		if ( typeof window.console !== 'undefined' && typeof window.console.log === 'function' ) {
			window.console.log( s );
		} else {
			// Set timestamp
			var d = new Date();
			var time = ( pad( d.getHours(), 2 ) + ':' + pad( d.getMinutes(), 2 ) + pad( d.getSeconds(), 2 ) + pad( d.getMilliseconds(), 3 ) );
			// Show a log box for console-less browsers
			var $log = $( '#mw-log-console' );
			if ( !$log.length ) {
				$log = $( '<div id="mw-log-console"></div>' )
					.css( {
						'position': 'fixed',
						'overflow': 'auto',
						'z-index': 500,
						'bottom': '0px',
						'left': '0px',
						'right': '0px',
						'height': '150px',
						'background-color': 'white',
						'border-top': 'solid 2px #ADADAD'
					} )
					.appendTo( 'body' );
			}
			$log.append(
				$( '<div></div>' )
					.css( {
						'border-bottom': 'solid 1px #DDDDDD',
						'font-size': 'small',
						'font-family': 'monospace',
						'padding': '0.125em 0.25em'
					} )
					.text( string )
					.append( '<span style="float:right">[' + time + ']</span>' )
			);
		}
	};
	
	/**
	 * Convenience function for logging cases where you want a prefix, or to log at a particular level.
	 */
	mw.log.logger = function( prefix, level ) {
		return function( s ) {
			mw.log( prefix + '> ' + s, level );
		}  
	};

	mw.log.SILENT = 0;
	mw.log.FATAL = 10;
	mw.log.WARN = 20;
	mw.log.INFO = 30;	
	mw.log.ALL = 100;
	
	mw.log.fatal = function( s ) {
		mw.log( s, mw.log.FATAL );
	};
	mw.log.warn = function( s ) {
		mw.log( s, mw.log.WARN );
	};
	mw.log.info = function( s ) {
		mw.log( s, mw.log.INFO );
	};

	mw.log.level = mw.log.ALL;

} )( window.mediaWiki, jQuery );

