<?php 

// Replaces the ResourceLoaderFileModule by stubbing out stuff that does not work in stand 
// alone mode

class MwEmbedResourceLoaderStartUpModule extends ResourceLoaderStartUpModule {
	
	protected function getConfig( $context ) {
		// @@todo set all the configuration variables 
		$vars = array();
		wfRunHooks( 'ResourceLoaderGetConfigVars', array( &$vars ) );
		
		return $vars;
	}
}

?>