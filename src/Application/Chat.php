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
	 * Bind server for this application
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

		$this->message ( '[system]: Wellcome to ' . ( new \ReflectionClass( $this ) )->getShortName () );
	}

	/**
	 * Opening a connection to the server event
	 *
	 * @param int $clientId connection identifier
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections
	 * @return void
	 */
	public function onOpen ( $clientId ) {

		$ip = long2ip ( $this->serverInstance->clients[$clientId][self::SOCKET_RESPONSE_IP] );

		// send a join notice to everyone but the person who joined
		foreach ( $this->serverInstance->clients as $id => $client ) {
			if ( $id != $clientId ) {
				// send only not for me :))
				$this->serverInstance->send ( $id, "User {$clientId} ({$ip}) has joined the room." );
			}
		}
	}

	/**
	 * Send responses from server event
	 *
	 * @param int    $clientId connection identifier
	 * @param string $message  server message
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections & send messages
	 * @return void
	 */
	public function onMessage ( $clientId, $message ) {

		// get client ip
		$ip = long2ip ( $this->serverInstance->clients[$clientId][self::SOCKET_RESPONSE_IP] );

		if ( 0 === strlen ( $message ) ) {
			// send nothing
			$this->serverInstance->close ( $clientId );

			return;
		}

		// the speaker is the only person in the room. Don't let them feel lonely.
		if ( 1 === sizeof ( $this->serverInstance->clients ) ) {
			$this->serverInstance->send ( $clientId, "There isn't anyone else in the room, but I'll still listen to you. Some one in the dark :))" );
		} else {
			// send the message to everyone but the person who said it
			foreach ( $this->serverInstance->clients as $id => $client ) {
				if ( $id != $clientId ) {
					$this->serverInstance->send ( $id, "User {$clientId} ({$ip}) said \"{$message}\"" );
				}
			}
		}
	}

	public function onError ( ServerInterface $serverInstance, \Exception $e ) {
		// TODO: Implement onError() method.
	}

	/**
	 * Closing connection event
	 *
	 * @param int $clientId connection identifier
	 *
	 * @uses \WebSockets\Service\WebsocketServer to retrieve available connections & send message
	 * @return void
	 */
	public function onClose ( $clientId ) {

		// get client ip
		$ip = long2ip ( $this->serverInstance->clients[$clientId][self::SOCKET_RESPONSE_IP] );

		// send a user left notice to everyone in the room
		foreach ( $this->serverInstance->clients as $id => $client ) {
			$this->serverInstance->send ( $id, "User {$clientId} ({$ip}) has left the room." );
		}
	}

	/**
	 * Run application
	 *
	 * @return boolean
	 */
	public function run () {
		try {
			return $this->serverInstance->start ();
		}
		catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * Server responses message interface
	 *
	 * @param  string $message
	 * @param int     $color
	 */
	private function message ( $message, $color = ColorInterface::BLUE ) {

		$message = mb_convert_encoding ( $message, $this->serverInstance->getConfig ()->getCharset() );
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
	 * @throws \Exception
	 */
	public function __call ( $name, $arguments ) {
		if ( false === method_exists ( get_class ( $this->serverInstance ), $name ) ) {
			throw new \Exception( "Error! Function {$name} does not exist in " . get_class ( $this->serverInstance ) );
		}
		if ( 2 != sizeof ( $arguments ) ) {
			throw new \Exception( "Error! Arguments setup incorrectly in " . __CLASS__ );
		}

		return $this->serverInstance->$name( $arguments[0], $arguments[1], $this );
	}
}
