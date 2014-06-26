<?php
namespace WebSockets\Aware;

use WebSockets\Service\WebsocketServer;
/**
 * ApplicationInterface. Implementing rules necessary functionality for client's applications
 * @package Zend Framework 2
 * @subpackage WebSockets
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanilav WEB
 * @license Zend Framework GUI licene
 * @filesource /vendor/WebSockets/src/WebSockets/Aware/ApplicationInterface.php
 */
interface ApplicationInterface {
    
    /**
     *  __construct(WebsocketServer $server)
     * @access public
     */
    public function __construct(WebsocketServer $server);
    
    /**
     * onOpen($clientId) opening a connection to the server
     * @param int $clientId connect identifier
     * @access public
     */
    public function onOpen($clientId);    
    
    /**
     * onMessage($clientId, $message) get messages from server (request / response)
     * @param int $clientId connect identifier
     * @param varchar $message costom message throught socket
     * @access public
     */
    public function onMessage($clientId, $message);
    
    /**
     * onMessage($clientId) closing a connection to the server
     * @param int $clientId connect identifier
     * @access public
     */
    public function onClose($clientId);
    
    /**
     * say($message) print messager
     * @param string $message 
     * @access public
     */
    public function say($message);     
    
    
    /**
     * run() running application
     * @access public
     */
    public function run();    
}
