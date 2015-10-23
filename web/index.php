<?php
require_once './bootstrap.php';

assert ( ! empty ( $FOLDER_ROOT ), 'Project root folder should be defined' );
assert ( ! empty ( $FOLDER_XML ), 'Xml status files folder should be defined' );
assert ( ! empty ( $FILE_HISTORY_CACHE ), 'History cache file should be defined' );
assert ( ! empty ( $SERVER_ROOT ), 'Server root string should not be empty' );

$loader = require_once "{$FOLDER_ROOT}/vendor/autoload.php";
$loader->add ( "Wurst", "{$FOLDER_ROOT}/src" );

use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use Suin\RSSWriter\Channel;

use Wurst\Transformer\RecordToCategoryTransformer;
use Wurst\Transformer\RecordToDescriptionTransformer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
	$transformerDescription = new RecordToDescriptionTransformer ();
	
	foreach ( $app ['wurst.history']->collection () as $id => $element ) {
		
		$category = $transformerCategory->transform ( $element );
		$description = $transformerDescription->transform ( $element );
		
		$item = new Item ();
		$item->author ( "Wurst status server" );
		$item->url ( "http://{$SERVER_ROOT}/details/{$id}" );
		$item->title ( "{$element->getName ()} " . strtolower ( $category ) );
		$item->pubDate ( $element->getDate () );
		$item->category ( $category );
		$item->description ( $description );
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
	
	$element->setNotice ( str_replace ( "\n", "<br/>", $element->getNotice () ) );
	$element->setNotice ( str_replace ( ",", ", ", $element->getNotice () ) );
	
	$element->setInfo ( str_replace ( "\n", "<br/>", $element->getInfo () ) );
	$element->setInfo ( str_replace ( ",", ", ", $element->getInfo () ) );
	
	$element->setError ( str_replace ( "\n", "<br/>", $element->getError () ) );
	$element->setError ( str_replace ( ",", ", ", $element->getError () ) );
	
	$element->setInfo ( str_replace ( "\n", "<br/>", $element->getInfo () ) );
	$element->setInfo ( str_replace ( ",", ", ", $element->getInfo () ) );
	
	$element->setFatal ( str_replace ( "\n", "<br/>", $element->getFatal () ) );
	$element->setFatal ( str_replace ( ",", ", ", $element->getFatal () ) );
	
	return $app ['twig']->render ( 'record.html.twig', array (
			'element' => $element 
	) );
} );

$app->run ();
