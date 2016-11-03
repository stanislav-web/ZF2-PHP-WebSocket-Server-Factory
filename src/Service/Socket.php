<?php
namespace WebSockets\Service;

use WebSockets\Aware\ConsoleInterface;
use WebSockets\Aware\SocketInterface;
use WebSockets\Aware\MessageInterface;
use WebSockets\Status\WinSocketErrors;
use WebSockets\Status\UnixSocketErrors;

/**
 * Class Socket
 * PHP Socket implementation
 *
 * @package    WebSockets\Service
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Service/Socket.php
 */
class Socket implements SocketInterface {

	/**
	 * Socket identifier
	 *
	 * @var resource $resource
	 */
	private $resource;
	
	/**
	 * Error messages constructor
	 *
	 * @var MessageInterface $error
	 */
	private $error;

	/**
	 * Socket constructor.
	 *
	 * @param ConsoleInterface $console
	 */
	public function __construct (ConsoleInterface $console) {

		if ( true === $console->isWin () ) {
			$this->error = new WinSocketErrors();
		} else {
			$this->error = new UnixSocketErrors();
		}
	}

	/**
	 * Create socket
	 *
	 * @param int $domain
	 * @param int $type
	 * @param int $protocol
	 * @uses socket_create()
	 * @throws \Exception
	 * @return resource
	 */
	public function create ( $domain = AF_INET, $type = SOCK_STREAM, $protocol = SOL_TCP ) {

		$this->resource = socket_create ( $domain, $type, $protocol );

		if ( false === $this->resource ) {
			throw new \Exception( $this->error->stdOut(socket_last_error ()), socket_last_error () );
		}

		return $this->resource;
	}

	/**
	 * Check socket to see if a write/send/sendTo will not block
	 *
	 * @param float|NULL $sec maximum time to wait (in seconds), 0 = immediate polling, null = no limit
	 * @return boolean true = socket ready (write will not block), false = timeout expired, socket is not ready
	 * @throws \Exception
	 * @uses socket_select()
	 */
	public function select($sec = 0) {

		$readBuffer = $except = [ ];
		$usec = $sec === null ? null : (($sec - floor($sec)) * 1000000);
		$read = array($this->resource);
		$result = socket_select($read, $readBuffer, $except, $sec, $usec);

		if ( false === $result ) {
			throw new \Exception( $this->error->stdOut(socket_last_error ()), socket_last_error () );
		}

		return $result;
	}

	/**
	 * Accept an incomming connection on this listening socket
	 *
	 * @throws \Exception
	 * @uses socket_accept()
	 * @return resource
	 */
	public function accept () {
		// TODO: Implement accept() method.
	}

	/**
	 * Binds a name/address/path to this socket has to be called before issuing connect() or listen()
	 *
	 * @param string $host either of IPv4, hostname, IPv6, unix-path
	 * @param int $port port
	 *
	 * @throws \Exception
	 * @uses socket_bind()
	 * @return SocketInterface
	 */
	public function bind ( $host , $port) {

		if ( false === socket_bind ( $this->resource, $host, $port ) ) {
			throw new \Exception( $this->error->stdOut(socket_last_error ()), socket_last_error () );
		}
	}

	/**
	 * Start listen for incoming connections
	 *
	 * @param int $backlog maximum number of incoming connections to be queued
	 *
	 * @throws \Exception
	 * @see  bind() has to be called first to bind name to socket
	 * @uses socket_listen()
	 * @return SocketInterface
	 */
	public function listen ( $backlog = 0 ) {

		$result = socket_listen($this->resource, $backlog);
		if ($result === false) {
			throw new \Exception( $this->error->stdOut(socket_last_error ()), socket_last_error () );
		}

		return $this;
	}

	/**
	 * Read up to $length bytes from connect()ed / accept()ed socket
	 *
	 * The $type parameter specifies if this should use either binary safe reading
	 * (PHP_BINARY_READ, the default) or stop at CR or LF characters (PHP_NORMAL_READ)
	 *
	 * @param int $length maximum length to read
	 * @param int $type   either of PHP_BINARY_READ (the default) or PHP_NORMAL_READ
	 *
	 * @throws \Exception
	 * @see  recv() if you need to pass flags
	 * @uses socket_read()
	 * @return string
	 */
	public function read ( $length, $type = PHP_BINARY_READ ) {
		// TODO: Implement read() method.
	}

	/**
	 * Write $buffer to connect()ed / accept()ed socket
	 *
	 * @param string $buffer
	 *
	 * @throws \Exception
	 * @see  send() if you need to pass flags
	 * @uses socket_write()
	 * @return int number of bytes actually written
	 */
	public function write ( $buffer ) {
		// TODO: Implement write() method.
	}

	/**
	 * Receive up to $length bytes from connect()ed / accept()ed socket
	 *
	 * @param int $length maximum length to read
	 * @param int $flags
	 *
	 * @throws \Exception
	 * @see  read() if you do not need to pass $flags
	 * @see  recvFrom() if your socket is not connect()ed
	 * @uses socket_recv()
	 * @return string
	 */
	public function recv ( $length, $flags ) {
		// TODO: Implement recv() method.
	}

	/**
	 * receive up to $length bytes from socket
	 *
	 * @param int    $length maximum length to read
	 * @param int    $flags
	 * @param string $remote reference will be filled with remote/peer address/path
	 *
	 * @throws \Exception
	 * @see  recv() if your socket is connect()ed
	 * @uses socket_recvfrom()
	 * @return string
	 */
	public function recvFrom ( $length, $flags, &$remote ) {
		// TODO: Implement recvFrom() method.
	}

	/**
	 * Send given $buffer to connect()ed / accept()ed socket
	 *
	 * @param string $buffer
	 * @param int    $flags
	 *
	 * @throws \Exception
	 * @see  write() if you do not need to pass $flags
	 * @uses socket_send()
	 * @return int number of bytes actually written (make sure to check against given buffer length!)
	 */
	public function send ( $buffer, $flags ) {
		// TODO: Implement send() method.
	}

	/**
	 * Close this socket
	 *
	 * ATTENTION: make sure to NOT re-use this socket instance after closing it!
	 * its socket resource remains closed and most further operations will fail!
	 *
	 * @uses socket_close()
	 * @return SocketInterface
	 */
	public function close () {
		socket_close ( $this->resource );
		return $this;
	}

	/**
	 * Shuts down socket for receiving, sending or both
	 *
	 * @param int $how 0 = shutdown reading, 1 = shutdown writing, 2 = shutdown reading and writing
	 *
	 * @throws \Exception
	 * @see  close()
	 * @uses socket_shutdown()
	 * @return SocketInterface
	 */
	public function shutdown ( $how = 2 ) {
		// TODO: Implement shutdown() method.
	}

	/**
	 * Initiates a new connection to given address, wait for up to $timeout seconds
	 *
	 * The given $timeout parameter is an upper bound, a maximum time to wait
	 * for the connection to be either accepted or rejected.
	 *
	 * The resulting socket resource will be set to non-blocking mode,
	 * regardless of its previous state and whether this method succedes or
	 * if it fails. Make sure to reset with `setBlocking(true)` if you want to
	 * continue using blocking calls.
	 *
	 * @param string $address either of IPv4:port, hostname:port, [IPv6]:port, unix-path
	 * @param float  $timeout maximum time to wait (in seconds)
	 *
	 * @throws \Exception
	 * @return SocketInterface
	 */
	public function connectTimeout ( $address, $timeout ) {
		// TODO: Implement connectTimeout() method.
	}

	/**
	 * Get actual socket resource
	 *
	 * @return resource
	 */
	public function getResource () {
		// TODO: Implement getResource() method.
	}

	/**
	 * Set socket option
	 *
	 * @param int   $level
	 * @param int   $optname
	 * @param mixed $optval
	 *
	 * @throws \Exception
	 * @see  getOption()
	 * @uses socket_set_option()
	 * @return SocketInterface
	 */
	public function setOption ( $level, $optname, $optval ) {

		$result = socket_set_option ( $this->resource, $level, $optname, $optval );

		if ( false === $result ) {
			throw new \Exception( $this->error->stdOut(socket_last_error ($this->resource)));
		}

		return $this;
	}

	/**
	 * Get socket option
	 *
	 * @param int $level
	 * @param int $optname
	 *
	 * @throws \Exception
	 * @uses socket_get_option()
	 * @return mixed
	 */
	public function getOption ( $level, $optname ) {
		// TODO: Implement getOption() method.
	}

	/**
	 * Get remote side's address/path
	 *
	 * @param mixed $address
	 * @param int   $port
	 *
	 * @throws \Exception
	 * @uses socket_getpeername()
	 * @return string
	 */
	public function getPeerName ( &$address, &$port ) {

		$result = socket_getpeername($this->resource, $address, $port);
		if ( false === $result ) {
			throw new \Exception( $this->error->stdOut(socket_last_error ($this->resource)));
		}
		return ip2long($address).':'.$port;
	}

	/**
	 * Get socket type as passed to socket_create()
	 *
	 * @throws \Exception
	 * @uses getOption()
	 * @return int usually either SOCK_STREAM or SOCK_DGRAM
	 */
	public function getType () {
		// TODO: Implement getType() method.
	}
}
