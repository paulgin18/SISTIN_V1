<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
	'service_manager' => array(
		'factories' => array(
			'Zend\Db\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
		),
	),
	'db' =>array(
		'username' => 'postgres',
		'password' =>'120989',
		'driver' => 'Pdo',
		// 'schema' => 'sem',
		//'dsn'=>'pgsql:host=192.168.1.34;port=5432;dbname=bdinventario', 

		//conexion local
		'dsn'=>'pgsql:host=localhost;port=5432;dbname=bdinventario3', 

	),    
);

