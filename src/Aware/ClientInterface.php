<?php
namespace WebSockets\Aware;

use WebSockets\DataObject\Client;

/**
 * Interface ClientStorageInterface.
 *
 * @package    WebSockets\Aware
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Aware/ClientStorageInterface.php
 */
interface ClientStorageInterface {

	/**
	 * Get all connected clients
	 *
	 * @param callable $callback
	 * @return array
	 */
	public function getAll(callable $callback);

	/**
	 * Add client to storage
	 *
	 * @param Client $client
	 *
	 * @return ClientStorageInterface
	 */
	public function add(Client $client);

	/**
	 * Client's counter
	 *
	 * @return int
	 */
	public function count();
}
