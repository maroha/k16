<?php

class Kandidaadid_Controller extends Base_Controller {

	public $restful = true;
	public $layout = "name: layout";

	public function get_index() {
		$this->layout->content = View::make("kandidaadid");
		$this->layout->javascript = array("candidates", "list");
		$this->layout->menu_item = "kandidaadid";
	}
}