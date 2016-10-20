<?php
use Zend\Console\Console;
use Zend\Json\Json;
use ZF\Console\Application;
use ZF\Console\Dispatcher;

require getcwd () . '/vendor/autoload.php';

if ( false === file_exists ( getcwd () . '/vendor/autoload.php' )
     || false === file_exists ( __DIR__ . '/../config/module.config.php' )
     || false === file_exists ( __DIR__ . '/../config/module.routes.php' )
     || false === file_exists ( __DIR__ . '/../composer.json' )
) {
	throw new \Exception( 'Configuration files does not dound' );
}
$config = include __DIR__ . '/../config/module.config.php';
$routes = include __DIR__ . '/../config/module.routes.php';
$meta = file_get_contents ( __DIR__ . '/../composer.json' );
$meta = Json::decode ( $meta );

$application = new Application(
	$meta->description,
	$meta->version,
	$routes,
	Console::getInstance (),
	new Dispatcher()
);
$application->setFooter ( $meta->authors['0']->homepage );
$application->run ();