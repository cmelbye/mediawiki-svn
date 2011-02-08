<?php 

// Replaces the ResourceLoaderFileModule by stubbing out stuff that does not work in stand 
// alone mode

class MwEmbedResourceLoaderStartUpModule extends ResourceLoaderStartUpModule {
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
		return array();
		/*$dbr = wfGetDB( DB_SLAVE );
		$deps = $dbr->selectField( 'module_deps', 'md_deps', array(
				'md_module' => $this->getName(),
				'md_skin' => $skin,
			), __METHOD__
		);
		if ( !is_null( $deps ) ) {
			$this->fileDeps[$skin] = (array) FormatJson::decode( $deps, true );
		} else {
			$this->fileDeps[$skin] = array();
		}
		return $this->fileDeps[$skin];*/
	}
	/**
	 * mwEmbedStartup is dependent on mwEmbedSupport 
	 */
	public function getStartupModuleList(){
		// Startup modules don't respect dependency manually add 'mwEmbedSupport' dependencies
		return array( 'jquery', 'mediawiki', 'jquery.triggerQueueCallback', 'jquery.mwEmbedUtil', 'mwEmbedStartup' );
	}
	
	public function getScript( ResourceLoaderContext $context ) {		
		$out = parent::getScript( $context );
		// Append mediaWiki.loader.go() for stand alone context:		
		$out.=  Xml::encodeJsCall( 'document.write', array( Html::inlineScript( "mediaWiki.loader.go();" ) ) );
		return $out;
	}
	
	protected function getConfig( $context ) {
		global $wgLoadScript; 
				
		$vars = array(
			'wgLoadScript' => $wgLoadScript,
			'debug' => $context->getDebug(),
			'skin' => $context->getSkin(),
			'wgUserLanguage' => $context->getLanguage(),
		);
		wfRunHooks( 'ResourceLoaderGetConfigVars', array( &$vars ) );
		
		return $vars;
	}
}
?>