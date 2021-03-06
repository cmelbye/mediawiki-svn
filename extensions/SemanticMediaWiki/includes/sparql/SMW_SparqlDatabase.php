<?php
/**
 * Base classes for SMW's binding to SPARQL stores.
 * 
 * @file
 * @ingroup SMWSparql
 * 
 * @author Markus Krötzsch
 */

/**
 * This group contains all parts of SMW that relate to communication with
 * storage backends and clients via SPARQL.
 * 
 * @defgroup SMWSparql SWMSparql
 * @ingroup SMW
 */

/**
 * Class to escalate SPARQL query errors to the interface. We only do this for
 * malformed queries, permission issues, etc. Connection problems are usually
 * ignored so as to keep the wiki running even if the SPARQL backend is down.
 *
 * @ingroup SMWSparql
 */
class SMWSparqlDatabaseError extends Exception {

	/// Error code: malformed query
	const ERROR_MALFORMED      = 1;
	/// Error code: service refused to handle the request
	const ERROR_REFUSED        = 2;
	/// Error code: the query required a graph that does not exist
	const ERROR_GRAPH_NOEXISTS = 3;
	/// Error code: some existing graph should not exist to run this query
	const ERROR_GRAPH_EXISTS   = 4;
	/// Error code: unknown error
	const ERROR_OTHER          = 5;
	/// Error code: required service not known
	const ERROR_NOSERVICE      = 6;

	/**
	 * SPARQL query that caused the problem.
	 * @var string
	 */
	public $queryText;
	/**
	 * Error code
	 * @var integer
	 */
	public $errorCode;
	
	/**
	 * Constructor that creates an error message based on the given data.
	 *
	 * @param $errorCode integer error code as defined in this class
	 * @param $queryText string with the original SPARQL query/update
	 * @param $endpoint string URL of the endpoint
	 * @param $httpCode mixed integer HTTP error code or some string to print there
	 */
	function __construct( $errorCode, $queryText, $endpoint, $httpCode = '<not given>' ) {
		switch ( $errorCode ) {
			case self::ERROR_MALFORMED:
				$errorName = 'Malformed query';
			break;
			case self::ERROR_REFUSED:
				$errorName = 'Query refused';
			break;
			case self::ERROR_GRAPH_NOEXISTS:
				$errorName = 'Graph not existing';
			break;
			case self::ERROR_GRAPH_EXISTS:
				$errorName = 'Graph already exists';
			break;
			case self::ERROR_OTHER: default:
				$errorName = 'Unkown error';
			break;
			case self::ERROR_NOSERVICE: default:
				$errorName = 'Required service has not been defined';
			break;
		}
		$message = "A SPARQL query error has occurred\n" .
		  "Query: $queryText\n" .
		  "Error: $errorName\n" .
		  "Endpoint: $endpoint\n" . 
		  "HTTP response code: $httpCode\n";

		parent::__construct( $message );
		$this->errorCode = $errorCode;
		$this->queryText = $queryText;
	}

}

/**
 * Basic database connector for exchanging data via SPARQL.
 * 
 * @ingroup SMWSparql
 * 
 * @author Markus Krötzsch
 */
class SMWSparqlDatabase {

	/// Flag denoting endpoints being capable of querying
	const EP_TYPE_QUERY = 1;
	/// Flag denoting endpoints being capable of updating
	const EP_TYPE_UPDATE = 2;
	/// Flag denoting endpoints being capable of SPARQL HTTP graph management
	const EP_TYPE_DATA   = 4;

	/**
	 * The URL of the endpoint for executing read queries.
	 * @var string
	 */
	protected $m_queryEndpoint;

	/**
	 * The URL of the endpoint for executing update queries, or empty if
	 * update is not allowed/supported.
	 * @var string
	 */
	protected $m_updateEndpoint;

	/**
	 * The URL of the endpoint for using the SPARQL Graph Store HTTP
	 * Protocol with, or empty if this method is not allowed/supported.
	 * @var string
	 */
	protected $m_dataEndpoint;

	/**
	 * The curl handle we use for communicating. We reuse the same handle
	 * throughout as this safes some initialization effort.
	 * @var resource
	 */
	protected $m_curlhandle;

	/**
	 * Constructor
	 *
	 * @param $queryEndpoint string of URL of query service (reading)
	 * @param $updateEndpoint string of URL of update service (writing)
	 */
	public function __construct( $queryEndpoint, $updateEndpoint = '', $dataEndpoint = '' ) {
		$this->m_queryEndpoint = $queryEndpoint;
		$this->m_updateEndpoint = $updateEndpoint;
		$this->m_dataEndpoint = $dataEndpoint;
		$this->m_curlhandle = curl_init();
		curl_setopt( $this->m_curlhandle, CURLOPT_FORBID_REUSE, false );
		curl_setopt( $this->m_curlhandle, CURLOPT_FRESH_CONNECT, false );
		curl_setopt( $this->m_curlhandle, CURLOPT_RETURNTRANSFER, true ); // put result into variable
		curl_setopt( $this->m_curlhandle, CURLOPT_CONNECTTIMEOUT, 10 ); // timeout in seconds
		curl_setopt( $this->m_curlhandle, CURLOPT_FAILONERROR, true );
	}

	/**
	 * Check if the database can be contacted.
	 *
	 * @param $pingQueryEndpoint boolean true if the query endpoint should
	 * be pinged, false if the update enpoint should be pinged
	 * @return boolean to indicate success
	 * @todo SPARQL endpoints sometimes return errors if no (valid) query
	 * is posted. The current implementation tries to catch this, but this
	 * might not be entirely correct. Especially, the SPARQL 1.1 HTTP error
	 * codes for Update are not defined yet (April 15 2011).
	 */
	public function ping( $endpointType = self::EP_TYPE_QUERY ){
		if ( $endpointType == self::EP_TYPE_QUERY ) {
			curl_setopt( $this->m_curlhandle, CURLOPT_URL, $this->m_queryEndpoint );
			curl_setopt( $this->m_curlhandle, CURLOPT_NOBODY, true );
		} elseif ( $endpointType == self::EP_TYPE_UPDATE ) {
			if ( $this->m_updateEndpoint == '' ) {
				return false;
			}
			curl_setopt( $this->m_curlhandle, CURLOPT_URL, $this->m_updateEndpoint );
			curl_setopt( $this->m_curlhandle, CURLOPT_NOBODY, false ); // 4Store gives 404 instead of 500 with CURLOPT_NOBODY
		} else { // ( $endpointType == self::EP_TYPE_DATA )
			if ( $this->m_dataEndpoint == '' ) {
				return false;
			} else { // try an empty POST
				return $this->doHttpPost( '' );
			}
		}

		curl_exec( $this->m_curlhandle );

		if ( curl_errno( $this->m_curlhandle ) == 0 ) {
			return true;
		} else {
			$httpCode = curl_getinfo( $this->m_curlhandle, CURLINFO_HTTP_CODE );
			return ( ( $httpCode == 500 ) || ( $httpCode == 400 ) ); // valid HTTP responses from a complaining SPARQL endpoint that is alive and kicking
		}
	}

	/**
	 * SELECT wrapper.
	 * The function declares the standard namespaces wiki, swivt, rdf, owl,
	 * rdfs, property, xsd, so these do not have to be included in
	 * $extraNamespaces.
	 *
	 * @param $vars mixed array or string, field name(s) to be retrieved, can be '*'
	 * @param $where string WHERE part of the query, without surrounding { }
	 * @param $options array (associative) of options, e.g. array('LIMIT' => '10')
	 * @param $extraNamespaces array (associative) of namespaceId => namespaceUri
	 * @return SMWSparqlResultWrapper
	 */
	public function select( $vars, $where, $options = array(), $extraNamespaces = array() ) {
		$sparql = self::getPrefixString( $extraNamespaces ) . 'SELECT ';
		if ( array_key_exists( 'DISTINCT', $options ) ) {
			$sparql .= 'DISTINCT ';
		}
		if ( is_array( $vars ) ) {
			$sparql .= implode( ',', $vars );
		} else {
			$sparql .= $vars;
		}
		$sparql .= " WHERE {\n" . $where . "\n}";
		if ( array_key_exists( 'ORDER BY', $options ) ) {
			$sparql .= "\nORDER BY " . $options['ORDER BY'];
		}
		if ( array_key_exists( 'OFFSET', $options ) ) {
			$sparql .= "\nOFFSET " . $options['OFFSET'];
		}
		if ( array_key_exists( 'LIMIT', $options ) ) {
			$sparql .= "\nLIMIT " . $options['LIMIT'];
		}

		return $this->doQuery( $sparql );
	}

	/**
	 * ASK wrapper.
	 * The function declares the standard namespaces wiki, swivt, rdf, owl,
	 * rdfs, property, xsd, so these do not have to be included in
	 * $extraNamespaces.
	 *
	 * @param $where string WHERE part of the query, without surrounding { }
	 * @param $extraNamespaces array (associative) of namespaceId => namespaceUri
	 * @return SMWSparqlResultWrapper
	 */
	public function ask( $where, $extraNamespaces = array() ) {
		$sparql = self::getPrefixString( $extraNamespaces ) . "ASK {\n" . $where . "\n}";
		return $this->doQuery( $sparql );
	}

	/**
	 * DELETE wrapper.
	 * The function declares the standard namespaces wiki, swivt, rdf, owl,
	 * rdfs, property, xsd, so these do not have to be included in
	 * $extraNamespaces.
	 *
	 * @param $deletePattern string CONSTRUCT pattern of tripples to delete
	 * @param $where string condition for data to delete
	 * @param $extraNamespaces array (associative) of namespaceId => namespaceUri
	 * @return boolean stating whether the operations succeeded
	 */
	public function delete( $deletePattern, $where, $extraNamespaces = array() ) {
		$sparql = self::getPrefixString( $extraNamespaces ) .
		          "DELETE { $deletePattern } WHERE { $where }";
		return $this->doUpdate( $sparql );
	}

	/**
	 * INSERT DELETE wrapper.
	 * The function declares the standard namespaces wiki, swivt, rdf, owl,
	 * rdfs, property, xsd, so these do not have to be included in
	 * $extraNamespaces.
	 *
	 * @param $insertPattern string CONSTRUCT pattern of tripples to insert
	 * @param $deletePattern string CONSTRUCT pattern of tripples to delete
	 * @param $where string condition for data to delete
	 * @param $extraNamespaces array (associative) of namespaceId => namespaceUri
	 * @return boolean stating whether the operations succeeded
	 */
	public function insertDelete( $insertPattern, $deletePattern, $where, $extraNamespaces = array() ) {
		$sparql = self::getPrefixString( $extraNamespaces ) .
		          "INSERT { $insertPattern } DELETE { $deletePattern } WHERE { $where }";
		return $this->doUpdate( $sparql );
	}

	/**
	 * INSERT DATA wrapper.
	 * The function declares the standard namespaces wiki, swivt, rdf, owl,
	 * rdfs, property, xsd, so these do not have to be included in
	 * $extraNamespaces.
	 *
	 * @param $triples string of triples to insert
	 * @param $extraNamespaces array (associative) of namespaceId => namespaceUri
	 * @return boolean stating whether the operations succeeded
	 */
	public function insertData( $triples, $extraNamespaces = array() ) {
		if ( $this->m_dataEndpoint != '' ) {
			$turtle = self::getPrefixString( $extraNamespaces, false ) . $triples;
			return $this->doHttpPost( $turtle );
		} else {
			$sparql = self::getPrefixString( $extraNamespaces, true ) . "INSERT DATA { $triples }";
			return $this->doUpdate( $sparql );
		}
	}

	/**
	 * DELETE DATA wrapper.
	 * The function declares the standard namespaces wiki, swivt, rdf, owl,
	 * rdfs, property, xsd, so these do not have to be included in
	 * $extraNamespaces.
	 *
	 * @param $triples string of triples to delete
	 * @param $extraNamespaces array (associative) of namespaceId => namespaceUri
	 * @return boolean stating whether the operations succeeded
	 */
	public function deleteData( $triples, $extraNamespaces = array() ) {
		$sparql = self::getPrefixString( $extraNamespaces ) . "DELETE DATA { $triples }";
		return $this->doUpdate( $sparql );
	}


	/**
	 * Execute a SPARQL query and return an SMWSparqlResultWrapper object
	 * that contains the results. The method throws exceptions based on
	 * SMWSparqlDatabase::throwSparqlErrors(). If errors occur and this
	 * method does not throw anything, then an empty result with an error
	 * code is returned.
	 *
	 * @param $sparql string with the complete SPARQL query (SELECT or ASK)
	 * @return SMWSparqlResultWrapper
	 */
	public function doQuery( $sparql ) {
		//debug_zval_dump( $sparql );
		curl_setopt( $this->m_curlhandle, CURLOPT_URL, $this->m_queryEndpoint );
		curl_setopt( $this->m_curlhandle, CURLOPT_POST, true );
		$parameterString = "query=" . urlencode( $sparql );
		curl_setopt( $this->m_curlhandle, CURLOPT_POSTFIELDS, $parameterString );

		$xmlResult = curl_exec( $this->m_curlhandle );

		if ( curl_errno( $this->m_curlhandle ) == 0 ) {
			$xmlParser = new SMWSparqlResultParser();
			return $xmlParser->makeResultFromXml( $xmlResult );
		} else {
			$this->throwSparqlErrors( $this->m_updateEndpoint, $sparql );
			return new SMWSparqlResultWrapper( array(), array(), SMWSparqlResultWrapper::ERROR_UNREACHABLE );
		}
	}

	/**
	 * Execute a SPARQL update and return a boolean to indicate if the
	 * operations was sucessfull. The method throws exceptions based on
	 * SMWSparqlDatabase::throwSparqlErrors(). If errors occur and this
	 * method does not throw anything, then false is returned.
	 *
	 * @param $sparql string with the complete SPARQL update query (INSERT or DELETE)
	 * @return boolean
	 */
	public function doUpdate( $sparql ) {
		if ( $this->m_updateEndpoint == '' ) {
			throw new SMWSparqlDatabaseError( SMWSparqlDatabaseError::ERROR_NOSERVICE, $sparql, 'not specified', $error );
		}
		curl_setopt( $this->m_curlhandle, CURLOPT_URL, $this->m_updateEndpoint );
		curl_setopt( $this->m_curlhandle, CURLOPT_POST, true );
		$parameterString = "update=" . urlencode( $sparql );
		curl_setopt( $this->m_curlhandle, CURLOPT_POSTFIELDS, $parameterString );

		curl_exec( $this->m_curlhandle );

		if ( curl_errno( $this->m_curlhandle ) == 0 ) {
			return true;
		} else {
			$this->throwSparqlErrors( $this->m_updateEndpoint, $sparql );
			return false;
		}
	}

	/**
	 * Execute a HTTP-based SPARQL POST request according to
	 * http://www.w3.org/2009/sparql/docs/http-rdf-update/.
	 * The method throws exceptions based on
	 * SMWSparqlDatabase::throwSparqlErrors(). If errors occur and this
	 * method does not throw anything, then an empty result with an error
	 * code is returned.
	 * 
	 * @note This method has not been tesetd sufficiently since 4Store uses
	 * another post encoding. To avoid using it, simply do not provide a
	 * data endpoint URL when configuring the SPARQL database.
	 *
	 * @param $payload string Turtle serialization of data to send
	 * @return SMWSparqlResultWrapper
	 */
	public function doHttpPost( $payload ) {
		if ( $this->m_dataEndpoint == '' ) {
			throw new SMWSparqlDatabaseError( SMWSparqlDatabaseError::ERROR_NOSERVICE, "SPARQL POST with data: $payload", 'not specified', $error );
		}
		curl_setopt( $this->m_curlhandle, CURLOPT_URL, $this->m_dataEndpoint );
		curl_setopt( $this->m_curlhandle, CURLOPT_POST, true );

		// POST as file (fails in 4Store)
		$payloadFile = tmpfile();
		fwrite( $payloadFile, $payload );
		fseek( $payloadFile, 0 ); 
		curl_setopt( $this->m_curlhandle, CURLOPT_INFILE, $payloadFile );
		curl_setopt( $this->m_curlhandle, CURLOPT_INFILESIZE, strlen( $payload ) ); 
		curl_setopt( $this->m_curlhandle, CURLOPT_HTTPHEADER, array( 'Content-Type: application/x-turtle' ) );

		curl_exec( $this->m_curlhandle );

		if ( curl_errno( $this->m_curlhandle ) == 0 ) {
			return true;
		} else { ///TODO The error reporting based on SPARQL (Update) is not adequate for the HTTP POST protocol
			$this->throwSparqlErrors( $this->m_dataEndpoint, $payload );
			return false;
		}
	}

	/**
	 * Create the standard PREFIX declarations for SPARQL or Turtle,
	 * possibly with additional namespaces involved.
	 *
	 * @param $extraNamespaces array (associative) of namespaceId => namespaceUri
	 * @param $forSparql boolean true to use SPARQL prefix syntax, false to use Turtle prefix syntax
	 */
	public static function getPrefixString( $extraNamespaces = array(), $forSparql = true ) {
		$prefixString = '';
		$prefixIntro = $forSparql ? 'PREFIX ' : '@prefix ';
		$prefixOutro = $forSparql ? "\n" : " .\n";
		foreach ( array( 'wiki', 'rdf', 'rdfs', 'owl', 'swivt', 'property', 'xsd' ) as $shortname ) {
			$prefixString .= "{$prefixIntro}{$shortname}: <" . SMWExporter::getNamespaceUri( $shortname ) . ">$prefixOutro";
			unset( $extraNamespaces[$shortname] ); // avoid double declaration
		}
		foreach ( $extraNamespaces as $shortname => $uri ) {
			$prefixString .= "{$prefixIntro}{$shortname}: <$uri>$prefixOutro";
		}
		return $prefixString;
	}

	/**
	 * Decide what to make of the errors reported by the Curl handler.
	 * Either throw a suitable exception or fall through if the error
	 * should be handled gracefully. It is attempted to throw exceptions
	 * for all errors that can generally be prevented by proper coding or
	 * configuration (e.g. query syntax errors), and to be silent on all
	 * errors that might be caused by network issues or temporary
	 * overloading of the server. In this case, calling methods rather
	 * return something that helps to make the best out of the situation.
	 *
	 * @param $endpoint string URL of endpoint that was used
	 * @param $sparql string query that caused the problem
	 */
	protected function throwSparqlErrors( $endpoint, $sparql ) {
		$error = curl_errno( $this->m_curlhandle );
		if ( $error == 22 ) { // 22 == CURLE_HTTP_RETURNED_ERROR, but this constant is not defined in PHP, it seems
			$httpCode = curl_getinfo( $this->m_curlhandle, CURLINFO_HTTP_CODE );
			/// TODO We are guessing the meaning of HTTP codes here -- the SPARQL 1.1 spec does not yet provide this information for updates (April 15 2011)
			if ( $httpCode == 400 ) { // malformed query
				throw new SMWSparqlDatabaseError( SMWSparqlDatabaseError::ERROR_MALFORMED, $sparql, $endpoint, $httpCode );
			} elseif ( $httpCode == 500 ) { // query refused; maybe fail gracefully here (depending on how stores use this)
				throw new SMWSparqlDatabaseError( SMWSparqlDatabaseError::ERROR_REFUSED, $sparql, $endpoint, $httpCode );
			} elseif ( $httpCode == 404 ) {
				return; // endpoint not found, maybe down; fail gracefully
			} else {
				throw new SMWSparqlDatabaseError( SMWSparqlDatabaseError::ERROR_OTHER, $sparql, $endpoint, $httpCode );
			}
		} elseif ( $error == CURLE_COULDNT_CONNECT ) {
			return; // fail gracefully if backend is down
		} elseif ( $error == 52 ) { // 52 == CURLE_GOT_NOTHING, but this constant is not defined in PHP, it seems
			return; // happens when 4Store crashes, do not bother the wiki
		} else {
			throw new Exception( "Failed to communicate with SPARQL store.\n Endpoint: " . $endpoint . "\n Curl error: '" . curl_error( $this->m_curlhandle ) . "' ($error)" );
		}
	}

}

