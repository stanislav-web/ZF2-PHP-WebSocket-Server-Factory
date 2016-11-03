<?php
namespace WebSockets\Service\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Class BaseLogger
 *
 * @package    WebSockets\Service\Logger
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Service/Logger/BaseLogger.php
 */
abstract class BaseLogger extends AbstractLogger {
	/**
	 * Minimum log level for the logger
	 *
	 * @var    string $level
	 */
	private $level = LogLevel::DEBUG;

	/**
	 * Set minimum log level
	 *
	 * @param  string $level
	 */
	public function setLevel ( $level ) {
		$this->level = $level;
	}

	/**
	 * Get minimum log level
	 *
	 * @access public
	 * @return string
	 */
	public function getLevel () {
		return $this->level;
	}

	/**
	 * Dump to log a variable (by example an array)
	 *
	 * @param mixed $variable
	 */
	public function dump ( $variable ) {
		$this->log ( LogLevel::DEBUG, var_export ( $variable, true ) );
	}

	/**
	 * Interpolates context values into the message placeholders.
	 *
	 * @param  string $message
	 * @param  array  $context
	 *
	 * @return string
	 */
	protected function interpolate ( $message, array $context = array () ) {
		// build a replacement array with braces around the context keys
		$replace = array ();
		foreach ( $context as $key => $val ) {
			$replace['{' . $key . '}'] = $val;
		}

		// interpolate replacement values into the message and return
		return strtr ( $message, $replace );
	}

	/**
	 * Format log message
	 *
	 * @param  int|string $level
	 * @param  string     $message
	 * @param  array      $context
	 *
	 * @return string
	 */
	protected function formatMessage ( $level, $message, array $context = array () ) {
		return '[' . date ( 'Y-m-d H:i:s' ) . '] [' . $level . '] ' . $this->interpolate ( $message, $context ) . PHP_EOL;
	}
}