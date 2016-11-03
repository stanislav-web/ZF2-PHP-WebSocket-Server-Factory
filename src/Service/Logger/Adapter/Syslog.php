<?php
namespace WebSockets\Service\Logger\Adapter;

use Psr\Log\LogLevel;
use WebSockets\Service\Logger\BaseLogger;

/**
 * Class Syslog
 * @package    WebSockets\Service\Logger\Adapter
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Service/Logger/Adapter/Syslog.php
 */
class Syslog extends BaseLogger {
	/**
	 * Setup Syslog configuration
	 *
	 * @param  string $ident    Application name
	 * @param  int    $facility See http://php.net/manual/en/function.openlog.php
	 */
	public function __construct ( $ident = 'PHP', $facility = LOG_USER ) {
		if ( !openlog ( $ident, LOG_ODELAY | LOG_PID, $facility ) ) {
			throw new \Exception( 'Unable to connect to syslog.' );
		}
	}

	/**
	 * Get syslog priority according to Psr\LogLevel
	 *
	 * @param  string $level
	 *
	 * @return integer
	 */
	public function getSyslogPriority ( $level ) {
		switch ( $level ) {
			case LogLevel::EMERGENCY:
				return LOG_EMERG;
			case LogLevel::ALERT:
				return LOG_ALERT;
			case LogLevel::CRITICAL:
				return LOG_CRIT;
			case LogLevel::ERROR:
				return LOG_ERR;
			case LogLevel::WARNING:
				return LOG_WARNING;
			case LogLevel::NOTICE:
				return LOG_NOTICE;
			case LogLevel::INFO:
				return LOG_INFO;
		}

		return LOG_DEBUG;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed  $level
	 * @param string $message
	 * @param array  $context
	 *
	 * @return void
	 */
	public function log ( $level, $message, array $context = [ ] ) {
		$syslogPriority = $this->getSyslogPriority ( $level );
		$syslogMessage = $this->interpolate ( $message, $context );
		syslog ( $syslogPriority, $syslogMessage );
	}
}