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
		
		$element->setInfo ( str_replace ( ",", ", ", substr ( $element->getInfo(), 0, $this->limit ) ) );
		$element->setInfo ( str_replace ( "\n", "<br/>", $element->getInfo () ) );
		
		$element->setFatal ( str_replace ( ",", ", ", substr ( $element->getFatal (), 0, $this->limit ) ) );
		$element->setFatal ( str_replace ( "\n", "<br/>", $element->getFatal () ) );
		
		$element->setError ( str_replace ( ",", ", ", substr ( $element->getError (), 0, $this->limit ) ) );
		$element->setError ( str_replace ( "\n", "<br/>", $element->getError () ) );
		
		$element->setNotice ( str_replace ( "\n", "<br/>", substr ( $element->getNotice (), 0, $this->limit ) ) );
		$element->setNotice ( str_replace ( ",", ", ", $element->getNotice () ) );
		
		return $this->templater->render ( 'description.html.twig', array (
				'element' => $element 
		) );
	}
}
