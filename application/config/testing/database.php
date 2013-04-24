<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Database Connection
	|--------------------------------------------------------------------------
	|
	| The name of your default database connection. This connection will be used
	| as the default for all database operations unless a different name is
	| given when performing said operation. This connection name should be
	| listed in the array of connections below.
	|
	*/

	'default' => 'sqlite_testing',

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| All of the database connections used by your application. Many of your
	| applications will no doubt only use one connection; however, you have
	| the freedom to specify as many connections as you can handle.
	|
	| All database work in Laravel is done through the PHP's PDO facilities,
	| so make sure you have the PDO drivers for your particular database of
	| choice installed on your machine.
	|
	*/

	'connections' => array(

		'sqlite_testing' => array(
			'driver'   => 'sqlite',
			'database' => 'testing',
			'prefix'   => '',
		),

	),

);