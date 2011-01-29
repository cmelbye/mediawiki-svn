/**
 * Runs all the triggers on all the named bindings of an object with
 * a single callback
 * 
 * NOTE THIS REQUIRES JQUERY 1.4.2 and above
 * 
 * Normal jQuery tirgger calls will run the callback directly
 * multiple times for every binded function.
 * 
 * With triggerQueueCallback() callback is not called until all the
 * binded events have been run.
 * 
 * @param {string}
 *            triggerName Name of trigger to be run
 * @param {object=}
 *            arguments Optional arguments object to be passed to
 *            the callback
 * @param {function}
 *            callback Function called once all triggers have been
 *            run
 * 
 */
( function( $ ) {
	$.fn.triggerQueueCallback = function( triggerName, triggerParam, callback ){
		var targetObject = this;
		// Support optional triggerParam data
		if( !callback && typeof triggerParam == 'function' ){
			callback = triggerParam;
			triggerParam = null;
		}
		// Support namespaced event segmentation ( jQuery
		var triggerBaseName = triggerName.split(".")[0]; 
		var triggerNamespace = triggerName.split(".")[1];
		// Get the callback set
		var callbackSet = [];
		if( ! triggerNamespace ){
			callbackSet = $j( targetObject ).data( 'events' )[ triggerBaseName ];
		} else{		
			$j.each( $j( targetObject ).data( 'events' )[ triggerBaseName ], function( inx, bindObject ){
				if( bindObject.namespace ==  triggerNamespace ){
					callbackSet.push( bindObject );
				}
			});
		}
	
		if( !callbackSet || callbackSet.length === 0 ){
			mw.log( '"mwEmbed::jQuery.triggerQueueCallback: No events run the callback directly: ' + triggerName );
			// No events run the callback directly
			callback();
			return ;
		}
		
		// Set the callbackCount
		var callbackCount = ( callbackSet.length )? callbackSet.length : 1;
		// mw.log("mwEmbed::jQuery.triggerQueueCallback: " + triggerName
		// + ' number of queued functions:' + callbackCount );
		var callInx = 0;
		var doCallbackCheck = function() {
			// mw.log( 'callback for: ' + mw.getCallStack()[0] +
			// callInx);
			callInx++;
			if( callInx == callbackCount ){
				callback();
			}
		};
		if( triggerParam ){
			$( this ).trigger( triggerName, [ triggerParam, doCallbackCheck ]);
		} else {
			$( this ).trigger( triggerName, [ doCallbackCheck ] );
		}
	};
} )( jQuery );