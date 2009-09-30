<?php
/**
 * LiquidThreads API Query module
 *
 * Data that can be returned:
 * - ID
 * - Subject
 * - "host page"
 * - parent
 * - ancestor
 * - creation time
 * - modification time
 * - author
 * - summary article ID
 * - "root" page ID
 * - type
 */

class ApiQueryLQTThreads extends ApiQueryBase {

	// Property definitions
	static $propRelations = array(
			'id' => 'thread_id',
			'subject' => 'thread_subject',
			'page' => array(
				'namespace' => 'thread_article_namespace',
				'title' => 'thread_article_title'
			),
			'parent' => 'thread_parent',
			'ancestor' => 'thread_ancestor',
			'created' => 'thread_created',
			'modified' => 'thread_modified',
			'author' => array(
				'id' => 'thread_author_id',
				'name' => 'thread_author_name'
			),
			'summaryid' => 'thread_summary_page',
			'rootid' => 'thread_root',
			'type' => 'thread_type',
		);

	public function __construct( $query, $moduleName ) {
		parent :: __construct( $query, $moduleName, 'th' );
	}

	public function execute() {
		global $wgUser;

		$params = $this->extractRequestParams();
		$prop = array_flip( $params['prop'] );
		$result = $this->getResult();
		$this->addTables( 'thread' );
		$this->addFields( 'thread_id' );

		foreach ( self::$propRelations as $name => $fields ) {
			// Pass a straight array rather than one with string
			// keys, to be sure that merging it into other added
			// arrays doesn't mess stuff up
			$this->addFieldsIf( array_values( (array)$fields ), isset( $prop[$name] ) );
		}

		// Check for conditions
		$conditionFields = array( 'page', 'root', 'summary', 'author', 'id' );
		foreach ( $conditionFields as $field ) {
			if ( isset( $params[$field] ) ) {
				$this->handleCondition( $field, $params[$field] );
			}
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addWhereRange( 'thread_id', $params['dir'],
			$params['startid'], $params['endid'] );

		if ( !$params['showdeleted'] ) {
			$delType = $this->getDB()->addQuotes( Threads::TYPE_DELETED );
			$this->addWhere( "thread_type != $delType" );
		}

		if ( $params['render'] ) {
			// All fields
			$allFields = array(
				'thread_id', 'thread_root', 'thread_article_namespace',
				'thread_article_title', 'thread_summary_page', 'thread_ancestor',
				'thread_parent', 'thread_modified', 'thread_created', 'thread_type',
				'thread_editedness', 'thread_subject', 'thread_author_id',
				'thread_author_name'
			);

			$this->addFields( $allFields );
		}

		$res = $this->select( __METHOD__ );
		$count = 0;
		foreach ( $res as $row )
		{
			if ( ++$count > $params['limit'] ) {
				// We've had enough
				$this->setContinueEnumParameter( 'startid', $row->thread_id );
				break;
			}

			$entry = array();
			foreach ( $prop as $name => $nothing ) {
				$fields = self::$propRelations[$name];
				self::formatProperty( $name, $fields, $row, $entry );
			}

			// Render if requested
			if ( $params['render'] ) {
				self::renderThread( $row, $params, $entry );
			}

			if ( $entry ) {
				$fit = $result->addValue( array( 'query',
						$this->getModuleName() ),
					null, $entry );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'startid', $row->thread_id );
					break;
				}
			}
		}
		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'thread' );
	}

	static function renderThread( $row, $params, &$entry ) {
		// Set up OutputPage
		global $wgOut, $wgUser, $wgRequest;
		$oldOutputText = $wgOut->getHTML();
		$wgOut->clearHTML();

		// Setup
		$thread = new Thread( $row );
		$article = $thread->root();
		$title = $article->getTitle();
		$view = new LqtView( $wgOut, $article, $title, $wgUser, $wgRequest );

		// Parameters
		$view->threadNestingLevel = $params['renderlevel'];

		$renderpos = $params['renderthreadpos'];
		$rendercount = $params['renderthreadcount'];

		$options = array();
		if ( isset( $params['rendermaxthreadcount'] ) )
			$options['maxCount'] = $params['rendermaxthreadcount'];
		if ( isset( $params['rendermaxdepth'] ) )
			$options['maxDepth'] = $params['rendermaxdepth'];
		if ( isset( $params['renderstartrepliesat'] ) )
			$options['startAt' ] = $params['renderstartrepliesat'];

		$view->showThread( $thread, $renderpos, $rendercount, $options );

		$result = $wgOut->getHTML();
		$wgOut->clearHTML();
		$wgOut->addHTML( $oldOutputText );

		$entry['content'] = $result;
	}

	static function formatProperty( $name, $fields, $row, &$entry ) {
		if ( !is_array( $fields ) ) {
			// Common case.
			$entry[$name] = $row->$fields;
		} else if ( $name == 'page' ) {
			// Special cases
			$nsField = $fields['namespace'];
			$tField = $fields['title'];
			$title = Title::makeTitle( $row->$nsField, $row->$tField );
			$result = array();
			ApiQueryBase::addTitleInfo( $entry, $title, 'page' );
		} else {
			// Complicated case.
			$result = array();
			foreach ( $fields as $part => $field ) {
				$entry[$name][$part] = $row->$field;
			}
		}
	}

	function addPageCond( $prop, $value ) {
		if ( count( $value ) === 1 ) {
			$cond = $this->getPageCond( $prop, $value[0] );
			$this->addWhere( $cond );
		} else {
			$conds = array();
			foreach ( $value as $page ) {
				$cond = $this->getPageCond( $prop, $page );
				$conds[] = $this->getDB()->makeList( $cond, LIST_AND );
			}

			$cond = $this->getDB()->makeList( $conds, LIST_OR );
			$this->addWhere( $cond );
		}
	}

	function getPageCond( $prop, $value ) {
		$fieldMappings = array(
			'page' => array(
				'namespace' => 'thread_article_namespace',
				'title' => 'thread_article_title',
			),
			'root' => array( 'id' => 'thread_root' ),
			'summary' => array( 'id' => 'thread_summary_id' ),
		);

		// Split.
		$t = Title::newFromText( $value );
		$cond = array();
		foreach ( $fieldMappings[$prop] as $type => $field ) {
			switch ( $type ) {
				case 'namespace':
					$cond[$field] = $t->getNamespace();
					break;
				case 'title':
					$cond[$field] = $t->getDBkey();
					break;
				case 'id':
					$cond[$field] = $t->getArticleID();
					break;
				default:
					ApiBase::dieDebug( __METHOD__, "Unknown condition type $type" );
			}
		}
		return $cond;
	}

	function handleCondition( $prop, $value ) {
		$titleParams = array( 'page', 'root', 'summary' );
		$fields = self::$propRelations[$prop];

		if ( in_array( $prop, $titleParams ) ) {
			// Special cases
			$this->addPageCond( $prop, $value );
		} else if ( $prop == 'author' ) {
			$this->addWhereFld( 'thread_author_name', $value );
		} else if ( !is_array( $fields ) ) {
			// Common case
			return $this->addWhereFld( $fields, $value );
		}
	}

	public function getAllowedParams() {
		return array (
			'startid' => array(
				ApiBase :: PARAM_TYPE => 'integer'
			),
			'endid' => array(
				ApiBase :: PARAM_TYPE => 'integer'
			),
			'dir' => array(
				ApiBase :: PARAM_TYPE => array(
					'older',
					'newer'
				),
				ApiBase :: PARAM_DFLT => 'newer'
			),
			'showdeleted' => false,
			'limit' => array(
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'prop' => array(
				ApiBase :: PARAM_DFLT => 'id|subject|page|parent|author',
				ApiBase :: PARAM_TYPE => array_keys( self::$propRelations ),
				ApiBase :: PARAM_ISMULTI => true
			),

			'page' => array(
				ApiBase :: PARAM_ISMULTI => true
			),
			'author' => array(
				ApiBase :: PARAM_ISMULTI => true
			),
			'root' => array(
				ApiBase :: PARAM_ISMULTI => true
			),
			'summary' => array(
				ApiBase :: PARAM_ISMULTI => true
			),
			'id' => array(
				ApiBase :: PARAM_ISMULTI => true
			),
			'render' => false,
			'renderlevel' => array(
				ApiBase :: PARAM_DFLT => 0,
			),
			'renderthreadpos' => array(
				ApiBase :: PARAM_DFLT => 1,
			),
			'renderthreadcount' => array(
				ApiBase :: PARAM_DFLT => 1,
			),
			'rendermaxthreadcount' => array(
				ApiBase :: PARAM_DFLT => null,
			),
			'rendermaxdepth' => array(
				ApiBase :: PARAM_DFLT => null,
			),
			'renderstartrepliesat' => array(
				ApiBase :: PARAM_DFLT => null,
			),
		);
	}

	public function getParamDescription() {
		return array (
			'startid' => 'The thread id to start enumerating from',
			'endid' => 'The thread id to stop enumerating at',
			'dir' => 'The direction in which to enumerate',
			'show' => 'Also show threads which meet these criteria',
			'limit' => 'The maximum number of threads to list',
			'prop' => 'Which properties to get',
			'page' => 'Limit results to threads on a particular page(s)',
			'author' => 'Limit results to threads by a particular author(s)',
			'root' => 'Limit results to threads with the given root(s)',
			'summary' => 'Limit results to threads corresponding to the given summary page(s)',
			'id' => 'Get threads with the given ID(s)',
		);
	}

	public function getDescription() {
		return 'Show details of LiquidThreads threads.';
	}

	protected function getExamples() {
 		return array(
 			'api.php?action=query&list=threads&lqtpage=Talk:Main_Page',
 			'api.php?action=query&list=threads&lqtid=1|2|3|4&lqtprop=id|subject|modified'
 		);
	}

	public function getVersion() {
		return __CLASS__ . '$Id$';
	}
}
