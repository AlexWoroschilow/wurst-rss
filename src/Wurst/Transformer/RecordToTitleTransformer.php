<?php

namespace Wurst\Transformer;

use Wurst\History\Entity\Record;

class RecordToTitleTransformer {
	protected $transformer;
	public function __construct($transformer) {
		$this->transformer = $transformer;
	}
	public function transform(Record $record) {
		switch (($category = $this->transformer->transform ( $record ))) {
			case 'fatal_probably' :
				$suffix = "Success but with some errors which may be critical";
				break;
			case 'fatal' :
			case 'success' :
			case 'unknown' :
				$suffix = $category;
		}
		return "{$record->getName ()} $category";
	}
}
