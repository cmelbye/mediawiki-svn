<?php
/**
 * @file
 * @ingroup SMWDataValues
 */

/**
 * This datavalue implements String-Datavalues suitable for defining
 * String-types of properties.
 *
 * @author Nikolas Iwan
 * @author Markus Krötzsch
 * @ingroup SMWDataValues
 */
class SMWStringValue extends SMWDataValue {

	protected function parseUserValue( $value ) {
		smwfLoadExtensionMessages( 'SemanticMediaWiki' );
		if ( $this->m_caption === false ) {
			$this->m_caption = ( $this->m_typeid == '_cod' ) ? $this->getCodeDisplay( $value ) : $value;
		}
		if ( $value == '' ) {
			$this->addError( wfMsgForContent( 'smw_emptystring' ) );
		}

		if ( ( $this->m_typeid == '_txt' ) || ( $this->m_typeid == '_cod' ) ) {
			$this->m_dataitem = new SMWDIBlob( $value, $this->m_typeid );
		} else {
			try {
				$this->m_dataitem = new SMWDIString( $value, $this->m_typeid );
			} catch ( SMWStringLengthException $e ) {
				$this->addError( wfMsgForContent( 'smw_maxstring', '"' . mb_substr( $value, 0, 15 ) . ' … ' . mb_substr( $value, mb_strlen( $value ) - 15 ) . '"' ) );
				$this->m_dataitem = new SMWDIBlob( 'ERROR', $this->m_typeid ); // just to make sure that something is defined here
			}
		}
	}

	protected function parseDBkeys( $args ) {
		$this->parseUserValue( $args[0] );
		$this->m_caption = $this->m_dataitem->getString(); // this is our output text
	}

	/**
	 * @see SMWDataValue::setDataItem()
	 * @param $dataitem SMWDataItem
	 * @return boolean
	 */
	public function setDataItem( SMWDataItem $dataItem ) {
		$diType = ( ( $this->m_typeid == '_txt' ) || ( $this->m_typeid == '_cod' ) ) ? SMWDataItem::TYPE_BLOB : SMWDataItem::TYPE_STRING;
		if ( $dataItem->getDIType() == $diType ) {
			$this->m_dataitem = $dataItem;
			if ( $this->m_typeid == '_cod' ) {
				$this->m_caption = $this->getCodeDisplay( $this->m_dataitem->getString() );
			} else {
				$this->m_caption = $this->m_dataitem->getString();
			}
			return true;
		} else {
			return false;
		}
	}

	public function getShortWikiText( $linked = null ) {
		$this->unstub();
		return $this->m_caption;
	}

	/**
	 * @todo Rather parse input to obtain properly formatted HTML.
	 */
	public function getShortHTMLText( $linker = null ) {
		return smwfXMLContentEncode( $this->getShortWikiText( $linker ) );
	}

	public function getLongWikiText( $linked = null ) {
		return $this->isValid() ? $this->getAbbValue( $linked, $this->m_dataitem->getString() ) : $this->getErrorText();
	}

	/**
	 * @todo Rather parse input to obtain properly formatted HTML.
	 */
	public function getLongHTMLText( $linker = null ) {
		return $this->isValid() ? $this->getAbbValue( $linker, smwfXMLContentEncode( $this->m_dataitem->getString() ) ) : $this->getErrorText();
	}

	public function getDBkeys() {
		$this->unstub();
		return array( $this->m_dataitem->getString() );
	}

	public function getSignature() {
		return  ( ( $this->m_typeid == '_txt' ) || ( $this->m_typeid == '_cod' ) ) ? 'l':'t';
	}

	/**
	 * For perfomance reasons, long text data like _txt and _cod does not
	 * support sorting. This class can be subclassed to change this.
	 */
	public function getValueIndex() {
		return ( $this->m_typeid == '_txt' || $this->m_typeid == '_cod' )  ? - 1 : 0;
	}

	/**
	 * For perfomance reasons, long text data like _txt and _cod does not
	 * support string matching. This class can be subclassed to change this.
	 */
	public function getLabelIndex() {
		return ( $this->m_typeid == '_txt' || $this->m_typeid == '_cod' )  ? - 1 : 0;
	}

	public function getWikiValue() {
		$this->unstub();
		return $this->m_dataitem->getString();
	}

	public function getInfolinks() {
		$this->unstub();
		if ( ( $this->m_typeid != '_txt' ) && ( $this->m_typeid != '_cod' ) ) {
			return parent::getInfolinks();
		} else {
			return $this->m_infolinks;
		}
	}

	protected function getServiceLinkParams() {
		$this->unstub();
		// Create links to mapping services based on a wiki-editable message. The parameters
		// available to the message are:
		// $1: urlencoded string
		if ( ( $this->m_typeid != '_txt' ) && ( $this->m_typeid != '_cod' ) ) {
			return array( rawurlencode( $this->m_dataitem->getString() ) );
		} else {
			return false; // no services for Type:Text and Type:Code
		}
	}

	public function getExportData() {
		if ( $this->isValid() ) {
			$lit = new SMWExpLiteral( smwfHTMLtoUTF8( $this->m_dataitem->getString() ), $this, 'http://www.w3.org/2001/XMLSchema#string' );
			return new SMWExpData( $lit );
		} else {
			return null;
		}
	}

	/**
	 * Make a possibly shortened printout string for displaying the value.
	 * The value must be specified as an input since necessary HTML escaping
	 * must be applied to it first, if desired. The result of getAbbValue()
	 * may contain wiki-compatible HTML mark-up that should not be escaped.
	 * @todo The method abbreviates very long strings for display by simply
	 * taking substrings. This is not in all cases a good idea, since it may
	 * break XML entities and mark-up.
	 */
	protected function getAbbValue( $linked, $value ) {
		$len = mb_strlen( $value );
		if ( ( $len > 255 ) && ( $this->m_typeid != '_cod' ) ) {
			if ( ( $linked === null ) || ( $linked === false ) ) {
				return mb_substr( $value, 0, 42 ) . ' <span class="smwwarning">…</span> ' . mb_substr( $value, $len - 42 );
			} else {
				SMWOutputs::requireHeadItem( SMW_HEADER_TOOLTIP );
				return mb_substr( $value, 0, 42 ) . ' <span class="smwttpersist"> … <span class="smwttcontent">' . $value . '</span></span> ' . mb_substr( $value, $len - 42 );
			}
		} elseif ( $this->m_typeid == '_cod' ) {
			return $this->getCodeDisplay( $value, true );
		} else {
			return $value;
		}
	}

	/**
	 * Special features for Type:Code formatting.
	 */
	protected function getCodeDisplay( $value, $scroll = false ) {
		SMWOutputs::requireHeadItem( SMW_HEADER_STYLE );
		$result = str_replace( array( '<', '>', ' ', '=', "'", ':', "\n" ), array( '&lt;', '&gt;', '&#160;', '&#x003D;', '&#x0027;', '&#58;', "<br />" ), $value );
		if ( $scroll ) {
			$result = "<div style=\"height:5em; overflow:auto;\">$result</div>";
		}
		return "<div class=\"smwpre\">$result</div>";
	}

}
