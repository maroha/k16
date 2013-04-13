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
	public function results($filters = array("type" => "party")) {
		$selector = "";
		$leftjoin = "";
		$groupby = "";
		$where = array();
		$argumendid = array();

		// Filters
		if(isset($filters["region"])) {
			$where[] = "k.Valimisringkonna_ID = ?";
			$argumendid[] = $filters["region"];
		}
		if(isset($filters["party"])) {
			$where[] = "k.Partei_ID = ?";
			$argumendid[] = $filters["party"];
			// Force by person
			$type = "person";
		}
		// Create query of awesome
		if($filters["type"] == "person") {
			$selector = "k.ID, CONCAT(u.Eesnimi, \" \", u.Perekonnanimi) AS Nimi, COUNT(h.ID) AS votes";
			$leftjoin = "LEFT JOIN haaletaja AS u ON k.Haaletaja_ID = u.ID";
			$groupby = "GROUP BY k.ID";
		} else { // == "party" (by default)
			$selector = "k.Partei_ID as ID, p.Nimetus as Nimi, COUNT(h.ID) AS votes";
			$leftjoin = "LEFT JOIN partei AS p ON k.Partei_ID = p.ID";
			$groupby = "GROUP BY k.Partei_ID";
		}

		if(count($where) > 0) {
			$where = " WHERE ".implode(" AND ", $where);
		} else {
			$where = "";
		}
		$sql = "SELECT {$selector} FROM kandidaat as k {$leftjoin} LEFT JOIN haal AS h ON h.Kandidaadi_ID = k.ID{$where} {$groupby} ORDER BY votes DESC";
		return DB::query($sql, $argumendid);
	}

	public function get_index() {
		$region = Input::get("region", -1);
		$party = Input::get("party", -1);
		$type = Input::get("type", "party");

		$filters = array("type" => $type);

		if($region > 0) {
			$filters["region"] = $region;
		}
		if($party > 0) {
			$filters["party"] = $party;
		}

		$results = $this->results($filters);

		// Calculate percentages
		$array_sum = function ($value, $result) {
			$value += $result->votes; return $value;
		};
		$total = array_reduce($results, $array_sum) ?: 1; // No division by 0 here good sir!
		$add_percentage = function (&$element, $key) use ($total) {
			$element->percent = round($element->votes / $total * 100, 2);
		};
		array_walk($results, $add_percentage);


		list($parteid, $ringkonnad) = $this->parteid_and_ringkonnad();
		$this->layout->content = View::make("tulemused", array(
			"parteid" => $parteid,
			"ringkonnad" => $ringkonnad,
			"results" => $results, "current" => array("region" => $region, "party" => $party, "type" => $type)
		));
		$this->layout->javascript = array("results");
		$this->layout->menu_item = "tulemused";
	}
	public function get_json() {
		$sql = "SELECT k.ID, CONCAT(u.Eesnimi, \" \", u.Perekonnanimi) AS Nimi, k.Partei_ID, p.Nimetus as Partei_Nimi, k.Valimisringkonna_ID, COUNT(h.ID) AS votes FROM kandidaat as k LEFT JOIN haaletaja AS u ON k.Haaletaja_ID = u.ID LEFT JOIN partei AS p ON k.Partei_ID = p.ID  LEFT JOIN haal AS h ON h.Kandidaadi_ID = k.ID GROUP BY k.ID";

		$results = DB::query($sql);
		return Response::json($results);
	}
}