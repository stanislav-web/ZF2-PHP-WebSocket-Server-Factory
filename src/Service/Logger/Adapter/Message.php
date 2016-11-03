<?php
namespace WebSockets\Service\Logger\Adapter;

use Psr\Log\LogLevel;
use WebSockets\Service\Logger\BaseLogger;

/**
 * Class Message
 * @package    WebSockets\Service\Logger\Adapter
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Service/Logger/Adapter/Message.php
 */
class Message extends BaseLogger {
	
	/**
	 * Logs with an arbitrary level.
	 *
	 * @param  string $level
	 * @param  string $message
	 * @param  array  $context
	 *
	 * @return null
	 */
	public function log ( $level = LOG_INFO, $message, array $context = [] ) {
		echo $this->formatMessage($level, $message, $context);
	}
}