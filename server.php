<?php
// This is a laravel 3 serving php file. It's taken from Laravel 4.
// https://github.com/laravel/laravel/blob/develop/server.php
// And some modifications for L3

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = urldecode($uri);

require_once __DIR__.'/paths.php';

$requested = path("public").$uri;

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' and file_exists($requested))
{
return false;
}

chdir(path("public"));
require_once path("public").'/index.php';