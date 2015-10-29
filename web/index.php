<?php
require_once './bootstrap.php';

assert ( ! empty ( $FOLDER_ROOT ), 'Variable $FOLDER_ROOT should be defined' );
assert ( ! empty ( $FOLDER_XML ), 'Variable $FOLDER_XML should be defined' );
assert ( ! empty ( $FILE_HISTORY_CACHE ), 'Variable $FILE_HISTORY_CACHE should be defined' );
assert ( ! empty ( $SERVER_ROOT ), 'Variable $SERVER_ROOT should be defined' );

$loader = require_once "{$FOLDER_ROOT}/vendor/autoload.php";
$loader->add ( "Wurst", "{$FOLDER_ROOT}/src" );

use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use Suin\RSSWriter\Channel;

use Wurst\Transformer\RecordToCategoryTransformer;
use Wurst\Transformer\RecordToDescriptionTransformer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wurst\Transformer\RecordToTitleTransformer;

$app = new Silex\Application ();
$app ['debug'] = true;

$app->register ( new Silex\Provider\TwigServiceProvider (), array (
		'twig.path' => "{$FOLDER_ROOT}/templates" 
) );

$app->register ( new Wurst\Provider\WurstServiceProvider ( array (
		'path.xml' => $FOLDER_XML,
		'path.cache' => $FILE_HISTORY_CACHE 
) ) );

/**
 * Main method to calculate rss string from
 * status xml files or from cache, of no statuses found
 * i think it is easy to understand
 */
$app->get ( '/', function (Request $request) use($app, $SERVER_ROOT) {
	
	$feed = new Feed ();
	
	$channel = new Channel ();
	$channel->title ( 'Wurst update status' );
	$channel->description ( "Uni-Hamburg server" );
	$channel->url ( 'http://zbh.uni-hamburg.de' );
	$channel->appendTo ( $feed );
	
	$transformerCategory = new RecordToCategoryTransformer ();
	$transformerTitle = new RecordToTitleTransformer ( $transformerCategory );
	$transformerDescription = new RecordToDescriptionTransformer ($app ['twig']);
	
	foreach ( $app ['wurst.history']->collection () as $id => $element ) {
		
		$item = new Item ();
		$item->author ( "wurst update" );
		$item->url ( "http://{$SERVER_ROOT}/details/{$id}" );
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

	$element->setInfo ( str_replace ( "\n", "<br/>", $element->getInfo () ) );
	$element->setInfo ( str_replace ( ",", ", ", $element->getInfo () ) );
	
	$element->setWarning ( str_replace ( "\n", "<br/>", $element->getWarning () ) );
	$element->setWarning ( str_replace ( ",", ", ", $element->getWarning () ) );
	
	$element->setError ( str_replace ( "\n", "<br/>", $element->getError () ) );
	$element->setError ( str_replace ( ",", ", ", $element->getError () ) );

	$element->setStderr ( str_replace ( "\n", "<br/>", $element->getStderr () ) );
	
	$element->setFatal ( str_replace ( "\n", "<br/>", $element->getFatal () ) );
	$element->setFatal ( str_replace ( ",", ", ", $element->getFatal () ) );
	
	return $app ['twig']->render ( 'record.html.twig', array (
			'element' => $element 
	) );
} );

$app->run ();
