ZF2 PHP WebSocket Server Factory v2.1 (Extended)
------
![Alt text](https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRpi209uZxeUrXP6cFLxuFbsTQkm9V0anTgp7Y-ltpEG6sw-txlvg "WebSockets")

Protocol WebSocket (standard RFC 6455) is designed to solve any problems and the removal of restrictions communication between browser and server.It allows you to transfer any data to any domain, safe and almost without unnecessary network traffic.

The main advantages of this module is that you can create a variety of applications based on Zend Framework 2, 
where it is necessary to implement persistent connections. 
Thanks to one interface, you can create a variety of applications and run them on the same server. 
You can organize a chat, monitor live site visit, you can create real-time statistics and save it to DB (MySQL, Mongo..etc), you can organize the postal service in the likeness of Google. Yes, anything!

#### Requirements
------------
* PHP 5.4+
* [Zend Framework 2](https://github.com/zendframework/zf2)

#### Changes
------------
v2.1
- Select client's application from console like `php -q index.php websocket open <app>`

v2.0
- Add "Chat" application as example
- More advanced interface
- **Ability to create multiple applications and bind it to the same server**
- Detailed log of each ping server's
- The ability to set the maximum number of connections to the server, the maximum number of connections per IP
- Pre defined response errors for Linux, Win platforms (extended by their descriptions)
- Ping using control frames such as PING PONG

v1.3
- Add logger

v1.2
- Add verbose turner (debug show\hide) (@see module.config.php)
- Add socket function's exception handler
- Fixed CLI stdout>> encoding
- Add ViewHelper for a simple get server config params into view
```
<?php
// for example
echo $this->socket()->config('host'); // print 127.0.0.1
?>
```
v1.1.2
- Console stdout>> while starting server

v1.1
- Fixes some problem with startup
- Add console command interface for costom system commands (for example)
`
php -q index.php websocket system -v "whoami"
`
(Note for ZF2.2): if you have an exceptions `Notice Undefined offset: 0` while starting console server please follow this:

`vendor\ZF2\library\Zend\Mvc\View\Console\RouteNotFoundStrategy.php` 381 and replace line by 
```
<?php
$result .= isset($row[0]) ? $row[0] . "\n" : '';
?>
```
It might be fixed until not fix in the next update.
You're always can ask me for this module if you have write me [issue](https://github.com/stanislav-web/ZF2-PHP-WebSocket-Server-Factory/issues/1)

#### Installation and Running Server :

1. That needs to be done is adding it to your application's list of active modules. Add module "WebSockets" in your application.config.php

2. Perform module configuration file module.config.php

3. Go to your shell command-line interface and type (running server as background): `php -q index.php websocket open <app>` (app like as your client application)

4. Setup your Client-side script's to communicating with the server .. ws://host:port/websocket/open/<app>

#### How can i do the Application ?
------------
New application you can do according to the rules interface 
```
WebSockets\src\WebSockets\Aware\ApplicationInterface.php
``` 
As an example, you can see the implementation "Chat" 
```
WebSockets\src\WebSockets\Application\Chat.php
```
then call server from your cli controller
```
<?php
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

	    // running server
	    $app->run();
?>
```
------------
In order to start using the module clone the repo in your vendor directory or add it as a submodule if you're already using git for your project:

    `
    git clone https://github.com/stanislav-web/ZF2-PHP-WebSocket-Server-Factory.git vendor/WebSockets
    or
    git submodule add     git clone https://github.com/stanislav-web/ZF2-PHP-WebSocket-Server-Factory.git vendor/WebSockets
    `
    
The module will also be available as a Composer package soon.

#### Libraries used

- [Zend Framework 2.3](https://github.com/zendframework/zf2)
- [WebSocket Protocol] (http://tools.ietf.org/html/rfc6455)
- This repository is the continuation of the module from which I started work [https://github.com/stanislav-web/ZF2-PHP-WebSocket-Server] (https://github.com/stanislav-web/ZF2-PHP-WebSocket-Server)

![Alt text](http://cdn.joxi.ru/uploads/prod/2014/06/26/68b/111/577c8d0197b1ddc6bd7db7dde5d07efb005ae24b.jpg "WebSockets")


