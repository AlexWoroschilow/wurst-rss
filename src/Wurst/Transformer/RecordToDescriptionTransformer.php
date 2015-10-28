<?php

namespace Wurst\Transformer;

use Wurst\History\Entity\Record;

class RecordToDescriptionTransformer {
	public function __construct() {
	}
	public function transform(Record $element) {
		$element->setError ( str_replace ( "\n", "<br/>", substr($element->getError (), 0, 500) ) );
		$element->setError ( str_replace ( ",", ", ", $element->getError () ) );
		
		$element->setFatal ( str_replace ( "\n", "<br/>", substr($element->getFatal (), 0, 500) ) );
		$element->setFatal ( str_replace ( ",", ", ", $element->getFatal () ) );
		
		return "{$element->getFatal()} {$element->getError()}";
	}
}
