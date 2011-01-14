<?php

class FileProperties {
	/**
	 * Constructor for the FileProperties class that represents proeprties
	 * associated with a file.
	 * 
	 * @param $file File
	 * @param $revision int Revision id
	 */
	public function __construct( $file, $revision = null ) {
		$this->file = file;
		$this->revId = $revision;
		
		$this->authors = array();
		$this->licenses = array();
		
		$this->load();
	}
	
	/**
	 * Load the properties for this file from the DB 
	 */
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
	
	/**
	 * Construct FileAuthor and FileProperties objects from a database result
	 */
	protected function loadFromResult( $res ) {
		$repo = $this->file->getRepo();
		$result = array();
		
		foreach ( $res as $row ) {
			switch ( $row->fp_key ) {
				case 'author':
					$this->authors[] = FileAuthor::newFromRow( $row );
					break;
				case 'license':
					$this->licenses[] = FileLicense::newFromRow( $repo, $row );
					break;
			}
		}
		
		if ( $this->licenses ) {
			FileLicense::loadArray( $this->licenses );
		}
	}
	
	/**
	 * Get all licenses associated with this file
	 */
	public function getLicenses() {
		return $this->licenses;
	}
	/**
	 * Get all authors associated with this file
	 */
	public function getAuthors() {
		return $this->authors;
	}
	
	/**
	 * Save the licenses and authors to the database
	 * 
	 * @param $comment string Edit summary
	 * @param $minor bool Minor edit
	 */
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
			
			$text = $author->getText();
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
	/**
	 * Construct a FileAuthor object from a database row
	 * 
	 * @param $row Stdclass 
	 * @return FileAuthor
	 */
	public static function newFromRow( $row ) {
		return new self( $row->fp_value_int, $row->fp_value_text );
	}
	/**
	 * Constructor for a FileAuthor object
	 * 
	 * @param $id int User id if any
	 * @param $text string User text
	 */
	public function __construct( $id, $text ) {
		$this->id = $id;
		$this->text = $text;	
	}
	/**
	 * Get the user id if any, 0 or null otherwise
	 * 
	 * @return int
	 */
	public function getUserId() {
		return $this->id;
	}
	/**
	 * Get the user text
	 * 
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}
}

class FileLicense {
	/**
	 * Initialize a license object from a row
	 * 
	 * @param $repo FileRepo
	 * @param $row Stdclass
	 * @return FileLicense
	 */
	public static function newFromRow( $repo, $row ) {
		$license = new self( $repo );
		$license->id = $row->fp_value_int;
		return $license;
	}
	/**
	 * Initialize a license object from a name
	 * 
	 * @param $repo FileRepo
	 * @param $name string
	 * @return FileLicense
	 */
	public static function newFromName( $repo, $name ) {
		$license = new self( $repo );
		$license->name = $name;
		return $license;
	}
	
	/**
	 * Constructor for the FileLicense 
	 * 
	 * @param $repo FileRepo
	 */
	public function __construct( $repo ) {
		$this->repo = $repo;
		$this->id = null;
		$this->name = null;
		$this->url = null;
		$this->count = 0;
	}
	public function getId() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
	public function setName( $name ) {
		$this->name = $name;
	}
	public function getUrl() {
		return $this->url;
	}
	public function setUrl( $url ) {
		$this->url = $url;
	}
	public function getCount() {
		return $this->count;
	}
	
	/**
	 * Initializes an uninitialized array of FileLicense objects from the db.
	 * Removes non-existent licenses from the array. Allows loading from id or
	 * name.
	 * 
	 * @param &$licenses array Array of FileLicense objects
	 * @param $loadFrom string id|name depending on from which field to load
	 */
	public static function loadArray( &$licenses, $loadFrom = 'id' ) {
		$licenseIds = array();
		foreach ( $licenses as $license ) {
			switch ( $loadFrom ) {
				case 'id':
					$licenseIds[] = $license->getId();
					break;
				case 'name':
					$licenseIds[] = $license->getName();
					break;
			}
			
		}
		if ( !$licenseIds ) {
			$licenses = array();
			return;
		}
		
		$dbr = $this->repo->getSlaveDb();
		$res = $dbr->select( 'licenses', 
			array( 'lic_id', 'lic_name', 'lic_url', 'lic_count' ),
			array( "lic_{$loadFrom}" => $licenseIds ),
			__METHOD__ );
		
		// Create an id<>row map
		$licenseData = array();
		foreach ( $res as $row ) {
			$licenseData[$row->lic_id] = $row;
		}
		// Initializes the licenses from the rows, removes non-existent licenses
		foreach ( $licenses as $key => $license ) {
			if ( isset( $licenseData[$license->getId()] ) ) {
				$license->loadFromLicenseRow( $licenseData[$license->getId()] );
			} else {
				unset( $licenses[$key] );
			}
		}
	}
	
	/**
	 * Initializes a license from a row.
	 * 
	 * @param $row Stdclass
	 */
	public function loadFromLicenseRow( $row ) {
		$this->name = $row->lic_name;
		$this->url = $row->lic_url;
		$this->count = $row->lic_count;
	}
	
	/**
	 * Inserts a new license into the database.
	 */
	public function insert() {
		$dbw = $this->repo->getMasterDb();
		$dbw->insert( 'licenses', array( 
			'lic_name' => $this->getName(),
			'lic_url' => $this->getUrl(),
			'lic_count' => 0 ),
			__METHOD__ );
		$this->id = $dbw->insertId();
	}
	/**
	 * Updates information for a license.
	 */
	public function update() {
		$dbw = $this->repo->getMasterDb();
		$dbw->update( 'licenses', array(
			'lic_name' => $this->getName(),
			'lic_url' => $this->getUrl(),
			'lic_count' => $this->count + 1 ),
			array( 'lic_id' => $this->id ),
			__METHOD__ );
	}
	/**
	 * Inserts a new license into the database if this license does not have
	 * an id, updates it otherwise
	 */
	public function save() {
		if ( $this->id ) {
			$this->update();
		} else {
			$this->insert();
		}
	}
}
