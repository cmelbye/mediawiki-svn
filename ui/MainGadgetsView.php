<?php

class MainGadgetsView extends GadgetsView {
	public function __construct( SpecialGadgets $parent ) {
		parent::__construct( $parent, array() );
	}

	public function execute() {
		global $wgOut, $wgUser, $wgLang, $wgContLang;

		$skin = $wgUser->getSkin();

		$wgOut->addWikiMsg( 'gadgets-pagetext' );

		$gadgets = Gadget::loadStructuredList();
		if ( !$gadgets ) return;

		$lang = "";
		if ( $wgLang->getCode() != $wgContLang->getCode() ) {
			$lang = "/" . $wgLang->getCode();
		}

		$listOpen = false;

		$msgOpt = array( 'parseinline', 'parsemag' );
		$editInterfaceAllowed = $wgUser->isAllowed( 'editinterface' );
			
		foreach ( $gadgets as $section => $entries ) {
			if ( $section !== false && $section !== '' ) {
				$t = Title::makeTitleSafe( NS_MEDIAWIKI, "Gadget-section-$section$lang" );
				if ( $editInterfaceAllowed ) {
					$lnkTarget = $t
						? $skin->link( $t, wfMsgHTML( 'edit' ), array(), array( 'action' => 'edit' ) ) 
						: htmlspecialchars( $section );
					$lnk =  "&#160; &#160; [$lnkTarget]";
				} else {
					$lnk = '';
				}
				$ttext = wfMsgExt( "gadget-section-$section", $msgOpt );

				if( $listOpen ) {
					$wgOut->addHTML( Xml::closeElement( 'ul' ) . "\n" );
					$listOpen = false;
				}
				$wgOut->addHTML( Html::rawElement( 'h2', array(), $ttext . $lnk ) . "\n" );
			}

			foreach ( $entries as $gadget ) {
				$t = Title::makeTitleSafe( NS_MEDIAWIKI, "Gadget-{$gadget->getName()}$lang" );
				if ( !$t ) continue;

				$links = array();
				if ( $editInterfaceAllowed ) {
					$links[] = $skin->link( $t, wfMsgHTML( 'edit' ), array(), array( 'action' => 'edit' ) );
				}
				$links[] = $skin->link( $this->getTitle( "export/{$gadget->getName()}" ), wfMsgHtml( 'gadgets-export' ) );
				
				$ttext = wfMsgExt( "gadget-{$gadget->getName()}", $msgOpt );

				if( !$listOpen ) {
					$listOpen = true;
					$wgOut->addHTML( Xml::openElement( 'ul' ) );
				}
				$lnk = '&#160;&#160;' . wfMsg( 'parentheses', $wgLang->pipeList( $links ) );
				$wgOut->addHTML( Xml::openElement( 'li' ) .
						$ttext . $lnk . "<br />" .
						wfMsgHTML( 'gadgets-uses' ) . wfMsg( 'colon-separator' )
				);

				$lnk = array();
				foreach ( $gadget->getScriptsAndStyles() as $codePage ) {
					$t = Title::makeTitleSafe( NS_MEDIAWIKI, $codePage );
					if ( !$t ) continue;

					$lnk[] = $skin->link( $t, htmlspecialchars( $t->getText() ) );
				}
				$wgOut->addHTML( $wgLang->commaList( $lnk ) );
				$rights = $gadget->getRequiredRights();
				if ( count( $rights ) ) {
					$wgOut->addHTML( '<br />' . 
						wfMessage( 'gadgets-required-rights', $wgLang->commaList( $rights ), count( $rights ) )->parse()
					);
				}
				if ( $gadget->isOnByDefault() ) {
					$wgOut->addHTML( '<br />' . wfMessage( 'gadgets-default' )->parse() );
				}
				
				$wgOut->addHTML( Xml::closeElement( 'li' ) . "\n" );
			}
		}

		if( $listOpen ) {
			$wgOut->addHTML( Xml::closeElement( 'ul' ) . "\n" );
		}
	}

	public function getTitle() {
		return wfMessage( 'gadgets-title' );
	}
}