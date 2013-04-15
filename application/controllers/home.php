<?php

class Home_Controller extends Base_Controller {

	public $restful = true; // Require correct GET/POST/etc...
	public $layout = "name: layout";

	public function get_index() {
		$this->layout->content = View::make("kodu");
		$this->layout->javascript = array("home");
	}

	public function get_login() {
		$facebook = new Facebook(array(
			'appId' => Config::get("facebook.app_id"),
			'secret' => Config::get("facebook.secret")
		));

		$loginUrl = $facebook->getLoginUrl(array(
			'display' => 'popup',
			'redirect_uri' => URL::to("login_callback")
		));
		if(Request::ajax()) {
			$metadata = array("redirect" => $loginUrl);
			return Response::make(null, 200, array(
				"K16-META" => json_encode($metadata)
			));
		} else {
			return Redirect::to($loginUrl);
		}
	}

	public function get_login_callback() {
		$facebook = new Facebook(array(
			'appId' => Config::get("facebook.app_id"),
			'secret' => Config::get("facebook.secret")
		));

		$session = $facebook->getUser();

		$me = null;

		if ($session) {
			try {
				$me = $facebook->api('/me?fields=picture,first_name,last_name,hometown');
			} catch (FacebookApiException $e) { }
		}

		if ($me) {
			// Facebook andis kasutaja
			$facebook_id = $me['id'];
			// Kontrolli andmebaasist kasutaja olemasolu
			$user = DB::first("SELECT * FROM `haaletaja` WHERE Fb_Id = ?", array($facebook_id));
			if($user) {
				// Logi kasutaja sisse
				dd($user);
				Auth::login($user->id);
				return Redirect::home();
			} else {
				$eesnimi = $me['first_name'];
				$perenimi = $me['last_name'];

				$valim_piirkond = rand(1,10);
				$sisestamine = DB::query("INSERT INTO `haaletaja` (Eesnimi, Perekonnanimi, Fb_Id, Valimisringkonna_ID) VALUES (?,?,?,?)", array($eesnimi,$perenimi,$facebook_id,$valim_piirkond));
				if($sisestamine) {
					$id = DB::connection()->pdo->lastInsertId();
					Auth::login($id);
				}
				return Redirect::home();
			}
		} else {
			// Facebook ei andnud kasutajat
			Session::flash("message", "Facebook ei andnud kasutajat :(");
			return Redirect::home();
		}
	}


	public function get_logout() {
		Auth::logout();
		if(Request::ajax()) {
			$metadata = array("reload" => true);
			return Response::make(null, 200, array(
				"K16-META" => json_encode($metadata)
			));
		} else {
			return Redirect::back();
		}
	}
}