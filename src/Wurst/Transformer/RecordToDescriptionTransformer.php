<?php

namespace Wurst\Transformer;

use Wurst\History\Entity\Record;

class RecordToDescriptionTransformer {
	protected $limit = 2000;
	protected $templater;
	public function __construct($templater) {
		$this->templater = $templater;
	}
	public function transform(Record $element) {
		return $this->templater->render ( 'description.html.twig', array (
				'element' => $element 
		) );
	}
}
