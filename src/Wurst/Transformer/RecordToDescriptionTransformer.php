<?php

namespace Wurst\Transformer;

use Wurst\History\Entity\Record;

class RecordToDescriptionTransformer {
	public function __construct() {
	}
	public function transform(Record $element) {
		$element->setError ( str_replace ( "\n", "<br/>", $element->getError () ) );
		$element->setError ( str_replace ( ",", ", ", $element->getError () ) );
		
		$element->setFatal ( str_replace ( "\n", "<br/>", $element->getFatal () ) );
		$element->setFatal ( str_replace ( ",", ", ", $element->getFatal () ) );
		
		return "{$element->getFatal()} {$element->getError()}";
	}
}
