<?php

/**
 *  WebSocket's module CLI usage helper.
 */

return [
	[
		'name'                 => 'system',
		'route'                => '<option>',
		'description'          => 'System echo commander',
		'short_description'    => 'System echo commander',
		'options_descriptions' => [
			'<option>' => 'Any system command',
		],
		'handler'              => [ 'WebSockets\Command\System', 'run' ],
		'filters'              => [
			'option' => ( function ( $option ) {
				return escapeshellcmd ( $option );
			} ),
		],
	],
	[
		'name'                 => 'open',
		'route'                => '<app>',
		'description'          => 'Application launcher',
		'short_description'    => 'Application launcher',
		'options_descriptions' => [
			'<app>' => 'Application wich will run throught socket',
		],
		'handler'              => [ 'WebSockets\Command\Open', 'run' ],
		'filters'              => [

			/** @var Zend\ServiceManager\ServiceLocatorInterface $serviceManager */
			'app' => ( function ( $app ) use ( $config, $serviceManager ) {

				if ( false === class_exists ( $config['applications_namespace'] . ucfirst ( $app ) ) ) {
					throw new \Exception( "`" . $app . "` application does not exist" );
				}

				return [
					'client'         => $config['applications_namespace'] . ucfirst ( $app ),
					'serviceLocator' => $serviceManager,
				];
			} ),
		],
	],
];