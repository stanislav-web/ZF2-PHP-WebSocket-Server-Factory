<?php
namespace WebSockets\Aware;

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
	 * Get configurations
	 *
	 * @return \StdClass
	 */
	public function getConfig();
}
