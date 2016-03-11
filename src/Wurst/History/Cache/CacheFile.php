<?php

namespace Wurst\History\Cache;

use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;

class CacheFile implements CacheInterface {
	protected $source;
	protected $limit;
	
	/**
	 * Just a class constructor
	 *
	 * @param unknown $source        	
	 * @param number $limit        	
	 */
	public function __construct($source, $limit = 100) {
		$this->source = $source;
		$this->limit = $limit;
	}
	
	/**
	 * Load and parse data from cache file
	 */
	public function load() {
		$files = new Filesystem ();
		if ($files->exists ( $this->source )) {
			$file = new SplFileInfo ( $this->source, null, null );
			return unserialize ( $file->getContents () );
		}
		return array ();
	}
	
	/**
	 * Refresh wurst cache
	 *
	 * @param unknown_type $collection        	
	 * @throws \Exception
	 */
	public function refresh($collection, \Closure $on_remove_record = null) {
		$filesystem = new Filesystem ();
		
		assert ( count ( $collection ), 'Parsed collection can not be empty' );
		assert ( ($collection = array_merge ( $this->load (), $collection )), 'Array could not be merged' );
		
		usort ( $collection, function ($item1, $item2) {
			$date1 = $item1->getDate ();
			$date2 = $item2->getDate ();
			return $date1 > $date2 ? - 1 : 1;
		} );
		
		assert ( ($slice = array_chunk ( $collection, $this->limit )), 'Can not chunk array' );
		assert ( ($collection = array_shift ( $slice )), 'Status collection can not be empty' );
		
		if (($collection_remove = array_shift ( $slice ))) {
			if ($on_remove_record instanceof \Closure) {
				foreach ( $collection_remove as $item ) {
					$on_remove_record ( $item );
				}
			}
		}
		
		assert ( ($collection = array_unique ( $collection )), 'Status collection can not be empty' );
		
		$filesystem->dumpFile ( $this->source, serialize ( $collection ) );
		
		return $collection;
	}
}