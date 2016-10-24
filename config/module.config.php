<?php
/**
 * WebSocket's module configure.
 * Here are the module's router settings.
 * Recorded all controllers in the process of creating an application
 * Set the path to the application by default
 */
return [

	// WS host
	'host'                     => '127.0.0.1',

	// WS Port
	'port'                     => 9001,

	// Enable (disable) CLI debug console to watching requests and responses data
	'debug'                    => true,

	// Console charset messages
	'charset'                  => 'utf-8',

	// Use messages logger
	'log'                      => false,

	// Logfile path
	'logfile'                  => 'logs/socket/actions.log',

	// Maximum amount of clients that can be connected at one time
	'max_clients'              => 10,

	// Maximum amount of clients that can be connected at one time on the same IP v4 address
	'max_clients_per_ip'       => 5,

	// Amount of seconds a client has to send data to the server, before a ping request is sent to the client.
	// If the client has not completed the opening handshake, the ping request is skipped and the client connection is closed
	'timeout_recv'             => 10,

	// Amount of seconds a client has to reply to a ping request, before the client connection is closed
	'timeout_pong'             => 10,

	// The maximum length (bytes) of a frame's payload data, this is also internally limited to 2,147,479,538
	'max_frame_payload_recv'   => 100000,

	// The maximum length (bytes) of a message's payload data, this is also internally limited to 2,147,483,647
	'max_message_payload_recv' => 500000,

	// DONT EDIT! There are the path to your applications
	'applications_namespace'   => '\\WebSockets\\Application\\',
];
