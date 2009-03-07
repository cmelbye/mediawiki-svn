<?php

/*
 * Created on Jun 30, 2007
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2007 Roan Kattouw <Firstname>.<Lastname>@home.nl
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ("ApiBase.php");
}


/**
 * API module that facilitates deleting pages. The API eqivalent of action=delete. 
 * Requires API write mode to be enabled.
 *
 * @addtogroup API
 */
class ApiDelete extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	/**
	 * Extracts the title, token, and reason from the request parameters and invokes
	 * the local delete() function with these as arguments. It does not make use of 
	 * the delete function specified by Article.php. If the deletion succeeds, the 
	 * details of the article deleted and the reason for deletion are added to the 
	 * result object.
	 */
	public function execute() {
		global $wgUser;
		$this->getMain()->requestWriteMode();
		$params = $this->extractRequestParams();
		
		$titleObj = NULL;
		if(!isset($params['title']))
			$this->dieUsageMsg(array('missingparam', 'title'));
		if(!isset($params['token']))
			$this->dieUsageMsg(array('missingparam', 'token'));

		$titleObj = Title::newFromText($params['title']);
		if(!$titleObj)
			$this->dieUsageMsg(array('invalidtitle', $params['title']));
		if(!$titleObj->exists())
			$this->dieUsageMsg(array('notanarticle'));

		$articleObj = new Article($titleObj);
		$reason = (isset($params['reason']) ? $params['reason'] : NULL);
		$dbw = wfGetDb(DB_MASTER);
		$dbw->begin();
		$retval = self::delete($articleObj, $params['token'], 	$reason);
		
		if(!empty($retval))
			// We don't care about multiple errors, just report one of them
			$this->dieUsageMsg(reset($retval));

		$dbw->commit();
		$r = array('title' => $titleObj->getPrefixedText(), 'reason' => $reason);
		$this->getResult()->addValue(null, $this->getModuleName(), $r);
	}

	/**
	 * We have our own delete() function, since Article.php's implementation is split in two phases
	 *
	 * @param Article $article - Article object to work on
	 * @param string $token - Delete token (same as edit token)
	 * @param string $reason - Reason for the deletion. Autogenerated if NULL
	 * @return Title::getUserPermissionsErrors()-like array
	 */
	public static function delete(&$article, $token, &$reason = NULL)
	{
		global $wgUser;

		// Check permissions
		$errors = $article->mTitle->getUserPermissionsErrors('delete', $wgUser);
		if(!empty($errors))
			return $errors;
		if(wfReadOnly())
			return array(array('readonlytext'));
		if($wgUser->isBlocked())
			return array(array('blocked'));

		// Check token
		if(!$wgUser->matchEditToken($token))
			return array(array('sessionfailure'));

		// Auto-generate a summary, if necessary
		if(is_null($reason))
		{
			$reason = $article->generateReason($hasHistory);
			if($reason === false)
				return array(array('cannotdelete'));
		}

		// Luckily, Article.php provides a reusable delete function that does the hard work for us
		if($article->doDeleteArticle($reason))
			return array();
		return array(array('cannotdelete', $article->mTitle->getPrefixedText()));
	}
	
	public function mustBePosted() { return true; }
	
	public function getAllowedParams() {
		return array (
			'title' => null,
			'token' => null,
			'reason' => null,
		);
	}

	public function getParamDescription() {
		return array (
			'title' => 'Title of the page you want to delete.',
			'token' => 'A delete token previously retrieved through prop=info',
			'reason' => 'Reason for the deletion. If not set, an automatically generated reason will be used.'
		);
	}

	public function getDescription() {
		return array(
			'Deletes a page. You need to be logged in as a sysop to use this function, see also action=login.'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=delete&title=Main%20Page&token=123ABC',
			'api.php?action=delete&title=Main%20Page&token=123ABC&reason=Preparing%20for%20move'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
