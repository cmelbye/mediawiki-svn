<?php

/**
 * File defining the settings for the Semantic Watchlist extension.
 * More info can be found at http://www.mediawiki.org/wiki/Extension:Semantic_Watchlist#Settings
 *
 *                          NOTICE:
 * Changing one of these settings can be done by copying or cutting it,
 * and placing it in LocalSettings.php, AFTER the inclusion of this extension.
 *
 * @file SemanticWatchlist.Settings.php
 * @ingroup SemanticWatchlist
 *
 * @licence GNU GPL v3+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

# Users that can use the semantic watchlist.
$wgGroupPermissions['*'            ]['semanticwatch'] = false;
$wgGroupPermissions['user'         ]['semanticwatch'] = true;
$wgGroupPermissions['autoconfirmed']['semanticwatch'] = true;
$wgGroupPermissions['bot'          ]['semanticwatch'] = false;
$wgGroupPermissions['sysop'        ]['semanticwatch'] = true;

# Users that can modify the watchlist groups via Special:WatchlistConditions
$wgGroupPermissions['*'            ]['semanticwatchgroups'] = false;
$wgGroupPermissions['user'         ]['semanticwatchgroups'] = false;
$wgGroupPermissions['autoconfirmed']['semanticwatchgroups'] = false;
$wgGroupPermissions['bot'          ]['semanticwatchgroups'] = false;
$wgGroupPermissions['sysop'        ]['semanticwatchgroups'] = true;

# Enable email notification or not?
$egSWLEnableEmailNotify = true;
