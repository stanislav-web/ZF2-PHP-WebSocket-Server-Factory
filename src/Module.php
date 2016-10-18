<?php
namespace WebSockets;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Console\Adapter\AdapterInterface as ConsoleInterface;
//	Zend\ModuleManager\Feature\ViewHelperProviderInterface,    // provide view helpers
//	Zend\ModuleManager\Feature\ConsoleUsageProviderInterface,   // interfaces for CLI
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
	 * Default directory separator
	 *
	 * @const string DS
	 */
	const DS = DIRECTORY_SEPARATOR;

	/**
	 * Load default module configurations
	 *
	 * @return array
	 */
	public function getConfig () {
		return include __DIR__ . self::DS .'..'.self::DS.'config'.self::DS.'module.config.php';
	}

	/**
	 * Console usage helper
	 *
	 * @return array
	 */
	public function getConsoleUsage ( ConsoleInterface $Console ) {

//		return array(
//			'name'  => 'self-update',
//			'description' => 'When executed via the Phar file, performs a self-update by querying
//the package repository. If successful, it will report the new version.',
//			'short_description' => 'Perform a self-update of the script',
//		),
//    array(
//	    'name' => 'build',
//	    'route' => '<package> [--target=]',
//	    'description' => 'Build a package, using <package> as the package filename, and --target
//as the application directory to be packaged.',
//	    'short_description' => 'Build a package',
//	    'options_descriptions' => array(
//		    '<package>' => 'Package filename to build',
//		    '--target'  => 'Name of the application directory to package; defaults to current working directory',
//	    ),
//	    'defaults' => array(
//		    'target' => getcwd(), // default to current working directory
//	    ),
//	    'handler' => 'My\Builder',
//    );

		return [

			'websocket open <app>'      => 'Server start',
			'websocket system <option>' => 'type the system command',
			[ 'app' => 'application will be run throught socket' ],
			[ 'option' => 'system command for your CLI' ],
		];
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


}
