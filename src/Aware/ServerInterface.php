<?php
namespace WebSockets\Aware;

use Psr\Log\LoggerAwareInterface;

/**
 * Interface ServerInterface.
 * The necessary rules for the implementation new server
 *
 * @package    WebSockets\Aware
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Aware/ServerInterface.php
 */
interface ServerInterface {

	/**
	 * Get server configurations
	 *
	 * @return ConfigInterface
	 */
	public function getConfig();

	/**
	 * Get console
	 *
	 * @return ConsoleInterface
	 */
	public function getConsole();

	/**
	 * Get socket
	 *
	 * @return SocketInterface
	 */
	public function getSocket();

	/**
	 * Set messager interface
	 *
	 * @param LoggerAwareInterface $loggerInstance
	 * @return ServerInterface
	 */
	public function setMessager (LoggerAwareInterface $loggerInstance);

	/**
	 * Set logger interface
	 *
	 * @param LoggerAwareInterface $loggerInstance
	 * @return ServerInterface
	 */
	public function setLogger (LoggerAwareInterface $loggerInstance);

	/**
	 * Set debug interface
	 *
	 * @param LoggerAwareInterface $loggerInstance
	 * @return ServerInterface
	 */
	public function setDebugger (LoggerAwareInterface $loggerInstance);

	/**
	 * Start server
	 * 
	 * @return boolean
	 */
	public function start();

	/**
	 * Shutdown server
	 *
	 * @return void
	 */
	public function shutdown();
}
