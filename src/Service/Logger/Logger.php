<?php
namespace WebSockets\Service\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class Logger
 *
 * @package    WebSockets\Service\Logger
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Service/Logger/Logger.php
 */
class Logger extends AbstractLogger implements LoggerAwareInterface {

	/**
	 * Logger instances
	 *
	 * @var array $loggers
	 */
	private $loggers = [ ];

	/**
	 * Get level priority
	 *
	 * @param  string $level
	 *
	 * @return integer
	 */
	public function getLevelPriority ( $level ) {
		switch ( $level ) {
			case LogLevel::EMERGENCY:
				return 600;
			case LogLevel::ALERT:
				return 550;
			case LogLevel::CRITICAL:
				return 500;
			case LogLevel::ERROR:
				return 400;
			case LogLevel::WARNING:
				return 300;
			case LogLevel::NOTICE:
				return 250;
			case LogLevel::INFO:
				return 200;
		}

		return 100;
	}

	/**
	 * Sets a logger instance on the object
	 *
	 * @param  LoggerInterface $logger
	 *
	 * @return LoggerInterface
	 */
	public function setLogger ( LoggerInterface $logger ) {
		$this->loggers[] = $logger;

		return $this;
	}

	/**
	 * Proxy method to the real loggers
	 *
	 * @param  mixed  $level
	 * @param  string $message
	 * @param  array  $context
	 *
	 * @return null
	 */
	public function log ( $level, $message, array $context = array () ) {
		foreach ( $this->loggers as $logger ) {
			if ( $this->getLevelPriority ( $level ) >= $this->getLevelPriority ( $logger->getLevel () ) ) {
				$logger->log ( $level, $message, $context );
			}
		}
	}

	/**
	 * Dump variables for debugging
	 *
	 * @param mixed $variable
	 */
	public function dump ( $variable ) {
		foreach ( $this->loggers as $logger ) {
			if ( $this->getLevelPriority ( LogLevel::DEBUG ) >= $this->getLevelPriority ( $logger->getLevel () ) ) {
				$logger->dump ( $variable );
			}
		}
	}
}