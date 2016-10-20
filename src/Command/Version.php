<?php
namespace WebSockets\Command;

use ZF\Console\Route;
use Zend\Console\Adapter\AdapterInterface;

/**
 * Class Version
 * @package     WebSockets\Command
 * @since       PHP >=5.6
 * @version     v3.2.1
 * @author      Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright   Stanislav WEB
 * @license     Zend Framework GUI license (New BSD License)
 * @filesource  /vendor/stanislav-web/zf2-websocket-server-factory/src/Command/Version.php
 */
class Version {
	public static function run ( Route $route, AdapterInterface $console ) {
		$console->writeLine ( "Hi  you have call me. Now this is an awesome day!" );
	}
}