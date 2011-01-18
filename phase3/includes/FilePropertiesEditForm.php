<?php

class FilePropertiesEditForm {
	public function __construct( $request = null, $file = null ) {
		$this->authors = array();
		$this->licenses = array();
		
		if ( $request ) {
			$authorsUsernames = $request->getArray( 'author_usernames' );
			$authorsAttribution = $request->getArray( 'author_attribution' );
			
			$this->makeAuthors( $authorsUsernames, $authorsAttribution );
			
			$this->makeLicenses( $request->getArray( 'license' ) );
		} else {

		}
	}
	
	public function getHtml() {
		
	}
	

	protected function makeAuthors( $authorsUsernames, $authorsAttribution ) {
		$usernames = array_values( $authorsUsernames );
		$attribution = array_values( $authorsAttribution );
		
		for ( $i = min( count( $usernames ), count( $attribution ) ); $i; $i-- ) {
			$username = trim( $usernames[$i] );
			$attribution = trim( $usernames[$i] );
			
			$user = User::newFromName( $username );
			if ( $user && $user->getId() ) {
				$id = $user->getId();
			} else {
				$id = null;
			}
			if ( !$id && !$attribution ) {
				continue;
			}
			if ( !$attribution ) {
				$attribution = null;
			}
			
			$this->authors[] = new FileAuthor( $id, $attribution );
		}
		
	}

	protected function makeLicenses( $licenseNames ) {
		$this->licenses = array_map( 'FileLicense::newFromName', 
			array_map( 'trim', $licenseNames ) );
		FileLicense::loadArrayFrom( $this->licenses, 'name' );
	}
	
}