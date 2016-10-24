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
	 * ApplicationInterface constructor.
	 * Server implementation
	 *
	 * @param ServerInterface  $serverInstance
	 * @param AdapterInterface $consoleInstance
	 */
	public function __construct ( ServerInterface $serverInstance, AdapterInterface $consoleInstance );

	/**
	 * Start server
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve Server instance
	 * @return bool
	 */
	public function onStart ();

	/**
	 * Opening a connection to the server
	 *
	 * @param int $clientId connection identifier
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections
	 * @return void
	 */
	public function onOpen ( $clientId );

	/**
	 * Send responses from server
	 *
	 * @param int    $clientId connection identifier
	 * @param string $message  server message
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections & send messages
	 * @return void
	 */
	public function onMessage ( $clientId, $message );

	/**
	 * Closing connection
	 *
	 * @param int $clientId connection identifier
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections & send message
	 * @return void
	 */
	public function onClose ( $clientId );
}
