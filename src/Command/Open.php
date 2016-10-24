<?php
namespace WebSockets\Command;

use ZF\Console\Route;
use Zend\Console\Adapter\AdapterInterface;
use WebSockets\Factory\ApplicationFactory;

/**
 * Class Open
 * @package     WebSockets\Command
 * @since       PHP >=5.6
 * @version     v3.2.1
 * @author      Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright   Stanislav WEB
 * @license     Zend Framework GUI license (New BSD License)
 * @filesource  /vendor/stanislav-web/zf2-websocket-server-factory/src/Command/Open.php
 */
class Open {

	/**
	 * Point of entering commands
	 *
	 * @param Route            $route
	 * @param AdapterInterface $console
	 */
	public static function run ( Route $route, AdapterInterface $console ) {

		$param = $route->getMatchedParam ( "app" );
		$serviceLocator = $param['serviceLocator'];

		try {

			// get factory container
			$application = new ApplicationFactory( $serviceLocator, $console );
			$application = $application->dispatch ( $param['client'] );

			// bind listeners
			$application->bind ( 'open', 'onOpen' );
			//$application->bind ( 'message', 'onMessage' );
			//$application->bind ( 'close', 'onClose' );

			// running server
			$application->run ();

		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage ());
		}
	}
}