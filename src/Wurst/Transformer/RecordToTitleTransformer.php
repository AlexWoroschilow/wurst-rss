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
			case 'success' :
			case 'fatal' :
			case 'fatal_probably' :
			case 'unknown' :
		}
		return "{$record->getName ()} $category";
	}
}
