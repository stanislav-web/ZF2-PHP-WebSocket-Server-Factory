<?php

namespace WebSockets\Status;

/**
 * Class WebSocketFrameCode
 * Error codes & messages for Socket frames
 *
 * @package    WebSockets\Status
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Status/WebSocketFrameCode.php
 */
class WebSocketFrameCode {

	/**
	 * @const DEFAULT_CODE
	 */
	const DEFAULT_CODE = 58;

	/**
	 * Get error message
	 *
	 * @param int $code
	 *
	 * @return string
	 */
	public static function get ( $code ) {
		if ( true === array_key_exists ( $code, self::$message ) ) {
			return self::$message[$code];
		} else {
			return self::$message[self::DEFAULT_CODE];
		}
	}

	/**
	 * Error messages container
	 *
	 * @var array $message
	 */
	public static $message = [
		'SERVER_FIN'                             => 128,
		'SERVER_MASK'                            => 128,
		'SERVER_OPCODE_CONTINUATION'             => 0,
		'SERVER_OPCODE_TEXT'                     => 1,
		'SERVER_OPCODE_BINARY'                   => 2,
		'SERVER_OPCODE_CLOSE'                    => 8,
		'SERVER_OPCODE_PING'                     => 9,
		'SERVER_OPCODE_PONG'                     => 10,
		'SERVER_PAYLOAD_LENGTH_16'               => 126,
		'SERVER_PAYLOAD_LENGTH_63'               => 127,
		'SERVER_READY_STATE_CONNECTING'          => 0,
		'SERVER_READY_STATE_OPEN'                => 1,
		'SERVER_READY_STATE_CLOSING'             => 2,
		'SERVER_READY_STATE_CLOSED'              => 3,
		'SERVER_STATUS_NORMAL_CLOSE'             => 1000,
		'SERVER_STATUS_GONE_AWAY'                => 1001,
		'SERVER_STATUS_PROTOCOL_ERROR'           => 1002,
		'SERVER_STATUS_UNSUPPORTED_MESSAGE_TYPE' => 1003,
		'SERVER_STATUS_MESSAGE_TOO_BIG'          => 1004,
		'SERVER_STATUS_TIMEOUT'                  => 3000,
	];

}