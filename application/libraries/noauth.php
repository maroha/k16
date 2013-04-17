<?php
class NoAuth extends \Laravel\Auth\Drivers\Driver {

	public function retrieve($id) {
		return null;
	}

	public function attempt($arguments = array()) {
		return true;
	}
}