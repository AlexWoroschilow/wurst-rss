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
		
		$element->setInfo ( str_replace ( ",", ", ", $element->getInfo() ) );
		$element->setInfo ( str_replace ( "\n", "<br/>", $element->getInfo () ) );

		$element->setWarning ( str_replace ( "\n", "<br/>", substr ( $element->getWarning(), 0, $this->limit ) ) );
		$element->setWarning ( str_replace ( ",", ", ", $element->getWarning () ) );

		$element->setError ( str_replace ( ",", ", ", substr ( $element->getError (), 0, $this->limit ) ) );
		$element->setError ( str_replace ( "\n", "<br/>", $element->getError () ) );

		$element->setStderr ( str_replace ( "\n", "<br/>", substr ( $element->getStderr(), 0, $this->limit ) ) );
		
		$element->setFatal ( str_replace ( ",", ", ", substr ( $element->getFatal (), 0, $this->limit ) ) );
		$element->setFatal ( str_replace ( "\n", "<br/>", $element->getFatal () ) );
		
		return $this->templater->render ( 'description.html.twig', array (
				'element' => $element 
		) );
	}
}
