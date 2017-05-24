<?php
/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */

return array(
	'service_manager' => array(
		'factories' => array(
			'Zend\Db\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
		),
	),
	'db' =>array(
		'username' => 'postgres',
		'password' =>'123',
		'driver' => 'Pdo',
		// 'schema' => 'sem',
		'dsn'=>'pgsql:host=192.168.1.34;port=5432;dbname=SISCOMAVE', 

	),
);