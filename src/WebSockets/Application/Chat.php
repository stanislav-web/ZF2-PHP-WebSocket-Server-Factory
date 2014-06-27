<?php
namespace WebSockets\Application;

use	WebSockets\Exception,
	WebSockets\Aware,
	WebSockets\Service\WebsocketServer;

/**
 * Simply web socket Chat. (Notice: do the favourite app's like this example ;-)
 * @package Zend Framework 2
 * @subpackage Websockets
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanilav WEB
 * @license Zend Framework GUI licene
 * @filesource /vendor/Websockets/src/Websockets/Application/Chat.php
 */
class Chat implements Aware\ApplicationInterface {
    
    /**
     * \WebSockets\Service\WebsocketServer $_server
     * @access protected
     * @var object 
     */      
    protected $_server    =   null;
    
    /**
     * __construct(WebsocketServer $server) 
     * @param \WebSockets\Service\WebsocketServer $server
     * @return instance of WebsocketServer object
     */
    public function __construct(WebsocketServer $server) 
    {
        // set ServiceManager throught constructor
        if(null === $this->_server)  $this->_server =   $server;
	
	$this->say('System: Wellcome to '.basename(get_class()));
    }
    
    /**
     * onOpen() opening a connection to the server
     * @param int $clientId connect identifier
     * @access public
     */
    public function onOpen($clientId)
    {
	// get client ip
	$ip = long2ip($this->_server->clients[$clientId][6]);

	// send a join notice to everyone but the person who joined
	foreach($this->_server->clients as $id => $client)
	{
	    if($id != $clientId)
	    {	// send only not  for me :))
		$this->_server->send($id, "User $clientId ($ip) has joined the room.");
	    }
	}
    }
    
    /**
     * onMessage($clientId, $message) get messages from server (request / response)
     * @param int $clientId connect identifier
     * @param varchar $message costom message throught socket
     * @access public
     */
    public function onMessage($clientId, $message)
    {	
	// get client ip
	$ip = long2ip($this->_server->clients[$clientId][6]);

	// check if message length is 0
	if(strlen($message) == 0) 
	{
	    // send nothing
	    $this->_server->close($clientId);
	    return;
	}

	// the speaker is the only person in the room. Don't let them feel lonely.
	if(sizeof($this->_server->clients) == 1) 
	{
	    $this->_server->send($clientId, "There isn't anyone else in the room, but I'll still listen to you. Some one in the dark :))");
	}
	else
	{
	    // send the message to everyone but the person who said it
	    foreach($this->_server->clients as $id => $client)
	    {
		if($id != $clientId) $this->_server->send($id, "User $clientId ($ip) said \"$message\"");
	    }
	}
    }
    
    /**
     * onClose($clientId) closing a connection to the server
     * @param int $clientId connect identifier
     * @access public
     */
    public function onClose($clientId)
    {
	// get client ip
	$ip = long2ip($this->_server->clients[$clientId][6]);
	
	// send a user left notice to everyone in the room
	foreach($this->_server->clients as $id => $client)
	{
	    $this->_server->send($id, "User $clientId ($ip) has left the room.");
	}
    }
    
    /**
     * say($message) print console messanger. Will be able as Server callback function!
     * @param string $message 
     * @access public
     */    
    public function say($message)
    {
	$message = mb_convert_encoding($message, $this->_server->config['encoding']);
	echo date('[Y-m-d H:i:s] ').$message."\r\n";
    }
    
    /**
     * __call($name, $argument) need to do overloading! Its mostly setup event from server object
     * @param string $name function from webSocket Server class
     * @param array $argument 
     * @return null
     */
    public function __call($name, $arguments)
    {
	if(!method_exists(get_class($this->_server), $name)) 
		throw new Exception\ExceptionStrategy("Error! Function {$name} does not exist in ".get_class($this->_server)); 
	
	else if(sizeof($arguments) != 2)
		throw new Exception\ExceptionStrategy("Error! Arguments setup incorrectly in ".__CLASS__); 
	
	return $this->_server->$name($arguments[0], $arguments[1], $this);
    }
    
    /**
     * run() running application
     * @access public
     */    
    public function run()
    {
	return $this->_server->run();
    }
}
