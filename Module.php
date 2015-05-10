<?php
namespace WebSockets; // declare namespace for the current module "WebSockets"

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,     // provide autoloader configurations
    Zend\ModuleManager\Feature\ViewHelperProviderInterface,    // provide view helpers
    Zend\ModuleManager\Feature\ConfigProviderInterface,         // interfaces for configurator
    Zend\ModuleManager\Feature\ConsoleUsageProviderInterface,   // interfaces for CLI
    Zend\Console\Adapter\AdapterInterface as Console,         // add adapter for provider
    Zend\Console\Charset\CharsetInterface;

/**
 * Module for the console launch permanent connection WebSockets
 * @package Zend Framework 2
 * @subpackage WebSockets
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanilav WEB
 * @license Zend Framework GUI licene
 * @filesource /vendor/WebSockets/Module.php
 */
class Module implements
    AutoloaderProviderInterface,
    ViewHelperProviderInterface,
    ConfigProviderInterface,
    ConsoleUsageProviderInterface,
    CharsetInterface
{

    /**
     * getConfig() configurator boot method for application
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * getAutoloaderConfig() installation method autoloaders
     *      * In my case, I connect the class map
     *      * And set the namespace for the MVC application directory
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            // add classmap file. Be careful! Update this map when adding a new provider!
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
        ];
    }

    /**
     * getViewHelperConfig() Setup your view helpers
     *
     * @return array
     */
    public function getViewHelperConfig()
    {
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
    public function getServiceConfig()
    {
        return [];
    }

    /**
     * getConsoleUsage(Console $console) cantilever load scripts, descriptions of commands (For Console usage help)
     *
     * @return array
     */
    public function getConsoleUsage(Console $console)
    {
        return [

            // Here I describe the console Command

            'websocket open <app>' => 'Server start',
            'websocket system <option>' => 'type the system command',
            ['app' => 'application will be run throught socket'],
            ['option' => 'system command for your CLI'],
        ];
    }
}
