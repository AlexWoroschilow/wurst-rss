<?php

namespace Wurst\Transformer;

use Wurst\History\Entity\Record;

class RecordToCategoryTransformer {
	public function __construct() {
	}
	public function transform(Record $record) {
		if (strlen ( $record->getFatal () )) {
			return "Failed";
		}
		return "Success";
	}
}
