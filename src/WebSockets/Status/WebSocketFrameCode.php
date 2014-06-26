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
     * Internal server set response codes
     */
    const SERVER_FIN =  128;
    const SERVER_MASK = 128;

    const SERVER_OPCODE_CONTINUATION = 0;
    const SERVER_OPCODE_TEXT =         1;
    const SERVER_OPCODE_BINARY =       2;
    const SERVER_OPCODE_CLOSE =        8;
    const SERVER_OPCODE_PING =         9;
    const SERVER_OPCODE_PONG =         10;

    const SERVER_PAYLOAD_LENGTH_16 = 126;
    const SERVER_PAYLOAD_LENGTH_63 = 127;

    const SERVER_READY_STATE_CONNECTING = 0;
    const SERVER_READY_STATE_OPEN =       1;
    const SERVER_READY_STATE_CLOSING =    2;
    const SERVER_READY_STATE_CLOSED =     3;

    const SERVER_STATUS_NORMAL_CLOSE =             1000;
    const SERVER_STATUS_GONE_AWAY =                1001;
    const SERVER_STATUS_PROTOCOL_ERROR =           1002;
    const SERVER_STATUS_UNSUPPORTED_MESSAGE_TYPE = 1003;
    const SERVER_STATUS_MESSAGE_TOO_BIG =          1004;

    const SERVER_STATUS_TIMEOUT = 3000;    
}
?>