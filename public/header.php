<?php
if(!isset($menu_item)) {
	$menu_item = null;
}
if(!isset($javascript)) {
	$javascript = array();
}
session_start();
if(!isset($_SESSION["logged_in"])) {
	$_SESSION["logged_in"] = false;
}

if($ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	// Send metadata using headers
	// No X- prefix: http://tools.ietf.org/html/rfc6648
	$metadata = array("menuItem" => $menu_item, "javascript" => $javascript);
	header("K16-META: ".json_encode($metadata));
} else {
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>e-Valimised</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">

		<link href='http://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/normalize.min.css">
		<link rel="stylesheet" href="css/main.css">

		<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	</head>
	<body<?php if(isset($javascript[0])) echo " data-controller=\"{$javascript[0]}\""; if(isset($javascript[1])) echo " data-action=\"{$javascript[1]}\""; ?>>
		<!--[if lt IE 7]>
			<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->
		<div class="header-container">
			<header class="wrapper clearfix">
				<h1 class="title"><a href="index.php">e-Valimised</a></h1>
				<div class="user">
					<?php if($_SESSION["logged_in"]) { ?>
					Tere Isik Isikuline! &ndash; <a href="user_switch.php">logi välja &rsaquo;</a>
					<?php } else { ?>
					Pole sisse loginud &ndash; <a href="user_switch.php">logi sisse &rsaquo;</a>
					<?php } ?>
				</div>
				<nav class="clearfix">
					<ul>
						<li data-item="kandidaadid"<?php if($menu_item == "kandidaadid") echo ' class="active"'; ?>><a href="kandidaadid.php">Kandidaadid</a></li>
						<li data-item="tulemused"<?php if($menu_item == "tulemused") echo ' class="active"'; ?>><a href="tulemused.php">Tulemused</a></li>
						<li data-item="haaleta"<?php if($menu_item == "haaleta") echo ' class="active"'; ?>><a href="haaleta.php">Hääleta</a></li>
					</ul>
				</nav>
			</header>
		</div>
		<div class="main-container">
			<div id="content" class="main wrapper clearfix">
<?php } ?>