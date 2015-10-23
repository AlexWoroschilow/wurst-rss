<?php

namespace Wurst\Transformer;

use Wurst\History\Entity\Record;
class RecordToDescriptionTransformer {
	public function __construct() {
	}
	public function transform(Record $record) {
		return str_replace("\n", "<br/>", $record->getInfo());
	}
}
