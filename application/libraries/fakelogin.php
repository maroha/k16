<?php
class FakeLogin extends \Laravel\Auth\Drivers\Driver {

	public function retrieve($id) {
		if($id > 0)
			return array("id" => $id);
	}

	public function attempt($arguments = array()) {
		return true;
	}
}