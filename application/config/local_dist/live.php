<?php
return array(
	/*
	|--------------------------------------------------------------------------
	| Clinet-side live server
	|--------------------------------------------------------------------------
	| This server will face the people.
	| Port will be used by server, while full_url is given to the clients.
	*/

	"client" => array(
		"port" => 9098,
		"full_url" => "ws://localhost:9098"
	),
	"server" => array(
		"port" => 9099
	)
);