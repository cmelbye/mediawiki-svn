<?php
if ( ! defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 * @todo document
 */
class OutputPage {
	var $mMetatags = array(), $mKeywords = array(), $mLinktags = array();
	var $mExtStyles = array();
	var $mPagetitle = '', $mBodytext = '', $mDebugtext = '';
	var $mHTMLtitle = '', $mIsarticle = true, $mPrintable = false;
	var $mSubtitle = '', $mRedirect = '', $mStatusCode;
	var $mLastModified = '', $mETag = false;
	var $mCategoryLinks = array(), $mCategories = array(), $mLanguageLinks = array();

	var $mScripts = '', $mLinkColours, $mPageLinkTitle = '', $mHeadItems = array();
	var $mInlineMsg = array();

	var $mTemplateIds = array();

	var $mAllowUserJs;
	var $mSuppressQuickbar = false;
	var $mOnloadHandler = '';
	var $mDoNothing = false;
	var $mContainsOldMagic = 0, $mContainsNewMagic = 0;
	var $mIsArticleRelated = true;
	protected $mParserOptions = null; // lazy initialised, use parserOptions()

	var $mFeedLinks = array();

	var $mEnableClientCache = true;
	var $mArticleBodyOnly = false;

	var $mNewSectionLink = false;
	var $mHideNewSectionLink = false;
	var $mNoGallery = false;
	var $mPageTitleActionText = '';
	var $mParseWarnings = array();
	var $mSquidMaxage = 0;
	var $mRevisionId = null;
	protected $mTitle = null;

	/**
	 * An array of stylesheet filenames (relative from skins path), with options
	 * for CSS media, IE conditions, and RTL/LTR direction.
	 * For internal use; add settings in the skin via $this->addStyle()
	 */
	var $styles = array();

	private $mIndexPolicy = 'index';
	private $mFollowPolicy = 'follow';
	private $mVaryHeader = array( 'Accept-Encoding' => array('list-contains=gzip'),
								  'Cookie' => null );

	/**
	 * Constructor
	 * Initialise private variables
	 */
	function __construct() {
		global $wgAllowUserJs;
		$this->mAllowUserJs = $wgAllowUserJs;
	}

	public function redirect( $url, $responsecode = '302' ) {
		# Strip newlines as a paranoia check for header injection in PHP<5.1.2
		$this->mRedirect = str_replace( "\n", '', $url );
		$this->mRedirectCode = $responsecode;
	}

	public function getRedirect() {
		return $this->mRedirect;
	}

	/**
	 * Set the HTTP status code to send with the output.
	 *
	 * @param int $statusCode
	 * @return nothing
	 */
	function setStatusCode( $statusCode ) { $this->mStatusCode = $statusCode; }

	/**
	 * Add a new <meta> tag
	 * To add an http-equiv meta tag, precede the name with "http:"
	 *
	 * @param $name tag name
	 * @param $val tag value
	 */
	function addMeta( $name, $val ) {
		array_push( $this->mMetatags, array( $name, $val ) );
	}

	function addKeyword( $text ) {
		if( is_array( $text )) {
			$this->mKeywords = array_merge( $this->mKeywords, $text );
		} else {
			array_push( $this->mKeywords, $text );
		}
	}
	function addScript( $script ) {
		$this->mScripts .= $script . "\n";
	}

	/**
	 * Register and add a stylesheet from an extension directory.
	 * @param $url String path to sheet.  Provide either a full url (beginning
	 *             with 'http', etc) or a relative path from the document root
	 *             (beginning with '/').  Otherwise it behaves identically to
	 *             addStyle() and draws from the /skins folder.
	 */
	public function addExtensionStyle( $url ) {
		array_push( $this->mExtStyles, $url );
	}

	/**
	 * Add a JavaScript file out of skins/common, or a given relative path.
	 * @param string $file filename in skins/common or complete on-server path (/foo/bar.js)
	 */
	function addScriptFile( $file ) {
		global $wgStylePath, $wgStyleVersion, $wgJsMimeType;
		if( substr( $file, 0, 1 ) == '/' ) {
			$path = $file;
		} else {
			$path =  "{$wgStylePath}/common/{$file}";
		}
		$this->addScript( 
			Xml::element( 'script', 
				array(
					'type' => $wgJsMimeType,
					'src' => "$path?$wgStyleVersion",
				),
				'', false
			)
		);
	}

	/**
	 * Add a self-contained script tag with the given contents
	 * @param string $script JavaScript text, no <script> tags
	 */
	function addInlineScript( $script ) {
		$this->mScripts .= Html::inlineScript( "\n$script\n" ) . "\n";
	}

	/**
	 * Get all registered JS and CSS tags for the header.
	 */
	function getScript() {
		return $this->mScripts . $this->getHeadItems();
	}

	function getHeadItems() {
		$s = '';
		foreach ( $this->mHeadItems as $item ) {
			$s .= $item;
		}
		return $s;
	}

	function addHeadItem( $name, $value ) {
		$this->mHeadItems[$name] = $value;
	}

	function hasHeadItem( $name ) {
		return isset( $this->mHeadItems[$name] );
	}

	function setETag($tag) { $this->mETag = $tag; }
	function setArticleBodyOnly($only) { $this->mArticleBodyOnly = $only; }
	function getArticleBodyOnly() { return $this->mArticleBodyOnly; }

	function addLink( $linkarr ) {
		# $linkarr should be an associative array of attributes. We'll escape on output.
		array_push( $this->mLinktags, $linkarr );
	}

	# Get all links added by extensions
	function getExtStyle() {
		return $this->mExtStyles;
	}

	function addMetadataLink( $linkarr ) {
		# note: buggy CC software only reads first "meta" link
		static $haveMeta = false;
		$linkarr['rel'] = ($haveMeta) ? 'alternate meta' : 'meta';
		$this->addLink( $linkarr );
		$haveMeta = true;
	}

	/**
	 * checkLastModified tells the client to use the client-cached page if
	 * possible. If sucessful, the OutputPage is disabled so that
	 * any future call to OutputPage->output() have no effect.
	 *
	 * Side effect: sets mLastModified for Last-Modified header
	 *
	 * @return bool True iff cache-ok headers was sent.
	 */
	function checkLastModified( $timestamp ) {
		global $wgCachePages, $wgCacheEpoch, $wgUser, $wgRequest;

		if ( !$timestamp || $timestamp == '19700101000000' ) {
			wfDebug( __METHOD__ . ": CACHE DISABLED, NO TIMESTAMP\n" );
			return false;
		}
		if( !$wgCachePages ) {
			wfDebug( __METHOD__ . ": CACHE DISABLED\n", false );
			return false;
		}
		if( $wgUser->getOption( 'nocache' ) ) {
			wfDebug( __METHOD__ . ": USER DISABLED CACHE\n", false );
			return false;
		}

		$timestamp = wfTimestamp( TS_MW, $timestamp );
		$modifiedTimes = array(
			'page' => $timestamp,
			'user' => $wgUser->getTouched(),
			'epoch' => $wgCacheEpoch
		);
		wfRunHooks( 'OutputPageCheckLastModified', array( &$modifiedTimes ) );

		$maxModified = max( $modifiedTimes );
		$this->mLastModified = wfTimestamp( TS_RFC2822, $maxModified );

		if( empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
			wfDebug( __METHOD__ . ": client did not send If-Modified-Since header\n", false );
			return false;
		}

		# Make debug info
		$info = '';
		foreach ( $modifiedTimes as $name => $value ) {
			if ( $info !== '' ) {
				$info .= ', ';
			}
			$info .= "$name=" . wfTimestamp( TS_ISO_8601, $value );
		}

		# IE sends sizes after the date like this:
		# Wed, 20 Aug 2003 06:51:19 GMT; length=5202
		# this breaks strtotime().
		$clientHeader = preg_replace( '/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"] );

		wfSuppressWarnings(); // E_STRICT system time bitching
		$clientHeaderTime = strtotime( $clientHeader );
		wfRestoreWarnings();
		if ( !$clientHeaderTime ) {
			wfDebug( __METHOD__ . ": unable to parse the client's If-Modified-Since header: $clientHeader\n" );
			return false;
		}
		$clientHeaderTime = wfTimestamp( TS_MW, $clientHeaderTime );

		wfDebug( __METHOD__ . ": client sent If-Modified-Since: " .
			wfTimestamp( TS_ISO_8601, $clientHeaderTime ) . "\n", false );
		wfDebug( __METHOD__ . ": effective Last-Modified: " .
			wfTimestamp( TS_ISO_8601, $maxModified ) . "\n", false );
		if( $clientHeaderTime < $maxModified ) {
			wfDebug( __METHOD__ . ": STALE, $info\n", false );
			return false;
		}

		# Not modified
		# Give a 304 response code and disable body output
		wfDebug( __METHOD__ . ": NOT MODIFIED, $info\n", false );
		ini_set('zlib.output_compression', 0);
		$wgRequest->response()->header( "HTTP/1.1 304 Not Modified" );
		$this->sendCacheControl();
		$this->disable();

		// Don't output a compressed blob when using ob_gzhandler;
		// it's technically against HTTP spec and seems to confuse
		// Firefox when the response gets split over two packets.
		wfClearOutputBuffers();

		return true;
	}

	function setPageTitleActionText( $text ) {
		$this->mPageTitleActionText = $text;
	}

	function getPageTitleActionText () {
		if ( isset( $this->mPageTitleActionText ) ) {
			return $this->mPageTitleActionText;
		}
	}

	/**
	 * Set the robot policy for the page: <http://www.robotstxt.org/meta.html>
	 *
	 * @param $policy string The literal string to output as the contents of
	 *   the meta tag.  Will be parsed according to the spec and output in
	 *   standardized form.
	 * @return null
	 */
	public function setRobotPolicy( $policy ) {
		$policy = Article::formatRobotPolicy( $policy );

		if( isset( $policy['index'] ) ){
			$this->setIndexPolicy( $policy['index'] );
 		}
		if( isset( $policy['follow'] ) ){
			$this->setFollowPolicy( $policy['follow'] );
 		}
	}

	/**
	 * Set the index policy for the page, but leave the follow policy un-
	 * touched.
	 *
	 * @param $policy string Either 'index' or 'noindex'.
	 * @return null
	 */
	public function setIndexPolicy( $policy ) {
		$policy = trim( $policy );
		if( in_array( $policy, array( 'index', 'noindex' ) ) ) {
			$this->mIndexPolicy = $policy;
		}
	}

	/**
	 * Set the follow policy for the page, but leave the index policy un-
	 * touched.
	 *
	 * @param $policy string Either 'follow' or 'nofollow'.
	 * @return null
	 */
	public function setFollowPolicy( $policy ) {
		$policy = trim( $policy );
		if( in_array( $policy, array( 'follow', 'nofollow' ) ) ) {
			$this->mFollowPolicy = $policy;
		}
	}

	/**
	 * "HTML title" means the contents of <title>. It is stored as plain, unescaped text and will be run through htmlspecialchars in the skin file.
	 */
	public function setHTMLTitle( $name ) {
		$this->mHTMLtitle = $name;
	}

	/**
	 * "Page title" means the contents of <h1>. It is stored as a valid HTML fragment.
	 * This function allows good tags like <sup> in the <h1> tag, but not bad tags like <script>.
	 * This function automatically sets <title> to the same content as <h1> but with all tags removed.
	 * Bad tags that were escaped in <h1> will still be escaped in <title>, and good tags like <i> will be dropped entirely.
	 */
	public function setPageTitle( $name ) {
		global $wgContLang;
		$name = $wgContLang->convert( $name, true );
		# change "<script>foo&bar</script>" to "&lt;script&gt;foo&amp;bar&lt;/script&gt;"
		# but leave "<i>foobar</i>" alone
		$nameWithTags = Sanitizer::normalizeCharReferences( Sanitizer::removeHTMLtags( $name ) );
		$this->mPagetitle = $nameWithTags;

		$taction =  $this->getPageTitleActionText();
		if( !empty( $taction ) ) {
			$name .= ' - '.$taction;
		}

		# change "<i>foo&amp;bar</i>" to "foo&bar"
		$this->setHTMLTitle( wfMsg( 'pagetitle', Sanitizer::stripAllTags( $nameWithTags ) ) );
	}

	public function setTitle( $t ) {
		$this->mTitle = $t;
	}

	public function getTitle() {
		if ( $this->mTitle instanceof Title ) {
			return $this->mTitle;
		}
		else {
			wfDebug( __METHOD__ . ' called and $mTitle is null. Return $wgTitle for sanity' );
			global $wgTitle;
			return $wgTitle;
		}
	}

	public function getHTMLTitle() { return $this->mHTMLtitle; }
	public function getPageTitle() { return $this->mPagetitle; }
	public function setSubtitle( $str ) { $this->mSubtitle = /*$this->parse(*/$str/*)*/; } // @bug 2514
	public function appendSubtitle( $str ) { $this->mSubtitle .= /*$this->parse(*/$str/*)*/; } // @bug 2514
	public function getSubtitle() { return $this->mSubtitle; }
	public function isArticle() { return $this->mIsarticle; }
	public function setPrintable() { $this->mPrintable = true; }
	public function isPrintable() { return $this->mPrintable; }
	public function getFeedAppendQuery() { return $this->mFeedLinksAppendQuery; }
	public function setOnloadHandler( $js ) { $this->mOnloadHandler = $js; }
	public function getOnloadHandler() { return $this->mOnloadHandler; }
	public function disable() { $this->mDoNothing = true; }
	public function isDisabled() { return $this->mDoNothing; }

	public function setSyndicated( $show = true ) { $this->mShowFeedLinks = $show; }

	public function setFeedAppendQuery( $val ) {
		global $wgFeedClasses;

		$this->mFeedLinks = array();

		foreach( $wgFeedClasses as $type => $class ) {
			$query = "feed=$type&".$val;
			$this->mFeedLinks[$type] = $this->getTitle()->getLocalURL( $query );
		}
	}

	public function addFeedLink( $format, $href ) {
		$this->mFeedLinks[$format] = $href;
	}

	public function isSyndicated() { return count($this->mFeedLinks); }

	public function setArticleRelated( $v ) {
		$this->mIsArticleRelated = $v;
		if ( !$v ) {
			$this->mIsarticle = false;
		}
	}
	public function setArticleFlag( $v ) {
		$this->mIsarticle = $v;
		if ( $v ) {
			$this->mIsArticleRelated = $v;
		}
	}

	public function isArticleRelated() { return $this->mIsArticleRelated; }

	public function getLanguageLinks() { return $this->mLanguageLinks; }
	public function addLanguageLinks($newLinkArray) {
		$this->mLanguageLinks += $newLinkArray;
	}
	public function setLanguageLinks($newLinkArray) {
		$this->mLanguageLinks = $newLinkArray;
	}

	public function getCategoryLinks() {
		return $this->mCategoryLinks;
	}

	public function getCategories() {
		return $this->mCategories;
	}

	/**
	 * Add an array of categories, with names in the keys
	 */
	public function addCategoryLinks( $categories ) {
		global $wgUser, $wgContLang;

		if ( !is_array( $categories ) || count( $categories ) == 0 ) {
			return;
		}

		# Add the links to a LinkBatch
		$arr = array( NS_CATEGORY => $categories );
		$lb = new LinkBatch;
		$lb->setArray( $arr );

		# Fetch existence plus the hiddencat property
		$dbr = wfGetDB( DB_SLAVE );
		$pageTable = $dbr->tableName( 'page' );
		$where = $lb->constructSet( 'page', $dbr );
		$propsTable = $dbr->tableName( 'page_props' );
		$sql = "SELECT page_id, page_namespace, page_title, page_len, page_is_redirect, pp_value
			FROM $pageTable LEFT JOIN $propsTable ON pp_propname='hiddencat' AND pp_page=page_id WHERE $where";
		$res = $dbr->query( $sql, __METHOD__ );

		# Add the results to the link cache
		$lb->addResultToCache( LinkCache::singleton(), $res );

		# Set all the values to 'normal'. This can be done with array_fill_keys in PHP 5.2.0+
		$categories = array_combine( array_keys( $categories ),
			array_fill( 0, count( $categories ), 'normal' ) );

		# Mark hidden categories
		foreach ( $res as $row ) {
			if ( isset( $row->pp_value ) ) {
				$categories[$row->page_title] = 'hidden';
			}
		}

		# Add the remaining categories to the skin
		if ( wfRunHooks( 'OutputPageMakeCategoryLinks', array( &$this, $categories, &$this->mCategoryLinks ) ) ) {
			$sk = $wgUser->getSkin();
			foreach ( $categories as $category => $type ) {
				$origcategory = $category;
				$title = Title::makeTitleSafe( NS_CATEGORY, $category );
				$wgContLang->findVariantLink( $category, $title, true );
				if ( $category != $origcategory )
					if ( array_key_exists( $category, $categories ) )
						continue;
				$text = $wgContLang->convertHtml( $title->getText() );
				$this->mCategories[] = $title->getText();
				$this->mCategoryLinks[$type][] = $sk->link( $title, $text );
			}
		}
	}

	public function setCategoryLinks($categories) {
		$this->mCategoryLinks = array();
		$this->addCategoryLinks($categories);
	}

	public function suppressQuickbar() { $this->mSuppressQuickbar = true; }
	public function isQuickbarSuppressed() { return $this->mSuppressQuickbar; }

	public function disallowUserJs() { $this->mAllowUserJs = false; }
	public function isUserJsAllowed() { return $this->mAllowUserJs; }

	public function prependHTML( $text ) { $this->mBodytext = $text . $this->mBodytext; }
	public function addHTML( $text ) { $this->mBodytext .= $text; }
	public function clearHTML() { $this->mBodytext = ''; }
	public function getHTML() { return $this->mBodytext; }
	public function debug( $text ) { $this->mDebugtext .= $text; }

	/* @deprecated */
	public function setParserOptions( $options ) {
		wfDeprecated( __METHOD__ );
		return $this->parserOptions( $options );
	}

	public function parserOptions( $options = null ) {
		if ( !$this->mParserOptions ) {
			$this->mParserOptions = new ParserOptions;
		}
		return wfSetVar( $this->mParserOptions, $options );
	}

	/**
	 * Set the revision ID which will be seen by the wiki text parser
	 * for things such as embedded {{REVISIONID}} variable use.
	 * @param mixed $revid an integer, or NULL
	 * @return mixed previous value
	 */
	public function setRevisionId( $revid ) {
		$val = is_null( $revid ) ? null : intval( $revid );
		return wfSetVar( $this->mRevisionId, $val );
	}

	public function getRevisionId() {
		return $this->mRevisionId;
	}

	/**
	 * Convert wikitext to HTML and add it to the buffer
	 * Default assumes that the current page title will
	 * be used.
	 *
	 * @param string $text
	 * @param bool   $linestart
	 */
	public function addWikiText( $text, $linestart = true ) {
		$title = $this->getTitle(); // Work arround E_STRICT
		$this->addWikiTextTitle( $text, $title, $linestart );
	}

	public function addWikiTextWithTitle($text, &$title, $linestart = true) {
		$this->addWikiTextTitle($text, $title, $linestart);
	}

	function addWikiTextTitleTidy($text, &$title, $linestart = true) {
		$this->addWikiTextTitle( $text, $title, $linestart, true );
	}

	public function addWikiTextTitle($text, &$title, $linestart, $tidy = false) {
		global $wgParser;

		wfProfileIn( __METHOD__ );

		wfIncrStats( 'pcache_not_possible' );

		$popts = $this->parserOptions();
		$oldTidy = $popts->setTidy( $tidy );

		$parserOutput = $wgParser->parse( $text, $title, $popts,
			$linestart, true, $this->mRevisionId );

		$popts->setTidy( $oldTidy );

		$this->addParserOutput( $parserOutput );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * @todo document
	 * @param ParserOutput object &$parserOutput
	 */
	public function addParserOutputNoText( &$parserOutput ) {
		global $wgExemptFromUserRobotsControl, $wgContentNamespaces;

		$this->mLanguageLinks += $parserOutput->getLanguageLinks();
		$this->addCategoryLinks( $parserOutput->getCategories() );
		$this->mNewSectionLink = $parserOutput->getNewSection();
		$this->mHideNewSectionLink = $parserOutput->getHideNewSection();

		$this->mParseWarnings = $parserOutput->getWarnings();
		if ( $parserOutput->getCacheTime() == -1 ) {
			$this->enableClientCache( false );
		}
		$this->mNoGallery = $parserOutput->getNoGallery();
		$this->mHeadItems = array_merge( $this->mHeadItems, (array)$parserOutput->mHeadItems );
		// Versioning...
		foreach ( (array)$parserOutput->mTemplateIds as $ns => $dbks ) {
			if ( isset( $this->mTemplateIds[$ns] ) ) {
				$this->mTemplateIds[$ns] = $dbks + $this->mTemplateIds[$ns];
			} else {
				$this->mTemplateIds[$ns] = $dbks;
			}
		}
		// Page title
		if( ( $dt = $parserOutput->getDisplayTitle() ) !== false )
			$this->setPageTitle( $dt );
		else if ( ( $title = $parserOutput->getTitleText() ) != '' )
			$this->setPageTitle( $title );

		// Hooks registered in the object
		global $wgParserOutputHooks;
		foreach ( $parserOutput->getOutputHooks() as $hookInfo ) {
			list( $hookName, $data ) = $hookInfo;
			if ( isset( $wgParserOutputHooks[$hookName] ) ) {
				call_user_func( $wgParserOutputHooks[$hookName], $this, $parserOutput, $data );
			}
		}

		wfRunHooks( 'OutputPageParserOutput', array( &$this, $parserOutput ) );
	}

	/**
	 * @todo document
	 * @param ParserOutput &$parserOutput
	 */
	function addParserOutput( &$parserOutput ) {
		$this->addParserOutputNoText( $parserOutput );
		$text = $parserOutput->getText();
		wfRunHooks( 'OutputPageBeforeHTML',array( &$this, &$text ) );
		$this->addHTML( $text );
	}

	/**
	 * Add wikitext to the buffer, assuming that this is the primary text for a page view
	 * Saves the text into the parser cache if possible.
	 *
	 * @param string  $text
	 * @param Article $article
	 * @param bool    $cache
	 * @deprecated Use Article::outputWikitext
	 */
	public function addPrimaryWikiText( $text, $article, $cache = true ) {
		global $wgParser;

		wfDeprecated( __METHOD__ );

		$popts = $this->parserOptions();
		$popts->setTidy(true);
		$parserOutput = $wgParser->parse( $text, $article->mTitle,
			$popts, true, true, $this->mRevisionId );
		$popts->setTidy(false);
		if ( $cache && $article && $parserOutput->getCacheTime() != -1 ) {
			$parserCache = ParserCache::singleton();
			$parserCache->save( $parserOutput, $article, $popts);
		}

		$this->addParserOutput( $parserOutput );
	}

	/**
	 * @deprecated use addWikiTextTidy()
	 */
	public function addSecondaryWikiText( $text, $linestart = true ) {
		wfDeprecated( __METHOD__ );
		$this->addWikiTextTitleTidy($text, $this->getTitle(), $linestart);
	}

	/**
	 * Add wikitext with tidy enabled
	 */
	public function addWikiTextTidy(  $text, $linestart = true ) {
		$title = $this->getTitle();
		$this->addWikiTextTitleTidy($text, $title, $linestart);
	}


	/**
	 * Add the output of a QuickTemplate to the output buffer
	 *
	 * @param QuickTemplate $template
	 */
	public function addTemplate( &$template ) {
		ob_start();
		$template->execute();
		$this->addHTML( ob_get_contents() );
		ob_end_clean();
	}

	/**
	 * Parse wikitext and return the HTML.
	 *
	 * @param string $text
	 * @param bool   $linestart Is this the start of a line?
	 * @param bool   $interface ??
	 */
	public function parse( $text, $linestart = true, $interface = false ) {
		global $wgParser;
		if( is_null( $this->getTitle() ) ) {
			throw new MWException( 'Empty $mTitle in ' . __METHOD__ );
		}
		$popts = $this->parserOptions();
		if ( $interface) { $popts->setInterfaceMessage(true); }
		$parserOutput = $wgParser->parse( $text, $this->getTitle(), $popts,
			$linestart, true, $this->mRevisionId );
		if ( $interface) { $popts->setInterfaceMessage(false); }
		return $parserOutput->getText();
	}

	/** Parse wikitext, strip paragraphs, and return the HTML. */
	public function parseInline( $text, $linestart = true, $interface = false ) {
		$parsed = $this->parse( $text, $linestart, $interface );

		$m = array();
		if ( preg_match( '/^<p>(.*)\n?<\/p>\n?/sU', $parsed, $m ) ) {
			$parsed = $m[1];
		}

		return $parsed;
	}

	/**
	 * @param Article $article
	 * @param User    $user
	 *
	 * @deprecated
	 *
	 * @return bool True if successful, else false.
	 */
	public function tryParserCache( &$article ) {
		wfDeprecated( __METHOD__ );
		$parserOutput = ParserCache::singleton()->get( $article, $article->getParserOptions() );

		if ($parserOutput !== false) {
			$this->addParserOutput( $parserOutput );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param int $maxage Maximum cache time on the Squid, in seconds.
	 */
	public function setSquidMaxage( $maxage ) {
		$this->mSquidMaxage = $maxage;
	}

	/**
	 * Use enableClientCache(false) to force it to send nocache headers
	 * @param $state ??
	 */
	public function enableClientCache( $state ) {
		return wfSetVar( $this->mEnableClientCache, $state );
	}

	function getCacheVaryCookies() {
		global $wgCookiePrefix, $wgCacheVaryCookies;
		static $cookies;
		if ( $cookies === null ) {
			$cookies = array_merge(
				array(
					"{$wgCookiePrefix}Token",
					"{$wgCookiePrefix}LoggedOut",
					session_name()
				),
				$wgCacheVaryCookies
			);
			wfRunHooks('GetCacheVaryCookies', array( $this, &$cookies ) );
		}
		return $cookies;
	}

	function uncacheableBecauseRequestVars() {
		global $wgRequest;
		return	$wgRequest->getText('useskin', false) === false
			&& $wgRequest->getText('uselang', false) === false;
	}

	/**
	 * Check if the request has a cache-varying cookie header
	 * If it does, it's very important that we don't allow public caching
	 */
	function haveCacheVaryCookies() {
		global $wgRequest;
		$cookieHeader = $wgRequest->getHeader( 'cookie' );
		if ( $cookieHeader === false ) {
			return false;
		}
		$cvCookies = $this->getCacheVaryCookies();
		foreach ( $cvCookies as $cookieName ) {
			# Check for a simple string match, like the way squid does it
			if ( strpos( $cookieHeader, $cookieName ) ) {
				wfDebug( __METHOD__.": found $cookieName\n" );
				return true;
			}
		}
		wfDebug( __METHOD__.": no cache-varying cookies found\n" );
		return false;
	}

	public function addVaryHeader( $header, $option = null ) {
		if ( !array_key_exists( $header, $this->mVaryHeader ) ) {
			$this->mVaryHeader[$header] = $option;
		}
		elseif( is_array( $option ) ) {
			if( is_array( $this->mVaryHeader[$header] ) ) {
				$this->mVaryHeader[$header] = array_merge( $this->mVaryHeader[$header], $option );
			}
			else {
				$this->mVaryHeader[$header] = $option;
			}
		}
		$this->mVaryHeader[$header] = array_unique( $this->mVaryHeader[$header] );
	}

	/** Get a complete X-Vary-Options header */
	public function getXVO() {
		$cvCookies = $this->getCacheVaryCookies();
		
		$cookiesOption = array();
		foreach ( $cvCookies as $cookieName ) {
			$cookiesOption[] = 'string-contains=' . $cookieName;
		}
		$this->addVaryHeader( 'Cookie', $cookiesOption );
		
		$headers = array();
		foreach( $this->mVaryHeader as $header => $option ) {
			$newheader = $header;
			if( is_array( $option ) )
				$newheader .= ';' . implode( ';', $option );
			$headers[] = $newheader;
		}
		$xvo = 'X-Vary-Options: ' . implode( ',', $headers );
		
		return $xvo;
	}

	/** bug 21672: Add Accept-Language to Vary and XVO headers
		if there's no 'variant' parameter existed in GET.
		
		For example:
		 /w/index.php?title=Main_page should always be served; but
		 /w/index.php?title=Main_page&variant=zh-cn should never be served.
		
		patched by Liangent and Philip */
	function addAcceptLanguage() {
		global $wgRequest, $wgContLang;
		if( $wgRequest->getText('variant') == false && $wgContLang->hasVariants() ) {
			$variants = $wgContLang->getVariants();
			$aloption = array();
			foreach ( $variants as $variant ) {
				if( $variant === $wgContLang->getCode() )
					continue;
				else
					$aloption[] = "string-contains=$variant";
			}
			$this->addVaryHeader( 'Accept-Language', $aloption );
		}
	}

	public function sendCacheControl() {
		global $wgUseSquid, $wgUseESI, $wgUseETag, $wgSquidMaxage, $wgRequest, $wgUseXVO;

		$response = $wgRequest->response();
		if ($wgUseETag && $this->mETag)
			$response->header("ETag: $this->mETag");

		$this->addAcceptLanguage();

		# don't serve compressed data to clients who can't handle it
		# maintain different caches for logged-in users and non-logged in ones
		$response->header( 'Vary: ' . join( ', ', array_keys( $this->mVaryHeader ) ) );

		if ( $wgUseXVO ) {
			# Add an X-Vary-Options header for Squid with Wikimedia patches
			$response->header( $this->getXVO() );
		}

		if( !$this->uncacheableBecauseRequestVars() && $this->mEnableClientCache ) {
			if( $wgUseSquid && session_id() == '' &&
			  ! $this->isPrintable() && $this->mSquidMaxage != 0 && !$this->haveCacheVaryCookies() )
			{
				if ( $wgUseESI ) {
					# We'll purge the proxy cache explicitly, but require end user agents
					# to revalidate against the proxy on each visit.
					# Surrogate-Control controls our Squid, Cache-Control downstream caches
					wfDebug( __METHOD__ . ": proxy caching with ESI; {$this->mLastModified} **\n", false );
					# start with a shorter timeout for initial testing
					# header( 'Surrogate-Control: max-age=2678400+2678400, content="ESI/1.0"');
					$response->header( 'Surrogate-Control: max-age='.$wgSquidMaxage.'+'.$this->mSquidMaxage.', content="ESI/1.0"');
					$response->header( 'Cache-Control: s-maxage=0, must-revalidate, max-age=0' );
				} else {
					# We'll purge the proxy cache for anons explicitly, but require end user agents
					# to revalidate against the proxy on each visit.
					# IMPORTANT! The Squid needs to replace the Cache-Control header with
					# Cache-Control: s-maxage=0, must-revalidate, max-age=0
					wfDebug( __METHOD__ . ": local proxy caching; {$this->mLastModified} **\n", false );
					# start with a shorter timeout for initial testing
					# header( "Cache-Control: s-maxage=2678400, must-revalidate, max-age=0" );
					$response->header( 'Cache-Control: s-maxage='.$this->mSquidMaxage.', must-revalidate, max-age=0' );
				}
			} else {
				# We do want clients to cache if they can, but they *must* check for updates
				# on revisiting the page.
				wfDebug( __METHOD__ . ": private caching; {$this->mLastModified} **\n", false );
				$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
				$response->header( "Cache-Control: private, must-revalidate, max-age=0" );
			}
			if($this->mLastModified) {
				$response->header( "Last-Modified: {$this->mLastModified}" );
			}
		} else {
			wfDebug( __METHOD__ . ": no caching **\n", false );

			# In general, the absence of a last modified header should be enough to prevent
			# the client from using its cache. We send a few other things just to make sure.
			$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
			$response->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
			$response->header( 'Pragma: no-cache' );
		}
		wfRunHooks('CacheHeadersAfterSet', array( $this ) );
	}

	/**
	 * Get the message associed with the HTTP response code $code
	 *
	 * @param $code Integer: status code
	 * @return String or null: message or null if $code is not in the list of
	 *         messages
	 */
	public static function getStatusMessage( $code ) {
		static $statusMessage = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			102 => 'Processing',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			207 => 'Multi-Status',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Large',
			415 => 'Unsupported Media Type',
			416 => 'Request Range Not Satisfiable',
			417 => 'Expectation Failed',
			422 => 'Unprocessable Entity',
			423 => 'Locked',
			424 => 'Failed Dependency',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			507 => 'Insufficient Storage'
		);
		return isset( $statusMessage[$code] ) ? $statusMessage[$code] : null;
	}

	/**
	 * Finally, all the text has been munged and accumulated into
	 * the object, let's actually output it:
	 */
	public function output() {
		global $wgUser, $wgOutputEncoding, $wgRequest;
		global $wgContLanguageCode, $wgDebugRedirects, $wgMimeType;
		global $wgUseAjax, $wgAjaxWatch;
		global $wgEnableMWSuggest, $wgUniversalEditButton;
		global $wgArticle;

		if( $this->mDoNothing ){
			return;
		}
		wfProfileIn( __METHOD__ );
		if ( '' != $this->mRedirect ) {
			# Standards require redirect URLs to be absolute
			$this->mRedirect = wfExpandUrl( $this->mRedirect );
			if( $this->mRedirectCode == '301' || $this->mRedirectCode == '303' ) {
				if( !$wgDebugRedirects ) {
					$message = self::getStatusMessage( $this->mRedirectCode );
					$wgRequest->response()->header( "HTTP/1.1 {$this->mRedirectCode} $message" );
				}
				$this->mLastModified = wfTimestamp( TS_RFC2822 );
			}
			$this->sendCacheControl();

			$wgRequest->response()->header( "Content-Type: text/html; charset=utf-8" );
			if( $wgDebugRedirects ) {
				$url = htmlspecialchars( $this->mRedirect );
				print "<html>\n<head>\n<title>Redirect</title>\n</head>\n<body>\n";
				print "<p>Location: <a href=\"$url\">$url</a></p>\n";
				print "</body>\n</html>\n";
			} else {
				$wgRequest->response()->header( 'Location: ' . $this->mRedirect );
			}
			wfProfileOut( __METHOD__ );
			return;
		} elseif ( $this->mStatusCode ) {
			$message = self::getStatusMessage( $this->mStatusCode );
			if ( $message )
				$wgRequest->response()->header( 'HTTP/1.1 ' . $this->mStatusCode . ' ' . $message );
		}

		$sk = $wgUser->getSkin();

		if ( $wgUseAjax ) {
			$this->addScriptFile( 'ajax.js' );

			wfRunHooks( 'AjaxAddScript', array( &$this ) );

			if( $wgAjaxWatch && $wgUser->isLoggedIn() ) {
				$this->addScriptFile( 'ajaxwatch.js' );
			}

			if ( $wgEnableMWSuggest && !$wgUser->getOption( 'disablesuggest', false ) ){
				$this->addScriptFile( 'mwsuggest.js' );
			}
		}

		if( $wgUser->getBoolOption( 'editsectiononrightclick' ) ) {
			$this->addScriptFile( 'rightclickedit.js' );
		}

		if( $wgUniversalEditButton ) {
			if( isset( $wgArticle ) && $this->getTitle() && $this->getTitle()->quickUserCan( 'edit' )
				&& ( $this->getTitle()->exists() || $this->getTitle()->quickUserCan( 'create' ) ) ) {
				// Original UniversalEditButton
				$msg = wfMsg('edit');
				$this->addLink( array(
					'rel' => 'alternate',
					'type' => 'application/x-wiki',
					'title' => $msg,
					'href' => $this->getTitle()->getLocalURL( 'action=edit' )
				) );
				// Alternate edit link
				$this->addLink( array(
					'rel' => 'edit',
					'title' => $msg,
					'href' => $this->getTitle()->getLocalURL( 'action=edit' )
				) );
			}
		}

		# Buffer output; final headers may depend on later processing
		ob_start();

		$wgRequest->response()->header( "Content-type: $wgMimeType; charset={$wgOutputEncoding}" );
		$wgRequest->response()->header( 'Content-language: '.$wgContLanguageCode );

		if ($this->mArticleBodyOnly) {
			$this->out($this->mBodytext);
		} else {
			// Hook that allows last minute changes to the output page, e.g.
			// adding of CSS or Javascript by extensions.
			wfRunHooks( 'BeforePageDisplay', array( &$this, &$sk ) );

			wfProfileIn( 'Output-skin' );
			$sk->outputPage( $this );
			wfProfileOut( 'Output-skin' );
		}

		$this->sendCacheControl();
		ob_end_flush();
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Actually output something with print(). Performs an iconv to the
	 * output encoding, if needed.
	 * @param string $ins The string to output
	 */
	public function out( $ins ) {
		global $wgInputEncoding, $wgOutputEncoding, $wgContLang;
		if ( 0 == strcmp( $wgInputEncoding, $wgOutputEncoding ) ) {
			$outs = $ins;
		} else {
			$outs = $wgContLang->iconv( $wgInputEncoding, $wgOutputEncoding, $ins );
			if ( false === $outs ) { $outs = $ins; }
		}
		print $outs;
	}

	/**
	 * @todo document
	 */
	public static function setEncodings() {
		global $wgInputEncoding, $wgOutputEncoding;
		global $wgContLang;

		$wgInputEncoding = strtolower( $wgInputEncoding );

		if ( empty( $_SERVER['HTTP_ACCEPT_CHARSET'] ) ) {
			$wgOutputEncoding = strtolower( $wgOutputEncoding );
			return;
		}
		$wgOutputEncoding = $wgInputEncoding;
	}

	/**
	 * Deprecated, use wfReportTime() instead.
	 * @return string
	 * @deprecated
	 */
	public function reportTime() {
		wfDeprecated( __METHOD__ );
		$time = wfReportTime();
		return $time;
	}

	/**
	 * Produce a "user is blocked" page.
	 *
	 * @param bool $return Whether to have a "return to $wgTitle" message or not.
	 * @return nothing
	 */
	function blockedPage( $return = true ) {
		global $wgUser, $wgContLang, $wgLang;

		$this->setPageTitle( wfMsg( 'blockedtitle' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );

		$name = User::whoIs( $wgUser->blockedBy() );
		$reason = $wgUser->blockedFor();
		if( $reason == '' ) {
			$reason = wfMsg( 'blockednoreason' );
		}
		$blockTimestamp = $wgLang->timeanddate( wfTimestamp( TS_MW, $wgUser->mBlock->mTimestamp ), true );
		$ip = wfGetIP();

		$link = '[[' . $wgContLang->getNsText( NS_USER ) . ":{$name}|{$name}]]";

		$blockid = $wgUser->mBlock->mId;

		$blockExpiry = $wgUser->mBlock->mExpiry;
		if ( $blockExpiry == 'infinity' ) {
			// Entry in database (table ipblocks) is 'infinity' but 'ipboptions' uses 'infinite' or 'indefinite'
			// Search for localization in 'ipboptions'
			$scBlockExpiryOptions = wfMsg( 'ipboptions' );
			foreach ( explode( ',', $scBlockExpiryOptions ) as $option ) {
				if ( strpos( $option, ":" ) === false )
					continue;
				list( $show, $value ) = explode( ":", $option );
				if ( $value == 'infinite' || $value == 'indefinite' ) {
					$blockExpiry = $show;
					break;
				}
			}
		} else {
			$blockExpiry = $wgLang->timeanddate( wfTimestamp( TS_MW, $blockExpiry ), true );
		}

		if ( $wgUser->mBlock->mAuto ) {
			$msg = 'autoblockedtext';
		} else {
			$msg = 'blockedtext';
		}

		/* $ip returns who *is* being blocked, $intended contains who was meant to be blocked.
		 * This could be a username, an ip range, or a single ip. */
		$intended = $wgUser->mBlock->mAddress;

		$this->addWikiMsg( $msg, $link, $reason, $ip, $name, $blockid, $blockExpiry, $intended, $blockTimestamp );

		# Don't auto-return to special pages
		if( $return ) {
			$return = $this->getTitle()->getNamespace() > -1 ? $this->getTitle() : null;
			$this->returnToMain( null, $return );
		}
	}

	/**
	 * Output a standard error page
	 *
	 * @param string $title Message key for page title
	 * @param string $msg Message key for page text
	 * @param array $params Message parameters
	 */
	public function showErrorPage( $title, $msg, $params = array() ) {
		if ( $this->getTitle() ) {
			$this->mDebugtext .= 'Original title: ' . $this->getTitle()->getPrefixedText() . "\n";
		}
		$this->setPageTitle( wfMsg( $title ) );
		$this->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->mBodytext = '';

		array_unshift( $params, 'parse' );
		array_unshift( $params, $msg );
		$this->addHTML( call_user_func_array( 'wfMsgExt', $params ) );

		$this->returnToMain();
	}

	/**
	 * Output a standard permission error page
	 *
	 * @param array $errors Error message keys
	 */
	public function showPermissionsErrorPage( $errors, $action = null )
	{
		$this->mDebugtext .= 'Original title: ' .
		$this->getTitle()->getPrefixedText() . "\n";
		$this->setPageTitle( wfMsg( 'permissionserrors' ) );
		$this->setHTMLTitle( wfMsg( 'permissionserrors' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->mBodytext = '';
		$this->addWikiText( $this->formatPermissionsErrorMessage( $errors, $action ) );
	}

	/** @deprecated */
	public function errorpage( $title, $msg ) {
		wfDeprecated( __METHOD__ );
		throw new ErrorPageError( $title, $msg );
	}

	/**
	 * Display an error page indicating that a given version of MediaWiki is
	 * required to use it
	 *
	 * @param mixed $version The version of MediaWiki needed to use the page
	 */
	public function versionRequired( $version ) {
		$this->setPageTitle( wfMsg( 'versionrequired', $version ) );
		$this->setHTMLTitle( wfMsg( 'versionrequired', $version ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->mBodytext = '';

		$this->addWikiMsg( 'versionrequiredtext', $version );
		$this->returnToMain();
	}

	/**
	 * Display an error page noting that a given permission bit is required.
	 *
	 * @param string $permission key required
	 */
	public function permissionRequired( $permission ) {
		global $wgLang;

		$this->setPageTitle( wfMsg( 'badaccess' ) );
		$this->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->mBodytext = '';

		$groups = array_map( array( 'User', 'makeGroupLinkWiki' ),
			User::getGroupsWithPermission( $permission ) );
		if( $groups ) {
			$this->addWikiMsg( 'badaccess-groups',
				$wgLang->commaList( $groups ),
				count( $groups) );
		} else {
			$this->addWikiMsg( 'badaccess-group0' );
		}
		$this->returnToMain();
	}

	/**
	 * Use permissionRequired.
	 * @deprecated
	 */
	public function sysopRequired() {
		throw new MWException( "Call to deprecated OutputPage::sysopRequired() method\n" );
	}

	/**
	 * Use permissionRequired.
	 * @deprecated
	 */
	public function developerRequired() {
		throw new MWException( "Call to deprecated OutputPage::developerRequired() method\n" );
	}

	/**
	 * Produce the stock "please login to use the wiki" page
	 */
	public function loginToUse() {
		global $wgUser, $wgContLang;

		if( $wgUser->isLoggedIn() ) {
			$this->permissionRequired( 'read' );
			return;
		}

		$skin = $wgUser->getSkin();

		$this->setPageTitle( wfMsg( 'loginreqtitle' ) );
		$this->setHtmlTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleFlag( false );

		$loginTitle = SpecialPage::getTitleFor( 'Userlogin' );
		$loginLink = $skin->link(
			$loginTitle,
			wfMsgHtml( 'loginreqlink' ),
			array(),
			array( 'returnto' => $this->getTitle()->getPrefixedText() ),
			array( 'known', 'noclasses' )
		);
		$this->addHTML( wfMsgWikiHtml( 'loginreqpagetext', $loginLink ) );
		$this->addHTML( "\n<!--" . $this->getTitle()->getPrefixedUrl() . "-->" );

		# Don't return to the main page if the user can't read it
		# otherwise we'll end up in a pointless loop
		$mainPage = Title::newMainPage();
		if( $mainPage->userCanRead() )
			$this->returnToMain( null, $mainPage );
	}

	/** @deprecated */
	public function databaseError( $fname, $sql, $error, $errno ) {
		throw new MWException( "OutputPage::databaseError is obsolete\n" );
	}

	/**
	 * @param array $errors An array of arrays returned by Title::getUserPermissionsErrors
	 * @return string The wikitext error-messages, formatted into a list.
	 */
	public function formatPermissionsErrorMessage( $errors, $action = null ) {
		if ($action == null) {
			$text = wfMsgNoTrans( 'permissionserrorstext', count($errors)). "\n\n";
		} else {
			global $wgLang;
			$action_desc = wfMsgNoTrans( "action-$action" );
			$text = wfMsgNoTrans( 'permissionserrorstext-withaction', count($errors), $action_desc ) . "\n\n";
		}

		if (count( $errors ) > 1) {
			$text .= '<ul class="permissions-errors">' . "\n";

			foreach( $errors as $error )
			{
				$text .= '<li>';
				$text .= call_user_func_array( 'wfMsgNoTrans', $error );
				$text .= "</li>\n";
			}
			$text .= '</ul>';
		} else {
			$text .= "<div class=\"permissions-errors\">\n" . call_user_func_array( 'wfMsgNoTrans', reset( $errors ) ) . "\n</div>";
		}

		return $text;
	}

	/**
	 * Display a page stating that the Wiki is in read-only mode,
	 * and optionally show the source of the page that the user
	 * was trying to edit.  Should only be called (for this
	 * purpose) after wfReadOnly() has returned true.
	 *
	 * For historical reasons, this function is _also_ used to
	 * show the error message when a user tries to edit a page
	 * they are not allowed to edit.  (Unless it's because they're
	 * blocked, then we show blockedPage() instead.)  In this
	 * case, the second parameter should be set to true and a list
	 * of reasons supplied as the third parameter.
	 *
	 * @todo Needs to be split into multiple functions.
	 *
	 * @param string $source    Source code to show (or null).
	 * @param bool   $protected Is this a permissions error?
	 * @param array  $reasons   List of reasons for this error, as returned by Title::getUserPermissionsErrors().
	 */
	public function readOnlyPage( $source = null, $protected = false, $reasons = array(), $action = null ) {
		global $wgUser;
		$skin = $wgUser->getSkin();

		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );

		// If no reason is given, just supply a default "I can't let you do
		// that, Dave" message.  Should only occur if called by legacy code.
		if ( $protected && empty($reasons) ) {
			$reasons[] = array( 'badaccess-group0' );
		}

		if ( !empty($reasons) ) {
			// Permissions error
			if( $source ) {
				$this->setPageTitle( wfMsg( 'viewsource' ) );
				$this->setSubtitle(
					wfMsg(
						'viewsourcefor',
						$skin->link(
							$this->getTitle(),
							null,
							array(),
							array(),
							array( 'known', 'noclasses' )
						)
					)
				);
			} else {
				$this->setPageTitle( wfMsg( 'badaccess' ) );
			}
			$this->addWikiText( $this->formatPermissionsErrorMessage( $reasons, $action ) );
		} else {
			// Wiki is read only
			$this->setPageTitle( wfMsg( 'readonly' ) );
			$reason = wfReadOnlyReason();
			$this->wrapWikiMsg( '<div class="mw-readonly-error">$1</div>', array( 'readonlytext', $reason ) );
		}

		// Show source, if supplied
		if( is_string( $source ) ) {
			$this->addWikiMsg( 'viewsourcetext' );

			$params = array(
				'id'   => 'wpTextbox1',
				'name' => 'wpTextbox1',
				'cols' => $wgUser->getOption( 'cols' ),
				'rows' => $wgUser->getOption( 'rows' ),
				'readonly' => 'readonly'
			);
			$this->addHTML( Html::element( 'textarea', $params, $source ) );

			// Show templates used by this article
			$skin = $wgUser->getSkin();
			$article = new Article( $this->getTitle() );
			$this->addHTML( "<div class='templatesUsed'>
{$skin->formatTemplates( $article->getUsedTemplates() )}
</div>
" );
		}

		# If the title doesn't exist, it's fairly pointless to print a return
		# link to it.  After all, you just tried editing it and couldn't, so
		# what's there to do there?
		if( $this->getTitle()->exists() ) {
			$this->returnToMain( null, $this->getTitle() );
		}
	}

	/** @deprecated */
	public function fatalError( $message ) {
		wfDeprecated( __METHOD__ );
		throw new FatalError( $message );
	}

	/** @deprecated */
	public function unexpectedValueError( $name, $val ) {
		wfDeprecated( __METHOD__ );
		throw new FatalError( wfMsg( 'unexpected', $name, $val ) );
	}

	/** @deprecated */
	public function fileCopyError( $old, $new ) {
		wfDeprecated( __METHOD__ );
		throw new FatalError( wfMsg( 'filecopyerror', $old, $new ) );
	}

	/** @deprecated */
	public function fileRenameError( $old, $new ) {
		wfDeprecated( __METHOD__ );
		throw new FatalError( wfMsg( 'filerenameerror', $old, $new ) );
	}

	/** @deprecated */
	public function fileDeleteError( $name ) {
		wfDeprecated( __METHOD__ );
		throw new FatalError( wfMsg( 'filedeleteerror', $name ) );
	}

	/** @deprecated */
	public function fileNotFoundError( $name ) {
		wfDeprecated( __METHOD__ );
		throw new FatalError( wfMsg( 'filenotfound', $name ) );
	}

	public function showFatalError( $message ) {
		$this->setPageTitle( wfMsg( "internalerror" ) );
		$this->setRobotPolicy( "noindex,nofollow" );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->mBodytext = $message;
	}

	public function showUnexpectedValueError( $name, $val ) {
		$this->showFatalError( wfMsg( 'unexpected', $name, $val ) );
	}

	public function showFileCopyError( $old, $new ) {
		$this->showFatalError( wfMsg( 'filecopyerror', $old, $new ) );
	}

	public function showFileRenameError( $old, $new ) {
		$this->showFatalError( wfMsg( 'filerenameerror', $old, $new ) );
	}

	public function showFileDeleteError( $name ) {
		$this->showFatalError( wfMsg( 'filedeleteerror', $name ) );
	}

	public function showFileNotFoundError( $name ) {
		$this->showFatalError( wfMsg( 'filenotfound', $name ) );
	}

	/**
	 * Add a "return to" link pointing to a specified title
	 *
	 * @param $title Title to link
	 * @param $query String: query string
	 */
	public function addReturnTo( $title, $query = array() ) {
		global $wgUser;
		$this->addLink( array( 'rel' => 'next', 'href' => $title->getFullUrl() ) );
		$link = wfMsgHtml( 'returnto', $wgUser->getSkin()->link(
			$title, null, array(), $query ) );
		$this->addHTML( "<p id=\"mw-returnto\">{$link}</p>\n" );
	}

	/**
	 * Add a "return to" link pointing to a specified title,
	 * or the title indicated in the request, or else the main page
	 *
	 * @param $unused No longer used
	 * @param $returnto Title or String to return to
	 * @param $returntoquery String: query string for the return to link
	 */
	public function returnToMain( $unused = null, $returnto = null, $returntoquery = null ) {
		global $wgRequest;

		if ( $returnto == null ) {
			$returnto = $wgRequest->getText( 'returnto' );
		}

		if ( $returntoquery == null ) {
			$returntoquery = $wgRequest->getText( 'returntoquery' );
		}

		if ( '' === $returnto ) {
			$returnto = Title::newMainPage();
		}

		if ( is_object( $returnto ) ) {
			$titleObj = $returnto;
		} else {
			$titleObj = Title::newFromText( $returnto );
		}
		if ( !is_object( $titleObj ) ) {
			$titleObj = Title::newMainPage();
		}

		$this->addReturnTo( $titleObj, $returntoquery );
	}

	/**
	 * @return string The doctype, opening <html>, and head element.
	 *
	 * @param $sk Skin The given Skin
	 */
	public function headElement( Skin $sk, $includeStyle = true ) {
		global $wgDocType, $wgDTD, $wgContLanguageCode, $wgOutputEncoding, $wgMimeType;
		global $wgXhtmlDefaultNamespace, $wgXhtmlNamespaces, $wgHtml5Version;
		global $wgContLang, $wgUseTrackbacks, $wgStyleVersion, $wgHtml5, $wgWellFormedXml;

		$this->addMeta( "http:Content-Type", "$wgMimeType; charset={$wgOutputEncoding}" );
		if ( $sk->commonPrintStylesheet() ) {
			$this->addStyle( 'common/wikiprintable.css', 'print' );
		}
		$sk->setupUserCss( $this );

		$ret = '';

		if( $wgMimeType == 'text/xml' || $wgMimeType == 'application/xhtml+xml' || $wgMimeType == 'application/xml' ) {
			$ret .= "<?xml version=\"1.0\" encoding=\"$wgOutputEncoding\" ?" . ">\n";
		}

		if ( '' == $this->getHTMLTitle() ) {
			$this->setHTMLTitle(  wfMsg( 'pagetitle', $this->getPageTitle() ));
		}

		$dir = $wgContLang->getDir();

		if ( $wgHtml5 ) {
			if ( $wgWellFormedXml ) {
				# Unknown elements and attributes are okay in XML, but unknown
				# named entities are well-formedness errors and will break XML
				# parsers.  Thus we need a doctype that gives us appropriate
				# entity definitions.  The HTML5 spec permits four legacy
				# doctypes as obsolete but conforming, so let's pick one of
				# those, although it makes our pages look like XHTML1 Strict.
				# Isn't compatibility great?
				$ret .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
			} else {
				# Much saner.
				$ret .= "<!doctype html>\n";
			}
			$ret .= "<html lang=\"$wgContLanguageCode\" dir=\"$dir\" ";
			if ( $wgHtml5Version ) $ret .= " version=\"$wgHtml5Version\" ";
			$ret .= ">\n";
		} else {
			$ret .= "<!DOCTYPE html PUBLIC \"$wgDocType\" \"$wgDTD\">\n";
			$ret .= "<html xmlns=\"{$wgXhtmlDefaultNamespace}\" ";
			foreach($wgXhtmlNamespaces as $tag => $ns) {
				$ret .= "xmlns:{$tag}=\"{$ns}\" ";
			}
			$ret .= "xml:lang=\"$wgContLanguageCode\" lang=\"$wgContLanguageCode\" dir=\"$dir\">\n";
		}

		$ret .= "<head>\n";
		$ret .= "<title>" . htmlspecialchars( $this->getHTMLTitle() ) . "</title>\n";
		$ret .= implode( "\n", array(
			$this->getHeadLinks(),
			$this->buildCssLinks(),
			$this->getHeadScripts( $sk ),
			$this->getHeadItems(),
		));
		if( $sk->usercss ){
			$ret .= Html::inlineStyle( $sk->usercss );
		}

		if ($wgUseTrackbacks && $this->isArticleRelated())
			$ret .= $this->getTitle()->trackbackRDF();

		$ret .= "</head>\n";
		return $ret;
	}

	/*
	 * gets the global variables and mScripts
	 *
	 * also adds userjs to the end if enabled:
	*/
	function getHeadScripts( Skin $sk ) {
		global $wgUser, $wgRequest, $wgJsMimeType, $wgUseSiteJs;
		global $wgStylePath, $wgStyleVersion;

		$scripts = Skin::makeGlobalVariablesScript( $sk->getSkinName() );
		$scripts .= Html::linkedScript( "{$wgStylePath}/common/wikibits.js?$wgStyleVersion" );

		//add site JS if enabled:
		if( $wgUseSiteJs ) {
			$jsCache = $wgUser->isLoggedIn() ? '&smaxage=0' : '';
			$this->addScriptFile(  Skin::makeUrl( '-',
					"action=raw$jsCache&gen=js&useskin=" .
					urlencode( $sk->getSkinName() )
					)
				);
		}

		//add user js if enabled:
		if( $this->isUserJsAllowed() && $wgUser->isLoggedIn() ) {
			$action = $wgRequest->getVal( 'action', 'view' );
			if( $this->mTitle && $this->mTitle->isJsSubpage() and $sk->userCanPreview( $action ) ) {
				# XXX: additional security check/prompt?
				$this->addInlineScript( $wgRequest->getText( 'wpTextbox1' ) );
			} else {
				$userpage = $wgUser->getUserPage();
				$userjs = Skin::makeUrl(
					$userpage->getPrefixedText() . '/' . $sk->getSkinName() . '.js',
					'action=raw&ctype=' . $wgJsMimeType );
				$this->addScriptFile( $userjs );
			}
		}

		$scripts .= "\n" . $this->mScripts;
		return $scripts;
	}

	protected function addDefaultMeta() {
		global $wgVersion, $wgHtml5;

		static $called = false;
		if ( $called ) {
			# Don't run this twice
			return;
		}
		$called = true;

		if ( !$wgHtml5 ) {
			$this->addMeta( 'http:Content-Style-Type', 'text/css' ); //bug 15835
		}
		$this->addMeta( 'generator', "MediaWiki $wgVersion" );

		$p = "{$this->mIndexPolicy},{$this->mFollowPolicy}";
		if( $p !== 'index,follow' ) {
			// http://www.robotstxt.org/wc/meta-user.html
			// Only show if it's different from the default robots policy
			$this->addMeta( 'robots', $p );
		}

		if ( count( $this->mKeywords ) > 0 ) {
			$strip = array(
				"/<.*?" . ">/" => '',
				"/_/" => ' '
			);
			$this->addMeta( 'keywords', preg_replace(array_keys($strip), array_values($strip),implode( ",", $this->mKeywords ) ) );
		}
	}

	/**
	 * @return string HTML tag links to be put in the header.
	 */
	public function getHeadLinks() {
		global $wgRequest, $wgFeed;

		// Ideally this should happen earlier, somewhere. :P
		$this->addDefaultMeta();

		$tags = array();

		foreach ( $this->mMetatags as $tag ) {
			if ( 0 == strcasecmp( 'http:', substr( $tag[0], 0, 5 ) ) ) {
				$a = 'http-equiv';
				$tag[0] = substr( $tag[0], 5 );
			} else {
				$a = 'name';
			}
			$tags[] = Html::element( 'meta',
				array(
					$a => $tag[0],
					'content' => $tag[1] ) );
		}
		foreach ( $this->mLinktags as $tag ) {
			$tags[] = Html::element( 'link', $tag );
		}

		if( $wgFeed ) {
			foreach( $this->getSyndicationLinks() as $format => $link ) {
				# Use the page name for the title (accessed through $wgTitle since
				# there's no other way).  In principle, this could lead to issues
				# with having the same name for different feeds corresponding to
				# the same page, but we can't avoid that at this low a level.

				$tags[] = $this->feedLink(
					$format,
					$link,
					wfMsg( "page-{$format}-feed", $this->getTitle()->getPrefixedText() ) ); # Used messages: 'page-rss-feed' and 'page-atom-feed' (for an easier grep)
			}

			# Recent changes feed should appear on every page (except recentchanges,
			# that would be redundant). Put it after the per-page feed to avoid
			# changing existing behavior. It's still available, probably via a
			# menu in your browser. Some sites might have a different feed they'd
			# like to promote instead of the RC feed (maybe like a "Recent New Articles"
			# or "Breaking news" one). For this, we see if $wgOverrideSiteFeed is defined.
			# If so, use it instead.

			global $wgOverrideSiteFeed, $wgSitename, $wgFeedClasses;
			$rctitle = SpecialPage::getTitleFor( 'Recentchanges' );

			if ( $wgOverrideSiteFeed ) {
				foreach ( $wgOverrideSiteFeed as $type => $feedUrl ) {
					$tags[] = $this->feedLink (
						$type,
						htmlspecialchars( $feedUrl ),
						wfMsg( "site-{$type}-feed", $wgSitename ) );
				}
			}
			else if ( $this->getTitle()->getPrefixedText() != $rctitle->getPrefixedText() ) {
				foreach( $wgFeedClasses as $format => $class ) {
					$tags[] = $this->feedLink(
						$format,
						$rctitle->getLocalURL( "feed={$format}" ),
						wfMsg( "site-{$format}-feed", $wgSitename ) ); # For grep: 'site-rss-feed', 'site-atom-feed'.
				}
			}
		}

		return implode( "\n", $tags );
	}

	/**
	 * Return URLs for each supported syndication format for this page.
	 * @return array associating format keys with URLs
	 */
	public function getSyndicationLinks() {
		return $this->mFeedLinks;
	}

	/**
	 * Generate a <link rel/> for an RSS feed.
	 */
	private function feedLink( $type, $url, $text ) {
		return Html::element( 'link', array(
			'rel' => 'alternate',
			'type' => "application/$type+xml",
			'title' => $text,
			'href' => $url ) );
	}

	/**
	 * Add a local or specified stylesheet, with the given media options.
	 * Meant primarily for internal use...
	 *
	 * @param $media -- to specify a media type, 'screen', 'printable', 'handheld' or any.
	 * @param $conditional -- for IE conditional comments, specifying an IE version
	 * @param $dir -- set to 'rtl' or 'ltr' for direction-specific sheets
	 */
	public function addStyle( $style, $media='', $condition='', $dir='' ) {
		$options = array();
		// Even though we expect the media type to be lowercase, but here we
		// force it to lowercase to be safe.
		if( $media )
			$options['media'] = $media;
		if( $condition )
			$options['condition'] = $condition;
		if( $dir )
			$options['dir'] = $dir;
		$this->styles[$style] = $options;
	}

	/**
	 * Adds inline CSS styles
	 * @param $style_css Mixed: inline CSS
	 */
	public function addInlineStyle( $style_css ){
		$this->mScripts .= Html::inlineStyle( $style_css );
	}

	/**
	 * Build a set of <link>s for the stylesheets specified in the $this->styles array.
	 * These will be applied to various media & IE conditionals.
	 */
	public function buildCssLinks() {
		$links = array();
		foreach( $this->styles as $file => $options ) {
			$link = $this->styleLink( $file, $options );
			if( $link )
				$links[] = $link;
		}

		return implode( "\n", $links );
	}

	protected function styleLink( $style, $options ) {
		global $wgRequest;

		if( isset( $options['dir'] ) ) {
			global $wgContLang;
			$siteDir = $wgContLang->getDir();
			if( $siteDir != $options['dir'] )
				return '';
		}

		if( isset( $options['media'] ) ) {
			$media = $this->transformCssMedia( $options['media'] );
			if( is_null( $media ) ) {
				return '';
			}
		} else {
			$media = 'all';
		}

		if( substr( $style, 0, 1 ) == '/' ||
			substr( $style, 0, 5 ) == 'http:' ||
			substr( $style, 0, 6 ) == 'https:' ) {
			$url = $style;
		} else {
			global $wgStylePath, $wgStyleVersion;
			$url = $wgStylePath . '/' . $style . '?' . $wgStyleVersion;
		}

		$link = Html::linkedStyle( $url, $media );

		if( isset( $options['condition'] ) ) {
			$condition = htmlspecialchars( $options['condition'] );
			$link = "<!--[if $condition]>$link<![endif]-->";
		}
		return $link;
	}

	function transformCssMedia( $media ) {
		global $wgRequest, $wgHandheldForIPhone;

		// Switch in on-screen display for media testing
		$switches = array(
			'printable' => 'print',
			'handheld' => 'handheld',
		);
		foreach( $switches as $switch => $targetMedia ) {
			if( $wgRequest->getBool( $switch ) ) {
				if( $media == $targetMedia ) {
					$media = '';
				} elseif( $media == 'screen' ) {
					return null;
				}
			}
		}

		// Expand longer media queries as iPhone doesn't grok 'handheld'
		if( $wgHandheldForIPhone ) {
			$mediaAliases = array(
				'screen' => 'screen and (min-device-width: 481px)',
				'handheld' => 'handheld, only screen and (max-device-width: 480px)',
			);

			if( isset( $mediaAliases[$media] ) ) {
				$media = $mediaAliases[$media];
			}
		}

		return $media;
	}

	/**
	 * Turn off regular page output and return an error reponse
	 * for when rate limiting has triggered.
	 */
	public function rateLimited() {

		$this->setPageTitle(wfMsg('actionthrottled'));
		$this->setRobotPolicy( 'noindex,follow' );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->clearHTML();
		$this->setStatusCode(503);
		$this->addWikiMsg( 'actionthrottledtext' );

		$this->returnToMain( null, $this->getTitle() );
	}

	/**
	 * Show an "add new section" link?
	 *
	 * @return bool
	 */
	public function showNewSectionLink() {
		return $this->mNewSectionLink;
	}

	/**
	* Forcibly hide the new section link?
	*
	* @return bool
	*/
	public function forceHideNewSectionLink() {
		return $this->mHideNewSectionLink;
	}

	/**
	 * Show a warning about slave lag
	 *
	 * If the lag is higher than $wgSlaveLagCritical seconds,
	 * then the warning is a bit more obvious. If the lag is
	 * lower than $wgSlaveLagWarning, then no warning is shown.
	 *
	 * @param int $lag Slave lag
	 */
	public function showLagWarning( $lag ) {
		global $wgSlaveLagWarning, $wgSlaveLagCritical, $wgLang;
		if( $lag >= $wgSlaveLagWarning ) {
			$message = $lag < $wgSlaveLagCritical
				? 'lag-warn-normal'
				: 'lag-warn-high';
			$wrap = Html::rawElement( 'div', array( 'class' => "mw-{$message}" ), "\n$1\n" );
			$this->wrapWikiMsg( "$wrap\n", array( $message, $wgLang->formatNum( $lag ) ) );
		}
	}

	/**
	 * Add a wikitext-formatted message to the output.
	 * This is equivalent to:
	 *
	 *    $wgOut->addWikiText( wfMsgNoTrans( ... ) )
	 */
	public function addWikiMsg( /*...*/ ) {
		$args = func_get_args();
		$name = array_shift( $args );
		$this->addWikiMsgArray( $name, $args );
	}

	/**
	 * Add a wikitext-formatted message to the output.
	 * Like addWikiMsg() except the parameters are taken as an array
	 * instead of a variable argument list.
	 *
	 * $options is passed through to wfMsgExt(), see that function for details.
	 */
	public function addWikiMsgArray( $name, $args, $options = array() ) {
		$options[] = 'parse';
		$text = wfMsgExt( $name, $options, $args );
		$this->addHTML( $text );
	}

	/**
	 * This function takes a number of message/argument specifications, wraps them in
	 * some overall structure, and then parses the result and adds it to the output.
	 *
	 * In the $wrap, $1 is replaced with the first message, $2 with the second, and so
	 * on. The subsequent arguments may either be strings, in which case they are the
	 * message names, or arrays, in which case the first element is the message name,
	 * and subsequent elements are the parameters to that message.
	 *
	 * The special named parameter 'options' in a message specification array is passed
	 * through to the $options parameter of wfMsgExt().
	 *
	 * Don't use this for messages that are not in users interface language.
	 *
	 * For example:
	 *
	 *    $wgOut->wrapWikiMsg( '<div class="error">$1</div>', 'some-error' );
	 *
	 * Is equivalent to:
	 *
	 *    $wgOut->addWikiText( '<div class="error">' . wfMsgNoTrans( 'some-error' ) . '</div>' );
	 */
	public function wrapWikiMsg( $wrap /*, ...*/ ) {
		$msgSpecs = func_get_args();
		array_shift( $msgSpecs );
		$msgSpecs = array_values( $msgSpecs );
		$s = $wrap;
		foreach ( $msgSpecs as $n => $spec ) {
			$options = array();
			if ( is_array( $spec ) ) {
				$args = $spec;
				$name = array_shift( $args );
				if ( isset( $args['options'] ) ) {
					$options = $args['options'];
					unset( $args['options'] );
				}
			}  else {
				$args = array();
				$name = $spec;
			}
			$s = str_replace( '$' . ( $n + 1 ), wfMsgExt( $name, $options, $args ), $s );
		}
		$this->addHTML( $this->parse( $s, /*linestart*/true, /*uilang*/true ) );
	}
}
