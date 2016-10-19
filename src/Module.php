<?php
namespace WebSockets;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Console\Adapter\AdapterInterface;

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
	ConfigProviderInterface,
	ConsoleUsageProviderInterface,
	ConsoleBannerProviderInterface,
	ViewHelperProviderInterface {

	/**
	 * Default directory separator
	 *
	 * @const string DS
	 */
	const DS = DIRECTORY_SEPARATOR;

	/**
	 * Module banner
	 *
	 * @const BANNER
	 */
	const BANNER = 'ZF2 PHP WebSocket Server Factory v3.1 (Extended)';

	/**
	 * Returns configuration to merge with application configuration
	 *
	 * @return array|\Traversable
	 */
	public function getConfig () {

		$config = __DIR__ . self::DS . '..' . self::DS . 'config' . self::DS . 'module.config.php';

		return require_once $config;
	}

	/**
	 * Returns an array or a string containing usage information for this module's Console commands.
	 * The method is called with active Zend\Console\Adapter\AdapterInterface that can be used to directly access
	 * Console and send output.
	 *
	 * If the result is a string it will be shown directly in the console window.
	 * If the result is an array, its contents will be formatted to console window width. The array must
	 * have the following format:
	 *
	 *     return array(
	 *                'Usage information line that should be shown as-is',
	 *                'Another line of usage info',
	 *
	 *                '--parameter'        =>   'A short description of that parameter',
	 *                '-another-parameter' =>   'A short description of another parameter',
	 *                ...
	 *            )
	 *
	 * @param AdapterInterface $Console
	 *
	 * @return array|string|null
	 */
	public function getConsoleUsage ( AdapterInterface $Console ) {

		$routes = __DIR__ . self::DS . '..' . self::DS . 'config' . self::DS . 'module.usage.php';

		return require_once $routes;
	}

	/**
	 * Returns a string containing a banner text, that describes the module and/or the application.
	 * The banner is shown in the console window, when the user supplies invalid command-line parameters or invokes
	 * the application with no parameters.
	 *
	 * The method is called with active Zend\Console\Adapter\AdapterInterface that can be used to directly access Console and send
	 * output.
	 *
	 * @param AdapterInterface $console
	 *
	 * @return string|null
	 */
	public function getConsoleBanner ( AdapterInterface $console ) {

		return self::BANNER;
	}

	/**
	 * Expected to return \Zend\ServiceManager\Config object or array to
	 * seed such an object.
	 *
	 * @return array|\Zend\ServiceManager\Config
	 */
	public function getViewHelperConfig () {
		return [
			'invokables' => [
				'socket' => '\\View\Helper\Socket',
			]
		];
	}
}
