<?php

// Special:Code/MediaWiki/stats
class CodeRepoStatsView extends CodeView {

	function __construct( $repo ) {
		parent::__construct( $repo );
	}

	function execute() {
		global $wgOut, $wgLang;

		$stats = RepoStats::newFromRepo( $this->mRepo );
		$repoName = $this->mRepo->getName();
		$wgOut->wrapWikiMsg( '<h2 id="stats-main">$1</h2>', array( 'code-stats-header', $repoName ) );
		$wgOut->addWikiMsg( 'code-stats-main',
			$wgLang->timeanddate( $stats->time, true ),
			$wgLang->formatNum( $stats->revisions ),
			$repoName,
			$wgLang->formatNum( $stats->authors ),
			$wgLang->time( $stats->time, true ),
			$wgLang->date( $stats->time, true )
		);

		if ( !empty( $stats->states ) ) {
			$wgOut->wrapWikiMsg( '<h3 id="stats-revisions">$1</h3>', 'code-stats-status-breakdown' );
			$wgOut->addHTML( '<table class="TablePager">'
				. '<tr><th>' . wfMsgHtml( 'code-field-status' ) . '</th><th>'
				. wfMsgHtml( 'code-stats-count' ) . '</th></tr>' );
			foreach ( CodeRevision::getPossibleStates() as $state ) {
				$count = isset( $stats->states[$state] ) ? $stats->states[$state] : 0;
				$count = htmlspecialchars( $wgLang->formatNum( $count ) );
				$link = $this->skin->link(
					SpecialPage::getTitleFor( 'Code', $repoName . '/status/' . $state ),
					htmlspecialchars( $this->statusDesc( $state ) )
				);
				$wgOut->addHTML( "<tr><td>$link</td>"
					. "<td class=\"mw-codereview-status-$state\">$count</td></tr>" );
			}
			$wgOut->addHTML( '</table>' );
		}

		if ( !empty( $stats->fixmes ) ) {
			$wgOut->wrapWikiMsg( '<h3 id="stats-fixme">$1</h3>', 'code-stats-fixme-breakdown' );
			$wgOut->addHTML( '<table class="TablePager">'
				. '<tr><th>' . wfMsgHtml( 'code-field-author' ) . '</th><th>'
				. wfMsgHtml( 'code-stats-count' ) . '</th></tr>' );
			foreach ( $stats->fixmes as $user => $count ) {
				$count = htmlspecialchars( $wgLang->formatNum( $count ) );
				$link = $this->skin->link(
					SpecialPage::getTitleFor( 'Code', $repoName . '/status/fixme' ),
					htmlspecialchars( $user ),
					array(),
					array( 'author' => $user )
				);
				$wgOut->addHTML( "<tr><td>$link</td>"
					. "<td>$count</td></tr>" );
			}
			$wgOut->addHTML( '</table>' );
		}
	}
}
