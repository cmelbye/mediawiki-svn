<?php


class SMFormInput {

	/**
	 * @since 0.8
	 * 
	 * @var iMappingService
	 */
	protected $service;		
	
	/**
	 * Constructor.
	 * 
	 * @since 0.8
	 * 
	 * @param iMappingService $service
	 */
	public function __construct( iMappingService $service ) {
		$this->service = $service;
	}
	
	
}