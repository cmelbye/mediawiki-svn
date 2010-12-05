<?php

/**
 * Initialization file for the Distributed Semantic MediaWiki extension.
 * See https://secure.wikimedia.org/wikipedia/mediawiki/wiki/Extension:DSMW 
 * 
 * @file DSMW.php
 * @ingroup DSMW
 * 
 * @copyright 2009 INRIA-LORIA-ECOO project
 * 
 * @author jean-philippe muller & Morel Émile
 */

/**
 * This documenation group collects source code files belonging to DSMW.
 *
 * @defgroup DSMW Distributed Semantic MediaWiki
 */

if ( !defined( 'MEDIAWIKI' ) ) {
    die( 'Not a valid entry point.' );
}

require_once "$IP/includes/GlobalFunctions.php";
$wgDSMWIP = dirname( __FILE__ );

require_once( "$wgDSMWIP/includes/DSMWButton.php" );
require_once( "$wgDSMWIP/includes/Ajax/include.php" );

require_once 'includes/SemanticFunctions.php';
require_once 'includes/IntegrationFunctions.php';

define( 'DSMW_VERSION', '1.1 alpha' );

$wgSpecialPageGroups['ArticleAdminPage'] = 'dsmw_group';
$wgSpecialPageGroups['DSMWAdmin'] = 'dsmw_group';
$wgSpecialPageGroups['DSMWGeneralExhibits'] = 'dsmw_group';

$wgGroupPermissions['*']['upload_by_url'] = true;
$wgGroupPermissions['*']['reupload'] = true;
$wgGroupPermissions['*']['upload'] = true;

$wgAllowCopyUploads = true;

$wgExtensionMessagesFiles['DSMW'] = dirname( __FILE__ ) . '/DSMW.i18n.php';

$wgHooks['UnknownAction'][] = 'onUnknownAction';
// $wgHooks['MediaWikiPerformAction'][] = 'performAction';

$wgHooks['EditPage::attemptSave'][] = 'attemptSave';
$wgHooks['EditPageBeforeConflictDiff'][] = 'conflict';
$wgHooks['UploadComplete'][] = 'uploadComplete';

$wgAutoloadClasses['logootEngine'] = "$wgDSMWIP/logootComponent/logootEngine.php";
$wgAutoloadClasses['logoot'] = "$wgDSMWIP/logootComponent/logoot.php";
$wgAutoloadClasses['LogootId'] = "$wgDSMWIP/logootComponent/LogootId.php";
$wgAutoloadClasses['LogootPosition'] = "$wgDSMWIP/logootComponent/LogootPosition.php";
$wgAutoloadClasses['Diff1']
        = $wgAutoloadClasses['_DiffEngine1']
        = $wgAutoloadClasses['_DiffOp1']
        = $wgAutoloadClasses['_DiffOp_Add1']
        = $wgAutoloadClasses['_DiffOp_Change1']
        = $wgAutoloadClasses['_DiffOp_Copy1']
        = $wgAutoloadClasses['_DiffOp_Delete1']
        = "$wgDSMWIP/logootComponent/DiffEngine.php";

$wgAutoloadClasses['LogootIns'] = "$wgDSMWIP/logootComponent/LogootIns.php";
$wgAutoloadClasses['LogootDel'] = "$wgDSMWIP/logootComponent/LogootDel.php";
$wgAutoloadClasses['boModel'] = "$wgDSMWIP/logootModel/boModel.php";
$wgAutoloadClasses['dao'] = "$wgDSMWIP/logootModel/dao.php";
$wgAutoloadClasses['manager'] = "$wgDSMWIP/logootModel/manager.php";

$wgAutoloadClasses['Patch'] = "$wgDSMWIP/patch/Patch.php";
$wgAutoloadClasses['persistentClock'] = "$wgDSMWIP/clockEngine/persistentClock.php";
$wgAutoloadClasses['ApiQueryPatch'] = "$wgDSMWIP/api/ApiQueryPatch.php";
$wgAutoloadClasses['ApiQueryChangeSet'] = "$wgDSMWIP/api/ApiQueryChangeSet.php";
$wgAutoloadClasses['ApiUpload'] = "$wgDSMWIP/api/upload/ApiUpload.php";
$wgAutoloadClasses['ApiQueryImageInfo'] = "$wgDSMWIP/api/upload/ApiQueryImageInfo.php";

$wgAutoloadClasses['ApiPatchPush'] = "$wgDSMWIP/api/ApiPatchPush.php";
$wgAutoloadClasses['utils'] = "$wgDSMWIP/files/utils.php";
$wgAutoloadClasses['Math_BigInteger'] = "$wgDSMWIP/logootComponent/Math/BigInteger.php";
$wgAutoloadClasses['DSMWDBHelpers'] = "$wgDSMWIP/db/DSMWDBHelpers.php";

//// / Register Jobs
$wgJobClasses['DSMWUpdateJob'] = 'DSMWUpdateJob';
$wgAutoloadClasses['DSMWUpdateJob'] = "$wgDSMWIP/jobs/DSMWUpdateJob.php";
$wgJobClasses['DSMWPropertyTypeJob'] = 'DSMWPropertyTypeJob';
$wgAutoloadClasses['DSMWPropertyTypeJob'] = "$wgDSMWIP/jobs/DSMWPropertyTypeJob.php";

$wgAutoloadClasses['DSMWSiteId'] = "$wgDSMWIP/includes/DSMWSiteId.php";
$wgAutoloadClasses['DSMWExhibits'] = "$wgDSMWIP/includes/DSMWExhibits.php";

$wgExtensionFunctions[] = 'dsmwgSetupFunction';

$wgExtensionCredits[defined( 'SEMANTIC_EXTENSION_TYPE' ) ? 'semantic' : 'other'][] = array(
    'path' => __FILE__,
    'name' => 'Distributed&nbsp;Semantic&nbsp;MediaWiki',
    'version' => DSMW_VERSION,
    'author' => array(
		'[http://www.loria.fr/~mullejea Jean&ndash;Philippe&nbsp;Muller]',
		'[http://www.loria.fr/~molli Pascal&nbsp;Molli]',
		'[http://www.loria.fr/~skaf Hala&nbsp;Skaf&ndash;Molli]',
		'[http://www.loria.fr/~canals Gérôme&nbsp;Canals]',
		'[http://www.loria.fr/~rahalcha Charbel&nbsp;Rahal]',
		'[http://www.loria.fr/~weiss Stéphane&nbsp;Weiss]',
		'[http://m3p.gforge.inria.fr/pmwiki/pmwiki.php?n=Site.Team others]'
	),
    'url' => 'http://www.dsmw.org',
	'descriptionmsg' => 'dsmw-desc'
);

$queryModules = array(
	'patch' => 'ApiQueryPatch',
	'changeSet' => 'ApiQueryChangeSet',
	'patchPushed' => 'ApiPatchPush'
);

if ( compareMWVersion( $wgVersion ) == -1 ) {
    $wgApiQueryMetaModules = $queryModules; 
} else {
    $wgAPIMetaModules = $queryModules;
}

if ( compareMWVersion( $wgVersion, '1.16.0' ) == -1 ) {
    $wgAPIModules += array(
    	'upload' => 'ApiUpload',
        'ApiQueryImageInfo' => 'ApiQueryImageInfo',
    );
    
    $wgAutoloadLocalClasses['UploadBase'] = dirname( __FILE__ ) . '/api/upload/UploadBase.php';
    $wgAutoloadLocalClasses['UploadFromFile'] = dirname( __FILE__ ) . '/api/upload/UploadFromFile.php';
    $wgAutoloadLocalClasses['UploadFromStash'] = dirname( __FILE__ ) . '/api/upload/UploadFromStash.php';
    $wgAutoloadLocalClasses['UploadFromUrl'] = dirname( __FILE__ ) . '/api/upload/UploadFromUrl.php';
}



require_once dirname( __FILE__ ) . 'DSMW_Settings.php';
