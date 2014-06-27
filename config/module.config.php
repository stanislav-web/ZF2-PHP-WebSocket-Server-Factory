<?php
/** 
  * Configurator router current module (Websocket) 
  * Here are set settings aliases and URL template processing 
  * Recorded all controllers in the process of creating an application 
  * Set the path to the application by default 
  */
return [
    
    // The parameters of the compound (WS)
    
    'websockets'    => [
        'server'    => [ // setup your Server connection
	    
	    // connection's host
            'host'			=>  '127.0.0.1',  
	    
	    // connection's port
            'port'			=>  9000,
	    
	    // enable (disable) CLI debug console to watching request and response data 
	    'debug'			=>  true,
	    
	    // console encoding message (experementaly it)
	    'encoding'			=>  'utf-8',
	    
	    // save all data into log ile
	    'log'			=>  true,
	    
	    // logfile path
	    'logfile'			=>  'logs/socket/actions.log',

	    // maximum amount of clients that can be connected at one time
	    'max_clients'		=>  10,		
	    
	    // maximum amount of clients that can be connected at one time on the same IP v4 address
	    'max_clients_per_ip'	=>  5,		
	    
	    // amount of seconds a client has to send data to the server, before a ping request is sent to the client,
	    // if the client has not completed the opening handshake, the ping request is skipped and the client connection is closed
	    'timeout_recv'		=>  10,
	    
	    // amount of seconds a client has to reply to a ping request, before the client connection is closed
	    'timeout_pong'		=>  10,
	    
	    // the maximum length, in bytes, of a frame's payload data, this is also internally limited to 2,147,479,538
	    'max_frame_payload_recv'	=>  100000,
	    
	    // the maximum length, in bytes, of a message's payload data, this is also internally limited to 2,147,483,647
	    'max_message_payload_recv'	=>  500000,
        ],
    ],
    
     /**
      * Namespace for all controllers
      */
    'controllers' => [
        'invokables' => [
            'websocket.CLI'             => 'WebSockets\Controller\WebsocketCLIController',      // server controller (from CLI)
        ],
    ],

    /**
     * Configure the router module
     */

    'console' => [
        'router' => [
            'routes' => [
                'websocket-console' => [ // opening a connection through a CLI
                    'options'   => [
                        'route' => 'websocket open <app>',
                        'defaults' => [
                            '__NAMESPACE__' => 'WebSockets\Controller\WebsocketCLIController',
                            'controller'    => 'websocket.CLI',
                            'action'        => 'open',
                            'app'	    => 'Chat',
                        ],
                    ],
                ],  
                'websocket-console-info' => [ // costom system command
                    'options'   => [
                        'route' => 'websocket system [--verbose|-v] <option>',
                        'defaults' => [
                            '__NAMESPACE__' => 'WebSockets\Controller\WebsocketCLIController',
                            'controller'    => 'websocket.CLI',
                            'action'        => 'system',
                        ],
                    ],
                ],                
            ],
        ],
    ],
];
