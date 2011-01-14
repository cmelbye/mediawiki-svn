<?php

class FileProperties {
	public function __construct( $file, $revision = null ) {
		$this->file = file;
		$this->revId = $revision;
		
		$this->authors = array();
		$this->licenses = array();
		
		$this->load();
	}
	
	public function load() {
		$revision = Revision::newFromTitle( $this->file->getTitle(), $this->revId );
		$fp = $revision->getFilePropsVersion();
		if ( $fp ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'file_props',
				array( 'fp_key', 'fp_value_int', 'fp_value_tex' ),
				array( 'fp_id' => $fp ), 
				__METHOD__ );
			$this->loadFromResult( $res );
		}
	}
	
	public function loadFromResult( $res ) {
		$result = array();
		
		foreach ( $res as $row ) {
			switch ( $row->fp_key ) {
				case 'author':
					$this->authors[] = FileAuthor::newFromRow( $row );
					break;
				case 'license':
					$this->licenses[] = FileLicense::newFromRow( $row );
					break;
			}
		}
		
		if ( $this->licenses ) {
			FileLicense::loadArray( $this->licenses );
		}
	}
	
	public function getLicenses() {
		return $this->licenses;
	}
	public function getAuthors() {
		return $this->authors;
	}
}

class FileAuthor {
	public static function newFromRow( $row ) {
		return new self( $row->fp_value_int, $row->fp_value_text );
	}
	
	public function __construct( $id, $text ) {
		$this->id = $id;
		$this->text = $text;	
	}
	public function getUserId() {
		return $this->id;
	}
	public function getText() {
		if ( $this->text ) {
			return $this->text;
		}
		return User::newFromId( $this->id )->getName();
	}
}

class FileLicense {
	public static function newFromRow( $row ) {
		return new self( $row->fp_value_int );
	}
	
	public function __construct( $id ) {
		$this->id = $id;
	}
	
}
