<?php

abstract class GadgetsView {
	protected $parent,
		$params;

	public function __construct( SpecialGadgets $parent, $params ) {
		$this->parent = $parent;
		$this->params = $params;
	}

	public abstract function execute();
	
	public function post() {
	}

	/**
	 * @return Message
	 */
	public abstract function getTitle();

	protected function getContext() {
		return $this->parent->getContext();
	}
}