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
			'appId' => '547130382005847',
			'secret' => 'de274483131977f10c7b4be1865a4095',
			'cookie' => true
		));
		$loginUrl = $facebook->getLoginUrl(array(
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
			'appId' => '547130382005847',
			'secret' => 'de274483131977f10c7b4be1865a4095',
			'cookie' => true
		));
	
		$session = $facebook->getUser();
		$me = null;

		if ($session) {
			try {
				$me = $facebook->api('/me?fields=picture,first_name,last_name,hometown');
			} catch (FacebookApiException $e) { }
		}
	
		dd($facebook->getUser());
		
		if ($me) {
			// Facebook andis kasutaja
	
			// Kontrolli andmebaasist kasutaja olemasolu
			if($user) {
				// Logi kasutaja sisse
				Auth::login($user->id);
				return Redirect::home();
			} else {
				$eesnimi = $me['first_name'];
				$perenimi = $me['last_name'];
				$facebook_id =$me['id'];
				$query = DB::only("SELECT Fb_Id FROM `haaletaja` WHERE Fb_Id = ?", array($facebook_id));
				
				if ($query = Null) {
					$valim_piirkond = rand(1,10);
					$sisestamine = DB::only("INSERT INTO `haaletaja` (Eesnimi, Perekonnanimi, Fb_Id, Valimisringkonna_ID) VALUES (?,?,?,?)", array($eesnimi,$perenimi,$facebook_id,$valim_piirkond));
					Auth::login($user->id);
					return Redirect::home();
				}
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