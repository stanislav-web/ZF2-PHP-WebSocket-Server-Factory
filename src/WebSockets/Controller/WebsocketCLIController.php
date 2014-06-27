<?php
namespace WebSockets\Controller; // Namespaces of current controller

use Zend\Mvc\Controller\AbstractActionController,
    Zend\Console\Request as ConsoleRequest; // limiting console output


use WebSockets\Exception;

/**
 * Controller to run through a CLI
 * @package Zend Framework 2
 * @subpackage WebSockets
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanilav WEB
 * @license Zend Framework GUI licene
 * @filesource /vendor/Websocket/src/Websocket/Controller/WebsocketCLIController.php
 */
class WebsocketCLIController extends AbstractActionController
{
    /**
     * $_server Object server connection
     * @access private
     * @var resource
     */    
    private $_server = null;    

    /**
     * openAction() Running socket - server
     * @access public
     * @return console
     */    
    public function openAction()
    {   
        $request    = $this->getRequest();

        if(!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('Use only for CLI!');
        }
        
        // Try to start server
        
        try {        
	    
	    // get factory container
	    $factory        = $this->getServiceLocator()->get('WebSockets\Factory\ApplicationFactory');

            // applications from response <app>
	    // get it @see /src/WebSockets/Application/Chat.php etc..

	    $client	= $request->getParam('app');

	    $app	= $factory->dispatch(ucfirst($client));
	    
	    // bind events from application 
	    // ! must be implements of your every new Application
	    $app->bind('open', 'onOpen');
	    $app->bind('message', 'onMessage');
	    $app->bind('close', 'onClose');

	    // running server application
	    $app->run();
        }
        catch(Exception\ExceptionStrategy $e) 
        {
            echo $e->getMessage();
        }        
    } 
  
    /**
     * systemAction() System command
     * @access public
     * @return console
     */    
    public function systemAction()
    {   
        $request    = $this->getRequest();

        if(!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('Use only for CLI!');
        }
        
        // Try to start actions
        
        try {        
            // Get system service name  from console and check if the user used --verbose or -v flag
            $option     = $request->getParam('option', false);
            $verbose    = ($request->getParam('verbose')) ? $request->getParam('verbose') : $request->getParam('v');
            
            if($verbose != false) echo 'Command execute: '.$option.PHP_EOL;
            $option = preg_replace('#"#', '', $option);
            if(is_string($option)) system($option, $val);
        }
        catch(Exception\ExceptionStrategy $e) 
        {
            echo $e->throwMessage();
        }        
    }     
    
}
