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

	/**
	 * Point of entering commands
	 *
	 * @param Route            $route
	 * @param AdapterInterface $console
	 */
	public static function run ( Route $route, AdapterInterface $console ) {

		$param = $route->getMatchedParam ( "option", "php -v" );
		$console->writeLine(
			$console->colorize(exec ( $param, $val ), \Zend\Console\ColorInterface::BLUE)
		);
		$console->writeLine('');
	}
}