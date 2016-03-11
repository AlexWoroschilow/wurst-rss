<?php

namespace Wurst\Transformer;

use Wurst\History\Entity\Record;

class RecordToCategoryTransformer {
	public function __construct() {
	}

	/**
	 * Transform record to category
	 * 
	 * @param Record $record
	 */
	public function transform(Record $record) {
		return $record->getStatus();
	}
}
