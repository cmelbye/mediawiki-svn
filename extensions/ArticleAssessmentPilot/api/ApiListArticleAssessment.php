<?php
/**
 *
 *
 * @file
 * @ingroup API
 */
class ApiListArticleAssessment extends ApiQueryBase {
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'aa' );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$result = $this->getResult();

		$this->addTables( 'article_assessment_pages' );
		$this->addTables( 'article_assessment_ratings' );

		$this->addFields( array( 'aap_page_id', 'aap_total', 'aap_count', 'aap_rating_id', 'aar_rating' ) );

		$this->addWhere( 'aap_rating_id = aar_id' );

		if ( isset( $params['pageid'] ) ) {
			$this->addWhereFld( 'aap_page_id', $params['pageid'] );
		}

		$res = $this->select( __METHOD__ );

		$assessments = array();

		foreach ( $res as $row ) {
			if ( !isset( $assessments[$row->aap_page_id] ) ) {
				$assessments[$row->aap_page_id] = array(
					'pageid' => $row->aap_page_id,
				);
			}

			$assessments[$row->aap_page_id]['ratings'][$row->aap_rating_id] = array(
				'ratingid' => $row->aap_rating_id,
				'ratingdesc' => $row->aar_rating,
				'total' => $row->aap_total,
				'count' => $row->aap_count
			);
		}

		foreach ( $assessments as $ass ) {
			$result->setIndexedTagName( $ass['ratings'], 'r' );
			$result->addValue( array( 'query', $this->getModuleName() ), null, $ass );
		}

		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'aa' );
	}

	public function getAllowedParams() {
		return array(
			'pageid' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'pageid' => '',
		);
	}

	public function getDescription() {
		return array(
			'List all article assessments'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&list=articleassessment',
			'api.php?action=query&list=articleassessment&aapageid=1'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}