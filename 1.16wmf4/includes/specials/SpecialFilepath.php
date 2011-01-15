<?php
/**
 * @file
 * @ingroup SpecialPage
 */

function wfSpecialFilepath( $par ) {
	global $wgRequest, $wgOut;

	$file = isset( $par ) ? $par : $wgRequest->getText( 'file' );

	$title = Title::makeTitleSafe( NS_FILE, $file );

	if ( ! $title instanceof Title || $title->getNamespace() != NS_FILE ) {
		$cform = new FilepathForm( $title );
		$cform->execute();
	} else {
		$file = wfFindFile( $title );
		if ( $file && $file->exists() ) {
			$url = $file->getURL();
			$width = $wgRequest->getInt( 'width', -1 );
			$height = $wgRequest->getInt( 'height', -1 );
			if ( $width != -1 ) {
				$mto = $file->transform( array( 'width' => $width, 'height' => $height ) );
				if ( $mto && !$mto->isError() ) {
					$url = $mto->getURL();
				}
			}
			$wgOut->redirect( $url );
		} else {
			$wgOut->setStatusCode( 404 );
			$cform = new FilepathForm( $title );
			$cform->execute();
		}
	}
}

/**
 * @ingroup SpecialPage
 */
class FilepathForm {
	var $mTitle;

	function FilepathForm( &$title ) {
		$this->mTitle =& $title;
	}

	function execute() {
		global $wgOut, $wgScript;

		$wgOut->addHTML(
			Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'id' => 'specialfilepath' ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'filepath' ) ) .
			Xml::hidden( 'title', SpecialPage::getTitleFor( 'Filepath' )->getPrefixedText() ) .
			Xml::inputLabel( wfMsg( 'filepath-page' ), 'file', 'file', 25, is_object( $this->mTitle ) ? $this->mTitle->getText() : '' ) . ' ' .
			Xml::submitButton( wfMsg( 'filepath-submit' ) ) . "\n" .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		);
	}
}
