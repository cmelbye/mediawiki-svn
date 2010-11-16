<?php
/**
 * @file
 * @ingroup upload
 *
 * @author Bryan Tong Minh
 *
 * Implements regular file uploads
 */
class UploadFromFile extends UploadBase {
	protected $mUpload = null;

	function initializeFromRequest( &$request ) {
		$upload = $request->getUpload( 'wpUploadFile' );		
		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $upload->getName();
			
		return $this->initialize( $desiredDestName, $upload );
	}
	
	/**
	 * Initialize from a filename and a WebRequestUpload
	 */
	function initialize( $name, $webRequestUpload ) {
		$this->mUpload = $webRequestUpload;
		return $this->initializePathInfo( $name, 
			$this->mUpload->getTempName(), $this->mUpload->getSize() );
	}
	static function isValidRequest( $request ) {
		return (bool)$request->getFileTempName( 'wpUploadFile' );
	}

	/** 
	 * Get the path to the file underlying the upload
	 * @return String path to file
	 */
	public function getFileTempname() {
		return $this->mUpload->getTempname();
	}
}
