<?php
class Selenium_Task extends Task {
	public $session;

	public function run($arguments = array()) {
		ob_end_flush();
		Request::set_env("testing");

		if(!isset($arguments[0]) || is_numeric($arguments[0])) {
			$arguments[0] = 4444;
		} else {
			$arguments[0] = intval($arguments[0]);
		}
		/* Do a fresh database! */
		File::delete(path("storage")."database/testing.sqlite");
		File::copy(path("storage")."database/testing_base.sqlite", path("storage")."database/testing.sqlite");

		$wd_host = 'http://localhost:'.$arguments[0].'/wd/hub';
		$web_driver = new WebDriver($wd_host);

		echo "Creating a selenium session...".PHP_EOL;
		try {
			$this->session = $web_driver->session('firefox');
			$this->session->open("http://localhost:16161");
		} catch (Exception $e) {
			echo "Error!".PHP_EOL;
			echo $e->getResults().PHP_EOL;
			return;
		}
		sleep(1);
		// Check for test server alive
		$pagetitle = $this->session->title();
		if(strpos($pagetitle, "e-Valimised") === false) {
			echo "ERROR! Not sure where I am. Check if the test server is alive?".PHP_EOL;
			echo $pagetitle;
			return;
		}

		// 1. Log in
		echo "Logging in...".PHP_EOL;
		$this->session->element("class name", "user")->element("tag name", "a")->click();
		$this->waitForAjax();
		if(strpos($this->session->url(), "https://www.facebook.com/") !== false) {
			echo "!USER INPUT REQUIRED! Please log in to facebook.";
			while (strpos($this->session->url(), "https://www.facebook.com/") !== false) {
				echo ".";
				sleep(3);
			}
			echo PHP_EOL;
		}
		echo "Checking if login was successful".PHP_EOL;
		$userbox = $this->session->element("class name", "user");
		$usertext = $userbox->text();
		if(strpos($usertext, "Tere ") === false) {
			echo "ERROR! User didn't get properly logged in";
			$this->session->close();
			return;
		}
		echo "User logged in! ".$usertext.PHP_EOL;


		$this->session->close();
	}
	public function serve($arguments) {
		ob_end_flush();
		Request::set_env("testing");

		/* Do a fresh database! */
		File::delete(path("storage")."database/testing.sqlite");
		File::copy(path("storage")."database/testing_base.sqlite", path("storage")."database/testing.sqlite");

		/* Serveeri kausta, põhineb laravel 4  artisan serve meetodil */
		$this->checkPhpVersion();

		chdir(path("base"));

		$public = rtrim(path("public"), "\\"); // without rtrim bellow would be "...\public\" server.php

		echo "Testing environment started on http://localhost:16161".PHP_EOL;

		passthru("php -S localhost:16161 -t \"{$public}\" server.php");
	}
	protected function checkPhpVersion() {
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
			throw new \Exception('This PHP binary is not version 5.4 or greater.');
		}
	}
	protected function waitForAjax() {
		try {
			do {
				sleep(2);
			} while($this->session->execute(array('script' => 'return jQuery.active', 'args' => array())));
		} catch(Exception $e) { }
	}
}