<?php
/**
 * @file
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class InitEditCount extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'quick', 'Force the update to be done in a single query' );
		$this->addOption( 'background', 'Force replication-friendly mode; may be inefficient but
		avoids locking tables or lagging slaves with large updates;
		calculates counts on a slave if possible.

Background mode will be automatically used if the server is MySQL 4.0
(which does not support subqueries) or if multiple servers are listed
in $wgDBservers, usually indicating a replication environment.' );
		$this->mDescription = "Batch-recalculate user_editcount fields from the revision table";
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );
		$user = $dbw->tableName( 'user' );
		$revision = $dbw->tableName( 'revision' );

		$dbver = $dbw->getServerVersion();

		// Autodetect mode...
		$backgroundMode = count( $wgDBservers ) > 1 ||
			($dbw instanceof DatabaseMySql && version_compare( $dbver, '4.1' ) < 0);
	
		if( $this->hasOption('background') ) {
			$backgroundMode = true;
		} elseif( $this->hasOption('quick') ) {
			$backgroundMode = false;
		}

		if( $backgroundMode ) {
			$this->output( "Using replication-friendly background mode...\n" );

			$dbr = wfGetDB( DB_SLAVE );
			$chunkSize = 100;
			$lastUser = $dbr->selectField( 'user', 'MAX(user_id)', '', __METHOD__ );

			$start = microtime( true );
			$migrated = 0;
			for( $min = 0; $min <= $lastUser; $min += $chunkSize ) {
				$max = $min + $chunkSize;
				$result = $dbr->query(
					"SELECT
						user_id,
						COUNT(rev_user) AS user_editcount
					FROM $user
					LEFT OUTER JOIN $revision ON user_id=rev_user
					WHERE user_id > $min AND user_id <= $max
					GROUP BY user_id",
					__METHOD__ );

				while( $row = $dbr->fetchObject( $result ) ) {
					$dbw->update( 'user',
						array( 'user_editcount' => $row->user_editcount ),
						array( 'user_id' => $row->user_id ),
						__METHOD__ );
					++$migrated;
				}
				$dbr->freeResult( $result );

				$delta = microtime( true ) - $start;
				$rate = ($delta == 0.0) ? 0.0 : $migrated / $delta;
				$this->output( sprintf( "%s %d (%0.1f%%) done in %0.1f secs (%0.3f accounts/sec).\n",
					$wgDBname,
					$migrated,
					min( $max, $lastUser ) / $lastUser * 100.0,
					$delta,
					$rate ) );

				wfWaitForSlaves( 10 );
			}
		} else {
			// Subselect should work on modern MySQLs etc
			$this->output( "Using single-query mode...\n" );
			$sql = "UPDATE $user SET user_editcount=(SELECT COUNT(*) FROM $revision WHERE rev_user=user_id)";
			$dbw->query( $sql );
		}

		$this->output( "Done!\n" );
	}
}

$maintClass = "InitEditCount";
require_once( DO_MAINTENANCE );
