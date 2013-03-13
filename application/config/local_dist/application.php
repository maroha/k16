<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| The URL used to access your application without a trailing slash. The URL
	| does not have to be set. If it isn't, we'll try our best to guess the URL
	| of your application.
	|
	*/

	'url' => 'http://k16.dev',

	/*
	|--------------------------------------------------------------------------
	| Application Index
	|--------------------------------------------------------------------------
	|
	| If you are including the "index.php" in your URLs, you can ignore this.
	| However, if you are using mod_rewrite to get cleaner URLs, just set
	| this option to an empty string and we'll take care of the rest.
	|
	*/

	'index' => '',

	/*
	|--------------------------------------------------------------------------
	| Application Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the encryption and cookie classes to generate secure
	| encrypted strings and hashes. It is extremely important that this key
	| remains secret and it should not be shared with anyone. Make it about 32
	| characters of random gibberish.
	|
	*/

	'key' => 'YourSecretKeyGoesHere!',

	/*
	|--------------------------------------------------------------------------
	| Profiler Toolbar
	|--------------------------------------------------------------------------
	|
	| Laravel includes a beautiful profiler toolbar that gives you a heads
	| up display of the queries and logs performed by your application.
	| This is wonderful for development, but, of course, you should
	| disable the toolbar for production applications.
	|
	*/

	'profiler' => false,

	/*
	|--------------------------------------------------------------------------
	| SSL Link Generation
	|--------------------------------------------------------------------------
	|
	| Many sites use SSL to protect their users' data. However, you may not be
	| able to use SSL on your development machine, meaning all HTTPS will be
	| broken during development.
	|
	| For this reason, you may wish to disable the generation of HTTPS links
	| throughout your application. This option does just that. All attempts
	| to generate HTTPS links will generate regular HTTP links instead.
	|
	*/

	'ssl' => false,

);
