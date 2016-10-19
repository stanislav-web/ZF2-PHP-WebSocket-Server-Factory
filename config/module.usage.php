<?php
/**
 *  WebSocket's module CLI usage helper.
 */

return [
	// available commands
	'websocket [--version|-v]'  => 'Module version',
	'websocket open <app>'      => 'Application launcher',
	'websocket system <option>' => 'System echo commander',

	// available commands
	[ 'app' => 'Application wich will run throught socket' ],
	[ 'option' => 'Any system command' ],
	[ '--v|-version' => 'Module version' ],
];