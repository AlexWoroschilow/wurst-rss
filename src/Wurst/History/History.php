<?php

namespace Wurst\History;

use Wurst\History\Entity\Record;
use Wurst\History\Parser\Parser;
use Wurst\History\Cache\CacheInterface;
use Symfony\Component\Finder\Finder;

class History {
	protected $cache;
	protected $source;
	public function __construct($source, CacheInterface $cache) {
		$this->cache = $cache;
		$this->source = $source;
	}
	
	/**
	 * Get status records, parse new records
	 * and remove processed xml files
	 *
	 * @throws \Exception
	 */
	public function collection(\Closure $on_remove_record = null) {
		$finder = new Finder ();
		$finder->files ()->in ( $this->source );
		if ($finder->files ()->count ()) {
			
			$parser = new Parser ( $finder, function ($file) {
				
				assert ( \phpQuery::newDocumentFileXML ( $file ), 'Can not create php query object' );
				
				$record = new Record ();
				$record->setName ( pq ( 'task' )->text () );
				$record->setDate ( pq ( 'date' )->text () );
				$record->setStatus ( pq ( 'status' )->text () );
				$record->setInfo ( pq ( 'info' )->text () );
				$record->setWarning ( pq ( 'warning' )->text () );
				$record->setError ( pq ( 'error' )->text () );
				$record->setStderr ( pq ( 'stderr' )->text () );
				$record->setFatal ( pq ( 'fatal' )->text () );
				$record->setLogfile ( pq ( 'logfile' )->text () );
				
				return $record;
			} );
			
			$this->cache->refresh ( $parser->collection (), $on_remove_record );
		}
		
		return $this->cache->load ();
	}
}