<?php

namespace Wurst\Transformer;

use Wurst\History\Entity\Record;

class RecordToCategoryTransformer {
	public function __construct() {
	}
	public function transform(Record $record) {
		if ($this->isSuccess ( $record )) {
			return 'success';
		}
		
		if ($this->isFatal ( $record )) {
			return 'fatal';
		}
		
		if ($this->isProbablyFatal ( $record )) {
			return 'fatal_probably';
		}
		return 'unknown';
	}
	
	/**
	 * If no records in std error and no fatal errors from script
	 * it may be a success build
	 *
	 * @param Record $record        	
	 */
	public function isSuccess(Record $record) {
		return ! $this->isFatal ( $record ) and ! $this->isProbablyFatal ( $record );
	}
	
	/**
	 * Something went wrong, there are a fatal
	 * errors from workers or planners or from server
	 *
	 * @param Record $record        	
	 */
	public function isFatal(Record $record) {
		return strlen ( $record->getFatal () );
	}
	
	/**
	 * It is very probable that something went wrong, but not 100%
	 * there are a situations without fatal errors, but with stderr records,
	 * may be perl script compilation errors and so on, so, it may be probably fatal
	 *
	 * @param Record $record        	
	 */
	public function isProbablyFatal(Record $record) {
		return (! strlen ( $record->getFatal () ) and (strlen ( $record->getError () ) or strlen ( $record->getStderr () )));
	}
}
