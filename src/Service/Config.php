<?php
namespace WebSockets\Service;

use WebSockets\Aware\ConfigInterface;

/**
 * Class Config
 *
 * @package    WebSockets\Service
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Service/Config.php
 */
class Config implements ConfigInterface {

	/**
	 * Websocket host
	 *
	 * @var mixed $host
	 */
	private $host;

	/**
	 * Websocket port
	 *
	 * @var int $port
	 */
	private $port;

	/**
	 * Enable (disable) CLI debug console to watching requests and responses data
	 *
	 * @var boolean $debug
	 */
	private $debug;

	/**
	 * Console charset
	 *
	 * @var string $charset
	 */
	private $charset;

	/**
	 * Enable (disable) CLI logger
	 *
	 * @var boolean $log
	 */
	private $log;

	/**
	 * Logfile path
	 *
	 * @var string $logfile
	 */
	private $logfile;

	/**
	 * Maximum amount of clients that can be connected at one time
	 *
	 * @var int $maxClients
	 */
	private $maxClients;

	/**
	 * Maximum amount of clients that can be connected at one time on the same IP v4 address
	 *
	 * @var int $maxClientsPerIp
	 */
	private $maxClientsPerIp;

	/**
	 * Amount of seconds a client has to send data to the server, before a ping request is sent to the client.
	 * If the client has not completed the opening handshake, the ping request is skipped and the client connection is closed
	 *
	 * @var int $timeoutRecv
	 */
	private $timeoutRecv;

	/**
	 * Amount of seconds a client has to reply to a ping request, before the client connection is closed
	 *
	 * @var int $timeoutPong
	 */
	private $timeoutPong;

	/**
	 * The maximum length (bytes) of a frame's payload data, this is also internally limited to 2,147,479,538
	 *
	 * @var int $maxFramePayloadRecv
	 */
	private $maxFramePayloadRecv;

	/**
	 * The maximum length (bytes) of a message's payload data, this is also internally limited to 2,147,483,647
	 *
	 * @var int $maxMessagePayloadRecv
	 */
	private $maxMessagePayloadRecv;

	/**
	 * Config constructor.
	 *
	 * @param array $config
	 */
	public function __construct ( array $config ) {

		try {
			$this->setHost ( $config['host'] );
			$this->setPort ( $config['port'] );
			$this->setDebug ( $config['debug'] );
			$this->setCharset ( $config['charset'] );
			$this->setLog ( $config['log'] );
			$this->setLogfile ( $config['logfile'] );
			$this->setMaxClients ( $config['max_clients'] );
			$this->setMaxClientsPerIp ( $config['max_clients_per_ip'] );
			$this->setTimeoutRecv ( $config['timeout_recv'] );
			$this->setTimeoutPong ( $config['timeout_pong'] );
			$this->setMaxFramePayloadRecv ( $config['max_frame_payload_recv'] );
			$this->setMaxMessagePayloadRecv ( $config['max_message_payload_recv'] );
		} catch ( \Exception $e ) {
			throw new \Exception( $e->getMessage () );
		}
	}

	/**
	 * @return mixed
	 */
	public function getHost () {
		return $this->host;
	}

	/**
	 * @param mixed $host
	 */
	public function setHost ( $host ) {

		if ( false === filter_var ( $host, FILTER_VALIDATE_IP ) ) {
			$this->host = self::DEFAULT_HOST;
		} else {
			$this->host = trim ( $host );
		}
	}

	/**
	 * @return int
	 */
	public function getPort () {
		return $this->port;
	}

	/**
	 * @param int $port
	 */
	public function setPort ( $port ) {
		$this->port = (int) $port;
	}

	/**
	 * @return boolean
	 */
	public function isDebug () {
		return $this->debug;
	}

	/**
	 * @param boolean $debug
	 */
	public function setDebug ( $debug ) {
		$this->debug = (bool) $debug;
	}

	/**
	 * @return string
	 */
	public function getCharset () {
		return $this->charset;
	}

	/**
	 * @param string $charset
	 */
	public function setCharset ( $charset ) {
		$this->charset = trim ( $charset );
	}

	/**
	 * @return boolean
	 */
	public function isLog () {
		return $this->log;
	}

	/**
	 * @param boolean $log
	 */
	public function setLog ( $log ) {
		$this->log = (bool) $log;
	}

	/**
	 * @return string
	 */
	public function getLogfile () {
		return $this->logfile;
	}

	/**
	 * @param string $logfile
	 */
	public function setLogfile ( $logfile ) {
		$this->logfile = trim ( $logfile );
	}

	/**
	 * @return int
	 */
	public function getMaxClients () {
		return $this->maxClients;
	}

	/**
	 * @param int $maxClients
	 */
	public function setMaxClients ( $maxClients ) {
		$this->maxClients = (int) $maxClients;
	}

	/**
	 * @return int
	 */
	public function getMaxClientsPerIp () {
		return $this->maxClientsPerIp;
	}

	/**
	 * @param int $maxClientsPerIp
	 */
	public function setMaxClientsPerIp ( $maxClientsPerIp ) {
		$this->maxClientsPerIp = (int) $maxClientsPerIp;
	}

	/**
	 * @return int
	 */
	public function getTimeoutRecv () {
		return $this->timeoutRecv;
	}

	/**
	 * @param int $timeoutRecv
	 */
	public function setTimeoutRecv ( $timeoutRecv ) {
		$this->timeoutRecv = (int) $timeoutRecv;
	}

	/**
	 * @return int
	 */
	public function getTimeoutPong () {
		return $this->timeoutPong;
	}

	/**
	 * @param int $timeoutPong
	 */
	public function setTimeoutPong ( $timeoutPong ) {
		$this->timeoutPong = (int) $timeoutPong;
	}

	/**
	 * @return int
	 */
	public function getMaxFramePayloadRecv () {
		return $this->maxFramePayloadRecv;
	}

	/**
	 * @param int $maxFramePayloadRecv
	 */
	public function setMaxFramePayloadRecv ( $maxFramePayloadRecv ) {
		$this->maxFramePayloadRecv = (int) $maxFramePayloadRecv;
	}

	/**
	 * @return int
	 */
	public function getMaxMessagePayloadRecv () {
		return $this->maxMessagePayloadRecv;
	}

	/**
	 * @param int $maxMessagePayloadRecv
	 */
	public function setMaxMessagePayloadRecv ( $maxMessagePayloadRecv ) {
		$this->maxMessagePayloadRecv = (int) $maxMessagePayloadRecv;
	}
}
