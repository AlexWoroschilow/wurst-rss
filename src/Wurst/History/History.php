<?php

namespace Wurst\History;

use Wurst\History\Entity\Record;
use Wurst\History\Parser\Parser;
use Wurst\History\Cache\CacheInterface;


use Symfony\Component\Finder\Finder;

class History
{
	protected $cache;
	protected $source;

	public function __construct($source, CacheInterface $cache)
	{
		$this->cache = $cache;
		$this->source = $source;
	}

	/**
	 * Get status records, parse new records
	 * and remove processed xml files
	 *
	 * @throws \Exception
	 */
	public function collection()
	{
		$finder = new Finder();
		$finder->files()->in($this->source);
		if($finder->files()->count()) {

			$parser = new Parser($finder, function ($file) {

				if(!\phpQuery::newDocumentFileXML($file)) {
					throw new \Exception('Can not create php query object');
				}

				$record = new Record();
				$record->setName(pq('task')->text());
				$record->setDate(pq('date')->text());
				$record->setStatus(pq('status')->text());
				$record->setError(pq('error')->text());
				$record->setNotice(pq('notice')->text());
				$record->setFatal(pq('fatal')->text());
				$record->setInfo(pq('log')->text());
				
				return $record;
			});

			$this->cache->refresh($parser->collection());
		}

		return $this->cache->load();
	}
}