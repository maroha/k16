<?php

class Home_Controller extends Base_Controller {

	public $restful = true; // Require correct GET/POST/etc...
	public $layout = "name: layout";

	public function get_index() {
		$this->layout->content = View::make("kodu");
		$this->layout->javascript = array("home");
	}

	public function get_login() {
		// Need a (fake) user that gets logged in
		if(Request::ajax()) {
			$metadata = array("reload" => true);
			return Response::make(null, 200, array(
				"K16-META" => json_encode($metadata)
			));
		} else {
			return Redirect::back();
		}
	}

	public function get_logout() {
		Auth::logout();
		if(Request::ajax()) {
			$metadata = array("reload" => true);
			return Response::make(null, 200, array(
				"K16-META" => json_encode($metadata)
			));
		} else {
			return Redirect::back();
		}
	}
}