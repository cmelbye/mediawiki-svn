<?php 

// Replaces the ResourceLoaderFileModule by stubbing out stuff that does not work in stand 
// alone mode

class MwEmbedResourceLoaderFileModule extends ResourceLoaderFileModule {
	/**
	 * Get the files this module depends on indirectly for a given skin.
	 * Currently these are only image files referenced by the module's CSS.
	 *
	 * @param $skin String: Skin name
	 * @return Array: List of files
	 */
	public function getFileDependencies( $skin ) {
		// Try in-object cache first
		if ( isset( $this->fileDeps[$skin] ) ) {
			return $this->fileDeps[$skin];
		}
		// Use file cache instead of db ( for mwEmbed minimal config and minimal framework code ) 
		$deps = mweGetFromFileCache( implode( 
			array( 'module_deps', 'md_deps', 'md_module_' . $this->getName(), 'md_skin_' . $skin ) 
		) );
		/*
		$dbr = wfGetDB( DB_SLAVE );
		$deps = $dbr->selectField( 'module_deps', 'md_deps', array(
				'md_module' => $this->getName(),
				'md_skin' => $skin,
			), __METHOD__
		);
		*/
		if ( !is_null( $deps ) ) {
			$this->fileDeps[$skin] = (array) FormatJson::decode( $deps, true );
		} else {
			$this->fileDeps[$skin] = array();
		}
		return $this->fileDeps[$skin];
	}
	/**
	 * Get the last modification timestamp of the message blob for this
	 * module in a given language.
	 * @param $lang String: Language code
	 * @return Integer: UNIX timestamp, or 0 if no blob found
	 */
	public function getMsgBlobMtime( $lang ) {
		if ( !isset( $this->msgBlobMtime[$lang] ) ) {
			if ( !count( $this->getMessages() ) )
				return 0;
			// Use file cache instead of db ( for mwEmbed minimal config and minimal framework code ) 
			$msgBlobMtime = mweGetFromFileCache( implode( 
				array( 'msg_resource', 'mr_timestamp', 'mr_resource_' . $this->getName(), 'mr_lang_' . $lang ) 
			) );
			/*$dbr = wfGetDB( DB_SLAVE );
			$msgBlobMtime = $dbr->selectField( 'msg_resource', 'mr_timestamp', array(
					'mr_resource' => $this->getName(),
					'mr_lang' => $lang
				), __METHOD__
			);
			*/
			$this->msgBlobMtime[$lang] = $msgBlobMtime ? wfTimestamp( TS_UNIX, $msgBlobMtime ) : 0;
		}
		return $this->msgBlobMtime[$lang];
	}
}

?>