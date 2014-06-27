<?php
namespace WebSockets; // declare namespace for the current module "WebSockets"

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,     // provide autoloader configurations
    Zend\ModuleManager\Feature\ViewHelperProviderInterface,	// provide view helpers
    Zend\ModuleManager\Feature\ConfigProviderInterface,         // interfaces for configurator
    Zend\ModuleManager\Feature\ConsoleUsageProviderInterface,   // interfaces for CLI
    Zend\ModuleManager\Feature\ConsoleBannerProviderInterface,  // provide console banner
    Zend\Console\Adapter\AdapterInterface as Console,		 // add adapter for provider
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
                        ConsoleBannerProviderInterface,
			CharsetInterface {
       
    /**
     * getConfig() configurator boot method for application
     * @access public
     * @return file
     */
    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }
    
    /**
     * getAutoloaderConfig() installation method autoloaders 
     * In my case, I connect the class map 
     * And set the namespace for the MVC application directory
     * @access public
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            // add classmap file. Be careful! Update this map when adding a new provider!
            'Zend\Loader\ClassMapAutoloader'    =>  [
                __DIR__.'/autoload_classmap.php',
            ],
        ];        
    } 
    
    /**
     * getViewHelperConfig() Setup your view helpers
     * @access public
     * @return array
     */
    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                'socket' =>  '\WebSockets\View\Helper\Socket',
            ],
        ];
    }   
    
    /**
     * getServiceConfig() method of loading services
     * @access public
     * @return file
     */
    public function getServiceConfig()
    {
        return [
	    'service_manager'	=>  [
		'factoires' => [
	
		    // application dependency class
		    'WebSockets\Factory\ApplicationFactory'   =>  __NAMESPACE__.'\Factory\ApplicationFactory',	      
		],
	    ],
	];
    }
    
    /**
     * getConsoleUsage(Console $console) cantilever load scripts, descriptions of commands (For Console usage help)
     * @access public
     * @return console
     */
    public function getConsoleUsage(Console $console)
    {
        return [
            
            // Here I describe the console Command
            
            'websocket open <app>'	=> 'Server start',
            'websocket system [--verbose|-v] <option>'   =>  'type the system command',
            ['app'			=>  'application will be run throught socket'],
            ['option'			=>  'system command for your CLI'],
            ['--verbose|-v'		=>  '(optional) turn on verbose mode'],
        ];
    }
    
    public function getConsoleBanner(Console $console)
    {
        return '###################################'.PHP_EOL
        .'### Runned WebSocket Module...  ###'.PHP_EOL
        .'###################################';
    }    
}
