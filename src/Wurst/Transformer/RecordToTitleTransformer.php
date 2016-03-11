<?php

namespace Wurst\Transformer;

use Wurst\History\Entity\Record;

class RecordToTitleTransformer {
	protected $transformer;
	public function __construct($transformer) {
		$this->transformer = $transformer;
	}
	
	/**
	 * Transform record to title
	 * 
	 * @param Record $record
	 */
	public function transform(Record $record) {
		$serviceTransformer = $this->transformer;
		
		assert ( strlen ( $category = $serviceTransformer->transform ( $record ) ), "Category string can not be empty" );
		
		return "{$record->getName ()} {$category}";
	}
}
