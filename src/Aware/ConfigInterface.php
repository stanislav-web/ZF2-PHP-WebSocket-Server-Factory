<?php
namespace WebSockets\Aware;

/**
 * Interface ConfigInterface.
 *
 * @package    WebSockets\Aware
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Aware/ConfigInterface.php
 */
interface ConfigInterface {

	/**
	 * @const DEFAULT_HOST
	 */
	const DEFAULT_HOST = '127.0.0.1';

	/**
	 * Get websocket host
	 *
	 * @return mixed
	 */
	public function getHost ();

	/**
	 * Get websocket port
	 *
	 * @return int
	 */
	public function getPort ();

	/**
	 * Is debug enabled (disabled)
	 *
	 * @return boolean
	 */
	public function isDebug ();

	/**
	 * Get console charset
	 *
	 * @return string
	 */
	public function getCharset ();

	/**
	 * Is log enabled (disabled)
	 *
	 * @return boolean
	 */
	public function isLog ();

	/**
	 * Get log file
	 *
	 * @return string
	 */
	public function getLogfile ();

	/**
	 * Get maximum amount of clients that can be connected at one time
	 * @return int
	 */
	public function getMaxClients ();

	/**
	 * Get maximum amount of clients that can be connected at one time on the same IP v4 address
	 *
	 * @return int
	 */
	public function getMaxClientsPerIp ();

	/**
	 * Get amount of seconds a client has to send data to the server, before a ping request is sent to the client
	 *
	 * @return int
	 */
	public function getTimeoutRecv ();

	/**
	 * Get amount of seconds a client has to reply to a ping request, before the client connection is closed
	 *
	 * @return int
	 */
	public function getTimeoutPong ();

	/**
	 * Get maximum length (bytes) of a frame's payload data, this is also internally limited to 2,147,479,538
	 *
	 * @return int
	 */
	public function getMaxFramePayloadRecv ();

	/**
	 * Get maximum length (bytes) of a message's payload data, this is also internally limited to 2,147,483,647
	 *
	 * @return int
	 */
	public function getMaxMessagePayloadRecv ();

}
