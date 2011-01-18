<?php

class FileProperties {
	public static $propertyClasses = array( 
		'license' => 'FileLicense', 
		'author' => 'FileAuthor',
	);
	
	/**
	 * Constructor for the FileProperties class that represents proeprties
	 * associated with a file.
	 * 
	 * @param $file File
	 * @param $revision int Revision id
	 */
	public function __construct( $file, $revision = null ) {
		$this->file = $file;
		$this->revId = $revision;
		
		$this->props = array();
		$this->propsByType = array();
		
		$this->load();
	}
	
	/**
	 * Load the properties for this file from the DB 
	 */
	public function load() {
		if ( !$this->revId ) {
			// Load from the latest revision by default
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
		foreach ( $res as $row ) {
			if ( isset( self::$propertyClasses[$row->fp_key] ) ) {
				// Call the constructor
				$prop = call_user_func( self::$propertyClasses[$row->fp_key] . 
					'::newFromRow', $this->file, $row );
				
				$this->props[] = $prop;
				if ( !isset( $this->propsByType[$row->fp_key] ) ) {
					$this->propsByType[$row->fp_key] = array();
				}
				$this->propsByType[$row->fp_key][] = $prop;
			}
		}
		
		foreach ( self::$propertyClasses as $key => $class ) {
			// Batch load properties
			if ( !empty( $this->propsByType[$key] ) ) {
				call_user_func( "$class::loadArray", $this->propsByType[$key] );
			}
		}
	}
	
	/**
	 * Get all properties of $type, or all if null or omitted
	 * 
	 * @param $type mixed 
	 * @return array
	 */
	public function get( $type = null ) {
		if ( is_null( $type ) ) {
			return $this->props;
		}
		return isset( $this->propsByType[$type] ) ? 
			$this->propsByType[$type] : array();
	}
	
	/**
	 * Deleted all properties and sets them to the passed array
	 * 
	 * @param $props array
	 */
	public function set( $props ) {
		$this->props = array();
		$this->propsByType = array();
		
		$this->append( $props );
	}
	/**
	 * Appends an array of properties to the current properties
	 * 
	 * @param $props array
	 */
	public function append( $props ) {
		foreach ( $props as $prop ) {
			$this->props[] = $prop;
			
			$key = $prop->getKey();
			if ( !isset( $this->propsByType[$key] ) ) {
				$this->propsByType = array();
			}
			$this->propsByType[$key] = $prop;
		}
	}

	/**
	 * Save the properties to the database and create a new revision
	 * 
	 * @param $comment string Edit summary
	 * @param $minor bool Minor edit
	 */	
	public function saveWithNewRevision( $comment, $minor = false ) {
		$dbw = $this->file->getRepo()->getMasterDB();
		
		$rev = Revision::newNullRevision( $dbw, 
			$this->file->getTitle()->getArticleId( Article::GAID_FOR_UPDATE ),
			$comment, $minor );
		$rev->insertOn( $dbw );
		
		$this->save( $rev->getId() );		
	}
	
	/**
	 * Save the licenses and authors to the database associated with a certain
	 * revision
	 * 
	 * @param $revId int Revision id
	 */
	public function save( $revId ) {
		$dbw = $this->file->getRepo()->getMasterDB();
		
		$insert = array();
		foreach ( $this->props as $prop ) {
			$insert[] = array( 
				'fp_rev_id' => $revId,
				'fp_key' => $prop->getKey(),
				'fp_value_int' => $prop->getIntValue(),
				'fp_value_string' => $prop->getStringValue(),
			);
		}
		
		$dbw->insert( 'file_props', $insert, __METHOD__ );
		
		$this->revId = $revId;
	}
}

/**
 * Base class for file properties
 */
abstract class FileProperty {
	/**
	 * Create a new property object from a row
	 * 
	 * @param $file File
	 * @param $row Stdclass
	 */
	public static function newFromRow( $file, $row ) {
		throw new MWException( __CLASS__ . ' does not implement newFromRow' );
	}
	/**
	 * Load any other information for an array of properties
	 * 
	 * @param &$array array
	 */
	public static function loadArray( &$array ) {}
	/**
	 * Get the key name
	 * 
	 * @return string
	 */
	abstract public function getKey();
	/**
	 * Get the string value; null by default
	 * 
	 * @return string|null
	 */
	public function getStringValue() {
		return null;
	}
	/**
	 * Get the int value; null by default
	 * 
	 * @return int|null
	 */
	public function getIntValue() {
		return null;
	}
	/**
	 * Sets the value
	 * 
	 * @param $value int|string
	 */
	abstract public function setValue( $value );
}

/**
 * FileAuthor class that represents the author of a file; possibly a wiki user
 */
class FileAuthor extends FileProperty {
	/**
	 * Construct a FileAuthor object from a database row
	 * 
	 * @param $file File
	 * @param $row Stdclass 
	 * @return FileAuthor
	 */
	public static function newFromRow( $file, $row ) {
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
	public function getIntValue() {
		return $this->id;
	}
	/**
	 * Get the user text
	 * 
	 * @return string
	 */
	public function getStringValue() {
		return $this->text;
	}
	
	public function getKey() { 
		return 'author';
	}
	
	/**
	 * Sets the name or user id of the author. Changing either the id or the name
	 * does not change the other property
	 * 
	 * @param $value string 
	 */
	public function setValue( $value ) {
		if ( is_string( $value ) ) {
			$this->name = $value;
		} elseif ( is_int( $value ) ) {
			$this->id = $value;
		}
	}
}

/**
 * FileLicense class that represents the license of a file and provides access
 * to the extended license attributes
 */
class FileLicense extends FileProperty {
	/**
	 * Initialize a license object from a row
	 * 
	 * @param $file File
	 * @param $row Stdclass
	 * @return FileLicense
	 */
	public static function newFromRow( $file, $row ) {
		$license = new self( $file->getRepo() );
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
		$this->exists = null;
	}
	public function getKey() {
		return 'license';
	}
	/**
	 * Tries to load the license id and returns it
	 * 
	 * @return int|null
	 */
	public function getIntValue() {
		if ( is_null( $this->id ) ) {
			$this->load();
		}
		return $this->id;
	}
	/**
	 * Tries to load the license name and returns it
	 * 
	 * @return string|null
	 */
	public function getStringValue() {
		if ( is_null( $this->name ) ) {
			$this->load();
		}
		return $this->name;
	}
	/**
	 * Sets the license name or id and sets the other property to null
	 */
	public function setValue( $value ) {
		$this->exists = null;
		if ( is_string( $value ) ) {
			$this->id = null;
			$this->name = $value;
		} elseif ( is_int( $value ) ) {
			$this->id = $value;
			$this->name = null;
		}
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
	public function incrementCount( $inc ) {
		$this->count += $inc;
	}
	public function exists() {
		return $this->exists;
	}
	
	public function load() {
		if ( !is_null( $this->exists ) ) {
			// Already loaded
			return;
		}
		
		$a = array( $this );
		if ( !is_null( $this->id ) ) {
			$from = 'id';
		} elseif ( !is_null( $this->name ) ) {
			$from = 'name';
		} else {
			throw new MWException( 'Trying to load bogus license' );
		}
		self::loadArrayFrom( $a, $from );
		
		$this->exists = (bool)count( $a );
	}
	
	public static function loadArray( &$licenses ) {
		self::loadArrayFrom( $licenses, 'id' );
	}

	/**
	 * Initializes an uninitialized array of FileLicense objects from the db.
	 * Removes non-existent licenses from the array. Allows loading from id or
	 * name.
	 * 
	 * @param &$licenses array Array of FileLicense objects
	 * @param $loadFrom string id|name depending on from which field to load
	 */
	public static function loadArrayFrom( &$licenses, $loadFrom ) {
		$licenseIds = array();
		foreach ( $licenses as $license ) {
			switch ( $loadFrom ) {
				case 'id':
					$licenseIds[] = $license->getIntValue();
					break;
				case 'name':
					$licenseIds[] = $license->getStringValue();
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
		$this->exists = true;
	}
	
	/**
	 * Inserts a new license into the database.
	 */
	public function insert() {
		$dbw = $this->repo->getMasterDb();
		$dbw->insert( 'licenses', array( 
			'lic_name' => $this->getStringValue(),
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
			'lic_name' => $this->getStringValue(),
			'lic_url' => $this->getUrl(),
			'lic_count' => $this->count ),
			array( 'lic_id' => $this->id ),
			__METHOD__ );
	}
	/**
	 * Inserts a new license into the database if this license does not have
	 * an id, updates it otherwise. This updates the properties of this 
	 * license, as opposed to changing the license for this file. If you want
	 * to change the license of this file, you need to use FileProperties::save
	 */
	public function save() {
		if ( $this->id ) {
			$this->update();
		} else {
			$this->insert();
		}
	}
	
	/**
	 * Returns a key suitable for wfMsg() to get the license text
	 * 
	 * @return string
	 */
	public function getLicenseTextKey () {
		return "License-{$this->name}-text";
	}
	/**
	 * Returns a key suitabale for wfMsg() to get the license title
	 * 
	 * @return string
	 */
	public function getLicenseTitleKey () {
		return "License-{$this->name}-title";
	}
}
