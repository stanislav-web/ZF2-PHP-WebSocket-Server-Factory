<?php
namespace WebSockets\Factory;

use WebSockets\Exception;
use WebSockets\Service\WebsocketServer;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * ApplicationFactory. Use this factory for get some client applications
 * @package Zend Framework 2
 * @subpackage WebSockets
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanislav WEB
 * @license Zend Framework GUI license
 * @filesource /vendor/WebSockets/src/WebSockets/Factory/ApplicationFactory.php
 */
class ApplicationFactory
{

    /**
     * Service Manager
     *
     * @var \Zend\ServiceManager\ServiceManager $serviceManager
     */
    private $serviceManager;

    /**
     * Setup service manager
     *
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function __construct(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * dispatch($app) Get produced application object
     *
     * @param string $app application object
     *
     * @return \WebSockets\Application\  object
     * @throws Exception\ExceptionStrategy
     */
    public function dispatch($app)
    {
        // need to provide dynamic objects creations 

        //Get namespaces for application
        $config     = $this->serviceManager->get('Config');
        $namespaces = $config['websockets']['server']['applications_namespace'];

        foreach ($namespaces as $namespace) {
            // checking class..
            $Client = "$namespace\\$app";
            if ( TRUE === class_exists($Client) ) {
                $obj = new $Client(new WebsocketServer($config['websockets']['server']));
                //Deprecated
                if($obj instanceof ServiceLocatorAwareInterface) {
                    $obj->setServiceLocator($this->serviceManager);
                }
            }
            if ($this->serviceManager->has($Client)) {
                return $this->serviceManager->get($Client);
            }
        }
        throw new Exception\ExceptionStrategy($app . ' application does not exist');
    }
}
