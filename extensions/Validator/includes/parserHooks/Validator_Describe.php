<?php

/**
 * Class for the 'describe' parser hooks.
 * 
 * @since 0.4.3
 * 
 * @file Validator_Describe.php
 * @ingroup Validator
 * 
 * @licence GNU GPL v3 or later
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValidatorDescribe extends ParserHook {
	
	/**
	 * No LST in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */
	public static function staticMagic( array &$magicWords, $langCode ) {
		$className = __CLASS__;
		$instance = new $className();
		return $instance->magic( $magicWords, $langCode );
	}
	
	/**
	 * No LST in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */	
	public static function staticInit( Parser &$wgParser ) {
		$className = __CLASS__;
		$instance = new $className();
		return $instance->init( $wgParser );
	}

	/**
	 * Gets the name of the parser hook.
	 * @see ParserHook::getName
	 * 
	 * @since 0.4.3
	 * 
	 * @return string
	 */
	protected function getName() {
		return 'describe';
	}	
	
	/**
	 * Returns an array containing the parameter info.
	 * @see ParserHook::getParameterInfo
	 * 
	 * @since 0.4.3
	 * 
	 * @return array of Parameter
	 */
	protected function getParameterInfo( $type ) {
		$params = array();

		$params['hooks'] = new ListParameter( 'hooks' );
		
 		return $params;
	}	
	
	/**
	 * Returns the list of default parameters.
	 * @see ParserHook::getDefaultParameters
	 * 
	 * @since 0.4.3
	 * 
	 * @return array
	 */
	protected function getDefaultParameters( $type ) {
		return array( 'hooks' );
	}
	
	/**
	 * Renders and returns the output.
	 * @see ParserHook::render
	 * 
	 * @since 0.4.3
	 * 
	 * @param array $parameters
	 * 
	 * @return string
	 */
	public function render( array $parameters ) {
		$parts = array();
		
		foreach ( $parameters['hooks'] as $hookName ) {
			$parserHook = $this->getParserHookInstance( $hookName );
			
			if ( $parserHook === false ) {
				$parts[] = wfMsgExt( 'validator-describe-notfound', 'parsemag', $hookName );
			}
			else {
				$parts[] = $this->getParserHookDescription( $parserHook );
			}
		}
		
		return implode( "\n\n", $parts );
	}
	
	protected function getParserHookDescription( ParserHook $parserHook ) {
		$descriptionData = $parserHook->getDescriptionData( ParserHook::TYPE_TAG ); // TODO
		
		$tableRows = array();
		
		foreach ( $descriptionData['parameters'] as $parameter ) {
			$tableRows[] = $this->getDescriptionRow( $parameter );
		}
		
		if ( count( $tableRows ) > 0 ) {
			$tableRows = array_merge( array( '! Parameter
! Aliases
! Default
! Usage' ), $tableRows );
			
		$table = implode( "\n|-\n", $tableRows );
		
		$table = <<<EOT
{| class="wikitable sortable"'
{$table}
|}
EOT;
		}
		
		//return $table; // TODO
		return '<pre>' . $table . '</pre>';
	}
	
	protected function getDescriptionRow( Parameter $parameter ) {
		$aliases = $parameter->getAliases();
		$aliases = count( $aliases ) > 0 ? implode( ', ', $aliases ) : '-';
		
		$default = $parameter->isRequired() ? "''required''" : $parameter->getDefault(); 
		if ( $default == '' ) $default = "''empty''"; 
		
		// TODO
		
		return <<<EOT
| {$parameter->getName()}
| {$aliases}
| {$default}
| Description be here.
EOT;
	}
	
	/**
	 * Returns an instance of the class handling the specified parser hook,
	 * or false if there is none.
	 * 
	 * @since 0.4.3
	 * 
	 * @param string $parserHookName
	 * 
	 * @return mixed ParserHook or false
	 */
	protected function getParserHookInstance( $parserHookName ) {
		$className = ParserHook::getHookClassName( $parserHookName );
		return $className !== false && class_exists( $className ) ? new $className() : false;
	}
	
}