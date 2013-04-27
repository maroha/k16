<?php
class Selenium_Task extends Task {

	public $web_driver;
	public $session;
	public $username;
	public $user_id;

	public $tests = array(
		0 => array(
			"name" => "Creating a selenium session",
			"function" => "create_session"
		),
		1 => array(
			"name" => "Logging in using facebook",
			"function" => "logging_in",
			"user_input" => "During this step you'll need to log into facebook."
		),
		2 => array(
			"name" => "Candidate registartion",
			"function" => "candidate_register"
		),
		3 => array(
			"name" => "Candidate search",
			"function" => "candidate_search"
		),
		4 => array(
			"name" => "Voting",
			"function" => "voting"
		)
	);

	public function run($arguments = array()) {
		ob_end_flush();
		$this->prepare();

		if(!isset($arguments[0]) || is_numeric($arguments[0])) {
			$arguments[0] = 4444;
		} else {
			$arguments[0] = intval($arguments[0]);
		}

		$wd_host = 'http://localhost:'.$arguments[0].'/wd/hub';
		$this->web_driver = new WebDriver($wd_host);

		$results = array();

		foreach ($this->tests as $key => $test) {
			echo "# Test #{$key} - ".$test["name"]."...".PHP_EOL;
			if(isset($test["user_input"])) {
				echo "USER INPUT REQUIRED! ".$test["user_input"].PHP_EOL;
			}
			$method = "test_".$test["function"];
			$result = array();
			try {
				ob_start();
				ob_implicit_flush(true);
				$this->$method();
				$log = ob_get_contents();
				ob_end_clean();
				echo "SUCCESS!".PHP_EOL;
				$result["success"] = true;
			} catch (Testing_Exception $e) {
				$log = ob_get_contents();
				ob_end_flush();
				echo "FAIL! ".$e->getMessage().PHP_EOL;
				$e->data["screenshot"] = $this->session->screenshot();
				$result["success"] = false;
				$result["error"] = $e;
			} catch (Exception $e) {
				$log = ob_get_contents();
				ob_end_flush();
				echo "FAIL! Unhandled Expection!".PHP_EOL;
				$result["success"] = false;
				$result["error"] = new Testing_Exception("Unhandled Expection!", array(), 0, $e);
			}

			$result["log"] = $log;
			$results[$key] = $result;
			if(!$result["success"] and $result["error"]->getCode() > 0) {
				echo "Fatal Failure! Quitting...".PHP_EOL;
				break;
			}
		}

		if($this->session) {
			$this->session->close();
		}

		$filename = path("storage")."tests/results-".date('Y-m-d-H-i-s').".html";
		File::put($filename, View::make("selenium", array("tests" => $this->tests, "results" => $results)));
		echo "Test results written to {$filename}";
	}
	public function serve($arguments) {
		ob_end_flush();
		$this->prepare();

		/* Serveeri kausta, põhineb laravel 4  artisan serve meetodil */
		$this->check_php_version();

		chdir(path("base"));

		$public = rtrim(path("public"), "\\"); // without rtrim bellow would be "...\public\" server.php

		echo "Testing environment started on http://localhost:16161".PHP_EOL;

		passthru("php -S localhost:16161 -t \"{$public}\" server.php");
	}
	// Helpers
	protected function prepare() {
		Request::set_env("testing");

		/* Do a fresh database! */
		File::delete(path("storage")."database/testing.sqlite");
		File::copy(path("storage")."database/testing_base.sqlite", path("storage")."database/testing.sqlite");
	}
	protected function check_php_version() {
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
			throw new \Exception('This PHP binary is not version 5.4 or greater.');
		}
	}
	protected function wait_for_ajax() {
		// Sorry but I lost where I got this from.... Most likely stackoverflow.
		try {
			do {
				sleep(0.5);
			} while($this->session->execute(array('script' => 'return jQuery.active', 'args' => array())));
		} catch(Exception $e) { }
	}
	// Tests
	protected function test_create_session() {
		try {
			$this->session = $this->web_driver->session('firefox');
			$this->session->open("http://localhost:16161");
		} catch (Exception $e) {
			throw new Testing_Exception("WebDriver error", array(), 1, $e);
			return;
		}
		sleep(1);
		// Check for test server alive
		$page_title = $this->session->title();
		if(strpos($page_title, "e-Valimised") === false) {
			throw new Testing_Exception("Not sure where I am. Check if the test server is alive?", array("page_title" => $page_title), 1);
		}
	}

	protected function test_logging_in() {
		// 1. Log in
		echo "Logging in...".PHP_EOL;
		$this->session->element("class name", "user")->element("tag name", "a")->click("");
		sleep(3);
		if(strpos($this->session->url(), "https://www.facebook.com/") !== false) {
			echo "Waiting for user input.";
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
			throw new Testing_Exception("User didn't get properly logged in", array("usertext" => $usertext), 1);
		}
		preg_match('/Tere (.+)! – logi välja/', $usertext, $matches);
		$username = $matches[1];
		$this->username = $username;
		echo "User logged in! ".$username.PHP_EOL;
		// Look for yourself in the database. Should be the largest ID
		echo "Also checking database".PHP_EOL;
		$user_id = DB::only("SELECT `ID` FROM `haaletaja` ORDER BY `ID` DESC LIMIT 1;");
		if(!$user_id) {
			throw new Testing_Exception("Didn't find the user in the database", array("username" => $username, "user_id" => $user_id), 1);
		}
		echo "Found in database #{$user_id}".PHP_EOL;
		$this->user_id = $user_id;
		echo "Forcing user region #2".PHP_EOL;
		$sql = 'UPDATE "haaletaja" SET "Valimisringkonna_ID" = ? WHERE "ID" = ?';
		if(!DB::query($sql, array(2, $this->user_id))) {
			throw new Testing_Exception("Failed to update the database");
		}
	}

	protected function test_candidate_register() {
		echo "Navigating to candidates...".PHP_EOL;
		$this->session->element("css selector", "[data-item=kandidaadid] a")->click("");
		$this->wait_for_ajax();

		echo "Navigating to registration page...".PHP_EOL;
		$this->session->element("id", "candidate-register")->click("");
		$this->wait_for_ajax();

		echo "Filling out the form!".PHP_EOL;
		$fields = $this->session->elements("css selector", "#register-form input, #register-form select");
		$data = array(
			"birthplace" => "Village",
			"address" => "Blacksmith house",
			"party" => 4,
			"piirkond" => 4,
			"haridus" => "Testificate University",
			"academicdegree" => "Testificate",
			"occupation" => "Blacksmith",
			"work" => "Sells stuff",
			"phone" => "1234567890",
			"email" => "test@ifica.te"
		);
		foreach ($fields as $field) {
			$fieldtype = $field->name();
			$fieldname = $field->attribute("name");
			if(isset($data[$fieldname])) {
				if($fieldtype == "input") {
					$field->value(array("value" => str_split($data[$fieldname])));
				} else {
					// https://github.com/facebook/php-webdriver/issues/25
					$field->element("css selector", 'option[value="'.$data[$fieldname].'"]')->click();
				}
			}
		}

		echo "Submitting...".PHP_EOL;
		$this->session->element("css selector", "#register-form button[type=\"submit\"]")->click();
		sleep(1);

		echo "Checking for success message".PHP_EOL;
		$alert_text = $this->session->element("class name", "alert")->text();
		if($alert_text != "Te olete lisatud kandidaadina!") {
			throw new Test_Exception("Didn't find successful registartion confirmation message!", array("alert_text" => $alert_text));
		}
		sleep(1);

		echo "Checking database".PHP_EOL;
		$user_check = DB::first("SELECT * FROM `kandidaat` WHERE `Haaletaja_ID` = ? LIMIT 1;", array($this->user_id));
		if(!$user_check) {
			throw new Test_Exception("Didn't find successful registartion confirmation!", array("user_check" => $user_check));
		}
	}

	protected function test_candidate_search() {
		echo "Navigating to candidates...".PHP_EOL;
		$this->session->element("css selector", "[data-item=kandidaadid] a")->click("");
		$this->wait_for_ajax();
		sleep(1);

		echo "Searching by name. Expecting 1".PHP_EOL;
		$this->session->element("id", "search-name")->value(array("value" => str_split("Sinine Laev")));
		$this->session->element("css selector", "#search-form input[type=\"submit\"]")->click();
		$this->wait_for_ajax();

		$rows = $this->session->elements("css selector", "#candidate-list tbody tr");
		if(count($rows) != 1) {
			throw new Testing_Exception("Searching for candidates: Found an unexpected amount of candidates", array("rows" => $rows));
		}

		echo "Navigating to candidates...".PHP_EOL;
		$this->session->element("css selector", "[data-item=kandidaadid] a")->click("");
		$this->wait_for_ajax();

		echo "Searching by region (#6). Expecting 3".PHP_EOL;
		$this->session->element("css selector", 'select[name="region"] option[value="6"]')->click();
		$this->session->element("css selector", "#search-form input[type=\"submit\"]")->click();
		$this->wait_for_ajax();

		$rows = $this->session->elements("css selector", "#candidate-list tbody tr");
		if(count($rows) != 3) {
			throw new Testing_Exception("Searching for candidates: Found an unexpected amount of candidates", array("rows" => $rows));
		}

		echo "Navigating to candidates...".PHP_EOL;
		$this->session->element("css selector", "[data-item=kandidaadid] a")->click("");
		$this->wait_for_ajax();

		echo "Searching by party (#9). Expecting 2".PHP_EOL;
		$this->session->element("css selector", 'select[name="party"] option[value="9"]')->click();
		$this->session->element("css selector", "#search-form input[type=\"submit\"]")->click();
		$this->wait_for_ajax();

		$rows = $this->session->elements("css selector", "#candidate-list tbody tr");
		if(count($rows) != 2) {
			throw new Testing_Exception("Searching for candidates: Found an unexpected amount of candidates", array("rows" => $rows));
		}
	}

	protected function test_voting() {

		echo "Navigating to voting...".PHP_EOL;
		$this->session->element("css selector", "[data-item=haaleta] a")->click("");
		$this->wait_for_ajax();

		echo "Select (the only) candidate".PHP_EOL;
		$target = $this->session->element("css selector", "#candidate-list tbody a");
		try {
			$target->click("");
		} catch (Exception $e) {
			throw new Test_Exception("Error Processing Request", array("target" => $target), 0, $e);
		}
		$this->wait_for_ajax();

		echo "Voting!".PHP_EOL;
		$this->session->element("css selector", ".vote-form button").click("");
		sleep(1);

		echo "Checking for success message".PHP_EOL;
		$alert_text = $this->session->element("class name", "alert")->text();
		if($alert_text != "Teie hääl on salvestatud!") {
			throw new Test_Exception("Didn't find successful voting confirmation message!", array("alert_text" => $alert_text));
		}

		echo "Checking with the database".PHP_EOL;
		$vote_check = DB::first("SELECT * FROM `haal` WHERE `Haaletaja_ID` = ? AND `Kandidaadi_ID` = ? LIMIT 1;", array($this->user_id, 2));
		if(!$vote_check) {
			throw new Test_Exception("Didn't find successful registartion confirmation!", array("vote_check" => $vote_check));
		}
	}
}

class Testing_Exception extends Exception {
	public $data;

	public function __construct($message='', $data=array(), $code=0, $previous=NULL) {
		parent::__construct($message, $code, $previous);
		$this->data = $data;
	}
}