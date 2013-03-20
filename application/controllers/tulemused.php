<?php

class Tulemused_Controller extends Base_Controller {

	public $restful = true;
	public $layout = "name: layout";

	public function get_index() {
		$this->layout->content = View::make("tulemused");
		$this->layout->javascript = array("results");
		$this->layout->menu_item = "tulemused";
	}
}