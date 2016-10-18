<?php
namespace WebSockets;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

//	Zend\ModuleManager\Feature\ViewHelperProviderInterface,    // provide view helpers
//
//	Zend\ModuleManager\Feature\ConsoleUsageProviderInterface,   // interfaces for CLI
//	Zend\Console\Adapter\AdapterInterface as Console,         // add adapter for provider
//	Zend\Console\Charset\CharsetInterface;

/**
 * Class Module.
 * Module initialization
 *
 * @package    WebSockets
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Module.php
 */
class Module implements
	ConfigProviderInterface
//	ViewHelperProviderInterface,
//	ConsoleUsageProviderInterface,
//	CharsetInterface
 {

	/**
	 * Load default module configurations
	 *
	 * @return array
	 */
	public function getConfig () {
		return include __DIR__ . '/config/module.config.php';
	}

	/**
	 * getViewHelperConfig() Setup your view helpers
	 *
	 * @return array
	 */
	public function getViewHelperConfig () {
		return [
			'invokables' => [
				'socket' => '\\View\Helper\Socket',
			],
		];
	}

	/**
	 * getServiceConfig() method of loading services
	 *
	 * @return array
	 */
	public function getServiceConfig () {
		return [ ];
	}

	/**
	 * getConsoleUsage(Console $console) cantilever load scripts, descriptions of commands (For Console usage help)
	 *
	 * @return array
	 */
	public function getConsoleUsage ( Console $console ) {
		return [

			// Here I describe the console Command

			'websocket open <app>'      => 'Server start',
			'websocket system <option>' => 'type the system command',
			[ 'app' => 'application will be run throught socket' ],
			[ 'option' => 'system command for your CLI' ],
		];
	}
}
