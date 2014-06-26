<?php
namespace WebSockets\Controller; // Namespaces of current controller

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;
use WebSockets\Exception;

/**
 * Controller to run through a client browser
 * @package Zend Framework 2
 * @subpackage WebSockets
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanilav WEB
 * @license Zend Framework GUI licene
 * @filesource /vendor/Websocket/src/Websocket/Controller/WebsocketController.php
 */
class WebsocketController extends AbstractActionController
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
        // Try to start server
        
        try {        
	    // Get Factory container
	    $factory        = $this->getServiceLocator()->get('WebSockets\Factory\ApplicationFactory');

	    // Get Application @see /src/WebSockets/Application/Chat.php etc..
	    $app       = $factory->dispatch('Chat'); 
	    
	    // running server application
	    $app->run();
        }
        catch(Exception\ExceptionStrategy $e) 
        {
            echo $e->getMessage();
        } 
	$result = new ViewModel();
	$result->setTerminal(true);
	return $result;	
    } 
}
