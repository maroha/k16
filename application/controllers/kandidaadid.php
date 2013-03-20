<?php

class Kandidaadid_Controller extends Base_Controller {

	public $restful = true;
	public $layout = "name: layout";

	public function get_index() {
		$this->layout->content = View::make("kandidaadid.list");
		$this->layout->javascript = array("candidates", "list");
		$this->layout->menu_item = "kandidaadid";
	}

	public function get_haaleta()
	{
		$this->layout->content = View::make("kandidaadid.list_haaleta");
		$this->layout->javascript = array("candidates", "vote");
		$this->layout->menu_item = "haaleta";
	}

	public function get_info($kandidaat_id)
	{
		$this->layout->content = View::make("kandidaadid.info");
		$this->layout->javascript = array("candidates", "view");
		$this->layout->menu_item = "kandidaadid";
	}

	public function get_registeeri()
	{
		//TODO: add Auth filter
		$this->layout->content = View::make("kandidaadid.registeeri");
		$this->layout->javascript = array("candidates", "register");
		$this->layout->menu_item = "kandidaadid";
	}
	public function post_registeeri()
	{
		//TODO: Everything
		return Redirect::to("kandidaadid/registeeri");
	}
}