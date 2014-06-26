<?php

namespace WebSockets\Status; // Namespaces of current service

/**
 * Response frame status codes from sockets
 * @package Zend Framework 2
 * @subpackage WebSockets
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanilav WEB
 * @license Zend Framework GUI licene
 * @filesource /vendor/Websocket/src/Websocket/Status/WebSocketFrameCode.php
 */

class WebSocketFrameCode {

    /**
     * get($e) return message by code
     * @param int $e error code
     * @access static
     * @return string
     */
    public static function get($e)
    {
	if(array_key_exists($e, self::$message)) return self::$message[$e];
	else return self::$message[58];
    }

    /**
     * $message
     * @var array 
     */
    public static $message = [
	'SERVER_FIN'				    => 128,
	'SERVER_MASK'				    => 128,
	'SERVER_OPCODE_CONTINUATION'		    => 0,
	'SERVER_OPCODE_TEXT'			    => 1,
	'SERVER_OPCODE_BINARY'			    => 2,
	'SERVER_OPCODE_CLOSE'			    => 8,
	'SERVER_OPCODE_PING'			    => 9,
	'SERVER_OPCODE_PONG'			    => 10,
	'SERVER_PAYLOAD_LENGTH_16'		    => 126,
	'SERVER_PAYLOAD_LENGTH_63'		    => 127,
	'SERVER_READY_STATE_CONNECTING'		    => 0,
	'SERVER_READY_STATE_OPEN'		    => 1,
	'SERVER_READY_STATE_CLOSING'		    => 2,
	'SERVER_READY_STATE_CLOSED'		    => 3,
	'SERVER_STATUS_NORMAL_CLOSE'		    => 1000,
	'SERVER_STATUS_GONE_AWAY'		    => 1001,
	    'SERVER_STATUS_PROTOCOL_ERROR'	    => 1002,
	'SERVER_STATUS_UNSUPPORTED_MESSAGE_TYPE'    => 1003,
	'SERVER_STATUS_MESSAGE_TOO_BIG'		    => 1004,
	'SERVER_STATUS_TIMEOUT'			    => 3000,
    ];

}

?>