<?php
namespace WebSockets\Exception;

/**
 * Interface ExceptionInterface
 * 
 * @package     WebSockets\Exception
 * @since       PHP >=5.6
 * @version     v3.2.1
 * @author      Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright   Stanislav WEB
 * @license     Zend Framework GUI license (New BSD License)
 * @filesource  /vendor/stanislav-web/zf2-websocket-server-factory/src/Exception/ExceptionInterface.php
 */
interface ExceptionInterface {

	/**
	 * Throw error code
	 *
	 * @return int
	 */
	public function throwErrorCode ();

	/**
	 * Throw error message
	 *
	 * @return string
	 */
	public function throwErrorMessage ();
}
