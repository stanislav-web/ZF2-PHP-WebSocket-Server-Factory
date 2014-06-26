<?php
namespace WebSockets\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use WebSockets\Exception,
    WebSockets\Service\WebsocketServer;

/**
 * ApllicationFactory. Use this factory for get some client applications
 * @package Zend Framework 2
 * @subpackage WebSockets
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanilav WEB
 * @license Zend Framework GUI licene
 * @filesource /vendor/WebSockets/src/WebSockets/Factory/ApplicationFactory.php
 */
class ApplicationFactory implements FactoryInterface, ServiceLocatorAwareInterface {

    /**
     * $__serviceLocator Service Locator for create implemented object
     * @access private
     * @var object 
     */  
    private $__serviceLocator;
    
    /**
     * createService(ServiceLocatorInterface $serviceLocator) Create object method
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @access public
     * @return \Submissions\Factory\ProviderFactory
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->__serviceLocator = $serviceLocator;
        return $this;
    } 
    
    /**
     * setServiceLocator(ServiceLocatorInterface $serviceLocator) Implement ServiceLocator
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @access public
     * @return null
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->__serviceLocator = $serviceLocator;
    }

    /**
     * getServiceLocator()
     * @access public
     * @return object ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->__serviceLocator;
    }    
    
    /**
     * dispatch($app) Get produced application object
     * @param string $app application object
     * @return object \Websockets\Application
     * @throws Exception\ExceptionStrategy
     */
    public function dispatch($app)
    {
        // need to provide dynamic objects creations 
        
	$Client = "\\WebSockets\\Application\\$app";
	// checking class..
        if(!class_exists($Client)) throw new Exception\ExceptionStrategy($app.' application does not exist');
	    
	$config = $this->getServiceLocator()->get('Config');
        return new $Client(new WebsocketServer($config['websockets']['server']));
    }
}