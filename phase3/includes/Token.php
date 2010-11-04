<?php
/**
 * CSRF attacks (where a malicious website uses frames, <img> tags, or
 * similar, to prompt a wiki user to open a wiki page or submit a form,
 * without being aware of doing so) are most easily countered by using
 * tokens.  For normal browsing, loading the form for a protected action 
 * sets two copies of a random string: one in the $_SESSION, and one as 
 * a hidden field in the form.  When the form is submitted, it checks 
 * that a) the set of cookies submitted with the form *has* a copy of 
 * the session cookie, and b) that it matches.  Since malicious websites
 * don't have control over the session cookies, they can't craft a form
 * that can be instantly submitted which will have the appropriate tokens. 
 * 
 * Note that these tokens are distinct from those in User::setToken(), which
 * are used for persistent session authentication and are retained for as 
 * long as the user is logged in to the wiki.  These tokens are to protect
 * one individual action, and should ideally be cleared once the action is over.
 */

class Token {
	# Some punctuation to prevent editing from broken 
	# text-mangling proxies.
	const TOKEN_SUFFIX = '+\\';
	
	/**
	 * Ensure that a token is set in cookies, by setting a new one 
	 * if necessary.  
	 *
	 * @param $action String the action that's being protected by the token
	 * @param $salt Mixed String, Array Optional function-specific 
	 *     data for hashing
	 * @return \string The token that was set
	 */
	public static function set( $action='edit', $salt='' ) {
		# The 'generic' token is EditToken, which we don't store for anons
		# so they can still do things when they have cookies disabled.
		# So either use this for actions which annons can't access, or 
		# where you don't mind an attacker being able to trigger the action
		# anonymously from the user's IP.  However, the token is still 
		# useful because it fails with some broken proxies.
		global $wgUser;
		if ( $action == 'edit' && $wgUser->isAnon() ) {
			return self::TOKEN_SUFFIX;
		} 
		
		if( !self::has( $action ) ){
			$token = self::generate();
			if( session_id() == '' ) {
				wfSetupSession();
			}
			self::store( $token, $action );
		} else {
			$token = self::get( $action );
		}
		
		if( is_array( $salt ) ) {
			$salt = implode( '|', $salt );
		}
		
		return md5( $token . $salt ) . self::TOKEN_SUFFIX;
	}
	
	/**
	 * Check whether the copy of the token submitted with a form
	 * matches the version stored in session
	 * @param $val String version submitted with the form.  If null,
	 *     tries to get it from $wgRequest
	 * @param $action String
	 * @param $salt String
	 * @return Bool, or null if no session cookie is set
	 */
	public static function match( $val=null, $action='edit', $salt='' ){
		$action = ucfirst( $action );

		global $wgUser;
		if( $action == 'edit' && $wgUser->isAnon() ){
			return $val === self::TOKEN_SUFFIX;
		}
		
		if( !self::has( $action ) ){
			return null;
		}
		
		if( $val === null ){
			global $wgRequest;
			$val = $wgRequest->getText( "wp{$action}Token" );
		}

		return md5( self::get( $action ) . $salt ) . self::TOKEN_SUFFIX === $val;
	}
	
	/**
	 * Whether a token is set for the given action
	 * @return Bool
	 */
	public static function has( $action='edit' ){
		return self::get( $action ) !== null;
	}
	
	/**
	 * Get the token set for a given action
	 */
	public static function get( $action='edit' ){
		global $wgRequest;
		$action = ucfirst( $action );
		return $wgRequest->getSessionData( "ws{$action}Token" );
	}
	
	/**
	 * Set the given token for the given action in the session
	 * @param $token String
	 * @param $action String
	 */
	public static function store( $token, $action='edit' ){
		global $wgRequest;
		$action = ucfirst( $action );
		$wgRequest->setSessionData( "ws{$action}Token", $token );
	}
	
	/**
	 * Delete the token after use
	 */
	public static function clear( $action='edit' ){
		self::set( null, $action );
	}

	/**
	 * Generate a random token
	 *
	 * @param $salt String Optional salt value
	 * @return String 32-char random token
	 */
	public static function generate( $salt = '' ) {
		$rand = dechex( mt_rand() ) . dechex( mt_rand() );
		return md5( $rand . $salt );
	}
}
