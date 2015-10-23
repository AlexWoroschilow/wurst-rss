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
	public function register(Application $app) {
		$app ['wurst.history'] = new History ( $this->settings ['path.xml'], new CacheFile ( $this->settings ['path.cache'] ) );
	}
	public function boot(Application $app) {
	}
}
