<?php
namespace WebSockets\Service; // Namespaces of current service

use WebSockets\Status\WebSocketFrameCode as Frame,
    WebSockets\Exception,
    Zend\Debug\Debug,
    Zend\Console\Console;

/**
 * Server for WebSocket's protocol connection
 * @package Zend Framework 2
 * @subpackage WebSockets
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanilav WEB
 * @license Zend Framework GUI licene
 * @filesource /vendor/Websocket/src/Websocket/Service/WebsocketServer.php
 */
class WebsocketServer extends Console {

    /**
     * $config Server configuration
     * @see module.config.php
     * @access protected
     * @var  array
     */
    public $config = null;

    /**
     * $_callback Callback object from application. Hello, i'll be here :-)
     * @access protected
     * @var  object \WebSockets\Application
     */
    protected $_callback = null;
    
    /**
     * $error error response
     * @access static
     * @var  object WinSocketErrors | UnixSocketErrors
     */
    static $error = null;    

    /**
     * $_logger Log object
     * @access protected
     * @var  object \Zend\Log\Logger $_logger
     */
    protected $_logger = null;

    /**
     * $log Log state
     * @access private
     * @var  boolean $log
     */
    private $__log = false;

    /**
     * $clients socket clients connected
     * @access public
     * @var  array
     */
    public $clients = [];

    /**
     * $_read read sockets
     * @access protected
     * @var  array
     */
    protected $_read = [];

    /**
     * $_clientcount number of clients
     * @access protected
     * @var  int
     */
    protected $_clientCount = 0;

    /**
     * $_clientIPcount clients IP
     * @access protected
     * @var  array
     */
    protected $_clientIPcount = [];

    /**
     * $_onEvents event handling
     * @access protected
     * @var  array
     */
    protected $_onEvents = [];
    
    /**
     * __construct(array $config) basic connect to primary socket
     * @param array $config @see module.config.php
     * @access public
     * @return boolean
     * @throws Exception\ExceptionStrategy
     */
    public function __construct(array $config)
    {
	if(empty($config)) throw new Exception\ExceptionStrategy('Required parameters are incorrupted!');
	$this->config = $config;
	
	// pre define response server errors

	if(true === $this->isWindows())
	    self::$error  = new \WebSockets\Status\WinSocketErrors();

	else 
	    self::$error = new \WebSockets\Status\UnixSocketErrors();
	
	// check if loging service is available
	if(true === $this->config['log'])
	{
	    // add log writer
	    if(null === $this->_logger)
	    {
		if(!file_exists($this->config['logfile'])) throw new Exception\ExceptionStrategy("Error! File {$this->config['logfile']} does not exist");
		$this->__log = true;
		$this->_logger = new \Zend\Log\Logger();
		$this->_logger->addWriter(new \Zend\Log\Writer\Stream($this->config['logfile']));
	    }
	}

	$this->console("Running server...");
	
	// connect and listen primary socket
	$this->__socketListener();

	// throw console log (if enable)
	$this->console(sprintf("Listening on: %s:%d", $this->config['host'], $this->config['port']));
	$this->console(sprintf("Clients: %d / %d", $this->_clientCount, $this->config['max_clients']));
	return true;
    }

    /**
     * run() Run connection
     * @access public
     * @return null
     */
    public function run()
    {
	// difine here, because socket_select doesn't suppor clear values
	$write = $except = [];
	$nextPingCheck = time() + 1;

	while(isset($this->_read[0]))
	{
	    // always send first while u start the server

	    $changed = $this->_read;
	    if(false === ($result = socket_select($changed, $write, $except, 1)))
	    {
		$this->console($this->__errorTpl("socket_select", socket_last_error(), self::$error->get(socket_last_error($this->_read[0]))), true);
		socket_close($this->_read[0]);
		return false;
	    }
	    elseif($result > 0)
	    {
		foreach($changed as $clientId => $socket)
		{
		    if($clientId != 0)
		    {
			// client socket changed
			$buffer = '';
			if(false === ($bytes = socket_recv($socket, $buffer, 4096, 0)))
			{
			    // error on recv, remove client socket (will check to send close frame)
			    $this->close($clientId, Frame::get('SERVER_STATUS_PROTOCOL_ERROR'));
			}
			elseif($bytes > 0)
			{
			    // process handshake or frame(s)
			    if(!$this->processClient($clientId, $buffer, $bytes))
			    {
				// closing again. Some error from event or wron response code
				$this->close($clientId, Frame::get('SERVER_STATUS_PROTOCOL_ERROR'));
			    }
			}
			else
			{
			    // 0 bytes received from client, meaning the client closed the TCP connection
			    $this->removeClient($clientId);
			}
		    }
		    else
		    {
			// listening changed socket
			if(false === ($client = socket_accept($this->_read[0])))
			{
			    $this->console($this->__errorTpl("socket_accept", socket_last_error(), self::$error->get(socket_last_error($this->_read[0]))), true);
			    socket_close($this->_read[0]);
			    return false;
			}
			else
			{
			    // fetch client IP as integer
			    $result = socket_getpeername($client, $clientIP);
			    $clientIP = ip2long($clientIP);

			    if($result !== false && $this->_clientCount < $this->config['max_clients'] && (!isset($this->_clientIPcount[$clientIP]) 
				    || $this->_clientIPcount[$clientIP] < $this->config['max_clients_per_ip']))
			    {
				// add new client
				$this->addClient($client, $clientIP);
			    }
			    else 
			    {
				$this->console("Notice: exceeded the limit of connections. Access denied", true);
				socket_close($client);
			    }
			}
		    }
		}
	    }
	    if(time() >= $nextPingCheck)
	    {
		$this->clientTimeout();
		$nextPingCheck = time() + 1;
	    }
	}
	return true; // returned when shutdown() is called
    }
    
    /**
     * shutdown() shutdown connection
     * @access public
     * @return boolean
     */
    public function shutdown()
    {	
	// check if server is not running
	if(!isset($this->_read[0])) return false;

	// close all client connections
	foreach($this->clients as $clientId => $client)
	{
	    // if the client's opening handshake is complete, tell the client the server is 'going away'
	    if($client[2] != Frame::get('SERVER_READY_STATE_CONNECTING'))
	    { 
		$this->close($clientId, Frame::get('SERVER_STATUS_GONE_AWAY'));
	    }
	    $this->console("Destroy ".$client[0]);

	    socket_close($client[0]);
	}
	// close the socket which listens for incoming clients
	socket_close($this->_read[0]);
	$this->console("Connection close.");

	// reset variables
	
	$this->_read = [];
	$this->clients = [];
	$this->_clientCount = 0;
	$this->_clientIPcount = [];

	return true;
    }
    
    /**
     * getClients() get all connected clients
     * @access public
     * @return array
     */
    public function getClients() 
    {
	return $this->clients;
    }    
    
    /**
     * clientTimeout() check all connections to the expiration date
     * @access public
     * @return null
     */
    public function clientTimeout()
    {
	$time = time();
	foreach($this->clients as $clientId => $client)
	{
	    if($client[2] != Frame::get('SERVER_READY_STATE_CLOSED'))
	    {
		// client ready state is not closed
		if($client[4] !== false)
		{
		    // ping request has already been sent to client, pending a pong reply
		    if($time >= $client[4] + $this->config['timeout_pong'])
		    {
			// client didn't respond to the server's ping request in $this->config['timeout_pong'] seconds
			$this->close($clientId, Frame::get('SERVER_STATUS_TIMEOUT'));
			$this->removeClient($clientId);
		    }
		}
		elseif($time >= $client[3] + $this->config['timeout_recv'])
		{
		    // last data was received >= $this->config['timeout_recv'] seconds ago
		    if($client[2] != Frame::get('SERVER_READY_STATE_CONNECTING'))
		    {
			// client ready state is open or closing
			$this->clients[$clientId][4] = time();
			$this->dispatchMessage($clientId, Frame::get('SERVER_OPCODE_PING'), '');
		    }
		    else
		    {
			// client ready state is connecting
			$this->removeClient($clientId);
		    }
		}
	    }
	}
    }

    /**
     * addClient($socket, $clientIp) Adding client to the socket stream
     * @param type $socket current stream
     * @param type $clientIP new client IP
     * @access public
     * @return null
     */
    public function addClient($socket, $clientIp)
    {
	// increase amount of clients connected
	$this->_clientCount++;

	// increase amount of clients connected on this client's IP
	if(isset($this->_clientIPcount[$clientIp])) $this->_clientIPcount[$clientIp] ++;
	else $this->_clientIPcount[$clientIp] = 1;

	// fetch next client ID
	$clientId = $this->nextClientId();

	// store initial client data
	$this->clients[$clientId] = [$socket, '', Frame::get('SERVER_READY_STATE_CONNECTING'), time(), false, 0, $clientIp, false, 0, '', 0, 0];

	// store socket - used for socket_select()
	$this->_read[$clientId] = $socket;
    }

    /*
     * processClient($clientId, &$buffer, $bufferLength) parcel connections and selection events
     * @param int $clientId socket connection id
     * @param string $buffer send stream
     * @param int $bufferLength count($buffer)
     * @access public
     * return boolean
     */
    public function processClient($client_id, &$buffer, $bufferLength)
    {
	if($this->clients[$client_id][2] == Frame::get('SERVER_READY_STATE_OPEN'))
	{
	    // handshake completed
	    $result = $this->buildFrame($client_id, $buffer, $bufferLength);
	}
	elseif($this->clients[$client_id][2] == Frame::get('SERVER_READY_STATE_CONNECTING'))
	{
	    // handshake not completed
	    $result = $this->processClientHandshake($client_id, $buffer);
	    if($result)
	    {
		$this->clients[$client_id][2] = Frame::get('SERVER_READY_STATE_OPEN');

		if(array_key_exists('open', $this->_onEvents))
		{
		    foreach($this->_onEvents['open'] as $func)
		    {
			// hello from Application ))
			$this->_callback->$func($client_id);
		    }
		}
	    }
	}
	else
	{
	    // ready state is set to closed
	    $result = false;
	}
	return $result;
    }
    
    /**
     * removeClient($client_id) close current connection for current client
     * @param int $client_id socket stream id
     * @access public
     * return null
     */
    public function removeClient($client_id)
    {
	// fetch close status (which could be false), and call wsOnClose
	$closeStatus = $this->clients[$client_id][5];

	if(array_key_exists('close', $this->_onEvents)) 
	{
	    foreach($this->_onEvents['close'] as $func)
	    {
		// hello from Application ))
		$this->_callback->$func($client_id);
	    }
	}
	// close socket
	$socket = $this->clients[$client_id][0];
	socket_close($socket);

	// decrease amount of clients connected on this client's IP
	$clientIP = $this->clients[$client_id][6];
	if($this->_clientIPcount[$clientIP] > 1)
	{
	    $this->_clientIPcount[$clientIP]--;
	}
	else
	{
	    unset($this->_clientIPcount[$clientIP]);
	}

	// decrease amount of clients connected
	$this->_clientCount--;

	// remove socket and client data from arrays
	unset($this->_read[$client_id], $this->clients[$client_id]);
    }

    /**
     * nextClientId() select next client id
     * @access public
     * @return int
     */
    public function nextClientId()
    {
	$i = 1; // starts at 1 because 0 is the listen socket
	while(isset($this->_read[$i])) $i++;
	return $i;
    }

    /**
     * dispatchMessage($clientId, $opcode, $message) write and encrypt the message
     * @param int $clientId socket identifier
     * @param int $opcode response code
     * @param string $message text
     * @access public
     * @return boolean
     */
    public function dispatchMessage($clientId, $opcode, $message)
    {
	$collect = [];
	// check if client ready state is already closing or closed
	if(!in_array($this->clients[$clientId][2], 
	[
	    Frame::get('SERVER_READY_STATE_CLOSING'),
	    Frame::get('SERVER_READY_STATE_CLOSED')
	])) return true;

	// fetch message length
	$collect['messageLength'] = strlen($message);

	// set max payload length per frame
	$collect['bufferSize']	= 4096;

	// work out amount of frames to send, based on $bufferSize
	
	$frameCount = ceil($collect['messageLength'] / $collect['bufferSize']);
	$collect['frameCount']	= ($frameCount == 0) ? 1 : $frameCount;

	// set last frame variables
	$collect['maxFrame']	=   $collect['frameCount']-1;
	$lastFrameBufferLength = ($collect['messageLength'] % $collect['bufferSize']) != 0 ? ($collect['messageLength'] % $collect['bufferSize']) : ($collect['messageLength'] != 0 ? $collect['bufferSize'] : 0);

	// loop around all frames to send
	for($i = 0; $i < $collect['frameCount']; $i++)
	{
	    // fetch fin, opcode and buffer length for frame
	    $fin = $i != $collect['maxFrame'] ? 0 : Frame::get('SERVER_FIN');
	    $opcode = $i != 0 ? Frame::get('SERVER_OPCODE_CONTINUATION') : $opcode;
	    $collect['bufferLength'] = $i != $collect['maxFrame'] ? $collect['bufferSize'] : $lastFrameBufferLength;

	    // set payload length variables for frame
	    
	    if($collect['bufferLength'] <= 125)
	    {
		$payloadLength = $collect['bufferLength'];
		$payloadLengthExtended = '';
		$payloadLengthExtendedLength = 0;
	    }
	    elseif($collect['bufferLength'] <= 65535)
	    {
		$payloadLength = Frame::get('SERVER_PAYLOAD_LENGTH_16');
		$payloadLengthExtended = pack('n', $collect['bufferLength']);
		$payloadLengthExtendedLength = 2;
	    }
	    else
	    {
		$payloadLength = Frame::get('SERVER_PAYLOAD_LENGTH_63');
		$payloadLengthExtended = pack('xxxxN', $collect['bufferLength']); // pack 32 bit int, should really be 64 bit int
		$payloadLengthExtendedLength = 8;
	    }

	    // set frame bytes
	    $buffer = pack('n', (($fin | $opcode) << 8) | $payloadLength).$payloadLengthExtended.substr($message, $i * $collect['bufferSize'], $collect['bufferLength']);

	    // send frame
	    
	    $socket = $this->clients[$clientId][0];

	    $left = 2 + $payloadLengthExtendedLength + $collect['bufferLength'];

	    $this->__sendframe($socket, $buffer, $left);
	}
	return true;
    }
    
    /**
     * buildFrame($clientId, &$buffer, $bufferLength) create a frame to data transfering
     * @param int $clientId connection id
     * @param string $buffer request message
     * @param int $bufferLength request message length
     * @access public
     * @return boolean
     */
    public function buildFrame($clientId, &$buffer, $bufferLength)
    {
	// increase number of bytes read for the frame, and join buffer onto end of the frame buffer
	$this->clients[$clientId][8] += $bufferLength;
	$this->clients[$clientId][9] .= $buffer;

	// check if the length of the frame's payload data has been fetched, if not then attempt to fetch it from the frame buffer
	if($this->clients[$clientId][7] !== false || $this->checkSizeClientFrame($clientId) == true)
	{
	    // work out the header length of the frame
	    $headerLength = ($this->clients[$clientId][7] <= 125 ? 0 : ($this->clients[$clientId][7] <= 65535 ? 2 : 8)) + 6;

	    // check if all bytes have been received for the frame
	    $frameLength = $this->clients[$clientId][7] + $headerLength;
	    if($this->clients[$clientId][8] >= $frameLength)
	    {
		// check if too many bytes have been read for the frame (they are part of the next frame)
		$nextFrameBytesLength = $this->clients[$clientId][8] - $frameLength;
		if($nextFrameBytesLength > 0)
		{
		    $this->clients[$clientId][8] -= $nextFrameBytesLength;
		    $nextFrameBytes = substr($this->clients[$clientId][9], $frameLength);
		    $this->clients[$clientId][9] = substr($this->clients[$clientId][9], 0, $frameLength);
		}

		// process the frame
		$result = $this->processFrame($clientId);

		// check if the client wasn't removed, then reset frame data
		$this->__opcodereset($clientId, [7 => false,8 => 0, 9 => '']);

		// if there's no extra bytes for the next frame, or processing the frame failed, return the result of processing the frame
		if($nextFrameBytesLength <= 0 || !$result) return $result;

		// build the next frame with the extra bytes
		return $this->buildFrame($clientId, $nextFrameBytes, $nextFrameBytesLength);
	    }
	}

	return true;
    }
    
    /**
     * checkSizeClientFrame($clientId) check frame size
     * @param int $clientId connection id
     * @access public
     * @return boolean
     */
    public function checkSizeClientFrame($clientId)
    {
	// check if at least 2 bytes have been stored in the frame buffer
	if($this->clients[$clientId][8] > 1)
	{
	    // fetch payload length in byte 2, max will be 127
	    $payloadLength = ord(substr($this->clients[$clientId][9], 1, 1)) & 127;

	    if($payloadLength <= 125)
	    {
		// actual payload length is <= 125
		$this->clients[$clientId][7] = $payloadLength;
	    }
	    elseif($payloadLength == 126)
	    {
		// actual payload length is <= 65,535
		if(substr($this->clients[$clientId][9], 3, 1) !== false)
		{
		    // at least another 2 bytes are set
		    $payloadLengthExtended = substr($this->clients[$clientId][9], 2, 2);
		    $array = unpack('na', $payloadLengthExtended);
		    $this->clients[$clientId][7] = $array['a'];
		}
	    }
	    else
	    {
		// actual payload length is > 65,535
		if(substr($this->clients[$clientId][9], 9, 1) !== false)
		{
		    // at least another 8 bytes are set
		    $payloadLengthExtended = substr($this->clients[$clientId][9], 2, 8);

		    // check if the frame's payload data length exceeds 2,147,483,647 (31 bits)
		    // the maximum integer in PHP is "usually" this number. More info: http://php.net/manual/en/language.types.integer.php
		    $payloadLengthExtended32_1 = substr($payloadLengthExtended, 0, 4);
		    $array = unpack('Na', $payloadLengthExtended32_1);
		    if($array['a'] != 0 || ord(substr($payloadLengthExtended, 4, 1)) & 128)
		    {
			$this->close($clientId, Frame::get('SERVER_STATUS_MESSAGE_TOO_BIG'));
			return false;
		    }

		    // fetch length as 32 bit unsigned integer, not as 64 bit
		    $payloadLengthExtended32_2 = substr($payloadLengthExtended, 4, 4);
		    $array = unpack('Na', $payloadLengthExtended32_2);

		    // check if the payload data length exceeds 2,147,479,538 (2,147,483,647 - 14 - 4095)
		    // 14 for header size, 4095 for last recv() next frame bytes
		    if($array['a'] > 2147479538)
		    {
			$this->close($clientId, Frame::get('SERVER_STATUS_MESSAGE_TOO_BIG'));
			return false;
		    }

		    // store frame payload data length
		    $this->clients[$clientId][7] = $array['a'];
		}
	    }

	    // check if the frame's payload data length has now been stored
	    if($this->clients[$clientId][7] !== false)
	    {

		// check if the frame's payload data length exceeds Frame::get('WS_MAX_FRAME_PAYLOAD_RECV')
		if($this->clients[$clientId][7] > $this->config['max_frame_payload_recv'])
		{
		    $this->clients[$clientId][7] = false;
		    $this->close($clientId, Frame::get('SERVER_STATUS_MESSAGE_TOO_BIG'));
		    return false;
		}

		// check if the message's payload data length exceeds 2,147,483,647 or Frame::get('WS_MAX_MESSAGE_PAYLOAD_RECV')
		// doesn't apply for control frames, where the payload data is not internally stored
		$controlFrame = (ord(substr($this->clients[$clientId][9], 0, 1)) & 8) == 8;
		if(!$controlFrame)
		{
		    $newMessagePayloadLength = $this->clients[$clientId][11] + $this->clients[$clientId][7];
		    if($newMessagePayloadLength > $this->config['max_message_payload_recv'] || $newMessagePayloadLength > 2147483647)
		    {
			$this->close($clientId, Frame::get('SERVER_STATUS_MESSAGE_TOO_BIG'));
			return false;
		    }
		}
		return true;
	    }
	}
	return false;
    }
    
    /**
     * processMessage($clientId, $opcode, &$data, $dataLength)
     * @param int $clientId  socket identifier from collection
     * @param int $opcode proccess code
     * @param string $data received text
     * @param int $dataLength lenght
     * @access public
     * @return boolean
     */
    public function processMessage($clientId, $opcode, &$data, $dataLength)
    {
	// check opcodes
	if($opcode == Frame::get('SERVER_OPCODE_PING'))
	{
	    // received ping message
	    return $this->dispatchMessage($clientId, Frame::get('SERVER_OPCODE_PONG'), $data);
	}
	elseif($opcode == Frame::get('SERVER_OPCODE_PONG'))
	{
	    // received pong message (it's valid if the server did not send a ping request for this pong message)
	    if($this->clients[$clientId][4] !== false) $this->clients[$clientId][4] = false;
	}
	elseif($opcode == Frame::get('SERVER_OPCODE_CLOSE'))
	{
	    // received close message

	    if($this->clients[$clientId][2] == Frame::get('SERVER_READY_STATE_CLOSING'))
	    {
		// the server already sent a close frame to the client, this is the client's close frame reply
		// (no need to send another close frame to the client)
		$this->clients[$clientId][2] = Frame::get('SERVER_READY_STATE_CLOSED');
	    }
	    else
	    {
		// the server has not already sent a close frame to the client, send one now
		$this->close($clientId, Frame::get('SERVER_STATUS_NORMAL_CLOSE'));
	    }
	    $this->removeClient($clientId);
	}
	elseif($opcode == Frame::get('SERVER_OPCODE_TEXT') || $opcode == Frame::get('SERVER_OPCODE_BINARY'))
	{
	    if(array_key_exists('message', $this->_onEvents)) 
	    {
		foreach($this->_onEvents['message'] as $func) 
		{
		    // hello from Application ))
		    $this->_callback->$func($clientId, $data, $dataLength);
		}
	    }
	}
	else
	{
	    $this->console("When processing incoming data was returned an unknown fragment");
	    // unknown opcode
	    return false;
	}
	return true;
    }
    
    /**
     * processFrame($clientId) transfering frame
     * @param int $clientId connection id
     * @access public
     * @return boolean
     */
    public function processFrame($clientId)
    {
	// store the time that data was last received from the client
	$this->clients[$clientId][3] = time();

	// fetch frame buffer
	$buffer = &$this->clients[$clientId][9];

	// check at least 6 bytes are set (first 2 bytes and 4 bytes for the mask key)
	if(substr($buffer, 5, 1) === false) return false;

	// fetch first 2 bytes of header
	$octet0 = ord(substr($buffer, 0, 1));
	$octet1 = ord(substr($buffer, 1, 1));

	$fin = $octet0 & Frame::get('SERVER_FIN');
	$opcode = $octet0 & 15;

	$mask = $octet1 & Frame::get('SERVER_MASK');
	if(!$mask) return false; // close socket, as no mask bit was sent from the client

	    
// fetch byte position where the mask key starts
	$seek = $this->clients[$clientId][7] <= 125 ? 2 : ($this->clients[$clientId][7] <= 65535 ? 4 : 10);

	// read mask key
	$maskKey = substr($buffer, $seek, 4);

	$array = unpack('Na', $maskKey);
	$maskKey = $array['a'];
	$maskKey = [
	    $maskKey >> 24,
	    ($maskKey >> 16) & 255,
	    ($maskKey >> 8) & 255,
	    $maskKey & 255
	];
	$seek += 4;

	// decode payload data
	if(substr($buffer, $seek, 1) !== false)
	{
	    $data = str_split(substr($buffer, $seek));
	    foreach($data as $key => $byte)
	    {
		$data[$key] = chr(ord($byte) ^ ($maskKey[$key % 4]));
	    }
	    $data = implode('', $data);
	}
	else $data = '';

	// check if this is not a continuation frame and if there is already data in the message buffer
	if($opcode != Frame::get('SERVER_OPCODE_CONTINUATION') && $this->clients[$clientId][11] > 0)
	{
	    // clear the message buffer
	    $this->clients[$clientId][11] = 0;
	    $this->clients[$clientId][1] = '';
	}

	// check if the frame is marked as the final frame in the message
	if($fin == Frame::get('SERVER_FIN'))
	{
	    // check if this is the first frame in the message
	    if($opcode != Frame::get('SERVER_OPCODE_CONTINUATION'))
	    {
		// process the message
		return $this->processMessage($clientId, $opcode, $data, $this->clients[$clientId][7]);
	    }
	    else
	    {
		// increase message payload data length
		$this->clients[$clientId][11] += $this->clients[$clientId][7];

		// push frame payload data onto message buffer
		$this->clients[$clientId][1] .= $data;

		// process the message
		$result = $this->processMessage($clientId, $this->clients[$clientId][10], $this->clients[$clientId][1], $this->clients[$clientId][11]);

		// check if the client wasn't removed, then reset message buffer and message opcode
		$this->__opcodereset($clientId, [1 => '',10 => 0, 11 => 0]);
		return $result;
	    }
	}
	else
	{
	    // check if the frame is a control frame, control frames cannot be fragmented
	    if($opcode & 8) return false;

	    // increase message payload data length
	    $this->clients[$clientId][11] += $this->clients[$clientId][7];

	    // push frame payload data onto message buffer
	    $this->clients[$clientId][1] .= $data;

	    // if this is the first frame in the message, store the opcode
	    if($opcode != Frame::get('SERVER_OPCODE_CONTINUATION'))
	    {
		$this->clients[$clientId][10] = $opcode;
	    }
	}
	return true;
    }

    /**
     * processClientHandshake($clientId, &$buffer) request header action
     * @param int $clientId connection id
     * @param string $buffer request message
     * @accss public
     * @return boolean
     */
    public function processClientHandshake($clientId, &$buffer)
    {
	$params = array(); 
	
	$this->console(sprintf("Clients: %d / %d", $this->_clientCount, $this->config['max_clients']));

	if(preg_match("/Sec-WebSocket-Version: (.*)\r\n/", $buffer, $match)) $params['version'] = $match[1];
	else
	{
	    $this->console("The client doesn't support WebSocket");
	    return false;
	}
	if($params['version'] > 8)
	{
	    // Extract header variables
	    if(preg_match("/GET (.*) HTTP/", $buffer, $match)) $params['root'] = $match[1];
	    if(preg_match("/Host: (.*)\r\n/", $buffer, $match)) $params['host'] = $match[1];
	    if(preg_match("/Origin: (.*)\r\n/", $buffer, $match)) $params['origin'] = $match[1];
	    if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $buffer, $match)) $params['key'] = $match[1];

	    // check request data
	    if(array_search('',$params))  return false;

	    $this->console("New client headers are:");
	    $this->console("\t- Root: ".$params['root']);
	    $this->console("\t- Host: ".$params['host']);
	    $this->console("\t- Origin: ".$params['origin']);
	    $this->console("\t- Sec-WebSocket-Key: ".$params['key']);
	    $this->console("\t- Sec-WebSocket-Version: ".$params['version']);

	    $acceptKey = \Zend\Ldap\Ldif\Encoder::encode(pack('H*', sha1($params['key'].'258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

	    // setting up new response headers

	    $headers = [
		'HTTP/1.1 101 WebSocket Protocol Handshake',
		'Upgrade: websocket',
		'Connection: Upgrade',
		'WebSocket-Origin: '.$params['origin'],
		'WebSocket-Location: ws://'.$params['host'].$params['root'],
		'Sec-WebSocket-Accept: '.$acceptKey
	    ];
	    $headers = implode("\r\n", $headers)."\r\n\r\n";

	    // send headers back to client
	    $socket = $this->clients[$clientId][0];

	    //$this->console("Sending this response to the client {$clientId}:\r\n".$headers);
	    $left = strlen($headers);
	    $this->__sendframe($socket, $headers, $left);

	    return true;
	}
	else
	{
	    $this->console("WebSocket version 13 required (the client supports version {$params['version']})");
	    return false;
	}
    }
    
    /**
     * __opcodereset($clientId) reset opcode frame
     * @param int $clientId socket identifier
     * @access private
     * @return null
     */
    private function __opcodereset($clientId, array $keys)
    {
	if(isset($this->clients[$clientId]))
	{
	    // reset by keys
	    foreach($keys as $k => $v)
	    {
		$this->clients[$clientId][$k]	= $v;
	    }
	}	
    }
    
    /**
     * __sendframe($socket, $buffer, $len)	    send frame function
     * @param resource #id $socket socket
     * @param string $buffer message
     * @param int $len message length
     * @access private
     * @return null
     */
    private function __sendframe($socket, $buffer, $len)
    {
	do
	    {
		if(false === ($sent = @socket_send($socket, $buffer, $len, 0)))
		{
		    $this->console($this->__errorTpl("socket_send", socket_last_error(), self::$error->get(socket_last_error($socket))), true);
		    return false;
		}
		$len -= $sent;
		if($sent > 0) $buffer = substr($buffer, $sent);
	    }
	while($len > 0);	
    }    
    
    
    /**
     * __errorTpl($fname, $errno, $errmsg) Error template
     * @param string $fname funtion name
     * @param int $errno error number
     * @param string $errmsg error message
     * @access private
     * @return string
     */
    private function __errorTpl($fname, $errno, $errmsg)
    {
	return sprintf("(%s) Error [%d]: %s", $fname, $errno, $errmsg);
    }
    
    /**
     * __socketListener() read primary socket
     * @access private
     * @return boolean
     */
    private function __socketListener()
    {
	if(isset($this->_read[0]))
	{
	    $this->console("Failed. Server gone away ((");
	    $this->shutdown();
	}

	// open TCP / IP stream and hang port specified in the config
	if(!$this->_read[0] = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))
	{
	    $this->console($this->__errorTpl("socket_create", socket_last_error(), self::$error->get(socket_last_error())), true);
	    return false;
	}

	// setup connected socket
	if(!socket_set_option($this->_read[0], SOL_SOCKET, SO_REUSEADDR, 1))
	{
	    $this->console($this->__errorTpl("socket_set_option", socket_last_error(), self::$error->get(socket_last_error($this->_read[0]))), true);
	    socket_close($this->_read[0]);
	    return false;
	}

	//bind socket to specified host
	if(false === (socket_bind($this->_read[0], $this->config['host'], $this->config['port'])))
	{
	    $this->console($this->__errorTpl("socket_bind", socket_last_error(), self::$error->get(socket_last_error($this->_read[0]))), true);
	    $this->shutdown();
	}

	//bind socket to specified host
	if(false === (socket_listen($this->_read[0], $this->config['max_clients'])))
	{
	    $this->console($this->__errorTpl("socket_listen", socket_last_error(), self::$error->get(socket_last_error($this->_read[0]))), true);
	    $this->shutdown();
	}	
    }
    
    /**
     * send($client_id, $message, $binary = false) echo message sender
     * @param int $client_id sock identifier
     * @param string $message
     * @param boolean $binary
     * @access public
     * @return null
     */
    public function send($client_id, $message, $binary = false)
    {
	if(isset($message) && isset($client_id))
	{
	    $this->_callback->say($message);
	    return $this->dispatchMessage($client_id, $binary ? Frame::get('SERVER_OPCODE_BINARY') : Frame::get('SERVER_OPCODE_TEXT'), $message);
	}
    }
    /**
     * close($clientId, $status = false) close frame connect
     * @param int $clientId sock identifier
     * @param int $status response status
     * @access public
     * @return boolean
     */
    
    public function close($clientId, $status = false)
    {
	// check if client ready state is already closing or closed
	if($this->clients[$clientId][2] == Frame::get('SERVER_READY_STATE_CLOSING') || $this->clients[$clientId][2] == Frame::get('SERVER_READY_STATE_CLOSED')) return true;

	// store close status
	$this->clients[$clientId][5] = $status;

	// send close frame to client
	$status = $status !== false ? pack('n', $status) : '';
	$this->dispatchMessage($clientId, Frame::get('SERVER_OPCODE_CLOSE'), $status);

	// set client ready state to closing
	$this->clients[$clientId][2] = Frame::get('SERVER_READY_STATE_CLOSING');
    }    

    /**
     * bind($type, $func) add event listener
     * @param string $type event name
     * @param string $func handler
     * @param object $callback
     * access public
     * return null
     */
    public function bind($type, $func, $callback)
    {
	$this->_callback = $callback;

	if(!isset($this->_onEvents[$type])) $this->_onEvents[$type] = [];
	$this->_onEvents[$type][] = $func;
    }

    /**
     * unbind($type = false) remove event listener
     * @param string $type event name
     * return null
     */
    public function unbind($type = false)
    {
	if($type) unset($this->_onEvents[$type]);
	else $this->_onEvents = [];
    }

    /**
     * console($text, $exception = false, $exit = false) Output console message
     * @param string $data stdout data
     * @param boolean $exception throwed Eception
     * @param boolean $exit die console out
     * @acceess public
     */
    public function console($data, $exception = false, $exit = false)
    {
	// check if console is usable
	if(true === $this->config['debug'])
	{
	    if(is_array($data) || is_object($data))
	    {
		Debug::dump($data.'', date('[Y-m-d H:i:s]').' [DEBUG]');
		if(isset($this->__log)) $this->_logger->info($data);
	    }
	    else
	    {
		if(!is_resource($data)) $data = mb_convert_encoding($data, $this->config['encoding']);
		$text = date('[Y-m-d H:i:s]').'[DEBUG] '.$data."\r\n";
		if($exception)
		{
		    if($this->__log) $this->_logger->crit($text);
		    throw new Exception\ExceptionStrategy($text);
		}
		else
		{
		    if($this->__log) $this->_logger->info($text);
		    echo $text;
		}
	    }
	    if($exit) $this->shutdown();
	}
    }
}