<?php
/**
 * Gadgets extension - lets users select custom javascript gadgets
 *
 *
 * For more info see http://mediawiki.org/wiki/Extension:Gadgets
 *
 * @file
 * @ingroup Extensions
 * @author Daniel Kinzler, brightbyte.de
 * @copyright © 2007 Daniel Kinzler
 * @license GNU General Public Licence 2.0 or later
 */

class Gadgets {

	public static function ArticleSaveComplete( $article, $user, $text ) {
		//update cache if MediaWiki:Gadgets-definition was edited
		$title = $article->mTitle;
		if( $title->getNamespace() == NS_MEDIAWIKI && $title->getText() == 'Gadgets-definition' ) {
			Gadget::loadStructuredList( $text );
		}
		return true;
	}

	public static function getPreferences( $user, &$preferences ) {
		$gadgets = Gadget::loadStructuredList();
		if (!$gadgets) return true;
		
		$options = array();
		foreach( $gadgets as $section => $thisSection ) {
			if ( $section !== '' ) {
				$section = wfMsgExt( "gadget-section-$section", 'parseinline' );
				$options[$section] = array();
				$destination = &$options[$section];
			} else {
				$destination = &$options;
			}
			foreach( $thisSection as $gadget ) {
				$gname = $gadget->getName();
				$destination[wfMsgExt( "gadget-$gname", 'parseinline' )] = $gname;
			}
		}
		
		$preferences['gadgets-intro'] =
			array(
				'type' => 'info',
				'label' => '&#160;',
				'default' => Xml::tags( 'tr', array(),
					Xml::tags( 'td', array( 'colspan' => 2 ),
						wfMsgExt( 'gadgets-prefstext', 'parse' ) ) ),
				'section' => 'gadgets',
				'raw' => 1,
				'rawrow' => 1,
			);
		
		$preferences['gadgets'] = 
			array(
				'type' => 'multiselect',
				'options' => $options,
				'section' => 'gadgets',
				'label' => '&#160;',
				'prefix' => 'gadget-',
			);
			
		return true;
	}

	public static function registerModules( &$resourceLoader ) {
		$gadgets = Gadget::loadList();
		if ( !$gadgets ) {
			return true;
		}
		foreach ( $gadgets as $g ) {
			$module = $g->getModule();
			if ( $module ) {
				$resourceLoader->register( $g->getModuleName(), $module );
			}
		}
		return true;
	}

	public static function beforePageDisplay( $out ) {
		global $wgUser;
		if ( !$wgUser->isLoggedIn() ) return true;

		//disable all gadgets on critical special pages
		//NOTE: $out->isUserJsAllowed() is tempting, but always fals if $wgAllowUserJs is false.
		//      That would disable gadgets on wikis without user JS. Introducing $out->isJsAllowed()
		//	may work, but should that really apply also to MediaWiki:common.js? Even on the preference page?
		//	See bug 22929 for discussion.
		$title = $out->getTitle();
		if ( $title->isSpecial( 'Preferences' ) 
			|| $title->isSpecial( 'Resetpass' )
			|| $title->isSpecial( 'Userlogin' ) ) {
			return true;
		}

		$gadgets = Gadget::loadList();
		if ( !$gadgets ) return true;

		$lb = new LinkBatch();
		$lb->setCaller( __METHOD__ );
		$pages = array();

		foreach ( $gadgets as $gadget ) {
			$tname = 'gadget-' . $gadget->getName();
			if ( $wgUser->getOption( $tname ) ) {
				if ( $gadget->hasModule() ) {
					$out->addModules( $gadget->getModuleName() );
				}
				foreach ( $gadget->getLegacyScripts() as $page ) {
					$lb->add( NS_MEDIAWIKI, $page );
					$pages[] = $page;
				}
			}
		}

		$lb->execute( __METHOD__ );

		$pages = array();
		$done = array();
		foreach ( $pages as $page ) {
			if ( isset( $done[$page] ) ) continue;
			$done[$page] = true;
			self::applyGadgetCode( $page, $out );
		}

		return true;
	}

	private static function applyGadgetCode( $page, $out ) {
		global $wgJsMimeType;

		//FIXME: stuff added via $out->addScript appears below usercss and userjs in the head tag.
		//       but we'd want it to appear above explicit user stuff, so it can be overwritten.

		$t = Title::makeTitleSafe( NS_MEDIAWIKI, $page );
		if ( !$t ) continue;

		$u = $t->getLocalURL( 'action=raw&ctype=' . $wgJsMimeType );
		//switched to addScriptFile call to support scriptLoader
		$out->addScriptFile( $u, $t->getLatestRevID() );
	}
}

class Gadget {
	const GADGET_CLASS_VERSION = 1; // Increment this when changing fields

	private $version = self::GADGET_CLASS_VERSION,
	        $scripts = array(),
	        $styles = array(),
	        $name,
			$resourceLoaded = false;

	public static function newFromDefinition( $definition ) {
		if ( !preg_match( '/^\*+ *([a-zA-Z](?:[-_:.\w\d ]*[a-zA-Z0-9])?)\s*((\|[^|]*)+)\s*$/', $definition, $m ) ) {
			return false;
		}
		//NOTE: the gadget name is used as part of the name of a form field,
		//      and must follow the rules defined in http://www.w3.org/TR/html4/types.html#type-cdata
		//      Also, title-normalization applies.
		$gadget = new Gadget();
		$gadget->name = str_replace(' ', '_', $m[1] );
		foreach( preg_split( '/\s*\|\s*/', $m[2], -1, PREG_SPLIT_NO_EMPTY ) as $page ) {
			$page = "Gadget-$page";
			if ( preg_match( '/\.js/', $page ) ) {
				$gadget->scripts[] = $page;
			} elseif ( preg_match( '/\.css/', $page ) ) {
				$gadget->styles[] = $page;
			}
		}
		return $gadget;
	}

	public function getName() {
		return $this->name;
	}

	public function getModuleName() {
		return "ext.gadget.{$this->name}";
	}

	public function isOutdated() {
		return $this->version != GADGET_CLASS_VERSION;
	}

	public function supportsResourceLoader() {
		return $this->resourceLoaded;
	}

	public function hasModule() {
		return count( $this->styles ) 
			+ ( $this->supportsResourceLoader() ? count( $this->scripts ) : 0 ) 
				> 0;
	}

	public function getScripts() {
		return $this->scripts;
	}

	public function getStyles() {
		return $this->styles;
	}

	public function getScriptsAndStyles() {
		return $this->scripts + $this->styles;
	}

	public function getModule() {
		$pages = $this->styles;
		if ( $this->supportsResourceLoader() ) {
			$pages += $this->scripts;
		}
		if ( count( $pages ) ) {
		}
		return new GadgetResourceLoaderModule( $pages );
	}

	public function getLegacyScripts() {
		if ( $this->supportsResourceLoader() ) {
			return array();
		}
		return $this->scripts;
	}

	public static function loadList() {
		static $gadgets = null;

		if ( $gadgets !== null ) return $gadgets;

		$struct = self::loadStructuredList();
		if ( !$struct ) {
			$gadgets = $struct;
			return $gadgets;
		}

		$gadgets = array();
		foreach ( $struct as $section => $entries ) {
			$gadgets = array_merge( $gadgets, $entries );
		}

		return $gadgets;
	}

	public static function loadStructuredList( $forceNewText = null ) {
		global $wgMemc;

		static $gadgets = null;
		if ( $gadgets !== null && $forceNewText === null ) return $gadgets;

		$key = wfMemcKey( 'gadgets-definition' );

		if ( $forceNewText === null ) {
			//cached?
			$gadgets = $wgMemc->get( $key );
			// TODO: isOutdated()
			if ( is_array($gadgets) && next( $gadgets ) instanceof Gadget ) return $gadgets;

			$g = wfMsgForContentNoTrans( "gadgets-definition" );
			if ( wfEmptyMsg( "gadgets-definition", $g ) ) {
				$gadgets = false;
				return $gadgets;
			}
		} else {
			$g = $forceNewText;
		}

		$g = preg_replace( '/<!--.*-->/s', '', $g );
		$g = preg_split( '/(\r\n|\r|\n)+/', $g );

		$gadgets = array();
		$section = '';

		foreach ( $g as $line ) {
			if ( preg_match( '/^==+ *([^*:\s|]+?)\s*==+\s*$/', $line, $m ) ) {
				$section = $m[1];
			}
			else {
				$gadget = Gadget::newFromDefinition( $line );
				if ( $gadget ) {
					$gadgets[$section][$gadget->getName()] = $gadget;
				}
			}
		}

		//cache for a while. gets purged automatically when MediaWiki:Gadgets-definition is edited
		$wgMemc->set( $key, $gadgets, 60*60*24 );
		$source = $forceNewText !== null ? 'input text' : 'MediaWiki:Gadgets-definition';
		wfDebug( __METHOD__ . ": $source parsed, cache entry $key updated\n");

		return $gadgets;
	}
}

class GadgetResourceLoaderModule extends ResourceLoaderWikiModule {
	private $pages;

	public function __construct( $pages ) {
		$this->pages = $pages;
	}

	protected function getPages( ResourceLoaderContext $context ) {
		return $this->pages;
	}
}