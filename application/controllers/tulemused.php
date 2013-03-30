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
		/* SQL DUMP
		 *
		 * Kandidaadi kaupa:
		 * SELECT k.ID, CONCAT(u.Eesnimi, " ", u.Perekonnanimi) AS Nimi, COUNT(h.ID) AS votes FROM kandidaat AS k LEFT JOIN haaletaja AS u ON k.Haaletaja_ID = u.ID LEFT JOIN haal AS h ON h.Kandidaadi_ID = k.id GROUP BY k.ID ORDER BY votes DESC
		 *
		 * Partei kaupa:
		 * SELECT k.Partei_ID, p.Nimetus, COUNT(h.ID) AS votes FROM kandidaat AS k LEFT JOIN partei AS p ON k.Partei_ID = p.ID LEFT JOIN haal AS h ON h.Kandidaadi_ID = k.id GROUP BY k.Partei_ID ORDER BY votes DESC
		 */
		$selector = "";
		$leftjoin = "";
		$groupby = "";

		$type = "party";
		// $type = "person";
		if($type == "party") {
			$selector = "k.Partei_ID as ID, p.Nimetus as Nimi, COUNT(h.ID) AS votes";
			$leftjoin = "LEFT JOIN partei AS p ON k.Partei_ID = p.ID";
			$groupby = "GROUP BY k.Partei_ID";
		} else { // == "person" (by default)
			$selector = "k.ID, CONCAT(u.Eesnimi, \" \", u.Perekonnanimi) AS Nimi, COUNT(h.ID) AS votes";
			$leftjoin = "LEFT JOIN haaletaja AS u ON k.Haaletaja_ID = u.ID";
			$groupby = "GROUP BY k.ID";
		}

		$sql = "SELECT {$selector} FROM kandidaat as k {$leftjoin} LEFT JOIN haal AS h ON h.Kandidaadi_ID = k.ID {$groupby} ORDER BY votes DESC";
		$results = DB::query($sql);

		list($parteid, $ringkonnad) = $this->parteid_and_ringkonnad();
		$this->layout->content = View::make("tulemused", array(
			"parteid" => $parteid,
			"ringkonnad" => $ringkonnad,
			"results" => $results
		));
		$this->layout->javascript = array("results");
		$this->layout->menu_item = "tulemused";
	}
}