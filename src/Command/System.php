<?php
namespace WebSockets\Command;

use ZF\Console\Route;
use Zend\Console\Adapter\AdapterInterface;

/**
 * Class System
 * @package     WebSockets\Command
 * @since       PHP >=5.6
 * @version     v3.2.1
 * @author      Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright   Stanislav WEB
 * @license     Zend Framework GUI license (New BSD License)
 * @filesource  /vendor/stanislav-web/zf2-websocket-server-factory/src/Command/System.php
 */
class System {
	public static function run ( Route $route, AdapterInterface $console ) {
		$name = $route->getMatchedParam ( "name", "@gianarb" );
		$console->writeLine ( "Hi {$name}, you have call me. Now this is an awesome day!" );
	}
}