<?

#__________________________
#wiki class

class MediaWikiType {

	function main_action () {
		global $wgTitle , $wgArticle , $wgRequest , $wgOut , $wgServer ;
		global $wgDisableInternalSearch , $wgUseCategoryMagic , $wgDisabledActions , $action ;
		wfProfileIn( 'main-action' );

		if( !$wgDisableInternalSearch && !is_null( $this->mSearch ) && $this->mSearch !== '' ) {
			require_once( 'includes/SpecialSearch.php' );
			$wgTitle = Title::makeTitle( NS_SPECIAL, 'Search' );
			wfSpecialSearch();
		} else if( !$wgTitle or $wgTitle->getDBkey() == '' ) {
			$wgTitle = Title::newFromText( wfMsgForContent( 'badtitle' ) );
			$wgOut->errorpage( 'badtitle', 'badtitletext' );
		} else if ( $wgTitle->getInterwiki() != '' ) {
			if( $rdfrom = $wgRequest->getVal( 'rdfrom' ) ) {
				$url = $wgTitle->getFullURL( 'rdfrom=' . urlencode( $rdfrom ) );
			} else {
				$url = $wgTitle->getFullURL();
			}
			# Check for a redirect loop
			if ( !preg_match( '/^' . preg_quote( $wgServer, '/' ) . '/', $url ) && $wgTitle->isLocal() ) {
				$wgOut->redirect( $url );
			} else {
				$wgTitle = Title::newFromText( wfMsgForContent( 'badtitle' ) );
				$wgOut->errorpage( 'badtitle', 'badtitletext' );
			}
		} else if ( ( $action == 'view' ) &&
			(!isset( $_GET['title'] ) || $wgTitle->getPrefixedDBKey() != $_GET['title'] ) &&
			!count( array_diff( array_keys( $_GET ), array( 'action', 'title' ) ) ) )
		{
			/* redirect to canonical url, make it a 301 to allow caching */
			$wgOut->setSquidMaxage( 1200 );
			$wgOut->redirect( $wgTitle->getFullURL(), '301');
		} else if ( NS_SPECIAL == $wgTitle->getNamespace() ) {
			# actions that need to be made when we have a special pages
			SpecialPage::executePath( $wgTitle );
		} else {
			if ( NS_MEDIA == $wgTitle->getNamespace() ) {
				$wgTitle = Title::makeTitle( NS_IMAGE, $wgTitle->getDBkey() );
			}
		
			$ns = $wgTitle->getNamespace();
		
			// Namespace might change when using redirects
			if($action == 'view' && !$wgRequest->getVal( 'oldid' ) ) {
				$wgArticle = new Article( $wgTitle );
				$rTitle = Title::newFromRedirect( $wgArticle->fetchContent() );
				if($rTitle) {
					# Reload from the page pointed to later
					$wgArticle->mContentLoaded = false;
					$ns = $rTitle->getNamespace();
				}
			}

			// Categories and images are handled by a different class
			if ( $ns == NS_IMAGE ) {
				unset($wgArticle);
				require_once( 'includes/ImagePage.php' );
				$wgArticle = new ImagePage( $wgTitle );
			} elseif ( $wgUseCategoryMagic && $ns == NS_CATEGORY ) {
				unset($wgArticle);
				require_once( 'includes/CategoryPage.php' );
				$wgArticle = new CategoryPage( $wgTitle );
			}
		
			if ( in_array( $action, $wgDisabledActions ) ) {
				$wgOut->errorpage( 'nosuchaction', 'nosuchactiontext' );
			} else {
				$act = 'act_' . $action ;
				if ( method_exists ( $this , $act ) ) {
					$this->$act ( $action ) ;
				} else {
					$this->action_unknown ( $action ) ;
				}
			}
		}

		wfProfileOut( 'main-action' );
	}

#____________________________________________________________________________________
#Action methods
	
	function act_view ( $action ) {
		global $wgOut , $wgSquidMaxage , $wgArticle ;
		$wgOut->setSquidMaxage( $wgSquidMaxage );
		$wgArticle->view();
	}
	
	function act_print ( $action ) {
		global $wgArticle ;
		$wgArticle->view () ;
	}
	
	function act_dublincore ( $action ) {
		global $wgArticle , $wgEnableDublinCoreRdf ;
		if( !$wgEnableDublinCoreRdf ) {
			wfHttpError( 403, 'Forbidden', wfMsg( 'nodublincore' ) );
		} else {
			require_once( 'includes/Metadata.php' );
			wfDublinCoreRdf( $wgArticle );
		}
	}
	
	function act_creativecommons ( $action ) { 
		global $wgArticle , $wgEnableCreativeCommonsRdf ;
		if( !$wgEnableCreativeCommonsRdf ) {
			wfHttpError( 403, 'Forbidden', wfMsg('nocreativecommons') );
		} else {
			require_once( 'includes/Metadata.php' );
			wfCreativeCommonsRdf( $wgArticle );
		}
	}
	
	function act_credits ( $action ) { 
		global $wgArticle ;
		require_once( 'includes/Credits.php' );
		showCreditsPage( $wgArticle );
	}
	
	function act_submit ( $action ) { 
		global $wgCommandLineMode , $wgRequest ;
		if( !$wgCommandLineMode && !$wgRequest->checkSessionCookie() ) {
			# Send a cookie so anons get talk message notifications
			User::SetupSession();
		}
		$this->act_edit ( $action ) ;
	}
	
	function act_edit ( $action ) { 
		global $wgRequest , $wgUseExternalEditor , $wgUser , $wgArticle ;
		$internal = $wgRequest->getVal( 'internaledit' );
		$external = $wgRequest->getVal( 'externaledit' );
		$section = $wgRequest->getVal( 'section' );
		$oldid = $wgRequest->getVal( 'oldid' );
		if(!$wgUseExternalEditor || $action=='submit' || $internal ||
			$section || $oldid || (!$wgUser->getOption('externaleditor') && !$external)) {
			require_once( 'includes/EditPage.php' );
			$editor = new EditPage( $wgArticle );
			$editor->submit();
		} elseif($wgUseExternalEditor && ($external || $wgUser->getOption('externaleditor'))) {
			require_once( 'includes/ExternalEdit.php' );
			$mode = $wgRequest->getVal( 'mode' );
			$extedit = new ExternalEdit( $wgArticle, $mode );
			$extedit->edit();
		}
	}
	
	function act_history ( $action ) { 
		global $wgTitle , $wgArticle , $wgSquidMaxage ;
		if ($_SERVER['REQUEST_URI'] == $wgTitle->getInternalURL('action=history')) {
			$wgOut->setSquidMaxage( $wgSquidMaxage );
		}
		require_once( 'includes/PageHistory.php' );
		$history = new PageHistory( $wgArticle );
		$history->history();
	}
	
	function act_raw ( $action ) { 
		global $wgArticle ;
		require_once( 'includes/RawPage.php' );
		$raw = new RawPage( $wgArticle );
		$raw->view();
	}
	
	
	function action_unknown ( $action ) {
		global $wgArticle , $wgOut ;
		if (wfRunHooks('UnknownAction', array($action, $wgArticle))) {
			$wgOut->errorpage( 'nosuchaction', 'nosuchactiontext' );
		}
	}
	
	
	function act_watch ( $action ) { $this->article_action ( $action ) ; } 
	function act_unwatch ( $action ) { $this->article_action ( $action ) ; } 
	function act_delete ( $action ) { $this->article_action ( $action ) ; } 
	function act_revert ( $action ) { $this->article_action ( $action ) ; } 
	function act_rollback ( $action ) { $this->article_action ( $action ) ; } 
	function act_protect ( $action ) { $this->article_action ( $action ) ; } 
	function act_unprotect ( $action ) { $this->article_action ( $action ) ; } 
	function act_info ( $action ) { $this->article_action ( $action ) ; } 
	function act_markpatrolled ( $action ) { $this->article_action ( $action ) ; } 
	function act_validate ( $action ) { $this->article_action ( $action ) ; } 
	function act_render ( $action ) { $this->article_action ( $action ) ; } 
	function act_deletetrackback ( $action ) { $this->article_action ( $action ) ; } 
	function act_purge ( $action ) { $this->article_action ( $action ) ; } 

	function article_action ( $action ) {
		global $wgArticle ;
		$wgArticle->$action() ;
	}

	
} ; # end of class MediaWikiType

?>

