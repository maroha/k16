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
		$parteid = DB::query("SELECT * FROM `partei`");
		$ringkonnad = DB::query("SELECT * FROM `valimisringkond`");
		return array($parteid, $ringkonnad);
	}

	public function get_index() {
		list($parteid, $ringkonnad) = $this->parteid_and_ringkonnad();
		$sql = <<<EOL
SELECT k.ID, k.Number, h.Eesnimi, h.Perekonnanimi,
p.Nimetus AS Partei_Nimi,
r.Nimetus AS Valimisringkonna_nimi
FROM `kandidaat` AS k
LEFT JOIN `haaletaja` AS h ON k.Haaletaja_ID = h.ID
LEFT JOIN `valimisringkond` AS r ON k.Valimisringkonna_ID = r.Id
LEFT JOIN `partei` AS p ON k.Partei_ID = p.Id
EOL;
		$kandidaadid = DB::query($sql);
		$this->layout->content = View::make("kandidaadid.list", array(
			"ringkonnad" => $ringkonnad, "parteid" => $parteid, "kandidaadid" => $kandidaadid
		));
		$this->layout->javascript = array("candidates", "list");
		$this->layout->menu_item = "kandidaadid";
	}

	public function get_haaleta()
	{
		list($parteid, $ringkonnad) = $this->parteid_and_ringkonnad();
		$sql = <<<EOL
SELECT k.ID, k.Number, h.Eesnimi, h.Perekonnanimi,
p.Nimetus AS Partei_Nimi,
r.Nimetus AS Valimisringkonna_nimi
FROM `kandidaat` AS k
LEFT JOIN `haaletaja` AS h ON k.Haaletaja_ID = h.ID
LEFT JOIN `valimisringkond` AS r ON k.Valimisringkonna_ID = r.Id
LEFT JOIN `partei` AS p ON k.Partei_ID = p.Id
EOL;
		$kandidaadid = DB::query($sql);
		$this->layout->content = View::make("kandidaadid.list_haaleta", array(
			"ringkonnad" => $ringkonnad, "parteid" => $parteid, "kandidaadid" => $kandidaadid
		));
		$this->layout->javascript = array("candidates", "vote");
		$this->layout->menu_item = "haaleta";
	}

	public function get_info($kandidaat_id)
	{
list($parteid, $ringkonnad) = $this->parteid_and_ringkonnad();
		$sql = <<<EOL
SELECT k.ID, k.Number, h.Eesnimi, h.Perekonnanimi, k.Sunnikoht, k.Haridus, k.Akadeemiline_kraad, k.Elukutse, k.Tookoht, p.Nimetus as partei_nimetus, k.Email, k.Telefoninumber
FROM  `kandidaat` AS k
LEFT JOIN  `haaletaja` AS h ON k.Haaletaja_ID = h.ID
LEFT JOIN  `partei` AS p ON k.Partei_ID = p.ID 
WHERE k.ID = ?
EOL;
		$kandidaat = DB::query($sql, array($kandidaat_id));
		$this->layout->content = View::make("kandidaadid.info", array("kandidaat" => $kandidaat[0]));
		$this->layout->javascript = array("candidates", "view");
		$this->layout->menu_item = "kandidaadid";
	}

	public function get_otsi()
	{
		$sql = <<<EOL
SELECT k.ID, k.Number, h.Eesnimi, h.Perekonnanimi,
p.Nimetus AS Partei_Nimi,
r.Nimetus AS Valimisringkonna_nimi
FROM `kandidaat` AS k
LEFT JOIN `haaletaja` AS h ON k.Haaletaja_ID = h.ID
LEFT JOIN `valimisringkond` AS r ON k.Valimisringkonna_ID = r.Id
LEFT JOIN `partei` AS p ON k.Partei_ID = p.Id
EOL;
		$argumendid = array();
		$name = Input::get('name');
		if(isset($name)) {
			$sql .= " WHERE h.Eesnimi LIKE ? OR h.Perekonnanimi LIKE ?";
			$argumendid[] = $name."%";
			$argumendid[] = $name."%";
		}
		$region = Input::get('region');
		if(isset($region)) {
			if(count($argumendid)==0) {
				$sql .= " WHERE k.Valimisringkonna_ID = ?";
			} else {
				$sql .= " AND k.Valimisringkonna_ID = ?";
			}
			$argumendid[] = $region;
		}
		$party = Input::get('party');
		if(isset($party)) {
			if(count($argumendid)==0) {
				$sql .= " WHERE k.Partei_ID = ?";
			} else {
				$sql .= " AND k.Partei_ID = ?";
			}
			$argumendid[] = $party;
		}
		//dd($sql);
		$kandidaadid = DB::query($sql, $argumendid);
		//dd($kandidaadid);
		return Response::json($kandidaadid);
	}

	public function get_registeeri()
	{
		list($parteid, $ringkonnad) = $this->parteid_and_ringkonnad();
		$this->layout->content = View::make("kandidaadid.registeeri", array("ringkonnad" => $ringkonnad, "parteid" => $parteid));
		$this->layout->javascript = array("candidates", "register");
		$this->layout->menu_item = "kandidaadid";
	}
	public function post_registeeri()
	{
		$sql = <<<EOT
INSERT INTO `kandidaat` (`Partei_ID`, `Valimisringkonna_ID`, `Sunnikoht`, `Elukohaaadress`, `Haridus`, `Akadeemiline_kraad`, `Elukutse`, `Tookoht`, `Telefoninumber`, `Email`, `Pilt`, `Haaletaja_ID`) VALUES
		(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
EOT;
		$sunnikoht = Input::get("birthplace");
		$elukohaaadress = Input::get("address");
		$partei_id = Input::get("party");
		$valimisringkonna_id = Input::get("piirkond");
		$haridus = Input::get("haridus");
		
		$akadeemiline_kraad = Input::get("academicdegree");
		$elukutse = Input::get("occupation");
		$tookoht = Input::get("work");
		$telefoninumber = Input::get("phone");
		$email = Input::get("email");
		$pilt = Input::get("picture");
		
		$vaartused = array($partei_id, $valimisringkonna_id, $sunnikoht, $elukohaaadress, $haridus, $akadeemiline_kraad, $elukutse, $tookoht, $telefoninumber, $email, $pilt, Auth::user()->id);
		DB::query($sql, $vaartused);
		//TODO: Everything
		return Redirect::to("kandidaadid/registeeri");
	}
}