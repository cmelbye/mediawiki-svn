<?php

abstract class CodeCommentLinker {

	/**
	 * @var Skin
	 */
	protected $skin;

	/**
	 * @var CodeRepository
	 */
	protected $mRepo;

	function __construct( $repo ) {
		global $wgUser;
		$this->skin = $wgUser->getSkin();
		$this->mRepo = $repo;
	}

	function link( $text ) {
		# Catch links like http://www.mediawiki.org/wiki/Special:Code/MediaWiki/44245#c829
		# Ended by space or brackets (like those pesky <br /> tags)
		$text = preg_replace_callback( '/(^|[^\w[])(' . wfUrlProtocols() . ')(' . Parser::EXT_LINK_URL_CLASS . '+)/',
			array( $this, 'generalLink' ), $text );
		$text = preg_replace_callback( '/\br(\d+)\b/',
			array( $this, 'messageRevLink' ), $text );
		$text = preg_replace_callback( CodeRevision::BugReference,
			array( $this, 'messageBugLink' ), $text );
		return $text;
	}

	function generalLink( $arr ) {
		$url = $arr[2] . $arr[3];
		// Re-add the surrounding space/punctuation
		return $arr[1] . $this->makeExternalLink( $url, $url );
	}

	function messageBugLink( $arr ) {
		$text = $arr[0];
		$bugNo = intval( $arr[1] );
		$url = $this->mRepo->getBugPath( $bugNo );
		if ( $url ) {
			return $this->makeExternalLink( $url, $text );
		} else {
			return $text;
		}
	}

	function messageRevLink( $matches ) {
		$text = $matches[0];
		$rev = intval( $matches[1] );

		$repo = $this->mRepo->getName();
		$title = SpecialPage::getTitleFor( 'Code', "$repo/$rev" );

		return $this->makeInternalLink( $title, $text );
	}

	abstract function makeExternalLink( $url, $text );

	abstract function makeInternalLink( $title, $text );
}

class CodeCommentLinkerHtml extends CodeCommentLinker {
	function makeExternalLink( $url, $text ) {
		return $this->skin->makeExternalLink( $url, $text );
	}

	function makeInternalLink( $title, $text ) {
		return $this->skin->link( $title, $text );
	}
}

class CodeCommentLinkerWiki extends CodeCommentLinker {
	function makeExternalLink( $url, $text ) {
		return "[$url $text]";
	}

	/**
	 * @param Title $title
	 * @param  $text
	 * @return string
	 */
	function makeInternalLink( $title, $text ) {
		return "[[" . $title->getPrefixedText() . "|$text]]";
	}
}
