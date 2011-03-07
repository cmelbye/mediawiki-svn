<?php

/**
 * A query printer for maps using the Yahoo Maps API.
 *
 * @file SM_YahooMapsQP.php
 * @ingroup SMYahooMaps
 *
 * @author Jeroen De Dauw
 */
class SMYahooMapsQP extends SMMapPrinter {

	/**
	 * @see SMMapPrinter::getServiceName
	 */		
	protected function getServiceName() {
		return 'yahoomaps';
	}	
	
}
