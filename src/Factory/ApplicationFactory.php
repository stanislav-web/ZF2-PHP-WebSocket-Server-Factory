<?php
namespace WebSockets\Factory;

use Zend\Console\Adapter\AdapterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use WebSockets\Aware\ApplicationInterface;
use WebSockets\Service\WebsocketServer;
use WebSockets\Service\ConsoleBridge;

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
	 * @throws \RuntimeException
	 */
	public function dispatch ( $clientClassName ) {

		$config = (object) $this->serviceLocator->get ( 'Config' );

		try {
			$obj = new $clientClassName(
				new WebsocketServer( new ConsoleBridge(), $config ),
				$this->consoleAdapter
			);
			if ( !$obj instanceof ApplicationInterface ) {
				throw new \RuntimeException( 'This application does not supported by Module interface' );
			}

			return $obj;
		} catch ( \RuntimeException $e ) {
			throw new \Exception( $e->getMessage () );
		}
	}
}
