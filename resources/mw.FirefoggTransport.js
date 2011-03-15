/**
 * Represents a "transport" for files to upload; in this case an firefogg.
 * XXX dubious whether this is really separated from "ApiUploadHandler", which does a lot of form config.
 *
 * The iframe is made to be the target of a form so that the existing page does not reload, even though it's a POST.
 * @param form	jQuery selector for HTML form
 * @param progressCb	callback to execute when we've started. (does not do float here because iframes can't 
 *			  monitor fractional progress).
 * @param transportedCb	callback to execute when we've finished the upload
 */
mw.FirefoggTransport = function( $form, fogg, progressCb, transportedCb ) {
	this.$form = $form;
	this.fogg = fogg;
	this.progressCb = progressCb;
	this.transportedCb = transportedCb;
};

mw.FirefoggTransport.prototype = {

	passthrough: false,
	/**
	 * Do an upload on a given fogg object: 
	 */
	doUpload: function(){
		// check if the server supports chunks:
		if( this.isChunkUpload() ){
			mw.log("FirefoggTransport::doUpload> Chunks");
			// encode and upload at the same time: 
			this.doChunkUpload();
		} else {
			mw.log("FirefoggTransport::doUpload> Encode then upload");
			this.doEncodeThenUpload();
		}
	},
	isChunkUpload: function(){
		return false;
		return ( mw.UploadWizard.config[ 'enableFirefoggChunkUpload' ] );
	},			
	/**
	 * Check if the asset should be uploaded in passthrough mode ( or if it should be encoded )
	 */
	isPassThrough: function(){
		// Check if the server supports raw webm uploads: 
		var wembExt = ( $j.inArray( mw.UploadWizard.config[ 'fileExtensions'], 'webm') !== -1 )
		// Determine passthrough mode
		if ( this.isOggFormat() || ( wembExt && isWebMFormat() ) ) {
			// Already Ogg, no need to encode
			return true;
		} else if ( this.isSourceAudio() || this.isSourceVideo() ) {
			// OK to encode
			return false;
		} else {
			// Not audio or video, can't encode
			return true;
		}
	},

	isSourceAudio: function() {
		return ( this.getSourceFileInfo().contentType.indexOf("audio/") != -1 );
	},

	isSourceVideo: function() {
		return ( this.getSourceFileInfo().contentType.indexOf("video/") != -1 );
	},

	isOggFormat: function() {
		var contentType = this.getSourceFileInfo().contentType;
		return ( contentType.indexOf("video/ogg") != -1
			|| contentType.indexOf("application/ogg") != -1 
			|| contentType.indexOf("audio/ogg") != -1);
	},
	isWebMFormat: function() {
		return (  this.getSourceFileInfo().contentType.indexOf('webm') != -1 );
	},
	
	/**
	 * Get the source file info for the current file selected into this.fogg
	 */
	getSourceFileInfo: function() {
		if ( !this.fogg.sourceInfo ) {
			mw.log( 'Error:: No firefogg source info is available' );
			return false;
		}
		try {
			this.sourceFileInfo = JSON.parse( this.fogg.sourceInfo );
		} catch ( e ) {
			mw.log( 'Error :: could not parse fogg sourceInfo' );
			return false;
		}
		return this.sourceFileInfo;
	},
	
	// Get the filename
	getFileName: function(){
		// If passthrough don't change it
		if( this.isPassThrough() ){
			return this.fogg.sourceFilename;
		} else {			
			if( this.isSourceAudio() ){
				return this.fogg.sourceFilename.split('.').slice(0,-1).join('.') + '.oga';
			}
			if( this.isSourceVideo() ){
				return this.fogg.sourceFilename.split('.').slice(0,-1).join('.') + '.webm';
			}
		}
	},
	getEncodeExt: function(){
		if( this.getEncodeSettings()['videoCodec'] 
		            && 
		            this.getEncodeSettings()['videoCodec'] == 'vp8' )
		{ 
			return 'webm';
		} else { 
			return 'ogv';
		}
	},
	/**
	 * Get the encode settings from configuration and the current selected video type 
	 */
	getEncodeSettings: function(){
		var encodeSettings = $j.extend( {}, mw.UploadWizard.config[ 'firefoggEncodeSettings'] , {
			'passthrough' : this.isPassThrough()
		});
		// Update the format: 
		this.fogg.setFormat( ( this.getEncodeExt == 'webm' )? 'webm' : 'ogg' );
		
		mw.log("FirefoggTransport::getEncodeSettings> " +  JSON.stringify(  encodeSettings ) );
		return encodeSettings;
	},
	
	/**
	 * Encode then upload
	 */
	doEncodeThenUpload: function(){
		// If doing passthrough jump direct to upload: 
		if( this.isPassThrough() ){
			this.doFoggPost();
			return ;
		}		
		this.fogg.encode( JSON.stringify( this.getEncodeSettings() ) );
		
		this.monitorProgress();
	},
	
	/**
	 * do fogg post 
	 */
	doFoggPost: function(){
		var _this = this;		
		// Get the upload request with a callback ( populates the request token ) 	
		 this.getUploadRequest( function( request ){
			mw.log("FirefoggTransport::doFoggPost> " + _this.getUploadUrl() + ' req:' + 
					JSON.stringify( request ) );
			
			_this.fogg.post( _this.getUploadUrl(), 
				'file', 
				JSON.stringify( request ) 
			);
			_this.monitorProgress();
		} );
	},
	/**
	 * Encode and upload in chunks
	 */
	doChunkUpload: function(){
		var _this = this;
		this.getUploadRequest( function( request ){
			this.fogg.upload( 
					JSON.stringify( _this.getEncodeSettings() ), 
					_this.getUploadUrl(),
					JSON.stringify( request )
			);
		});
		_this.monitorProgress();
	},
	/**
	 * Get the upload url
	 */ 
	getUploadUrl: function(){
		return mw.UploadWizard.config['apiUrl'];
	},
	
	/**
	 * Get the upload settings
	 * @param {function} callback function to send the request object 
	 */	
	getUploadRequest: function( callback ){
		var _this = this;
		// ugly probably would be nice to have base refrence to the upload class so we can use the 
		 new mw.Api( { 
			 'url' : _this.getUploadUrl() 
		 } )
		 .getEditToken( function( token ) {
			callback( {
				'action' : ( _this.isChunkUpload() )? 'firefoggupload' : 'upload',
				'stash' :1,
				'comment' : 'DUMMY TEXT',
				'format' : 'json',
				'filename' : _this.getFileName(),
				'token' : token
			} );
		}, function( code, info ) {
			_this.upload.setError( code, info );
		} );		
	},
	/**
	 * Monitor progress on an upload:
	 */
	monitorProgress: function(){
		var _this = this;
		var fogg = this.fogg;
		var progress = fogg.progress();
		var state = fogg.state;
		
		mw.log("FirefoggTransport::monitorProgress> " + progress + ' state: ' + state  + ' status: ' + this.fogg.status() + ' rt: ' + this.getResponseText() );
		this.progressCb( progress );
		
		if( state == 'encoding done' && ! this.isChunkUpload() ){
			// ( if encoding done, we are in a two step encode then upload process )
			this.doFoggPost();
			return ;
		}
		// If state is 'in progress' ... fire monitor progress
		if( state == 'encoding' || state == 'uploading' || state == '' ){
			setTimeout( function(){
				_this.monitorProgress();
			}, mw.UploadWizard.config['uploadProgressInterval'] );
		}
		// return the api result: 
		if( state == 'done' || state == 'upload done' ){
			this.transportedCb( this.getResponseText() );
		}
		
	},
	
	getResponseText: function(){
		var _this = this;
		try {
			var pstatus = JSON.parse( _this.fogg.uploadstatus() );
			return pstatus["responseText"];
		} catch( e ) {
			mw.log( "Error:: Firefogg could not parse uploadstatus / could not get responseText: " + e );
		}		
	}
};


