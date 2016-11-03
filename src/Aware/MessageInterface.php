<?php
namespace WebSockets\Aware;

/**
 * Interface MessageInterface.
 * Response statuses interface
 *
 * @package    WebSockets\Aware
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Aware/MessageInterface.php
 */
interface MessageInterface {

	/**
	 * Print console message
	 *
	 * @param int|string $message
	 * @param boolean $isDebug
	 * @param int $color
	 *
	 * @return string
	 */
	public function stdOut ( $message , $isDebug = false, $color = null);
}
