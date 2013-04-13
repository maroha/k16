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
	public function leiaKandidaadid($otsi = array()) {
		$sql = <<<EOL
SELECT k.ID, h.Eesnimi, h.Perekonnanimi,
p.Nimetus AS Partei_Nimi,
r.Nimetus AS Valimisringkonna_nimi
FROM `kandidaat` AS k
LEFT JOIN `haaletaja` AS h ON k.Haaletaja_ID = h.ID
LEFT JOIN `valimisringkond` AS r ON k.Valimisringkonna_ID = r.Id
LEFT JOIN `partei` AS p ON k.Partei_ID = p.Id
EOL;
		$argumendid = array();
		if(isset($otsi["name"])) {
			$nime_osad = explode(" ", $otsi["name"]);
			foreach ($nime_osad as $osa) {
				if(count($argumendid) == 0) {
					// Ainult eesnimi
					$sql .= " WHERE h.Eesnimi LIKE ?";
					$argumendid[] = $osa."%";
				} elseif (count($argumendid) == 1) {
					// Teine osa eesnimest või esimene osa perekonnanimest
					$sql .= " AND (h.Eesnimi LIKE ? OR h.Perekonnanimi LIKE ?)";
					$argumendid[] = "%".$osa."%";
					$argumendid[] = $osa."%";
				} else {
					// Teine osa eesnimest või perekonnanimest
					$sql .= " AND (h.Eesnimi LIKE ? OR h.Perekonnanimi LIKE ?)";
					$argumendid[] = "%".$osa."%";
					$argumendid[] = "%".$osa."%";
				}
			}
		}
		if(isset($otsi["region"]) && $otsi["region"] != -1) {
			if(count($argumendid)==0) {
				$sql .= " WHERE k.Valimisringkonna_ID = ?";
			} else {
				$sql .= " AND k.Valimisringkonna_ID = ?";
			}
			$argumendid[] = $otsi["region"];
		}
		if(isset($otsi["party"]) && $otsi["party"] != -1) {
			if(count($argumendid)==0) {
				$sql .= " WHERE k.Partei_ID = ?";
			} else {
				$sql .= " AND k.Partei_ID = ?";
			}
			$argumendid[] = $otsi["party"];
		}
		$sql .= " ORDER BY k.ID ASC";
		return DB::query($sql, $argumendid);
	}

	public function get_index() {
		list($parteid, $ringkonnad) = $this->parteid_and_ringkonnad();
		$kandidaadid = $this->leiaKandidaadid(Input::all());
		$this->layout->content = View::make("kandidaadid.list", array(
			"ringkonnad" => $ringkonnad, "parteid" => $parteid, "kandidaadid" => $kandidaadid
		));
		$this->layout->javascript = array("candidates", "list");
		$this->layout->menu_item = "kandidaadid";
	}

	public function get_haaleta()
	{
		list($parteid, $ringkonnad) = $this->parteid_and_ringkonnad();
		$kandidaadid = $this->leiaKandidaadid(Input::all());

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
SELECT k.ID, h.Eesnimi, h.Perekonnanimi, k.Sunnikoht, k.Haridus, k.Akadeemiline_kraad, k.Elukutse, k.Tookoht, p.Nimetus as partei_nimetus, k.Email, k.Telefoninumber, k.Pilt
FROM  `kandidaat` AS k
LEFT JOIN  `haaletaja` AS h ON k.Haaletaja_ID = h.ID
LEFT JOIN  `partei` AS p ON k.Partei_ID = p.ID
WHERE k.ID = ?
LIMIT 1
EOL;
		$kandidaat = DB::first($sql, array($kandidaat_id));
		if(!$kandidaat) {
			Session::flash("message", "Kandidaati ei leitud!");
			return Redirect::to("kandidaadid");
		}
		// Check if user has already voted
		$juba = false;
		if(Auth::check()) {
			$votecheck = DB::only("SELECT COUNT(*) AS voted FROM `haal` WHERE `Haaletaja_ID` = ?", array(Auth::user()->id));
			if($votecheck > 0) {
				$juba = true;
			}
		}
		$this->layout->content = View::make("kandidaadid.info", array("kandidaat" => $kandidaat, "juba_haaletanud" => $juba));
		$this->layout->javascript = array("candidates", "view");
		$this->layout->menu_item = "kandidaadid";
	}
	public function get_autocomplete() {
		$otsi = Input::get("q");
		$sql = <<<EOL
SELECT k.ID, h.Eesnimi, h.Perekonnanimi
FROM `kandidaat` AS k
LEFT JOIN `haaletaja` AS h ON k.Haaletaja_ID = h.ID
WHERE h.Eesnimi LIKE ?
ORDER BY h.Eesnimi ASC
EOL;
		$results = DB::query($sql, array($otsi."%"));
		$output = "";
		foreach ($results as $person) {
			$output .= "{$person->eesnimi} {$person->perekonnanimi}\n";
		}
		return Response::make($output);
	}

	public function get_otsi()
	{
		$kandidaadid = $this->leiaKandidaadid(Input::all());
		//dd($kandidaadid);
		return Response::json($kandidaadid);
	}
	public function post_haaleta() {
		$kandidaat = Input::get("kandidaat");
		// Kontrolli kandidaati
		$kontroll = DB::only("SELECT COUNT(*) as count FROM `kandidaat` WHERE `ID` = ?", array($kandidaat));
		if($kontroll == 0) {
			return Response::error('404');
		}
		// Kontrolli duplikaati.... vüi kohe lihtsalt kustuta ära
		DB::query("DELETE FROM `haal` WHERE (`Haaletaja_ID` = ?)", array(Auth::user()->id));
		// Haaleta
		$success = DB::query("INSERT INTO `haal` (`Aeg`, `Haaletaja_ID`, `Kandidaadi_ID`) VALUES (?, ?, ?);", array(date('Y-m-d H:i:s'), Auth::user()->id, $kandidaat));
		if($success) {
			Session::flash("message", "Teie hääl on salvestatud!");
			Command::run(array('liveserver:update'));
		} else {
			Session::flash("message", "Hääle salvestamisel tekkis viga! Palun proovige hiljem uuesti :(");
		}
		return Redirect::home();
	}

	public function get_registeeri()
	{
		$kontroll = DB::only("SELECT COUNT(*) as count FROM `kandidaat` WHERE `Haaletaja_ID` = ?", array(Auth::user()->id));
		if($kontroll) {
			Session::flash("message", "Te olete juba lisatud kandidaadina!");
			return Redirect::to("kandidaadid");
		}

		list($parteid, $ringkonnad) = $this->parteid_and_ringkonnad();
		$this->layout->content = View::make("kandidaadid.registeeri", array("ringkonnad" => $ringkonnad, "parteid" => $parteid));
		$this->layout->javascript = array("candidates", "register");
		$this->layout->menu_item = "kandidaadid";
	}
	public function post_registeeri()
	{
		$kontroll = DB::only("SELECT COUNT(*) as count FROM `kandidaat` WHERE `Haaletaja_ID` = ?", array(Auth::user()->id));
		if($kontroll) {
			Session::flash("message", "Te olete juba lisatud kandidaadina!");
			return Redirect::to("kandidaadid");
		}

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

		// picture
		$pilt = Input::file("picture");
		$pilturl = null;
		if($pilt and $pilt['tmp_name'] and $pilt['size'] < 524288) { // max 512 KB
			$extensions = array('jpg', 'png', 'gif');
			foreach ($extensions as $extension) {
				if(File::is($extension, $pilt['tmp_name'])) {
					$newid = DB::only("SELECT MAX(`ID`) FROM `kandidaat`;") + 1;
					$piltnimi = md5($newid).".{$extension}";
					Input::upload("picture", path("public")."img/uploads", $piltnimi);
					$pilturl = "img/uploads/".$piltnimi;
					break;
				}
			}
		}
		if(!$pilturl) {
			$pilturl = "img/isik_isikuline.png";
		}


		$vaartused = array($partei_id, $valimisringkonna_id, $sunnikoht, $elukohaaadress, $haridus, $akadeemiline_kraad, $elukutse, $tookoht, $telefoninumber, $email, $pilturl, Auth::user()->id);
		DB::query($sql, $vaartused);


		Session::flash("message", "Te olete lisatud kandidaadina!");
		return Redirect::to("kandidaadid");
	}
}