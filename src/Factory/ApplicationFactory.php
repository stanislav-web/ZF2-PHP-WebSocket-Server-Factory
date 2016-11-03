<?php
namespace WebSockets\Factory;

use Zend\Console\Adapter\AdapterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use WebSockets\Aware\ApplicationInterface;
use WebSockets\Service\Logger\Logger;
use WebSockets\Service\Console;
use WebSockets\Service\Server;
use WebSockets\Service\Socket;
use WebSockets\Service\Config;
use WebSockets\Service\Clients;

/**
 * Class ApplicationFactory.
 * Use this factory for get client applications
 *
 * @package    WebSockets\Factory
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Factory/ApplicationFactory.php
 */
class ApplicationFactory {

	/**
	 * Service locator
	 *
	 * @var ServiceLocatorInterface $serviceLocator
	 */
	private $serviceLocator;

	/**
	 * Console adapter
	 *
	 * @var AdapterInterface $consoleAdapter
	 */
	private $consoleAdapter;

	/**
	 * ApplicationFactory constructor.
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @param AdapterInterface        $consoleAdapter
	 */
	public function __construct ( ServiceLocatorInterface $serviceLocator, AdapterInterface $consoleAdapter ) {
		$this->serviceLocator = $serviceLocator;
		$this->consoleAdapter = $consoleAdapter;
	}

	/**
	 * Dispatch client application
	 *
	 * @param string $clientClassName
	 *
	 * @return ApplicationInterface
	 * @throws \Exception
	 */
	public function dispatch ( $clientClassName ) {

		$config = $this->serviceLocator->get ( 'Config' );

		try {
			$consoleInstance = new Console();
			$socketInstance = new Socket($consoleInstance);
			$configInstance = new Config( $config );
			$loggerInstance = new Logger();
			$clientStorageInstance = new Clients();

			$obj = new $clientClassName(
				new Server(
					$socketInstance,
					$consoleInstance,
					$configInstance,
					$loggerInstance,
					$clientStorageInstance
				),
				$this->consoleAdapter
			);
			if ( !$obj instanceof ApplicationInterface ) {
				throw new \Exception( 'This application does not supported by Module interface' );
			}

			return $obj;
		} catch ( \Exception $e ) {
			throw new \Exception( $e->getMessage () );
		}
	}

}
