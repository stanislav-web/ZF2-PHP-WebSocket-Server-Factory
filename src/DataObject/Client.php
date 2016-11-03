<?php
namespace WebSockets\DataObject;

/**
 * Class Client.
 * Client data object
 *
 * @package    WebSockets\DataObject
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/DataObject/Client.php
 */
class Client {

	/**
	 * Client constructor.
	 *
	 * @param $id
	 * @param $ip
	 * @param $port
	 */
	public function __construct ($id, $ip = null, $port = null) {

		$this->id = $id;
		$this->ip = $ip;
		$this->port = $port;
	}

	public $id;

	public $ip;

	public $port;
}
