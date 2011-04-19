<?php
/**
 * Special:Gadgets, provides a preview of MediaWiki:Gadgets.
 *
 * @file
 * @ingroup SpecialPage
 * @author Daniel Kinzler, brightbyte.de
 * @copyright © 2007 Daniel Kinzler
 * @license GNU General Public License 2.0 or later
 */

if( !defined( 'MEDIAWIKI' ) ) {
	echo( "not a valid entry point.\n" );
	die( 1 );
}

/**
 *
 */
class SpecialGadgets extends SpecialPage {

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct( 'Gadgets', '', true );
	}

	/**
	 * Main execution function
	 * @param $par Parameters passed to the page
	 */
	function execute( $par ) {
		// $parts = explode( '/', $par );
		// if ( count( $parts ) == 2 && $parts[0] == 'export' ) {
			// $this->showExportForm( $parts[1] );
		// } else {
			$view = new MainGadgetsView( $this );
		// }
		$this->setHeaders();
		$this->getContext()->getOutput()->setPageTitle( $view->getTitle()->text() );

		if ( $this->getContext()->getRequest()->wasPosted() ) {
			$view->post();
		} else {
			$view->execute();
		}
	}
}
