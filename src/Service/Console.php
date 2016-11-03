<?php
namespace WebSockets\Service;

use Zend\Console\ColorInterface;
use Zend\Console\Console as ZendConsole;
use WebSockets\Aware\ConsoleInterface;

/**
 * Class Console
 *
 * @package    WebSockets\Service
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Service/Console.php
 */
class Console extends ZendConsole implements ConsoleInterface {

	/**
	 * Check if currently running under MS Windows
	 *
	 * @see http://stackoverflow.com/questions/738823/possible-values-for-php-os
	 * @return bool
	 */
	public function isWin() {
		return parent::isWindows();
	}

	/**
	 * Get console adapter
	 *
	 * @return \Zend\Console\Adapter\AdapterInterface
	 */
	public function getConsoleAdapter() {
		return parent::getInstance();
	}
}
