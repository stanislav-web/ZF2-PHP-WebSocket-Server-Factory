<?php
namespace WebSockets\Service;

use WebSockets\Aware\SocketInterface;

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
class SocketOld {

	/**
	 * Reference to actual socket resource
	 *
	 * @var resource $resource
	 */
	protected $resource;

	/**
	 * get actual socket resource
	 *
	 * @return resource
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 * accept an incomming connection on this listening socket
	 *
	 * @return \Socket\Raw\Socket new connected socket used for communication
	 * @throws Exception on error, if this is not a listening socket or there's no connection pending
	 * @see self::selectRead() to check if this listening socket can accept()
	 * @see Factory::createServer() to create a listening socket
	 * @see self::listen() has to be called first
	 * @uses socket_accept()
	 */
	public function accept()
	{
		$resource = @socket_accept($this->resource);
		if ($resource === false) {
			throw Exception::createFromGlobalSocketOperation();
		}
		return new Socket($resource);
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
	 * @return self $this (chainable)
	 * @throws Exception on error
	 * @uses self::setBlocking() to enable non-blocking mode
	 * @uses self::connect() to initiate the connection
	 * @uses self::selectWrite() to wait for the connection to complete
	 * @uses self::assertAlive() to check connection state
	 */
	public function connectTimeout($address, $timeout)
	{
		$this->setBlocking(false);

		try {
			// socket is non-blocking, so connect should emit EINPROGRESS
			$this->connect($address);

			// socket is already connected immediately?
			return $this;
		}
		catch (Exception $e) {
			// non-blocking connect() should be EINPROGRESS => otherwise re-throw
			if ($e->getCode() !== SOCKET_EINPROGRESS) {
				throw $e;
			}

			// connection should be completed (or rejected) within timeout
			if ($this->selectWrite($timeout) === false) {
				throw new Exception('Timed out while waiting for connection', SOCKET_ETIMEDOUT);
			}

			// confirm connection success (or fail if connected has been rejected)
			$this->assertAlive();

			return $this;
		}
	}

	/**
	 * get socket option
	 *
	 * @param int $level
	 * @param int $optname
	 * @return mixed
	 * @throws Exception on error
	 * @uses socket_get_option()
	 */
	public function getOption($level, $optname)
	{
		$value = @socket_get_option($this->resource, $level, $optname);
		if ($value === false) {
			throw Exception::createFromSocketResource($this->resource);
		}
		return $value;
	}

	/**
	 * get remote side's address/path
	 *
	 * @return string
	 * @throws Exception on error
	 * @uses socket_getpeername()
	 */
	public function getPeerName($address, $port)
	{
		$ret = @socket_getpeername($this->resource, $address, $port);
		if ($ret === false) {
			throw Exception::createFromSocketResource($this->resource);
		}
		return $this->formatAddress($address, $port);
	}

	/**
	 * get local side's address/path
	 *
	 * @return string
	 * @throws Exception on error
	 * @uses socket_getsockname()
	 */
	public function getSockName()
	{
		$ret = @socket_getsockname($this->resource, $address, $port);
		if ($ret === false) {
			throw Exception::createFromSocketResource($this->resource);
		}
		return $this->formatAddress($address, $port);
	}



	/**
	 * read up to $length bytes from connect()ed / accept()ed socket
	 *
	 * The $type parameter specifies if this should use either binary safe reading
	 * (PHP_BINARY_READ, the default) or stop at CR or LF characters (PHP_NORMAL_READ)
	 *
	 * @param int $length maximum length to read
	 * @param int $type   either of PHP_BINARY_READ (the default) or PHP_NORMAL_READ
	 * @return string
	 * @throws Exception on error
	 * @see self::recv() if you need to pass flags
	 * @uses socket_read()
	 */
	public function read($length, $type = PHP_BINARY_READ)
	{
		$data = @socket_read($this->resource, $length, $type);
		if ($data === false) {
			throw Exception::createFromSocketResource($this->resource);
		}
		return $data;
	}

	/**
	 * receive up to $length bytes from connect()ed / accept()ed socket
	 *
	 * @param int $length maximum length to read
	 * @param int $flags
	 * @return string
	 * @throws Exception on error
	 * @see self::read() if you do not need to pass $flags
	 * @see self::recvFrom() if your socket is not connect()ed
	 * @uses socket_recv()
	 */
	public function recv($length, $flags)
	{
		$ret = @socket_recv($this->resource, $buffer, $length, $flags);
		if ($ret === false) {
			throw \Exception::createFromSocketResource($this->resource);
		}
		return $buffer;
	}

	/**
	 * receive up to $length bytes from socket
	 *
	 * @param int    $length maximum length to read
	 * @param int    $flags
	 * @param string $remote reference will be filled with remote/peer address/path
	 * @return string
	 * @throws Exception on error
	 * @see self::recv() if your socket is connect()ed
	 * @uses socket_recvfrom()
	 */
	public function recvFrom($length, $flags, &$remote)
	{
		$ret = @socket_recvfrom($this->resource, $buffer, $length, $flags, $address, $port);
		if ($ret === false) {
			throw Exception::createFromSocketResource($this->resource);
		}
		$remote = $this->formatAddress($address, $port);
		return $buffer;
	}


	/**
	 * check socket to see if a write/send/sendTo will not block
	 *
	 * @param float|NULL $sec maximum time to wait (in seconds), 0 = immediate polling, null = no limit
	 * @return boolean true = socket ready (write will not block), false = timeout expired, socket is not ready
	 * @throws Exception on error
	 * @uses socket_select()
	 */
	public function selectWrite($sec = 0)
	{
		$usec = $sec === null ? null : (($sec - floor($sec)) * 1000000);
		$w = array($this->resource);
		$ret = @socket_select($x, $w, $x, $sec, $usec);
		if ($ret === false) {
			throw Exception::createFromGlobalSocketOperation('Failed to select socket for writing');
		}
		return !!$ret;
	}

	/**
	 * send given $buffer to connect()ed / accept()ed socket
	 *
	 * @param string $buffer
	 * @param int    $flags
	 * @return int number of bytes actually written (make sure to check against given buffer length!)
	 * @throws Exception on error
	 * @see self::write() if you do not need to pass $flags
	 * @see self::sendTo() if your socket is not connect()ed
	 * @uses socket_send()
	 */
	public function send($buffer, $flags)
	{
		$ret = @socket_send($this->resource, $buffer, strlen($buffer), $flags);
		if ($ret === false) {
			throw Exception::createFromSocketResource($this->resource);
		}
		return $ret;
	}

	/**
	 * send given $buffer to socket
	 *
	 * @param string $buffer
	 * @param int    $flags
	 * @param string $remote remote/peer address/path
	 * @return int number of bytes actually written
	 * @throws Exception on error
	 * @see self::send() if your socket is connect()ed
	 * @uses socket_sendto()
	 */
	public function sendTo($buffer, $flags, $remote)
	{
		$ret = @socket_sendto($this->resource, $buffer, strlen($buffer), $flags, $this->unformatAddress($remote, $port), $port);
		if ($ret === false) {
			throw Exception::createFromSocketResource($this->resource);
		}
		return $ret;
	}

	/**
	 * enable/disable blocking/nonblocking mode (O_NONBLOCK flag)
	 *
	 * @param boolean $toggle
	 * @return self $this (chainable)
	 * @throws Exception on error
	 * @uses socket_set_block()
	 * @uses socket_set_nonblock()
	 */
	public function setBlocking($toggle = true)
	{
		$ret = $toggle ? @socket_set_block($this->resource) : @socket_set_nonblock($this->resource);
		if ($ret === false) {
			throw Exception::createFromSocketResource($this->resource);
		}
		return $this;
	}

	/**
	 * shuts down socket for receiving, sending or both
	 *
	 * @param int $how 0 = shutdown reading, 1 = shutdown writing, 2 = shutdown reading and writing
	 * @return self $this (chainable)
	 * @throws Exception on error
	 * @see self::close()
	 * @uses socket_shutdown()
	 */
	public function shutdown($how = 2)
	{
		$ret = @socket_shutdown($this->resource, $how);
		if ($ret === false) {
			throw Exception::createFromSocketResource($this->resource);
		}
		return $this;
	}

	/**
	 * write $buffer to connect()ed / accept()ed socket
	 *
	 * @param string $buffer
	 * @return int number of bytes actually written
	 * @throws Exception on error
	 * @see self::send() if you need to pass flags
	 * @uses socket_write()
	 */
	public function write($buffer)
	{
		$ret = @socket_write($this->resource, $buffer);
		if ($ret === false) {
			throw Exception::createFromSocketResource($this->resource);
		}
		return $ret;
	}

	/**
	 * get socket type as passed to socket_create()
	 *
	 * @return int usually either SOCK_STREAM or SOCK_DGRAM
	 * @throws Exception on error
	 * @uses self::getOption()
	 */
	public function getType()
	{
		return $this->getOption(SOL_SOCKET, SO_TYPE);
	}

	/**
	 * assert that this socket is alive and its error code is 0
	 *
	 * This will fetch and reset the current socket error code from the
	 * socket and options and will throw an Exception along with error
	 * message and code if the code is not 0, i.e. if it does indicate
	 * an error situation.
	 *
	 * Calling this method should not be needed in most cases and is
	 * likely to not throw an Exception. Each socket operation like
	 * connect(), send(), etc. will throw a dedicated Exception in case
	 * of an error anyway.
	 *
	 * @return self $this (chainable)
	 * @throws Exception if error code is not 0
	 * @uses self::getOption() to retrieve and clear current error code
	 * @uses self::getErrorMessage() to translate error code to
	 */
	public function assertAlive()
	{
		$code = $this->getOption(SOL_SOCKET, SO_ERROR);
		if ($code !== 0) {
			throw Exception::createFromCode($code, 'Socket error');
		}
		return $this;
	}


}
