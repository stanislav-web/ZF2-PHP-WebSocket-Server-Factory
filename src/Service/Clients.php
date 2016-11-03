<?php
namespace WebSockets\Service;

use WebSockets\Aware\ClientStorageInterface;
use WebSockets\DataObject\Client;

/**
 * Class Clients
 *
 * @package    WebSockets\Service
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Service/Clients.php
 */
class Clients implements ClientStorageInterface {

	/**
	 * Active connections (clients)
	 *
	 * @var  \SplObjectStorage $storage
	 */
	public $storage;

	/**
	 * Clients constructor.
	 * @uses \SplObjectStorage
	 */
	public function __construct () {
		$this->storage = new \SplObjectStorage();
	}

	/**
	 * Get all connected clients
	 *
	 * @param callable $callback
	 * @return array
	 */
	public function getAll(callable $callback) {

		var_dump($this->storage);
		foreach ($this->storage as $client) {
			$callback($client);
		}
	}

	/**
	 * Client's counter
	 *
	 * @return int
	 */
	public function count() {
		return $this->storage->count();
	}

	/**
	 * Add client to storage
	 *
	 * @param Client $client
	 *
	 * @return ClientStorageInterface
	 */
	public function add(Client $client) {

		var_dump($client);

		// increase amount of clients connected on this client's IP
		if (isset($this->_clientIPcount[$clientIp])) $this->_clientIPcount[$clientIp]++;
		else $this->_clientIPcount[$clientIp] = 1;

		// fetch next client ID
		$clientId = $this->nextClientId();

		// store initial client data
		$this->clients[$clientId] = [$socket, '', Frame::get('SERVER_READY_STATE_CONNECTING'), time(), false, 0, $clientIp, false, 0, '', 0, 0];

		// store socket - used for socket_select()
		$this->_read[$clientId] = $socket;
		
	}

}
