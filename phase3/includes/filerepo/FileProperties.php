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
		if ( !$this->revId ) {
			$this->revId = $this->file->getTitle()->getLatestRevID();
		}

		$dbr = $this->file->getRepo()->getSlaveDB();
		$res = $dbr->select( 'file_props',
			array( 'fp_key', 'fp_value_int', 'fp_value_text' ),
			array( 'fp_rev_id' => $this->revId ), 
			__METHOD__ );
		$this->loadFromResult( $res );
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
	
	public function save( $comment, $minor = false ) {
		$dbw = $this->file->getRepo()->getMasterDB();
		
		$rev = Revision::newNullRevision( $dbw, 
			$this->file->getTitle()->getArticleId( Article::GAID_FOR_UPDATE ),
			$comment, $minor );
		$rev->insertOn( $dbw );
		
		$id = $rev->getId();
		
		$insert = array();
		foreach ( $this->authors as $author ) {
			$a = array( 
				'fp_rev_id' => $id,
				'fp_key' => 'author',
				'fp_value_int' => $author->getId()
			);
			
			$text = $author->getRawText();
			if ( $text ) {
				$a['fp_value_text'] = $text;
			}
			$insert[] = $a;
		}
		foreach ( $this->licenses as $license ) {
			$insert[] = array(
				'fp_rev_id' => $id,
				'fp_key' => 'license',
				'fp_value_int' => $license->getId(),
			);
		}
		
		
		$dbw->insert( 'file_props', $insert, __METHOD__ );
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
	public function getRawText() {
		return $this->text;
	}
}

class FileLicense {
	public static function newFromRow( $row ) {
		return new self( $row->fp_value_int );
	}
	
	public function __construct( $id ) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
	public function getUrl() {
		return $this->url;
	}
	public function getCount() {
		return $this->count;
	}
	
	public static function loadArray( &$licenses ) {
		$licenseIds = array();
		foreach ( $licenses as $license ) {
			$licenseIds[] = $license->getId();
		}
		
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'licenses', 
			array( 'lic_id', 'lic_name', 'lic_url', 'lic_count' ),
			array( 'lic_id' => $licenseIds ),
			__METHOD__ );
			
		$licenseData = array();
		foreach ( $res as $row ) {
			$licenseData[$row->lic_id] = $row;
		}
		foreach ( $licenses as $key => $license ) {
			if ( isset( $licenseData[$license->getId()] ) ) {
				$license->loadFromLicenseRow( $licenseData[$license->getId()] );
			} else {
				unset( $licenses[$key] );
			}
		}
	}
	
	
	public function loadFromLicenseRow( $row ) {
		$this->name = $row->lic_name;
		$this->url = $row->lic_url;
		$this->count = $row->lic_count;
	}
	
}
