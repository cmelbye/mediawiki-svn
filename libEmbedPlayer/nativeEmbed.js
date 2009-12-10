/**
* Native embed library:
* 
* Enables embedPlayer support for native html5 browser playback system
*/
var nativeEmbed = {

	//Instance Name
	instanceOf:'nativeEmbed',
	
	// Counts the number of times we tried to access the video element 
	grab_try_count:0,
	
	// Flag to only load the video ( not play it ) 
	onlyLoadFlag:false,
	
	//Callback fired once video is "loaded" 
	onLoadedCallback: null,
	
	//For retrying a player embed with a distinct url
	// NOTE: this bug workaround may no longer be applicable	
	urlAppend:'',
	
	// The previus "currentTime" to snif seek actions 
	// NOTE the bug where onSeeked does not seem fire consistently may no longer be applicable	 
	prevCurrentTime: -1,
	
	// Native player supported feature set
	supports: {
		'play_head':true,
		'pause':true,
		'fullscreen':false,
		'time_display':true,
		'volume_control':true,
		
		'overlays':true,
		'playlist_swap_loader':true // if the object supports playlist functions		
	},	
	/**
	* Wraps the embed object and returns the output
	*/
	getEmbedHTML : function () {
		var embed_code =  this.getEmbedObj();
		js_log( "embed code: " + embed_code )
		setTimeout( '$j(\'#' + this.id + '\').get(0).postEmbedJS()', 150 );
		return this.wrapEmebedContainer( embed_code );
	},
	
	/**
	* Get the native embeed  code
	*/
	getEmbedObj:function() {
		// We want to let mwEmbed handle the controls so notice the absence of control attribute
		// controls=false results in controls being displayed: 
		// http://lists.whatwg.org/pipermail/whatwg-whatwg.org/2008-August/016159.html		
		js_log( "native play url:" + this.getSrc() + ' startOffset: ' + this.start_ntp + ' end: ' + this.end_ntp );
		var eb = '<video ' +
					'id="' + this.pid + '" ' +
					'style="width:' + this.width + 'px;height:' + this.height + 'px;" ' +
					'width="' + this.width + '" height="' + this.height + '" ' +
					'src="' + this.getSrc() + '" ' +				
				 '</video>';
		return eb;
	},	
	
	/**
	* Post element javascript, binds event listeners and starts monitor 
	*/	
	postEmbedJS:function() {
		var _this = this;
		js_log( "f:native:postEmbedJS:" );
		this.getPlayerElement();
		if ( typeof this.playerElement != 'undefined' ) {
		
			// Setup some bindings:
			var vid = $j( this.playerElement ).get(0);
			var wtf = function(){
				alert("wtf");
			}			
			// Bind events to local js methods:			
			vid.addEventListener( 'canplaythrough',  function(){ _this.canplaythrough }, true);			 
			vid.addEventListener( 'loadedmetadata', function(){ _this.onloadedmetadata() }, true);
			vid.addEventListener( 'progress', function( e ){  _this.onprogress( e )  }, true);
			vid.addEventListener( 'ended', function(){  _this.onended() }, true);		
			vid.addEventListener( 'seeking', function(){ _this.onseeking() }, true);
			vid.addEventListener( 'seeked', function(){ _this.onseeked() }, true);			
		
			// Always load the media:
			if ( this.onlyLoadFlag ) {
				this.playerElement.load();
			} else {
				// Issue play request				
				this.playerElement.play();
			}
			setTimeout( function(){
				_this.monitor();
			}, 100 );
			
		} else {		
			// False inserts don't seem to be as much of a problem as before: 
			js_log( 'Could not grab vid obj trying again:' + typeof this.playerElement );
			this.grab_try_count++;
			if (	this.grab_count == 20 ) {
				js_log( 'Could not get vid object after 20 tries re-run: getEmbedObj() ?' ) ;
			} else {
				setTimeout( function(){
					_this.postEmbedJS();
				}, 200 );
			}
			
		}
	},		
	
	/**
	* Issue a seeking request. 
	*
	* @param {Float} percentage
	*/
	doSeek:function( percentage ) {
		js_log( 'native:seek:p: ' + percentage + ' : '  + this.supportsURLTimeEncoding() + ' dur: ' + this.getDuration() + ' sts:' + this.seek_time_sec );
		// @@todo check if the clip is loaded here (if so we can do a local seek)
		if ( this.supportsURLTimeEncoding() ) {
			// Make sure we could not do a local seek instead:
			if ( percentage < this.bufferedPercent && this.playerElement.duration && !this.didSeekJump ) {
				js_log( "do local seek " + percentage + ' is already buffered < ' + this.bufferedPercent );
				this.doNativeSeek( percentage );
			} else {
				// We support URLTimeEncoding call parent seek: 
				this.parent_doSeek( percentage );
			}
		} else if ( this.playerElement && this.playerElement.duration ) {
			// (could also check bufferedPercent > percentage seek (and issue oggz_chop request or not) 
			this.doNativeSeek( percentage );
		} else {
			// try to do a play then seek: 
			this.doPlayThenSeek( percentage )
		}
	},
	
	/**
	* Do a native seek by updating the currentTime
	*/
	doNativeSeek:function( percentage ) {
		js_log( 'native::doNativeSeek::' + percentage );
		this.seek_time_sec = 0;
		this.playerElement.currentTime = percentage * this.duration;
		this.monitor();
	},
	
	/**
	* Do a play request 
	* then check if the video is ready to play
	* then seek
	*
	* @param {Float} percentage Percentage of the stream to seek to between 0 and 1
	*/
	doPlayThenSeek:function( percentage ) {
		js_log( 'native::doPlayThenSeek::' );
		var _this = this;
		this.play();
		var rfsCount = 0;
		var readyForSeek = function() {
			_this.getPlayerElement();			
			// if we have duration then we are ready to do the seek
			if ( _this.playerElement && _this.playerElement.duration ) {
				_this.doNativeSeek( percentage );
			} else {
				// Try to get player for 40 seconds: 
				// (it would be nice if the onmetadata type callbacks where fired consistently)
				if ( rfsCount < 800 ) {
					setTimeout( readyForSeek, 50 );
					rfsCount++;
				} else {
					js_log( 'error:doPlayThenSeek failed' );
				}
			}
		}
		readyForSeek();
	},
	
	/**
	* Set the current time with a callback
	*/
	setCurrentTime: function( position , callback ) {	
		var _this = this;
		js_log( 'native:setCurrentTime::: ' + position + ' :  dur: ' + _this.getDuration() );
		this.getPlayerElement();
		if ( !this.playerElement ) {
			this.load( function() {				
				_this.doSeekedCb( position, callback );		
			} );
		} else {
			_this.doSeekedCb( position, callback );		
		}
	},
	/**
	* Do the seek request with a callback
	* 
	* @param {Float} position Position in seconds
	* @param {Function} callback Function to call once seeking completes
	*/
	doSeekedCallback : function( position, callback ){
		var _this = this;			
		this.getPlayerElement();		
		var once = function( event ) {
			js_log("did seek callback");
			callback();
			_this.playerElement.removeEventListener( 'seeked', once, false );
		};		
		// Assume we will get to add the Listener before the seek is done
		_this.playerElement.currentTime = position;
		_this.playerElement.addEventListener( 'seeked', once, false );						
	},
	
	/**
	* Monitor the video playback & update the currentTime
	*/
	monitor: function() {
		this.getPlayerElement(); // make sure we have .vid obj
		if ( !this.playerElement ) {
			js_log( 'could not find video embed: ' + this.id + ' stop monitor' );
			this.stopMonitor();
			return false;
		}
		// don't update status if we are not the current clip 
		// (playlist leakage?) .. should move to playlist overwrite of monitor? 
		if ( this.pc ) {
			if ( this.pc.pp.cur_clip.id != this.pc.id )
				return true;
		}
		
		// Do a seek check (on seeked does not seem fire consistently) 	
		if ( this.prevCurrentTime != -1 && this.prevCurrentTime != 0
			&& this.prevCurrentTime < this.currentTime && this.seeking )
			this.seeking = false;
								
		this.prevCurrentTime =	this.currentTime;
		
		// update currentTime				
		this.currentTime = this.playerElement.currentTime;		
				
		// js_log('currentTime:' + this.currentTime);
		// js_log('this.currentTime: ' + this.currentTime );
		// once currentTime is updated call parent_monitor
		this.parent_monitor();
	},
	
	/**
	* Get video src URI
	*/
	getSrc: function() {
		var src = this.parent_getSrc();
		if (  this.urlAppend != '' )
			return src + ( ( src.indexOf( '?' ) == -1 ) ? '?':'&' ) + this.urlAppend;
		return src;
	},	
	
	/**
	* Pause the video playback
	* calls parent_pause to update the interface
	*/
	pause: function() {
		this.getPlayerElement();
		this.parent_pause(); // update interface		
		if ( this.playerElement ) {
			this.playerElement.pause();
		}
		// stop updates: 
		this.stopMonitor();
	},
	
	/**
	* Play back the video stream
	*  calls parent_play to update the interface
	*/
	play: function() {
		this.getPlayerElement();
		this.parent_play(); // update interface
		if ( this.playerElement ) {
			this.playerElement.play();
			// re-start the monitor: 
			this.monitor();
		}
	},
	
	/**
	* Toggle the Mute
	*  calls parent_toggleMute to update the interface
	*/	
	toggleMute: function() {
		this.parent_toggleMute();
		this.getPlayerElement();
		if ( this.playerElement )
			this.playerElement.muted = this.muted;
	},
	
	/**
	* Update Volume
	*
	* @param {Float} percentage Value between 0 and 1 to set audio volume
	*/	
	updateVolumen: function( percentage ) {
		this.getPlayerElement();
		if ( this.playerElement )
			this.playerElement.volume = percentage;
	},
	
	/**
	* get Volume
	*
	* @return {Float} 
	* 	Audio volume between 0 and 1.
	*/	
    getVolumen: function() {
		this.getPlayerElement();
		if ( this.playerElement )
			return this.playerElement.volume;
	},
	
	/**
	* Get the native media duration
	*/
	getNativeDuration: function() {
		if ( this.playerElement )
			return this.playerElement.duration;
	},
	
	/**
	* load the video stream with a callback fired once the video is "loaded"
	*
	* @parma {Function} callbcak Function called once video is loaded
	*/
	load: function( callback ) {
		this.getPlayerElement();		
		if ( !this.playerElement ) {
			// No vid loaded
			js_log( 'native::load() ... doEmbed' );
			this.onlyLoadFlag = true;
			this.doEmbedHTML();
			this.onLoadedCallback =  callback;
		} else {
			// Should not happen offten
			this.playerElement.load();
			if( callback)
				callback();
		}
	},
	
	/**
	* Get /update the playerElement value 
	*/ 
	getPlayerElement : function () {
		this.playerElement = $j( '#' + this.pid ).get( 0 );
	},
	
	/**
 	* Bindings for the Video Element Events 
 	*/
	 
	/**
	* Local method for seeking event
	*  fired when "seeking" 
	*/
	onseeking:function() {
		js_log( "onseeking" );
		this.seeking = true;
		this.setStatus( gM( 'mwe-seeking' ) );
	},
	
	/**
	* Local method for seeked event
	*  fired when done seeking 
	*/
	onseeked: function() {
		js_log("onseeked");
		this.seeking = false;
	},
	
	/**
	* Local method for can play through
	*  fired when done video can play through without re-buffering
	*/	
	oncanplaythrough : function() {
		js_log('f:oncanplaythrough');
		this.getPlayerElement();
		if ( ! this.paused )
			this.playerElement.play();		
	},
	
	/**
	* Local method for metadata ready
	*  fired when metadata becomes available
	*
	* Used to update the media duration to 
	* accurately reflect the src duration 
	*/
	onloadedmetadata: function() {
		this.getPlayerElement();
		js_log( 'f:onloadedmetadata metadata ready (update duration)' );
		// update duration if not set (for now trust the getDuration more than this.playerElement.duration		
		if ( this.getDuration() == 0  &&  ! isNaN( this.playerElement.duration ) ) {
			js_log( 'updaed duration via native video duration: ' + this.playerElement.duration )
			this.duration = this.playerElement.duration;
		}
		
		//Fire "onLoaded" flags if set		
		if( typeof this.onLoadedCallback == 'function' ){
			this.onLoadedCallback();
		}
	},
	
	/**
	* Local method for progress event
	*  fired as the video is downloaded / buffered
	*
	*  Used to update the bufferedPercent
	*/
	onprogress: function( e ) {
		this.bufferedPercent =   e.loaded / e.total;		
	},
	
	/**
	* Local method for progress event
	*  fired as the video is downloaded / buffered
	*
	*  Used to update the bufferedPercent
	*/	
	onended: function() {
		var _this = this
		this.getPlayerElement();
		js_log( 'native:onended:' + this.playerElement.currentTime + ' real dur:' +  this.getDuration() );
		// if we just started (under 1 second played) & duration is much longer.. don't run onClipDone just yet . (bug in firefox native sending onended event early) 
		if ( this.playerElement.currentTime  < 1 && this.getDuration() > 1 && this.grab_try_count < 5 ) {
			js_log( 'native on ended called with time:' + this.playerElement.currentTime + ' of total real dur: ' +  this.getDuration() + ' attempting to reload src...' );
			var doRetry = function() {
				_this.urlAppend = 'retry_src=' + _this.grab_try_count;
				_this.doEmbedHTML();
				_this.grab_try_count++;
			}
			setTimeout( doRetry, 100 );
		} else {
			js_log( 'native onClipDone done call' );
			this.onClipDone();
		}
	}
};
