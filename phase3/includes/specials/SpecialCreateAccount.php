<?php
/**
 * Special page for creating/registering new user accounts.
 * @ingroup SpecialPage
 */
class SpecialCreateAccount extends SpecialPage {

	var $mUsername, $mPassword, $mRetype, $mReturnTo, $mPosted;
	var $mCreateaccountMail, $mEmail, $mDomain, $mLanguage;
	var $mReturnToQuery;

	public $mDomains = array();
	
	public $mUseEmail = true; # Can be switched off by AuthPlugins etc
	
	public $mFormHeader = '';
	public $mFormFields = array(
		'Name' => array(
			'type'          => 'text',
			'label-message' => 'yourname',
			'id'            => 'wpName2',
			'tabindex'      => '1',
			'size'          => '20',
			'required'      => '1',
			'autofocus'     => '',
		),
		'Password' => array(
			'type'          => 'password',
			'label-message' => 'yourpassword',
			'size'          => '20',
			'id'            => 'wpPassword2',
			'required'      => '',
		),
		'Retype' => array(
			'type'          => 'password',
			'label-message' => 'yourpasswordagain',
			'size'          => '20',
			'id'            => 'wpRetype',
			'required'      => '',
		),
		'Email' => array(
			'type'          => 'email',
			'label-message' => 'youremail',
			'size'          => '20',
			'id'            => 'wpEmail',
		),
		'RealName' => array(
			'type'          => 'text',
			'label-message' => 'yourrealname',
			'id'            => 'wpRealName',
			'size'          => '20',
		),
		'Remember' => array(
			'type'          => 'check',
			'id'            => 'wpRemember',
		),
		'Domain' => array(
			'type'          => 'select',
			'id'            => 'wpDomain',
			'label-message' => 'yourdomainname',
			'options'       => null,
			'default'       => null, 
		),
	);
	
	public function __construct(){
		parent::__construct( 'CreateAccount', 'createaccount' );
		
		$this->mFormFields['RealName']['label-help'] = 'prefs-help-realname';
		$this->mFormFields['Retype']['validation-callback'] = array( 'SpecialCreateAccount', 'formValidateRetype' );
	
		
		global $wgCookieExpiration, $wgLang;
		$this->mFormFields['Remember']['label'] = wfMsgExt( 
			'remembermypassword', 
			'parseinline', 
			$wgLang->formatNum( ceil( $wgCookieExpiration / 86400 ) ) 
		);
		
		global $wgRequest, $wgRedirectOnLogin;
		$this->mCreateaccountMail = $wgRequest->getCheck( 'wpCreateaccountMail' )
			&& $wgEnableEmail;
		
		$this->mReturnTo = $wgRequest->getVal( 'returnto' );
		$this->mReturnToQuery = $wgRequest->getVal( 'returntoquery' );
		$this->mLanguage = $wgRequest->getText( 'uselang' );
		
		if ( $wgRedirectOnLogin ) {
			$this->mReturnTo = $wgRedirectOnLogin;
			$this->mReturnToQuery = '';
		}

		# When switching accounts, it sucks to get automatically logged out
		$returnToTitle = Title::newFromText( $this->mReturnTo );
		if( is_object( $returnToTitle ) && $returnToTitle->isSpecial( 'Userlogout' ) ) {
			$this->mReturnTo = '';
			$this->mReturnToQuery = '';
		}
	}
	
	public function execute( $par ){
		global $wgUser, $wgOut;
		
		$this->setHeaders();
		$wgOut->disallowUserJs();  # Stop malicious userscripts sniffing passwords
				
		# Block signup here if in readonly. Keeps user from 
		# going through the process (filling out data, etc) 
		# and being informed later.
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		} 
		# Bail out straightaway on permissions errors
		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		} elseif ( $wgUser->isBlockedFromCreateAccount() ) {
			$this->userBlockedMessage();
			return;
		} elseif ( count( $permErrors = $this->getTitle()->getUserPermissionsErrors( 'createaccount', $wgUser, true ) )>0 ) {
			$wgOut->showPermissionsErrorPage( $permErrors, 'createaccount' );
			return;
		}
		
		$form = $this->getForm();
		$form->show();
	}
	
	/**
	 * Check that the user actually managed to type the password 
	 * in the same both times
	 * @param unknown_type $data
	 * @param unknown_type $alldata
	 */
	public static function formValidateRetype( $retype, $alldata ){
		# blank == blank, but the 'this field is required' validation
		# will catch that.
		if( $retype === '' ){
			return true;
		}

		# The other password field could be 'Password' (Special:CreateAccount)
		# or 'NewPassword' (Special:ResetPass).
		if( isset( $alldata['Password'] ) ){
			$password = $alldata['Password'];
		} elseif ( isset( $alldata['NewPassword'] ) ){
			$password = $alldata['NewPassword'];
		} else {
			$password = null;
		}
				
		return $retype === $password 
			? true
			: wfMsgExt( 'badretype', 'parseinline' );
	}

	/**
	 * Create a new user account from the provided data
	 */
	public function formSubmitCallback( $data ) {
		global $wgUser, $wgEmailAuthentication;
		
		# Create the account and abort if there's a problem doing so
		$login = new Login( $data );
		$status = $login->attemptCreation( $this->mCreateaccountMail );
		
		switch( $status ){
			case Login::SUCCESS: 
			case Login::MAIL_ERROR: 
				break;
				
			case Login::CREATE_BADDOMAIN: 
			case Login::CREATE_EXISTS: 
			case Login::NO_NAME:
			case Login::CREATE_NEEDEMAIL: 
			case Login::CREATE_BADEMAIL: 
			case Login::CREATE_BADNAME:
			case Login::WRONG_PLUGIN_PASS:
			case Login::ABORTED:
				return wfMsgExt( $login->mCreateResult, 'parseinline' );
			
			case Login::CREATE_SORBS: 
				return wfMsgExt( 'sorbs_create_account_reason', 'parseinline' ) . ' (' . wfGetIP() . ')';
				
			case Login::CREATE_BLOCKED:
				$this->userBlockedMessage();
				return true;
				
			case Login::CREATE_BADPASS:
				global $wgMinimalPasswordLength;
				return wfMsgExt( $login->mCreateResult, array( 'parsemag' ), $wgMinimalPasswordLength );
				
			case Login::THROTTLED: 
				global $wgAccountCreationThrottle;
				return wfMsgExt( 'acct_creation_throttle_hit', array( 'parseinline' ), $wgAccountCreationThrottle ); 
			
			default: 
				throw new MWException( "Unhandled status code $status in " . __METHOD__ );
		}
		
		Token::clear( 'createaccount' );

		# If we showed up language selection links, and one was in use, be
		# smart (and sensible) and save that language as the user's preference
		global $wgLoginLanguageSelector;
		if( $wgLoginLanguageSelector && $this->mLanguage ){
			$login->getUser()->setOption( 'language', $this->mLanguage );
			$login->getUser()->saveSettings();
		}
	
		if( $this->mCreateaccountMail ) {
			if( $status == Login::MAIL_ERROR ){
				# FIXME: we are totally screwed if we end up here...
				return wfMsgExt( 'mailerror', 'parseinline', $login->mMailResult->getMessage() );
			} else {
				global $wgOut;
				$wgOut->setPageTitle( wfMsg( 'accmailtitle' ) );
				$wgOut->addWikiMsg( 'accmailtext', $login->getUser()->getName(), $login->getUser()->getEmail() );
				$wgOut->returnToMain( false );
				return true;
			}
			
		} else {

			# There might be a message stored from the confirmation mail
			# send, which we can display.
			if( $wgEmailAuthentication && $login->mMailResult ) {
				global $wgOut;
				if( WikiError::isError( $login->mMailResult ) ) {
					$wgOut->addWikiMsg( 'confirmemail_sendfailed', $login->mMailResult->getMessage() );
				} else {
					$wgOut->addWikiMsg( 'confirmemail_oncreate' );
				}
			}
			
			# If not logged in, assume the new account as the current 
			# one and set session cookies then show a "welcome" message 
			# or a "need cookies" message as needed
			if( $wgUser->isAnon() ) {
				$login->attemptLogin( true );
				$this->successfulCreation();
				return true;
			} else {
				# Show confirmation that the account was created
				global $wgOut;
				$self = SpecialPage::getTitleFor( 'Userlogin' );
				$wgOut->setPageTitle( wfMsgHtml( 'accountcreated' ) );
				$wgOut->addHTML( wfMsgWikiHtml( 'accountcreatedtext', $login->getUser()->getName() ) );
				$wgOut->returnToMain( false, $self );
				return true;
			}
		}
	}

	/**
	 * Run any hooks registered for logins, then 
	 * display a message welcoming the user.
	 */
	protected function successfulCreation(){
		global $wgUser, $wgOut;

		# Run any hooks; display injected HTML
		$injected_html = '';
		wfRunHooks('UserLoginComplete', array(&$wgUser, &$injected_html));

		SpecialUserLogin::displaySuccessfulLogin( 
			'welcomecreation', 
			$injected_html,
			$this->mReturnTo,
			$this->mReturnToQuery );
	}

	/**
	 * Display a message indicating that account creation from their IP has 
	 * been blocked by a (range)block with 'block account creation' enabled. 
	 * It's likely that this feature will be used for blocking large numbers 
	 * of innocent people, e.g. range blocks on schools. Don't blame it on 
	 * the user. There's a small chance that it really is the user's fault, 
	 * i.e. the username is blocked and they haven't bothered to log out 
	 * before trying to create an account to evade it, but we'll leave that 
	 * to their guilty conscience to figure out...
	 */
	protected function userBlockedMessage() {
		global $wgOut, $wgUser;

		$wgOut->setPageTitle( wfMsg( 'cantcreateaccounttitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$ip = wfGetIP();
		$blocker = User::whoIs( $wgUser->mBlock->mBy );
		$block_reason = $wgUser->mBlock->mReason;

		if ( strval( $block_reason ) === '' ) {
			$block_reason = wfMsgExt( 'blockednoreason', 'parseinline' );
		}
		$wgOut->addWikiMsg( 'cantcreateaccount-text', $ip, $block_reason, $blocker );
		$wgOut->returnToMain( false );
	}

	/**
	 * Show the main input form, with an appropriate error message
	 * from a previous iteration, if necessary
	 * @param $msg String HTML of message received previously
	 * @param $msgtype String type of message, usually 'error'
	 */
	protected function getForm() {
		global $wgUser, $wgOut, $wgHiddenPrefs, $wgEnableEmail;
		global $wgCookiePrefix, $wgLoginLanguageSelector;
		global $wgAuth, $wgEmailConfirmToEdit, $wgCookieExpiration;

		# Make sure the returnTo strings don't get lost if the
		# user changes language, etc
		$linkq = array();
		if ( !empty( $this->mReturnTo ) ) {
			$linkq['returnto'] = wfUrlencode( $this->mReturnTo );
			if ( !empty( $this->mReturnToQuery ) )
				$linkq['returntoquery'] = wfUrlencode( $this->mReturnToQuery );
		}

		# Pass any language selection on to the mode switch link
		if( $wgLoginLanguageSelector && $this->mLanguage ){
			$linkq['uselang'] = $this->mLanguage;
		}

		$skin = $wgUser->getSkin();
		$link = $skin->link( 
			SpecialPage::getTitleFor( 'Userlogin' ),
			wfMsgHtml( 'gotaccountlink' ),
			array(),
			$linkq );
		$link = $wgUser->isLoggedIn()
			? ''
			: wfMsgWikiHtml( 'gotaccount', $link );
		
		# Prepare language selection links as needed
		$langSelector = $wgLoginLanguageSelector 
			? Html::rawElement( 
				'div',
				array( 'id' => 'languagelinks' ),
				SpecialUserLogin::makeLanguageSelector( $this->getTitle(), $this->mReturnTo ) )
			: '';
				
		# Give authentication and captcha plugins a chance to 
		# modify the form, by hook or by using $wgAuth
		//$wgAuth->modifyUITemplate( $this, 'new' );
		wfRunHooks( 'UserCreateForm', array( &$this ) );
		
		# The most likely use of the hook is to enable domains;
		# check that now, and add fields if necessary
		if( $this->mDomains ){
			$this->mFormFields['Domain']['options'] = $this->mDomains;
			$this->mFormFields['Domain']['default'] = $this->mDomain;
		} else {
			unset( $this->mFormFields['Domain'] );
		}
		
		# Or to switch email on or off
		if( !$wgEnableEmail || !$this->mUseEmail ){
			unset( $this->mFormFields['Email'] );
		} else {
			if( $wgEmailConfirmToEdit ){
				$this->mFormFields['Email']['help-message'] = 'prefs-help-email-required';
				$this->mFormFields['Email']['required'] = '';
			} else {
				$this->mFormFields['Email']['help-message'] = 'prefs-help-email';
			}
		}
		
		# Or to play with realname
		if( in_array( 'realname', $wgHiddenPrefs ) ){
			unset( $this->mFormFields['Realname'] );
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
		
		$form = new HTMLForm( $this->mFormFields );
		
		$form->setTitle( $this->getTitle() );
		$form->setSubmitText( wfMsg( 'createaccount' ) );
		$form->setSubmitId( 'wpCreateaccount' );
		$form->suppressReset();
		$form->setWrapperLegend( wfMsg( 'createaccount' ) );
		$form->setTokenAction( 'createaccount' );
		
		$form->addHiddenField( 'returnto', $this->mReturnTo );
		$form->addHiddenField( 'returntoquery', $this->mReturnToQuery );
		if( $this->mLanguage ){
			$form->addHiddenField( 'uselang', $this->mLanguage );
		}
		
		$form->addHeaderText( ''
			. Html::rawElement( 'p', array( 'id' => 'userloginlink' ),
				$link )
			. $this->mFormHeader
			. $langSelector
		);
		$form->addPreText( ''
			. Html::rawElement( 
				'div', 
				array( 'id' => 'signupstart' ), 
				wfMsgExt( 'signupstart', array( 'parseinline' ) )
			)
		);
		$form->addPostText(
			Html::rawElement( 
				'div', 
				array( 'id' => 'signupend' ), 
				wfMsgExt( 'signupend', array( 'parseinline' ) )
			)
		);
		
		# Add a  'send password by email' button if available
		if( $wgEnableEmail && $wgUser->isLoggedIn() ){
			$form->addButton(
				'wpCreateaccountMail',
				wfMsg( 'createaccountmail' ),
				'wpCreateaccountMail'
			);
		}
		
		$form->setSubmitCallback( array( $this, 'formSubmitCallback' ) );
		$form->loadData();
		
		return $form;
		
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
	 * Since the UserCreateForm hook was changed to pass a SpecialPage
	 * instead of a QuickTemplate derivative, old extensions might
	 * easily try calling these methods expecing them to exist.  Tempting
	 * though it is to let them have the fatal error, let's at least
	 * fail gracefully...
	 * @deprecated
	 */
	public function set(){
		wfDeprecated( __METHOD__ );
	}
	public function addInputItem(){
		wfDeprecated( __METHOD__ );
	}
}
