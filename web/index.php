<?php
require_once './bootstrap.php';

assert ( ! empty ( $FOLDER_ROOT ) );
assert ( ! empty ( $FOLDER_XML ) );
assert ( ! empty ( $FILE_HISTORY_CACHE ) );
assert ( ! empty ( $SERVER_ROOT ) );

$loader = require_once "{$FOLDER_ROOT}/vendor/autoload.php";
$loader->add ( "Wurst", "{$FOLDER_ROOT}/src" );

use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use Suin\RSSWriter\Channel;

use Wurst\Transformer\RecordToCategoryTransformer;
use Wurst\Transformer\RecordToDescriptionTransformer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;

use Wurst\Transformer\RecordToTitleTransformer;
use Wurst\History\Entity\Record;

$app = new Silex\Application ();
$app ['debug'] = true;

$app->register ( new Silex\Provider\TwigServiceProvider (), array (
		'twig.path' => "{$FOLDER_ROOT}/templates" 
) );

$app->register ( new Wurst\Provider\WurstServiceProvider ( array (
		'path.xml' => $FOLDER_XML,
		'path.cache' => $FILE_HISTORY_CACHE 
) ) );

$app->before ( function () use($app) {
	$filesystem = new Filesystem ();
	$app ['wurst.history']->collection ( function (Record $record) use($FOLDER_WEB, $filesystem) {
		$logfile = "{$FOLDER_WEB}/log/{$record->getLogfile ()}";
		if ($filesystem->exists ( $logfile )) {
			$filesystem->remove ( $logfile );
		}
	} );
} );

/**
 * Main method to calculate rss string from
 * status xml files or from cache, of no statuses found
 * i think it is easy to understand
 */
$app->get ( '/', function (Request $request) use($app, $SERVER_ROOT, $SERVER_ROOT_SCRIPT) {
	
	$feed = new Feed ();
	
	$channel = new Channel ();
	$channel->title ( 'Wurst update status' );
	$channel->description ( "Uni-Hamburg server" );
	$channel->url ( 'http://zbh.uni-hamburg.de' );
	$channel->appendTo ( $feed );
	
	$transformerCategory = new RecordToCategoryTransformer ();
	$transformerTitle = new RecordToTitleTransformer ( $transformerCategory );
	$transformerDescription = new RecordToDescriptionTransformer ( $app ['twig'] );
	
	foreach ( $app ['wurst.history']->collection () as $id => $element ) {
		
		$item = new Item ();
		$item->author ( "wurst update" );
		$item->url ( "http://{$SERVER_ROOT_SCRIPT}/details/{$id}" );
		$item->pubDate ( $element->getDate () );
		$item->title ( $transformerTitle->transform ( $element ) );
		$item->category ( $transformerCategory->transform ( $element ) );
		$item->description ( $transformerDescription->transform ( $element ) );
		$item->appendTo ( $channel );
	}
	
	return new Response ( $feed, 200 );
} );

/**
 * Method to show details for given record in rss stream
 *
 * @todo ids for all records are not unique,
 * @todo so it may be a problem but this situation is not so probable
 * @todo we can just ignore that, but it should be changed for good
 *      
 */
$app->get ( '/details/{unique}', function (Request $request, $unique) use($app) {
	
	assert ( ($collection = $app ['wurst.history']->collection ()), "History can not be empty" );
	assert ( ($element = isset ( $collection [$unique] ) ? $collection [$unique] : null), "Unknown record index" );
	
	return $app ['twig']->render ( 'record.html.twig', array (
			'element' => $element 
	) );
} );

$app->run ();
