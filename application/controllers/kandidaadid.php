<?php

class Kandidaadid_Controller extends Base_Controller {

	public $restful = true;
	public $layout = "name: layout";

	public function __construct()
	{
		parent::__construct();
		$this->filter("before", "auth")->only("registeeri");
	}

	public function parteid_and_ringkonnad()
	{
		$ringkonnad = DB::query("SELECT * FROM `valimisringkond`");
		$parteid = DB::query("SELECT * FROM `partei`");
		return array($ringkonnad, $parteid);
	}

	public function get_index() {
		list($ringkonnad, $parteid) = $this->parteid_and_ringkonnad();
		$this->layout->content = View::make("kandidaadid.list", array("ringkonnad" => $ringkonnad, "parteid" => $parteid));
		$this->layout->javascript = array("candidates", "list");
		$this->layout->menu_item = "kandidaadid";
	}

	public function get_haaleta()
	{
		list($ringkonnad, $parteid) = $this->parteid_and_ringkonnad();
		$this->layout->content = View::make("kandidaadid.list_haaleta", array("ringkonnad" => $ringkonnad, "parteid" => $parteid));
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
		list($ringkonnad, $parteid) = $this->parteid_and_ringkonnad();
		$this->layout->content = View::make("kandidaadid.registeeri", array("ringkonnad" => $ringkonnad, "parteid" => $parteid));
		$this->layout->javascript = array("candidates", "register");
		$this->layout->menu_item = "kandidaadid";
	}
	public function post_registeeri()
	{
		//TODO: Everything
		return Redirect::to("kandidaadid/registeeri");
	}
}