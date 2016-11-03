<?php
namespace WebSockets\Aware;

/**
 * Interface ConsoleInterface.
 *
 * @package    WebSockets\Aware
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Aware/ConsoleInterface.php
 */
interface ConsoleInterface {

	/**
	 * Check if currently running under MS Windows
	 *
	 * @see http://stackoverflow.com/questions/738823/possible-values-for-php-os
	 * @return bool
	 */
	public function isWin();

	/**
	 * Get console adapter
	 *
	 * @return \Zend\Console\Adapter\AdapterInterface
	 */
	public function getConsoleAdapter();
}
