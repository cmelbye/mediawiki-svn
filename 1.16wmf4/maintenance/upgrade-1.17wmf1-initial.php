<?php

require( dirname( __FILE__ ) . '/commandLine.inc' );

doAllSchemaChanges();

function doAllSchemaChanges() {
	global $wgLBFactoryConf, $wgConf;

	$sectionLoads = $wgLBFactoryConf['sectionLoads'];
	$sectionsByDB = $wgLBFactoryConf['sectionsByDB'];
	$rootPass = trim( wfShellExec( '/home/wikipedia/bin/mysql_root_pass' ) );

	// Compile wiki lists
	$wikisBySection = array();
	foreach ( $wgConf->getLocalDatabases() as $wiki ) {
		if ( isset( $sectionsByDB[$wiki] ) ) {
			$wikisBySection[$sectionsByDB[$wiki]][] = $wiki;
		} else {
			$wikisBySection['DEFAULT'][] = $wiki;
		}
	}

	// Do the upgrades
	foreach ( $sectionLoads as $section => $loads ) {
		$master = true;
		foreach ( $loads as $server => $load ) {
			if ( $master ) {
				echo "Skipping $section master $server\n";
				$master = false;
				continue;
			}

			$db = new DatabaseMysql(
				$server,
				'root',
				$rootPass,
				false, /* dbName */
				0, /* flags, no transactions */
				'' /* prefix */
			);

			foreach ( $wikisBySection[$section] as $wiki ) {
				$db->selectDB( $wiki );
				upgradeWiki( $db );
				while ( $db->getLag() > 10 ) {
					echo "Waiting for $server to catch up to master.\n";
					sleep( 60 );
				}
			}
		}
	}

	echo "All done (except masters).\n";
}

function upgradeWiki( $db ) {
	$wiki = $db->getDBname();
	$server = $db->getServer();

	$upgradeLogRow = $db->selectRow( 'updatelog',
		'ul_key',
		array( 'ul_key' => '1.17wmf1-initial' ),
		__FUNCTION__ );
	if ( $upgradeLogRow ) {
		echo $db->getDBname() . ": already done\n";
		return;
	}

	echo "$server $wiki 1.17wmf1-initial";

	sourceUpgradeFile( $db, dirname( __FILE__ ) .'/schema-changes-1.17wmf1-initial.sql' );

	if ( $wiki === 'commonswiki' ) {
		echo " GlobalUsage";
		sourceUpgradeFile( $db, dirname(__FILE__).'/../extensions/GlobalUsage/patches/' .
			'patch-globalimagelinks_wiki_nsid_title.sql' );
	}

	if ( $wiki === 'mediawikiwiki' ) {
		echo " CodeReview";
		sourceUpgradeFile( $db, dirname(_FILE__).'/../extensions/CodeReview/archives/' . 
			'code_signoffs.sql' );
	}

	$db->insert( 'updatelog', 
		array( 'ul_key' => '1.17wmf1-initial' ),
		__FUNCTION__ );
	echo " ok\n";
}

function sourceUpgradeFile( $db, $file ) {
	if ( !file_exists( $file ) ) {
		echo "File missing: $file\n";
		exit( 1 );
	}
	$db->sourceFile( $file );
}


