<?php
namespace WebSockets\Application;

use WebSockets\Aware\ServerInterface;
use WebSockets\Aware\ApplicationInterface;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface;

/**
 * Class Chat
 * Simply web socket Chat. (Notice: do the favorite app's like this example ;-)
 *
 * @package     WebSockets\Application
 * @since       PHP >=5.6
 * @version     v3.2.1
 * @author      Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright   Stanislav WEB
 * @license     Zend Framework GUI license (New BSD License)
 * @filesource  /vendor/stanislav-web/zf2-websocket-server-factory/src/Application/Chat.php
 */
class Chat implements ApplicationInterface {

	/**
	 * Server instance
	 * @var ServerInterface $serverInstance
	 */
	private $serverInstance;

	/**
	 * Console instance
	 *
	 * @var AdapterInterface $consoleInstance
	 */
	private $consoleInstance;

	/**
	 * ApplicationInterface constructor.
	 * Server implementation
	 *
	 * @param ServerInterface  $serverInstance
	 * @param AdapterInterface $consoleInstance
	 */
	public function __construct ( ServerInterface $serverInstance, AdapterInterface $consoleInstance ) {
		if ( true === is_null ( $this->serverInstance ) ) {
			$this->serverInstance = $serverInstance;
		}

		if ( true === is_null ( $this->consoleInstance ) ) {
			$this->consoleInstance = $consoleInstance;
		}

		$this->message('System: Wellcome to ' . (new \ReflectionClass($this))->getShortName());
	}

	/**
	 * Start server
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve Server instance
	 * @return bool
	 */
	public function onStart () {
		// TODO: Implement onStart() method.
	}

	/**
	 * Opening a connection to the server
	 *
	 * @param int $clientId connection identifier
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections
	 * @return void
	 */
	public function onOpen ( $clientId ) {
		// TODO: Implement onOpen() method.
	}

	/**
	 * Send responses from server
	 *
	 * @param int    $clientId connection identifier
	 * @param string $message  server message
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections & send messages
	 * @return void
	 */
	public function onMessage ( $clientId, $message ) {
		// TODO: Implement onMessage() method.
	}

	/**
	 * Closing connection
	 *
	 * @param int $clientId connection identifier
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections & send message
	 * @return void
	 */
	public function onClose ( $clientId ) {
		// TODO: Implement onClose() method.
	}

	/**
	 * Server responses message interface
	 *
	 * @param  string $message
	 * @param int     $color
	 */
	private function message ( $message, $color = ColorInterface::BLUE ) {

		$message = mb_convert_encoding($message, $this->serverInstance->getConfig()->charset);
		$this->consoleInstance->writeLine (
			$this->consoleInstance->colorize ( date ( '[Y-m-d H:i:s] ' ) . $message, $color )
		);
	}

	/**
	 * Need to do overloading! Its mostly setup event from `ServerInstance` object
	 *
	 * @param string $name
	 * @param string $arguments
	 *
	 * @return mixed
	 * @throws \RuntimeException
	 */
	public function __call($name, $arguments)
	{
		if(false === method_exists(get_class($this->serverInstance), $name)) {
			throw new \RuntimeException("Error! Function {$name} does not exist in ".get_class($this->serverInstance));
		}
		if(2 != sizeof($arguments)) {
			throw new \RuntimeException("Error! Arguments setup incorrectly in ".__CLASS__);
		}

		return $this->serverInstance->$name($arguments[0], $arguments[1], $this);
	}

	//
	//    /**
	//     * onOpen() opening a connection to the server
	//     * @param int $clientId connect identifier
	//     * @access public
	//     */
	//    public function onOpen($clientId)
	//    {
	//	// get client ip
	//	$ip = long2ip($this->_server->clients[$clientId][6]);
	//
	//	// send a join notice to everyone but the person who joined
	//	foreach($this->_server->clients as $id => $client)
	//	{
	//	    if($id != $clientId)
	//	    {	// send only not  for me :))
	//		$this->_server->send($id, "User $clientId ($ip) has joined the room.");
	//	    }
	//	}
	//    }
	//
	//    /**
	//     * onMessage($clientId, $message) get messages from server (request / response)
	//     * @param int $clientId connect identifier
	//     * @param varchar $message costom message throught socket
	//     * @access public
	//     */
	//    public function onMessage($clientId, $message)
	//    {
	//	// get client ip
	//	$ip = long2ip($this->_server->clients[$clientId][6]);
	//
	//	// check if message length is 0
	//	if(strlen($message) == 0)
	//	{
	//	    // send nothing
	//	    $this->_server->close($clientId);
	//	    return;
	//	}
	//
	//	// the speaker is the only person in the room. Don't let them feel lonely.
	//	if(sizeof($this->_server->clients) == 1)
	//	{
	//	    $this->_server->send($clientId, "There isn't anyone else in the room, but I'll still listen to you. Some one in the dark :))");
	//	}
	//	else
	//	{
	//	    // send the message to everyone but the person who said it
	//	    foreach($this->_server->clients as $id => $client)
	//	    {
	//		if($id != $clientId) $this->_server->send($id, "User $clientId ($ip) said \"$message\"");
	//	    }
	//	}
	//    }
	//
	//    /**
	//     * onClose($clientId) closing a connection to the server
	//     * @param int $clientId connect identifier
	//     * @access public
	//     */
	//    public function onClose($clientId)
	//    {
	//	// get client ip
	//	$ip = long2ip($this->_server->clients[$clientId][6]);
	//
	//	// send a user left notice to everyone in the room
	//	foreach($this->_server->clients as $id => $client)
	//	{
	//	    $this->_server->send($id, "User $clientId ($ip) has left the room.");
	//	}
	//    }
	//
	//

	//
	//    /**
	//     * run() running application
	//     * @access public
	//     */
	//    public function run()
	//    {
	//	return $this->_server->run();
	//    }
}
