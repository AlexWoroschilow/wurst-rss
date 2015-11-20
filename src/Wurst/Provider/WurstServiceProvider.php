<?php

namespace Wurst\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Wurst\History\History;
use Wurst\History\Cache\CacheFile;

class WurstServiceProvider implements ServiceProviderInterface {
	protected $settings;
	public function __construct($settings) {
		$this->settings = $settings;
	}
	/**
	 * Register a wurst service in current Silex application
	 *
	 * {@inheritDoc}
	 *
	 * @see \Silex\ServiceProviderInterface::register()
	 */
	public function register(Application $app) {
		assert ( strlen ( ($path_xml = $this->settings ['path.xml']) ), 'Xml file folder can not be empty' );
		assert ( strlen ( ($path_cache = $this->settings ['path.cache']) ), 'Cache file folder can not be empty' );
		
		$app ['wurst.history'] = new History ( $path_xml, new CacheFile ( $path_cache ) );
	}
	
	/**
	 * Do a boot actions for current wurst service
	 *
	 * {@inheritDoc}
	 *
	 * @see \Silex\ServiceProviderInterface::boot()
	 */
	public function boot(Application $app) {
	}
}
