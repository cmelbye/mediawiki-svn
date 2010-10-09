<?php

// Special:Code/MediaWiki/author
class CodeAuthorListView extends CodeView {
	function __construct( $repoName ) {
		parent::__construct();
		$this->mRepo = CodeRepository::newFromName( $repoName );
	}

	function execute() {
		global $wgOut, $wgLang;
		$authors = $this->mRepo->getAuthorList();
		$repo = $this->mRepo->getName();
		$text = wfMsg( 'code-authors-text' ) . "\n\n";
		$text .= '<strong>' . wfMsg( 'code-author-total', $wgLang->formatNum( $this->mRepo->getAuthorCount() ) )  . "</strong>\n";

		$wgOut->addWikiText( $text );

		$wgOut->addHTML( '<table class="TablePager">'
				. '<tr><th>' . wfMsgHtml( 'code-field-author' )
				. '</th><th>' . wfMsgHtml( 'code-author-lastcommit' ) . '</th></tr>' );

		foreach ( $authors as $committer ) {
			if ( $committer ) {
				$wgOut->addHTML( "<tr><td>" );
				$author = $committer["author"];
				$text = "[[Special:Code/$repo/author/$committer|$author]]";
				$user = $this->mRepo->authorWikiUser( $author );
				if ( $user ) {
					$title = htmlspecialchars( $user->getUserPage()->getPrefixedText() );
					$name = htmlspecialchars( $user->getName() );
					$text .= " ([[$title|$name]])";
				}
				$wgOut->addWikiText( $text );

			    $wgOut->addHTML( "</td><td>{$wgLang->timeanddate( $committer["lastcommit"], true )}</td></tr>" );
			}
		}

	    $wgOut->addHTML( '</table>' );
	}
}
