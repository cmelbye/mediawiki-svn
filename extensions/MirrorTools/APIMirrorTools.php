<?php

/**
 * Created on August 16, 2007
 *
 * API for MediaWiki 1.8+
 *
 * Copyright © 2007 Iker Labarga <Firstname><Lastname>@gmail.com
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
 */

/**
 * A module that allows for editing and creating pages.
 *
 * Currently, this wraps around the EditPage class in an ugly way,
 * EditPage.php should be rewritten to provide a cleaner interface
 * @ingroup API
 */
class ApiMirrorEditPage extends ApiEditPage {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName );
	}

	public function execute() {
		global $wgUser;
		$params = $this->extractRequestParams();
                
		if ( is_null( $params['user'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'user' ) );
		}
		$user = $params['user'];

		if ( is_null( $params['title'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'title' ) );
		}

		if ( is_null( $params['text'] ) && is_null( $params['appendtext'] ) &&
				is_null( $params['prependtext'] ) &&
				$params['undo'] == 0 )
		{
			$this->dieUsageMsg( array( 'missingtext' ) );
		}

		$titleObj = Title::newFromText( $params['title'] );
		if ( !$titleObj || $titleObj->isExternal() ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
		}

		// Some functions depend on $wgTitle == $ep->mTitle
		global $wgTitle;
		$wgTitle = $titleObj;

		if ( $params['createonly'] && $titleObj->exists() ) {
			$this->dieUsageMsg( array( 'createonly-exists' ) );
		}
		if ( $params['nocreate'] && !$titleObj->exists() ) {
			$this->dieUsageMsg( array( 'nocreate-missing' ) );
		}

		// Now let's check whether we're even allowed to do this
		$errors = $titleObj->getUserPermissionsErrors( 'mirroredit', $wgUser );
		if ( !$titleObj->exists() ) {
			$errors = array_merge( $errors, $titleObj->getUserPermissionsErrors( 'create', $wgUser ) );
		}
		if ( count( $errors ) ) {
			$this->dieUsageMsg( $errors[0] );
		}

		$articleObj = new Article( $titleObj );
		$toMD5 = $params['text'];
		if ( !is_null( $params['appendtext'] ) || !is_null( $params['prependtext'] ) )
		{
			// For non-existent pages, Article::getContent()
			// returns an interface message rather than ''
			// We do want getContent()'s behavior for non-existent
			// MediaWiki: pages, though
			if ( $articleObj->getID() == 0 && $titleObj->getNamespace() != NS_MEDIAWIKI ) {
				$content = '';
			} else {
				$content = $articleObj->getContent();
			}

			if ( !is_null( $params['section'] ) ) {
				// Process the content for section edits
				global $wgParser;
				$section = intval( $params['section'] );
				$content = $wgParser->getSection( $content, $section, false );
				if ( $content === false ) {
					$this->dieUsage( "There is no section {$section}.", 'nosuchsection' );
				}
			}
			$params['text'] = $params['prependtext'] . $content . $params['appendtext'];
			$toMD5 = $params['prependtext'] . $params['appendtext'];
		}

		if ( $params['undo'] > 0 ) {
			if ( $params['undoafter'] > 0 ) {
				if ( $params['undo'] < $params['undoafter'] ) {
					list( $params['undo'], $params['undoafter'] ) =
					array( $params['undoafter'], $params['undo'] );
				}
				$undoafterRev = Revision::newFromID( $params['undoafter'] );
			}
			$undoRev = Revision::newFromID( $params['undo'] );
			if ( is_null( $undoRev ) || $undoRev->isDeleted( Revision::DELETED_TEXT ) )
			{
				$this->dieUsageMsg( array( 'nosuchrevid', $params['undo'] ) );
			}

			if ( $params['undoafter'] == 0 ) {
				$undoafterRev = $undoRev->getPrevious();
			}
			if ( is_null( $undoafterRev ) || $undoafterRev->isDeleted( Revision::DELETED_TEXT ) )
			{
				$this->dieUsageMsg( array( 'nosuchrevid', $params['undoafter'] ) );
			}

			if ( $undoRev->getPage() != $articleObj->getID() ) {
				$this->dieUsageMsg( array( 'revwrongpage', $undoRev->getID(), $titleObj->getPrefixedText() ) );
			}
			if ( $undoafterRev->getPage() != $articleObj->getID() ) {
				$this->dieUsageMsg( array( 'revwrongpage', $undoafterRev->getID(), $titleObj->getPrefixedText() ) );
			}

			$newtext = $articleObj->getUndoText( $undoRev, $undoafterRev );
			if ( $newtext === false ) {
				$this->dieUsageMsg( array( 'undo-failure' ) );
			}
			$params['text'] = $newtext;
			// If no summary was given and we only undid one rev,
			// use an autosummary
			if ( is_null( $params['summary'] ) && $titleObj->getNextRevisionID( $undoafterRev->getID() ) == $params['undo'] )
			{
				$params['summary'] = wfMsgForContent( 'undo-summary', $params['undo'], $undoRev->getUserText() );
			}
		}

		// See if the MD5 hash checks out
		if ( !is_null( $params['md5'] ) && md5( $toMD5 ) !== $params['md5'] ) {
			$this->dieUsageMsg( array( 'hashcheckfailed' ) );
		}

		$ep = new MirrorEditPage( $articleObj );
		// EditPage wants to parse its stuff from a WebRequest
		// That interface kind of sucks, but it's workable
		$reqArr = array(
			'wpTextbox1' => $params['text'],
			'wpEditToken' => $params['token'],
			'wpIgnoreBlankSummary' => ''
		);

		if ( !is_null( $params['summary'] ) ) {
			$reqArr['wpSummary'] = $params['summary'];
		}

		// Watch out for basetimestamp == ''
		// wfTimestamp() treats it as NOW, almost certainly causing an edit conflict
		if ( !is_null( $params['basetimestamp'] ) && $params['basetimestamp'] != '' )
		{
			$reqArr['wpEdittime'] = wfTimestamp( TS_MW, $params['basetimestamp'] );
		} else {
			$reqArr['wpEdittime'] = $articleObj->getTimestamp();
		}

		if ( !is_null( $params['starttimestamp'] ) && $params['starttimestamp'] != '' ) {
			$reqArr['wpStarttime'] = wfTimestamp( TS_MW, $params['starttimestamp'] );
		} else {
			$reqArr['wpStarttime'] = $reqArr['wpEdittime'];	// Fake wpStartime
		}

		if ( $params['minor'] || ( !$params['notminor'] && $wgUser->getOption( 'minordefault' ) ) )	{
			$reqArr['wpMinoredit'] = '';
		}

		if ( $params['recreate'] ) {
			$reqArr['wpRecreate'] = '';
		}

		if ( !is_null( $params['section'] ) ) {
			$section = intval( $params['section'] );
			if ( $section == 0 && $params['section'] != '0' && $params['section'] != 'new' )
			{
				$this->dieUsage( "The section parameter must be set to an integer or 'new'", "invalidsection" );
			}
			$reqArr['wpSection'] = $params['section'];
		} else {
			$reqArr['wpSection'] = '';
		}

		$watch = $this->getWatchlistValue( $params['watchlist'], $titleObj );

		// Deprecated parameters
		if ( $params['watch'] ) {
			$watch = true;
		} elseif ( $params['unwatch'] ) {
			$watch = false;
		}

		if ( $watch ) {
			$reqArr['wpWatchthis'] = '';
		}

		$req = new FauxRequest( $reqArr, true );
		$ep->importFormData( $req );

		// Run hooks
		// Handle CAPTCHA parameters
		global $wgRequest;
		if ( !is_null( $params['captchaid'] ) ) {
			$wgRequest->setVal( 'wpCaptchaId', $params['captchaid'] );
		}
		if ( !is_null( $params['captchaword'] ) ) {
			$wgRequest->setVal( 'wpCaptchaWord', $params['captchaword'] );
		}

		$r = array();
		if ( !wfRunHooks( 'APIEditBeforeSave', array( $ep, $ep->textbox1, &$r ) ) )
		{
			if ( count( $r ) ) {
				$r['result'] = 'Failure';
				$this->getResult()->addValue( null, $this->getModuleName(), $r );
				return;
			} else {
				$this->dieUsageMsg( array( 'hookaborted' ) );
			}
		}

		// Do the actual save
		$oldRevId = $articleObj->getRevIdFetched();
		$result = null;
		// Fake $wgRequest for some hooks inside EditPage
		// FIXME: This interface SUCKS
		$oldRequest = $wgRequest;
		$wgRequest = $req;

		$retval = $ep->mirrorinternalAttemptSave( $result, $wgUser->isAllowed( 'bot' ) && $params['bot'], $user );
		$wgRequest = $oldRequest;
		switch( $retval ) {
			case EditPage::AS_HOOK_ERROR:
			case EditPage::AS_HOOK_ERROR_EXPECTED:
				$this->dieUsageMsg( array( 'hookaborted' ) );

			case EditPage::AS_IMAGE_REDIRECT_ANON:
				$this->dieUsageMsg( array( 'noimageredirect-anon' ) );

			case EditPage::AS_IMAGE_REDIRECT_LOGGED:
				$this->dieUsageMsg( array( 'noimageredirect-logged' ) );

			case EditPage::AS_SPAM_ERROR:
				$this->dieUsageMsg( array( 'spamdetected', $result['spam'] ) );

			case EditPage::AS_FILTERING:
				$this->dieUsageMsg( array( 'filtered' ) );

			case EditPage::AS_BLOCKED_PAGE_FOR_USER:
				$this->dieUsageMsg( array( 'blockedtext' ) );

			case EditPage::AS_MAX_ARTICLE_SIZE_EXCEEDED:
			case EditPage::AS_CONTENT_TOO_BIG:
				global $wgMaxArticleSize;
				$this->dieUsageMsg( array( 'contenttoobig', $wgMaxArticleSize ) );

			case EditPage::AS_READ_ONLY_PAGE_ANON:
				$this->dieUsageMsg( array( 'noedit-anon' ) );

			case EditPage::AS_READ_ONLY_PAGE_LOGGED:
				$this->dieUsageMsg( array( 'noedit' ) );

			case EditPage::AS_READ_ONLY_PAGE:
				$this->dieReadOnly();

			case EditPage::AS_RATE_LIMITED:
				$this->dieUsageMsg( array( 'actionthrottledtext' ) );

			case EditPage::AS_ARTICLE_WAS_DELETED:
				$this->dieUsageMsg( array( 'wasdeleted' ) );

			case EditPage::AS_NO_CREATE_PERMISSION:
				$this->dieUsageMsg( array( 'nocreate-loggedin' ) );

			case EditPage::AS_BLANK_ARTICLE:
				$this->dieUsageMsg( array( 'blankpage' ) );

			case EditPage::AS_CONFLICT_DETECTED:
				$this->dieUsageMsg( array( 'editconflict' ) );

			// case EditPage::AS_SUMMARY_NEEDED: Can't happen since we set wpIgnoreBlankSummary
			case EditPage::AS_TEXTBOX_EMPTY:
				$this->dieUsageMsg( array( 'emptynewsection' ) );

			case EditPage::AS_SUCCESS_NEW_ARTICLE:
				$r['new'] = '';
			case EditPage::AS_SUCCESS_UPDATE:
				$r['result'] = 'Success';
				$r['pageid'] = intval( $titleObj->getArticleID() );
				$r['title'] = $titleObj->getPrefixedText();
				// HACK: We create a new Article object here because getRevIdFetched()
				// refuses to be run twice, and because Title::getLatestRevId()
				// won't fetch from the master unless we select for update, which we
				// don't want to do.
				$newArticle = new Article( $titleObj );
				$newRevId = $newArticle->getRevIdFetched();
				if ( $newRevId == $oldRevId ) {
					$r['nochange'] = '';
				} else {
					$r['oldrevid'] = intval( $oldRevId );
					$r['newrevid'] = intval( $newRevId );
					$r['newtimestamp'] = wfTimestamp( TS_ISO_8601,
						$newArticle->getTimestamp() );
				}
				break;

			case EditPage::AS_END:
				// This usually means some kind of race condition
				// or DB weirdness occurred. 
				if ( is_array( $result ) && count( $result ) > 0 ) {
					$this->dieUsageMsg( array( 'unknownerror', $result[0][0] ) );					
				}
				
				// Unknown error, but no specific error message
				// Fall through
			default:
				$this->dieUsageMsg( array( 'unknownerror', $retval ) );
		}
		$this->getResult()->addValue( null, $this->getModuleName(), $r );
	}

	protected function getDescription() {
		return 'Create and edit pages using any username.';
	}
	
	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'missingparam', 'user' ),
		) );
	}

	protected function getAllowedParams() {
		return array_merge( array( 'user' => null ), parent::getAllowedParams() );
	}

	protected function getParamDescription() {
		return array_merge( array( 'user' => 'Username' ), parent::getParamDescription() );
	}

	protected function getExamples() {
		return array(
			'Edit a page (anonymous user):',
			'    api.php?action=edit&title=Test&summary=test%20summary&text=article%20content&basetimestamp=20070824123454&token=%2B\\',
			'Prepend __NOTOC__ to a page (anonymous user):',
			'    api.php?action=edit&title=Test&summary=NOTOC&minor&prependtext=__NOTOC__%0A&basetimestamp=20070824123454&token=%2B\\',
			'Undo r13579 through r13585 with autosummary (anonymous user):',
			'    api.php?action=edit&title=Test&undo=13585&undoafter=13579&basetimestamp=20070824123454&token=%2B\\',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiMirrorEditPage.php 68353 2010-06-21 13:13:32Z hartman $';
	}
}