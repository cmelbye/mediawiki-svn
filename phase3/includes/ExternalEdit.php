<?php
/**
 * External editors support
 *
 * License: Public domain
 *
 * @file
 * @author Erik Moeller <moeller@scireview.de>
 */

/**
 * Support for external editors to modify both text and files
 * in external applications. It works as follows: MediaWiki
 * sends a meta-file with the MIME type 'application/x-external-editor'
 * to the client. The user has to associate that MIME type with
 * a helper application (a reference implementation in Perl
 * can be found in extensions/ee), which will launch the editor,
 * and save the modified data back to the server.
 *
 */
class ExternalEdit {

	function __construct( $article, $mode ) {
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;
		$this->mCharset = 'UTF-8';
		$this->mMode = $mode;
	}

	function edit() {
		global $wgOut, $wgScript, $wgScriptPath, $wgServer, $wgLang;
		$wgOut->disable();
		$name=$this->mTitle->getText();
		$pos=strrpos($name,".")+1;
		header ( "Content-type: application/x-external-editor; charset=".$this->mCharset );
		header( "Cache-control: no-cache" );

		# $type can be "Edit text", "Edit file" or "Diff text" at the moment
		# See the protocol specifications at [[m:Help:External editors/Tech]] for
		# details.
		if(!isset($this->mMode)) {
			$type="Edit text";
			$url=$this->mTitle->getFullURL("action=edit&internaledit=true");
			# *.wiki file extension is used by some editors for syntax
			# highlighting, so we follow that convention
			$extension="wiki";
		} elseif($this->mMode=="file") {
			$type="Edit file";
			$image = wfLocalFile( $this->mTitle );
			$url = $image->getFullURL();
			$extension=substr($name, $pos);
		}
		$special=$wgLang->getNsText(NS_SPECIAL);
		$control = <<<CONTROL
; You're seeing this file because you're using Mediawiki's External Editor
; feature. This is probably because you selected use external editor
; in your preferences. To edit normally, either disable that preference
; or go to the URL $url .
; See http://www.mediawiki.org/wiki/Manual:External_editors for details.
[Process]
Type=$type
Engine=MediaWiki
Script={$wgServer}{$wgScript}
Server={$wgServer}
Path={$wgScriptPath}
Special namespace=$special

[File]
Extension=$extension
URL=$url
CONTROL;
		echo $control;
	}
}
