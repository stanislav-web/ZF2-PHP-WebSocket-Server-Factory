<?php
namespace WebSockets\Aware;

use Zend\Console\Adapter\AdapterInterface;

/**
 * Interface ApplicationInterface.
 * The necessary rules for the implementation of customer applications
 *
 * @package    WebSockets\Aware
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Aware/ApplicationInterface.php
 */
interface ApplicationInterface {

	/**
	 * Socket response IP index
	 */
	const SOCKET_RESPONSE_IP = 6;

	/**
	 * ApplicationInterface constructor.
	 * Server implementation
	 *
	 * @param ServerInterface  $serverInstance
	 * @param AdapterInterface $consoleInstance
	 */
	public function __construct ( ServerInterface $serverInstance, AdapterInterface $consoleInstance );

	/**
	 * Opening a connection to the server event
	 *
	 * @param int $clientId connection identifier
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections
	 * @return void
	 */
	public function onOpen ( $clientId );

	/**
	 * Send responses from server event
	 *
	 * @param int    $clientId connection identifier
	 * @param string $message  server message
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections & send messages
	 * @return void
	 */
	public function onMessage ( $clientId, $message );

	/**
	 * Error listener
	 *
	 * @param ServerInterface $serverInstance
	 * @param \Exception      $e
	 *
	 * @return void
	 */
	public function onError(ServerInterface $serverInstance, \Exception $e);

	/**
	 * Closing connection event
	 *
	 * @param int $clientId connection identifier
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections & send message
	 * @return void
	 */
	public function onClose ( $clientId );

	/**
	 * Run application
	 *
	 * @return void
	 */
	public function run();
}
