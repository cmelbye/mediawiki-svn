<?php
if ( !defined( 'MEDIAWIKI' ) ) { die( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" ); }
/**
 * An extension that adds test wiki features (such as a preference, recent changes for a test wiki, ...) specifically for the Wikimedia Incubator
 *
 * @file
 * @ingroup Extensions
 */

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Wikimedia Incubator',
	'author' => 'SPQRobin',
	'version' => '2.4',
	'url' => 'http://www.mediawiki.org/wiki/Extension:WikimediaIncubator',
	'descriptionmsg' => 'wminc-desc',
);

/* Config */
$wgGroupPermissions['*']['viewuserlang'] = false;
$wgGroupPermissions['sysop']['viewuserlang'] = true;

/* General (kind of globals) */
$wmincPref = 'incubatortestwiki'; // Name of the preference
$dir = dirname( __FILE__ ) . '/';
$wmincProjects = array(
	'Wikipedia' => 'p',
	'Wikibooks' => 'b',
	'Wiktionary' => 't',
	'Wikiquote' => 'q',
	'Wikinews' => 'n',
);
$wmincProjectSite = array(
	'name' => 'Incubator',
	'short' => 'inc',
);

$wgExtensionMessagesFiles['WikimediaIncubator'] = $dir . 'WikimediaIncubator.i18n.php';

/* Special:ViewUserLang */
$wgAutoloadClasses['SpecialViewUserLang'] = $dir . 'SpecialViewUserLang.php';
$wgSpecialPages['ViewUserLang'] = 'SpecialViewUserLang';
$wgSpecialPageGroups['ViewUserLang'] = 'users';
$wgAvailableRights[] = 'viewuserlang';
$wgHooks['ContributionsToolLinks'][] = 'efLoadViewUserLangLink';

/* TestWiki preference */
$wgAutoloadClasses['IncubatorTest'] = $dir . 'IncubatorTest.php';
$wgHooks['GetPreferences'][] = 'IncubatorTest::onGetPreferences';
$wgHooks['MagicWordwgVariableIDs'][] = 'IncubatorTest::magicWordVariable';
$wgHooks['LanguageGetMagic'][] = 'IncubatorTest::magicWord';
$wgHooks['ParserGetVariableValueSwitch'][] = 'IncubatorTest::magicWordValue';

/* Edit page */
$wgHooks['EditPage::showEditForm:initial'][] = 'IncubatorTest::editPageCheckPrefix';

/* Recent Changes */
$wgAutoloadClasses['TestWikiRC'] = $dir . 'TestWikiRC.php';
$wgHooks['SpecialRecentChangesQuery'][] = 'TestWikiRC::onRcQuery';
$wgHooks['SpecialRecentChangesPanel'][] = 'TestWikiRC::onRcForm';

/* Automatic pref on account creation */
$wgAutoloadClasses['AutoTestWiki'] = $dir . 'CreateAccountTestWiki.php';
$wgHooks['UserCreateForm'][] = 'AutoTestWiki::onUserCreateForm';
$wgHooks['AddNewAccount'][] = 'AutoTestWiki::onAddNewAccount';

/* Random page by test */
$wgAutoloadClasses['SpecialRandomByTest'] = $dir . 'SpecialRandomByTest.php';
$wgSpecialPages['RandomByTest'] = 'SpecialRandomByTest';
