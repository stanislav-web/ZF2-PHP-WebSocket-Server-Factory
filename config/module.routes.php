<?php
/**
 *  WebSocket's module CLI usage helper.
 */

return [
	[
		'name'  => 'version',
		'description' => 'Show module version',
		'short_description' => 'Module version',
		'handler' => ['WebSockets\Command\Version', 'run']
	],
	[
		'name' => 'system',
		'route' => '<option>',
		'description' => 'System echo commander',
		'short_description' => 'System echo commander',
		'options_descriptions' => [
			'<option>' => 'Any system command'
		],
		'handler' => ['WebSockets\Command\System', 'run']
	],
	[
		'name' => 'open',
		'route' => '<app>',
		'description' => 'Application launcher',
		'short_description' => 'Application launcher',
		'options_descriptions' => [
			'<app>' => 'Application wich will run throught socket'
		],
		'handler' => ['WebSockets\Command\Open', 'run']
	]
];