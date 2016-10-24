<?php

use Zend\ServiceManager\ServiceManager;
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
	throw new \Exception( 'Configuration files does not found' );
}
$config = include __DIR__ . '/../config/module.config.php';
$meta = file_get_contents ( __DIR__ . '/../composer.json' );
$meta = Json::decode ( $meta );
$serviceManager = new ServiceManager();
$serviceManager->setService('Config', $config);

$application = new Application(
	$meta->description,
	$meta->version,
	include __DIR__ . '/../config/module.routes.php',
	Console::getInstance (),
	new Dispatcher($serviceManager)
);
$application->setBanner("
____    __    ____   _______. _______ 
\   \  /  \  /   /  /       ||   ____|
 \   \/    \/   /  |   (----`|  |__   
  \            /    \   \    |   __|  
   \    /\    / .----)   |   |  |     
    \__/  \__/  |_______/    |__|   
");
$application->setBannerDisabledForUserCommands(true);
$application->setFooter ( $meta->authors['0']->homepage );
$application->run ();