<?php
/**
 * Implements Special:UserLogin
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

class SpecialUserLogin extends SpecialPage {

	var $mReturnTo, $mReturnToQuery;

	public $mDomains = array(); # set by hooks
	public $mFormHeader = ''; # Can be filled by hooks etc
	
	public $mFormFields = array(
		'Name' => array(
			'type'          => 'text',
			'label-message' => 'yourname',
			'id'            => 'wpName1',
			'tabindex'      => '1',
			'size'          => '20',
			'required'      => '1',
		),
		'Password' => array(
			'type'          => 'password',
			'label-message' => 'yourpassword',
			'size'          => '20',
			'id'            => 'wpPassword1',
		),
		'Domain' => array(
			'type'          => 'select',
			'id'            => 'wpDomain',
			'label-message' => 'yourdomainname',
			'options'       => null,
			'default'       => null, 
		),
		'Remember' => array(
			'type'          => 'check',
			'label'         => ''/* set in constructor */,
			'id'            => 'wpRemember',
		)
	);

	public function __construct(){
		parent::__construct( 'Userlogin' );
		
		global $wgLang, $wgCookieExpiration, $wgRequest, $wgRedirectOnLogin;
		
		$this->mFormFields['Remember']['label'] = wfMsgExt( 
			'remembermypassword', 
			'parseinline', 
			$wgLang->formatNum( ceil( $wgCookieExpiration / 86400 ) ) 
		);
		
		$this->mReturnTo = $wgRequest->getVal( 'returnto' );
		$this->mReturnToQuery = $wgRequest->getVal( 'returntoquery' );	
		
		if ( $wgRedirectOnLogin ) {
			$this->mReturnTo = $wgRedirectOnLogin;
			$this->mReturnToQuery = '';
		}
		
		# When switching accounts, it sucks to get automatically logged out
		$returnToTitle = Title::newFromText( $this->mReturnTo );
		if( $returnToTitle instanceof Title && $returnToTitle->isSpecial( 'Userlogout' ) ) {
			$this->mReturnTo = '';
			$this->mReturnToQuery = '';
		}
	}

	function execute( $par ) {
		global $wgRequest, $wgOut;

		# Pre-1.17, CreateAccount was at Special:UserLogin/signup
		if( $par == 'signup' || $wgRequest->getText( 'type' ) == 'signup' ){
			$sp = new SpecialCreateAccount();
			$sp->execute( $par );
			return;
		}
		
		$wgOut->setPageTitle( wfMsg( 'login' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
		$wgOut->disallowUserJs();  # Stop malicious userscripts sniffing passwords
		
		$form = $this->getForm();
		
		$form->show();
	}
	
	public function formFilterCallback( $data ){
		global $wgRequest, $wgEnableEmail;
		$data['mailpassword'] = $wgRequest->getCheck( 'wpMailmypassword' ) 
			&& $wgEnableEmail;
		return $data;
	}
	
	public function formSubmitCallback( $data ){
		if ( $data['mailpassword'] ) {
			return $this->showMailPage( $data );
		} else {
			return $this->processLogin( $data );
		}
	}

	/**
	 * Show the main login form
	 * @param $msg String a message key for a warning/error message
	 * that may have been generated on a previous iteration
	 */
	protected function getForm() {
		global $wgUser, $wgOut, $wgRequest, $wgEnableEmail;
		global $wgCookiePrefix, $wgLoginLanguageSelector;
		global $wgAuth, $wgCookieExpiration;

		# Preload the name field with something if we can
		if ( $wgUser->isLoggedIn() ) {
			$username = $wgUser->getName();
		} elseif( isset( $_COOKIE[$wgCookiePrefix.'UserName'] ) ) {
			$username = $_COOKIE[$wgCookiePrefix.'UserName'];
		} else {
			$username = false;
		}
		if( $username ){
			$this->mFormFields['Name']['default'] = $username;
			$this->mFormFields['Password']['autofocus'] = '1';
		} else {
			$this->mFormFields['Name']['autofocus'] = '1';
		}

		# Make sure the returnTo strings don't get lost if the
		# user changes language, etc
		$linkq = array();
		if ( !empty( $this->mReturnTo ) ) {
			$linkq['returnto'] = wfUrlencode( $this->mReturnTo );
			if ( !empty( $this->mReturnToQuery ) )
				$linkq['returntoquery'] = wfUrlencode( $this->mReturnToQuery );
		}

		# Pass any language selection on to the mode switch link
		if( $wgLoginLanguageSelector && $wgRequest->getText( 'uselang' ) )
			$linkq['uselang'] = $wgRequest->getText( 'uselang' );

		$skin = $wgUser->getSkin();
		$link = $skin->link( 
			SpecialPage::getTitleFor( 'CreateAccount' ),
			wfMsgHtml( 'nologinlink' ),
			array(),
			$linkq );

		# Don't show a "create account" link if the user can't
		$link = $wgUser->isAllowed( 'createaccount' ) && !$wgUser->isLoggedIn()
			? wfMsgExt( 'nologin', array('parseinline','replaceafter'), $link )
			: '';

		# Prepare language selection links as needed
		$langSelector = $wgLoginLanguageSelector 
			? Html::rawElement( 
				'div',
				array( 'id' => 'languagelinks' ),
				self::makeLanguageSelector( $this->getTitle(), $this->mReturnTo ) )
			: '';

		# Give authentication and captcha plugins a chance to 
		# modify the form, by hook or by using $wgAuth
		//$wgAuth->modifyUITemplate( $this, 'login' );
		wfRunHooks( 'UserLoginForm', array( &$this ) );
	
		# The most likely use of the hook is to enable domains;
		# check that now, and add fields if necessary
		if( $this->mDomains ){
			$this->mFormFields['Domain']['options'] = $this->mDomains;
			$this->mFormFields['Domain']['default'] = $this->mDomain;
		} else {
			unset( $this->mFormFields['Domain'] );
		}
		
		# Or to tweak the 'remember my password' checkbox
		if( !($wgCookieExpiration > 0) ){
			# Remove it altogether
			unset( $this->mFormFields['Remember'] );
		} elseif( $wgUser->getOption( 'rememberpassword' ) ){
			# Or check it by default
			# FIXME: this doesn't always work?
			$this->mFormFields['Remember']['default'] = '1';
		}
		
		$this->mFormFields['Token'] = array(
			'type' => 'hidden',
			'default' => Token::get( 'login' ),
		);
		
		$form = new HTMLForm( $this->mFormFields, '' );
		$form->setTitle( $this->getTitle() );
		$form->setSubmitText( wfMsg( 'login' ) );
		$form->setSubmitId( 'wpLoginAttempt' );
		$form->suppressReset();
		$form->setWrapperLegend( wfMsg( 'userlogin' ) );
		$form->setTokenAction( 'login' );
		
		$form->addHiddenField( 'returnto', $this->mReturnTo );
		$form->addHiddenField( 'returntoquery', $this->mReturnToQuery );
		if( $wgRequest->getText( 'uselang' ) ){
			$form->addHiddenField( 'uselang', $wgRequest->getText( 'uselang' ) );
		}
		
		$form->addHeaderText( ''
			. Html::rawElement( 'p', array( 'id' => 'userloginlink' ),
				$link )
			. Html::rawElement( 'div', array( 'id' => 'userloginprompt' ),
				wfMsgExt( 'loginprompt', array( 'parseinline' ) ) )
			. $this->mFormHeader
			. $langSelector
		);
		$form->addPreText( ''
			. Html::rawElement( 
				'div', 
				array( 'id' => 'loginstart' ), 
				wfMsgExt( 'loginstart', array( 'parseinline' ) )
			)
		);
		$form->addPostText(
			Html::rawElement( 
				'div', 
				array( 'id' => 'loginend' ), 
				wfMsgExt( 'loginend', array( 'parseinline' ) )
			)
		);
		
		# Add a  'mail reset' button if available
		if( $wgEnableEmail && $wgAuth->allowPasswordChange() ){
			$form->addButton(
				'wpMailmypassword',
				wfMsg( 'mailmypassword' ),
				'wpMailmypassword'
			);
		}
		
		$form->setFilterCallback( array( $this, 'formFilterCallback' ) );
		$form->setSubmitCallback( array( $this, 'formSubmitCallback' ) );
		$form->loadData();
		
		return $form;
	}

	/**
	 * Produce a bar of links which allow the user to select another language
	 * during login/registration but retain "returnto"
	 * @param $title Title to use in the link
	 * @param $returnTo query string to append
	 * @return String HTML for bar
	 */
	public static function makeLanguageSelector( $title, $returnTo=false ) {
		global $wgLang;

		$msg = wfMsgForContent( 'loginlanguagelinks' );
		if( $msg != '' && !wfEmptyMsg( 'loginlanguagelinks', $msg ) ) {
			$langs = explode( "\n", $msg );
			$links = array();
			foreach( $langs as $lang ) {
				$lang = trim( $lang, '* ' );
				$parts = explode( '|', $lang );
				if (count($parts) >= 2) {
					$links[] = self::makeLanguageSelectorLink( 
							$parts[0], $parts[1], $title, $returnTo );
				}
			}
			return count( $links ) > 0 
				? wfMsgHtml( 'loginlanguagelabel', $wgLang->pipeList( $links ) ) 
				: '';
		} else {
			return '';
		}
	}

	/**
	 * Create a language selector link for a particular language
	 * Links back to this page preserving type and returnto
	 * @param $text Link text
	 * @param $lang Language code
	 * @param $title Title to link to
	 * @param $returnTo String returnto query
	 */
	public static function makeLanguageSelectorLink( $text, $lang, $title, $returnTo=false ) {
		global $wgUser;
		$attr = array( 'uselang' => $lang );
		if( $returnTo )
			$attr['returnto'] = $returnTo;
		$skin = $wgUser->getSkin();
		return $skin->linkKnown(
			$title,
			htmlspecialchars( $text ),
			array(),
			$attr
		);
	}

	/**
	 * Display a "login successful" page.
	 * @param $message String message key of main message to display
	 * @param $html String HTML to optionally add
	 * @param $returnto Title to returnto
	 * @param $returntoQuery String query string for returnto link
	 */
	public static function displaySuccessfulLogin( $message, $html='', $returnTo=false, $returnToQuery=false ) {
		global $wgOut, $wgUser;
		
		$wgOut->setPageTitle( wfMsg( 'loginsuccesstitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
		$wgOut->addWikiMsg( $message, $wgUser->getName() );
		$wgOut->addHTML( $html );

		if ( $returnTo ) {
			$wgOut->returnToMain( null, $returnTo, $returnToQuery );
		} else {
			$wgOut->returnToMain( null );
		}
	}

	/**
	 * Display any messages generated by hooks, or HTTP redirect to
	 * $this->mReturnTo (or Main Page if that's undefined).  Formerly we had a
	 * nice message here, but that's not as useful as just being sent to
	 * wherever you logged in from.  It should be clear that the action was
	 * successful, given the lack of error messages plus the appearance of your
	 * name in the upper right.
	 * 
	 * Remember that this function can be accessed from a variety of 
	 * places, such as Special:ResetPass, or Special:CreateAccount.
	 * @param $message String message key of a message to display if
	 *   we don't redirect
	 * @param $returnTo String title of page to redirect to
	 * @param $returnToQuery String query string to add to the redirect.
	 * @param $html String empty string to go straight 
	 *   to the redirect, or valid HTML to add underneath the text.
	 */
	public static function successfulLogin( $message, $returnTo='', $returnToQuery='', $html='' ) {
		global $wgUser, $wgOut;

		if( $html === '' ) {
			$titleObj = Title::newFromText( $returnTo );
			if ( !$titleObj instanceof Title ) {
				$titleObj = Title::newMainPage();
			}
			$wgOut->redirect( $titleObj->getFullURL( $returnToQuery ) );
		} else {
			SpecialUserLogin::displaySuccessfulLogin( $message, $html, $returnTo, $returnToQuery );
		}
	}
	
	/**
	 * Try and login with the data provided, and react appropriately.
	 * @param $data Array from HTMLForm
	 * @return Mixed Bool true, or String error
	 */
	protected function processLogin( $data ){
		global $wgUser;
		
		$login = new Login( $data );
		$result = $login->attemptLogin();

		switch ( $result ) {
			case Login::SUCCESS:
				Token::clear( 'login' );
				# Replace the language object to provide user interface in
				# correct language immediately on this first page load.  Note
				# that this only has any effect if we display a login splash
				# screen.
				global $wgLang, $wgRequest, $wgOut;
				$code = $wgRequest->getVal( 'uselang', $wgUser->getOption( 'language' ) );
				$wgLang = Language::factory( $code );
				$wgOut->addHTML( self::successfulLogin( 
					'loginsuccess', 
					$this->mReturnTo, 
					$this->mReturnToQuery,
					$login->mLoginResult 
				) );
				return true;
				
			case Login::RESET_PASS:
				Token::clear( 'login' );
				# 'Shell out' to Special:ResetPass to get the user to 
	 			# set a new permanent password from a temporary one.
				$reset = new SpecialResetpass();
				$msg = wfMsgExt( 'resetpass_announce', 'parseinline' );
				$reset->getForm(true)->displayForm( $msg );
				return true;
				
			case Login::CREATE_BLOCKED:
			 	# Be nice about this, it's likely that this feature will be used
			 	# for blocking large numbers of innocent people, e.g. range blocks on
			 	# schools. Don't blame it on the user. There's a small chance that it
			 	# really is the user's fault, i.e. the username is blocked and they
			 	# haven't bothered to log out before trying to create an account to
			 	# evade it, but we'll leave that to their guilty conscience to figure
			 	# out.
			 	global $wgOut, $wgUser;
			 	$wgOut->setPageTitle( wfMsg( 'cantcreateaccounttitle' ) );
			 	$wgOut->setRobotPolicy( 'noindex,nofollow' );
			 	$wgOut->setArticleRelated( false );
			 	
			 	$ip = wfGetIP();
			 	$blocker = User::whoIs( $wgUser->mBlock->mBy );
			 	$blockReason = $wgUser->mBlock->mReason;
			 	
			 	if ( strval( $blockReason ) === '' ) {
			 		$blockReason = wfMsg( 'blockednoreason' );
			 	}
			 	$wgOut->addWikiMsg( 'cantcreateaccount-text', $ip, $blockReason, $blocker );
			 	$wgOut->returnToMain( false );
				return true;

			case Login::NO_NAME:
			case Login::ILLEGAL:
			case Login::WRONG_PLUGIN_PASS:
			case Login::WRONG_PASS:
			case Login::EMPTY_PASS:
			case Login::THROTTLED:
				return wfMsgExt( $login->mLoginResult, 'parseinline' );
				
			case Login::NOT_EXISTS:
				if( $wgUser->isAllowed( 'createaccount' ) ){
					return wfMsgExt( 'nosuchuser', 'parseinline', htmlspecialchars( $data['Name'] ) );
				} else {
					return wfMsgExt( 'nosuchusershort', 'parseinline', htmlspecialchars( $data['Name'] ) );
				}
				
			case Login::USER_BLOCKED:
				return wfMsgExt( 'login-userblocked', 'parseinline', $login->getUser()->getName() );
				
			case Login::ABORTED: 
				$msg = $login->mLoginResult 
					? $login->mLoginResult 
					: $login->mCreateResult;
				return wfMsgExt( $msg, 'parseinline' );
				
			default:
				throw new MWException( "Unhandled case value: $result" );
		}
	}

	/**
	 * Attempt to send the user a password-reset mail, and return
	 * the results (good, bad or ugly).
	 * @param $data Array from HTMLForm
	 * @return Mixed Bool true on success, String HTML on failure
	 */
	protected function showMailPage( $data ){
		global $wgOut;
		$login = new Login( $data );
		$result = $login->mailPassword();

		switch( $result ){
			
			case Login::SUCCESS:
				Token::clear( 'login' );
				$wgOut->addWikiMsg( 'passwordsent', $login->getUser()->getName() );
				return true;
				
			case Login::READ_ONLY : 
				$wgOut->readOnlyPage();
				return true;
				
			case Login::MAIL_PING_THROTTLED: 
				$wgOut->rateLimited();
				return true;
				
			case Login::MAIL_PASSCHANGE_FORBIDDEN:
				return wfMsgExt( 'resetpass_forbidden', 'parseinline' );
				
			case Login::MAIL_BLOCKED: 
				return wfMsgExt( 'blocked-mailpassword', 'parseinline' );
				
			case Login::USER_BLOCKED:
				return wfMsgExt( 'login-userblocked-reset', 'parseinline' );

			case Login::MAIL_PASS_THROTTLED: 
				global $wgPasswordReminderResendTime;
				# Round the time in hours to 3 d.p., in case someone 
				# is specifying minutes or seconds.
				return wfMsgExt( 
					'throttled-mailpassword', 
					array( 'parsemag' ),
					round( $wgPasswordReminderResendTime, 3 )
				);
				
			case Login::NO_NAME: 
				return wfMsgExt( 'noname', 'parseinline' );
				
			case Login::NOT_EXISTS: 
				return wfMsgWikiHtml( 'nosuchuser', htmlspecialchars( $login->getUser()->getName() ) );
				
			case Login::MAIL_EMPTY_EMAIL: 
				return wfMsgExt( 'noemail', 'parseinline', $login->getUser()->getName() );

			case Login::MAIL_BAD_IP: 
				return wfMsgExt( 'badipaddress', 'parseinline' );

			case Login::MAIL_ERROR: 
				return wfMsgExt( 'mailerror', 'parseinline', $login->mMailResult->getMessage() );
			
			default:
				throw new MWException( "Unhandled case value: $result" );
		}
	}
	
	/**
	 * Add text to the header.  Only write to $mFormHeader directly  
	 * if you're determined to overwrite anything that other 
	 * extensions might have added.
	 * @param $text String HTML
	 */
	public function addFormHeader( $text ){
		$this->mFormHeader .= $text;
	}

	/**
	 * Since the UserLoginForm hook was changed to pass a SpecialPage
	 * instead of a QuickTemplate derivative, old extensions might
	 * easily try calling this method expecing it to exist.  Tempting
	 * though it is to let them have the fatal error, let's at least
	 * fail gracefully...
	 * @deprecated
	 */
	public function set(){
		wfDeprecated( __METHOD__ );
	}
}
