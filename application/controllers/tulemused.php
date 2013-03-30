<?php

class Tulemused_Controller extends Base_Controller {

	public $restful = true;
	public $layout = "name: layout";

	public function parteid_and_ringkonnad()
	{
		$parteid = DB::query("SELECT * FROM `partei`");
		$ringkonnad = DB::query("SELECT * FROM `valimisringkond`");
		return array($parteid, $ringkonnad);
	}


	public function get_index() {
		list($parteid, $ringkonnad) = $this->parteid_and_ringkonnad();
		$this->layout->content = View::make("tulemused", array(
			"parteid" => $parteid,
			"ringkonnad" => $ringkonnad
		));
		$this->layout->javascript = array("results");
		$this->layout->menu_item = "tulemused";
	}
}