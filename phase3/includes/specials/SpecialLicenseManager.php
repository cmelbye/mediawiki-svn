<?php
/**
 * Implements Special:LicenseManager
 *
 * Copyright © 2011 Roan Kattouw
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * This class is used to list and edit licenses
 *
 * @ingroup SpecialPage
 */
class SpecialLicenseManager extends SpecialPage {

	function __construct() {
		parent::__construct( 'LicenseManager' );
	}

	function execute( $par ) {
		// TODO: edit through Special:LicenseManager/123
		// TODO: table instead of ul/li
		// TODO: edit/delete restrictions
		// TODO: keep Special:LicenseManager for write actions, move list to separate special page
		// TODO: rm legal URL, add text of corresponding MW msgs
		global $wgRequest, $wgOut;
		$this->setHeaders();
		$pager = new LicensePager( $this );
		$wgOut->addHTML( $pager->getBody() );
	}
}

class LicensePager extends AlphabeticPager {
	protected $mSpecialPage;
	
	public function __construct( $specialpage ) {
		parent::__construct();
		$this->mSpecialPage = $specialpage;
	}
	public function getQueryInfo() {
		return array(
			'tables' => 'license',
			'fields' => array( 'lic_id', 'lic_name', 'lic_url', 'lic_count' ),
		);
	}
	
	public function getIndexField() {
		return 'lic_name';
	}
	
	public function getStartBody() {
		return "<ul>";
	}
	
	public function getEndBody() {
		return "</ul>";
	}
	
	public function formatRow( $row ) {
		global $wgUser, $wgLang;
		$sk = $wgUser->getSkin();
		$editlink = $sk->link( $this->mSpecialPage->getTitle(),
			wfMsg( 'licensemanager-edit-link' ),
			array(),
			array( 'edit' => $row->lic_id )
		);
		$deletelink = $sk->link( $this->mSpecialPage->getTitle(),
			wfMsg( 'licensemanager-delete-link' ),
			array(),
			array( 'delete' => $row->lic_id )
		);
		$name = htmlspecialchars( $row->lic_name );
		$urlLink = $sk->makeExternalLink( $row->lic_url, wfMsg( 'licensemanager-url-link' ) );
		$count = wfMsg( 'licensemanager-filecount', $wgLang->formatNum( $row->lic_count ) );
		return "<li>" . wfMsgHtml( 'licensemanager-row', $name, $urlLink, $count, $editlink, $deletelink ) . "</li>";
	}
}