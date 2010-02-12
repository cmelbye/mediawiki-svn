/**
* 
* Api proxy system
*
* Supports cross domain uploading, and api actions for a approved set of domains.
*
* The framework ~will eventually~ support a request approval system for per-user domain approval
* and a central blacklisting of domains controlled by the site 
*  
* if the browser supports it we should pass msgs with postMessage  API
* http://ejohn.org/blog/cross-window-messaging/
*
* NOTE: it would be nice if this supported multiple proxy targets (ie to a bright widgets future) 
*
*/

mw.addMessages( {
	"mwe-setting-up-proxy" : "Setting up proxy...",
	"mwe-re-try" : "Retry API request",
	"mwe-re-trying" : "Retrying API request...",
	"mwe-proxy-not-ready" : "Proxy is not configured",
	"mwe-please-login" : "You are not <a target=\"_new\" href=\"$1\">logged in<\/a> on $2 or mwEmbed has not been enabled. Resolve the issue, and then retry the request.",
	"mwe-remember-loging" : "General security reminder: Only login to web sites when your address bar displays that site's address."
} );




/**
 * apiProxy jQuery binding
 * 
 * Note: probably should split up "server" and "client" binding 
 * 
 * @param {String} mode Mode is either 'server' or 'client'
 * @param {Object} proxyConfig Proxy configuration
 * @param {Function} callback The function called once proxy request is done
 */

 /**
 * Set the base API Proxy object
 * 	 
 */
mw.ApiProxy = { };
	
// Proxy scoped functions: 
( function( $ ) {
	

	// Local scope public vars 
	//( should probably tie these var defines to the scope of the doRequest call using something like "curry" function ) 
	
	// Callback function for client requests 
	var proxyCallback = null;
	 
	// FrameProxy Flag: 	
	var frameProxyOk = false;
	
	// The last run api request data object
	var currentApiReq = { };
	
	// The url for the last api request target.
	var currentApiUrl = null; 
	
	// Time we should wait for proxy page callback
	// ( note this time starts form when the page is "done"
	// loading so it should only be the time need to load some 
	// cached js for the callback)	
	var proxyPageLoadTimeout = 10000;
			
	/**  
	* Takes a requestQuery, executes the query and then calls the callback
	*  sets the local callback to be called once requestQuery completes
	* 
	* @param {String} apiUrl Url to the api we want to do the request on.  
	* @param {Object} requestQuery Api request object
	* @param {Function} callback Function called once the request is complete 
	*/
	$.doRequest = function( apiUrl, requestQuery, callback ) {			
		// Reset local vars:
		proxyCallback = false;
		frameProxyOk = false;
			
		// Sanity check: 
		if ( mw.isLocalDomain( apiUrl ) ) {
			mw.log( "Error: trying to proxy local domain? " );
			return false;
		}
		
		mw.log( "doRequest:: " + JSON.stringify( requestQuery ) );
		currentApiReq = requestQuery;
		currentApiUrl = apiUrl;
		// Setup the callback:
		proxyCallback = callback;
		// Do the proxy req:
		doFrameProxy( requestQuery );
	}

	/**
	 * The nested iframe action that passes its result back up to the top frame instance 
	 * 
	 * Entry point for hashResult from nested iframe
	 *
	 * @param {Object} hashResult Value to be sent to parent frame	 
	 */
	$.nested = function( hashResult ) {
		// Close the loader if present: 
		mw.closeLoaderDialog();
		mw.log( '$.proxy.nested callback :: ' + unescape( hashResult ) );
		frameProxyOk = true;
		
		// Try to parse the hash result 
		try {
			var resultObject = JSON.parse( unescape( hashResult ) );
		} catch ( e ) {
			mw.log( "Error could not parse hashResult" );
		}
		
		// Special callback to frameProxyOk flag 
		// (only used to quickly test the proxy connection)   
		if ( resultObject.state == 'ok' ){
			return ;
		}
		
		// Pass the result object to the callback:
		proxyCallback( resultObject );
	}
	
	/**
	* Generates a remote browse file iframe window
	*	usefull for "uploading" cross domain
	* 
	* @param {Function} callback Function to host the 
	*/
	$.browseFile = function( options ){
	
		// Set frame proxy ok state flag: 
		frameProxyOk = false;
		
		if( ! options ){
			options = {};
		}
			
		if( ! options.target ){
			mw.log( "Error: no target for file browse iframe" ) ;
			return false;
		}
		if( ! options.api_url ){
			mw.log( "Error: no api url to target" );
			return false; 
		}
		//update the current apiUrl:
		currentApiUrl = options.api_url;
		
		if( ! options.width ){
			options.width = 270;		
		}
		if( ! options.height ){
			options.height = 27;
		}
		var iFrameName = ( options.iframeName ) ? options.iframeName : 'fileBrowse_' + $j('iframe').length;		
		// Setup an object to be packaged into the frame
		var hashPack ={
			'clientFrame' : getClientFrame(),
			'browseFile' : true			
		};
		
		// Set to host file browse rewrite position
		// NOTE we can't use jQuery buildout for iframes because IE throws away attributes  
		$j( options.target ).html(									 		
			 	'<iframe ' +
				 	'style="display:none;border:none;overflow:hidden;width:'+ parseInt( options.width ) + 'px;height:' + parseInt( options.height ) +'px;" '+ 
				 	'id="' + escape( iFrameName ) + '" name="' + escape( iFrameName ) + '" ' +
					'src="' + getServerBrowseFileFrame(  options.api_url ) + 
						'#' + escape( JSON.stringify( hashPack ) ) + '">' + 				
				'</iframe>' 
		);
		// add a loading spinner
		$j( options.target ).append( 
			$j('<div />').loadingSpinner()
		);
		
		// add an onLoad hook: 
		$j( '#' + iFrameName ).get( 0 ).onload = function() {		
			// Add a 10 second timeout for setting up the nested child callback (after page load)		
			setTimeout( function() {
				if ( !frameProxyOk ) {
					// we timmed out no api proxy (should make sure the user is "logged in")
					mw.log( "Error:: api proxy timeout are we logged in? mwEmbed is on?" );
					proxyNotReadyDialog();
				}
			}, proxyPageLoadTimeout );
		};
		
		// Setup the proxy callback to display the upload unhide the iframe upload form 
		proxyCallback = function( iframeData ){
			//hide the loading spinner
			$j( options.target ).find('.loading_spinner').fadeOut('fast');
			mw.log("iframe ready callback");
			$j( '#' + iFrameName ).fadeIn( 'fast' );
		}
		
	}
	
	/** 
	* Api server proxy entry point: 
	*
	* @param {Object} proxyConfig The server side proxy configuration
	* @param {Function} callbcak Function to call once server is setup
	*
	*/
	$.server = function( proxyConfig, callback ) {			
		// Validate the server request:
		if( validateIframeRequest( proxyConfig ) ){
			var clientRequest = getClientRequest();
			
			// Inform the client frame that we passed validation
			outputResultsFrame( clientRequest.clientFrame, 'nested_ok', { 'state':'ok' } );
			// Process request type
			if( clientRequest['browseFile'] ){
				mw.log( "DO BROWSE FILE" );
				serverBrowseFile( proxyConfig );
				return ;
			}						
			// Else do a normal api request : 
			return doApiRequest();
		}												
	}
	
	/**
	* Local scoped helper functions:  
	*/	
	
	/**
	* Get the client frame path ( within mwEmbed )
	*/
	function getClientFrame(){
		return mw.getMwEmbedPath() + 'modules/ApiProxy/NestedCallbackIframe.html';
	}
	
	/**
	* Get the server Frame path per requested Api url (presently hard coded to MediaWiki:ApiProxy per /remotes/medaiWiki.js )
	* 
	* NOTE: we can switch this for a real proxy entry point once we set that up on the server. 
	* 
	* NOTE: we add the gadget incase the user has not enabled the gadget on the project they want to iframe to. 
	* ( there is no cost if they do already have the gadget on ) 
	*/
	var gadgetWithJS = 'withJS=MediaWiki:Gadget-mwEmbed.js';
	function getServerFrame( apiUrl ){
		// Set to local scope currentApiUrl if unset by argument
		if( !apiUrl){
			apiUrl = currentApiUrl;
		}
		var  parsedUrl = mw.parseUri( apiUrl );
		return parsedUrl.protocol + '://' + parsedUrl.authority + '/w/index.php/MediaWiki:ApiProxy?' + gadgetWithJS;	
	}
	/**
	* Same as getServerFrame but for browse file interface
	*/
	function getServerBrowseFileFrame( apiUrl ){
		// Set to local scope currentApiUrl if unset by argument
		if( !apiUrl){
			apiUrl = currentApiUrl;
		}
		var  parsedUrl = mw.parseUri( apiUrl );
		return parsedUrl.protocol + '://' + parsedUrl.authority + '/w/index.php/MediaWiki:ApiProxyBrowserFile?' + gadgetWithJS;	
	}
	
	/** 
	* Do the frame proxy
	* 	Writes an iframe with a hashed value of the requestQuery
	*
	* @param {Object} requestQuery The api request object 
	*/
	function doFrameProxy ( requestQuery ) {
		
		var hashPack = {
			// Client domain frame ( will be approved by the server before sending and reciving msgs )
			'clientFrame' : getClientFrame(),
			'request' : requestQuery
		}
		
		mw.log( "Do frame proxy request on src: \n" + getServerFrame() + "\n" + JSON.stringify(  requestQuery ) );
					
		// We can't update src's so we have to remove and add all the time :(
		// NOTE: we should support frame msg system 
		$j( '#frame_proxy' ).remove();
		// NOTE we can't use jQuery buildout for iframes because IE throws away the "name"
		$j( 'body' ).append( '<iframe style="display:none" id="frame_proxy" name="frame_proxy" ' +
				'src="' + getServerFrame() +
				 '#' + escape( JSON.stringify( hashPack ) ) +
				 '"></iframe>' );
				 
		// add an onLoad hook: 
		$j( '#frame_proxy' ).get( 0 ).onload = function() {
		
			// Add a 10 second timeout for setting up the nested child callback (after page load)
			
			// NOTE: once we have a real entry point instead of ( mediaWiki:ApiProxy rewrite) 
			// this number can be reduced since it won't have to load all the style sheets and
			// javascript for a normal page view 
			
			setTimeout( function() {
				if ( !frameProxyOk ) {
					// we timmed out no api proxy (should make sure the user is "logged in")
					mw.log( "Error:: api proxy timeout are we logged in? mwEmbed is on?" );
					proxyNotReadyDialog();
				}
			}, proxyPageLoadTimeout );
		}
	}
	
	/**
	* Validate an iframe request 
	* checks the url hash for required paramaters 
	* checks  master_blacklist 
	* checks  master_whitelist
	*/
	function validateIframeRequest( proxyConfig ){
		var clientRequest = false;
		
		
		var clientRequest = getClientRequest();
		
		if ( !clientRequest || !clientRequest.clientFrame ) {
			mw.log( "Error: no client domain provided " );
			$j( 'body' ).append( "no client frame provided" ); 
			return false;
		}		
		
		// Make sure we are logged in 
		// (its a normal mediaWiki page so all site vars should be defined)		
		if ( typeof wgUserName != 'undefined' && !wgUserName ) {
			mw.log( 'Error Not logged in' );
			return false;
		}
		
		mw.log( "Setup server on: "  + mw.parseUri( document.URL ).host  );
		mw.log('Client frame: ' + clientRequest.clientFrame );
				
		var clientDomain =  mw.parseUri( clientRequest.clientFrame ).host ;
		
		/**
		*	HERE WE CHECK IF THE DOMAIN IS ALLOWED per the proxyConfig	
		*/		
		// Check master blacklist
		for ( var i in proxyConfig.master_blacklist ) {
			if ( clientDomain == proxyConfig.master_blacklist ) {
				mw.log( 'domain: ' + clientDomain + ' is blacklisted ( no request )' );
				return false;
			}
		}		 
		// Check the master whitelist:
		for ( var i in proxyConfig.master_whitelist ) {
			if ( clientDomain ==  proxyConfig.master_whitelist[ i ] ) {
				return true;
			}
		}
		
		// FIXME Add in user based approval :: 
		
		// FIXME offer the user the ability to "approve" requested domain save to
		// their user preference setup )
		
		// FIXME grab the users whitelist for our current domain					
		
		// Not a valid request return false
		return false;
	}
	
	/**
	* Get the client request from the document hash
	* @return {Object} the object result of parsing the document anchor msg
	*/
	function getClientRequest(){
		// Read the anchor data package from the requesting url
		var hashMsg = unescape( mw.parseUri( document.URL ).anchor );
		try {
			return JSON.parse( hashMsg );
		} catch ( e ) {
			mw.log( "ProxyServer:: could not parse anchor" );
			return false;
		}		
	}
	
	/**
	* Dialog to send the user if a proxy to the remote server could not be created 
	*/
	function proxyNotReadyDialog() {
		var buttons = { };
		buttons[ gM( 'mwe-re-try' ) ] = function() {
			mw.addLoaderDialog( gM( 'mwe-re-trying' ) );
			doFrameProxy( currentApiReq );
		}
		buttons[ gM( 'mwe-cancel' ) ] = function() {
			mw.closeLoaderDialog();
		}
		var pUri =  mw.parseUri( getServerFrame() );
		
		// FIXME we should have a Hosted page once we deploy mwEmbed on the servers.
		// A hosted page would be much faster since than a normal page view rewrite 
		
		var login_url = pUri.protocol + '://' + pUri.host;
		login_url += pUri.path.replace( 'MediaWiki:ApiProxy', 'Special:UserLogin' );
		
		mw.addDialog( 
			gM( 'mwe-proxy-not-ready' ), 
			gM( 'mwe-please-login', [ login_url, pUri.host] ) +
				'<p style="font-size:small">' + 
					gM( 'mwe-remember-loging' ) + 
				'</p>',
			buttons
		)
	}
	
				
	
	
	/**
	* API iFrame Server::
	*
	* Handles the server side proxy of requests 
	* it adds child frames pointing to the parent "blank" frames
	*/
	 	 
 	/**
	* Api iFrame request:
	* @param {Object} requestObj Api request object
	*/
	function doApiRequest( ) {		
		// Get the client request
		var clientRequest = getClientRequest();
		// Make sure its a json format 
		clientRequest.request[ 'format' ] = 'json';		

		// Process the API request. We don't use mw.apiReq since we need to "post" 
		$j.post( wgScriptPath + '/api' + wgScriptExtension,
			clientRequest.request,
			function( data ) {					
				// Put it result into nested frame hash string: 
				outputResultsFrame( clientRequest.clientFrame, 'nested_push', JSON.parse( data ) );
			}
		);
	}
	
	/**
	* Setup the browse file proxy on the "server"
	* 
	* Sets the page content to browser file 
	*/
	function serverBrowseFile( proxyConfig ){
		//check for fw ( file width )
		if( ! proxyConfig.fileWidth ){
			proxyConfig.fileWidth = 200;
		}
		//Build a form with bindings similar to uploadPage.js ( but only the browse button ) 		
		$j('body').html(
			$j('<form />')
			.attr( {
				'name' : "mw-upload-form",
				'id' : "mw-upload-form",
				'enctype' : "multipart/form-data",
				'method' : "post",
				// Submit to the local domain 
				'action' : 	mw.getLocalApiUrl()
			} )
			.append(
				//Add a single "browse for file" button
				$j('<input />')
				.attr({
					'type' : "file",					
					'name' : "wpUploadFile",
					'id' : "wpUploadFile"
				})
				.css({
					'width' : proxyConfig.fileWidth
				})
			)
		);
		
		// Local function to call once frame is ready:
		function clientFrameReady( status ){
			var clientRequest = getClientRequest();
			// Inform the client frame that we passed validation
			outputResultsFrame( clientRequest.clientFrame, 'browse_file', status );
		}
		
		// load the mw.upload library with iframe interface (similar to uploadPage.js)
		
		// If wgEnableFirefogg is not boolean false, set to true
		if ( typeof wgEnableFirefogg == 'undefined' ){
			wgEnableFirefogg = true;
		}
				
		if( wgEnableFirefogg ){
			mw.load( 'AddMedia.firefogg', function(){			
				$j( '#wpUploadFile' ).firefogg( {
					// Set the interface type
					'interface_type' : 'iframe'
				});
				// Update status 
				clientFrameReady( { 'firefogg' : 'ok' } );
			});
		} else {
			mw.load( 'AddMedia.UploadHandler', function(){						
				$j( mwUploadFormSelector ).uploadHandler({
					// Set the interface type
					'interface_type' : 'iframe'
				});
				clientFrameReady();
			});
		}
		
		
		
		
		
	};
	
	/**
	* Outputs the result object to the client domain
	*
	* @param {clientFrame} clientFrame Client frame name 
	* @param {String} nestName Name of iframe	
	* @param {resultObj} the result to pass back to the client domain
	*/ 
	function outputResultsFrame( clientFrame,  nestName, resultObj ) {
		$j( '#nested_push' ).remove();
		// Setup the nested iframe proxy that points back to top domain 
		// (can use jQuery buildout since we don't need to refrence this frame again)
		$j( 'body' ).append( 
			$j('<iframe>').attr({
				'id' 	: nestName,
				'name'	: nestName,
				'src'	: clientFrame + '#' + escape( JSON.stringify( resultObj ) )
			})
			.css({
				'display': 'none'
			})
		); 
	};
	 		
} )( window.mw.ApiProxy );
