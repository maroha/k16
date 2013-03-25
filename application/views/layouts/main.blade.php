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
		<link rel="stylesheet" href="{{ asset("css/normalize.min.css") }}">
		<link rel="stylesheet" href="{{ asset("css/main.css") }}">

		<script src="{{ asset("js/vendor/modernizr-2.6.2-respond-1.1.0.min.js") }}"></script>
	</head>
	<body{{ isset($javascript[0]) ? " data-controller=\"{$javascript[0]}\"" : null }}{{ isset($javascript[1]) ? " data-action=\"{$javascript[1]}\"" : null }} data-url="{{ URL::base() }}">
		<!--[if lt IE 7]>
			<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->
		<div class="header-container">
			<div class="disclaimer">Antud rakendus ei ole mõeldud kasutamiseks reaalsetel valimistel<br />Tere loeng :D</div>
			<header class="wrapper clearfix">
				<h1 class="title"><a href="{{ url("/") }}">e-Valimised</a></h1>
				<div class="user">
					@if(Auth::check())
						Tere Isik Isikuline! &ndash; <a href="{{ url("logout") }}">logi välja &rsaquo;</a>
					@else
						Pole sisse loginud &ndash; <a href="{{ url("login") }}">logi sisse &rsaquo;</a>
					@endif
				</div>
				<nav class="clearfix">
					<ul>
						<li data-item="kandidaadid"{{ $menu_item == "kandidaadid" ? ' class="active"' : null }}><a href="{{ url("kandidaadid") }}">Kandidaadid</a></li>
						<li data-item="tulemused"{{ $menu_item == "tulemused" ? ' class="active"' : null }}><a href="{{ url("tulemused") }}">Tulemused</a></li>
						<li data-item="haaleta"{{ $menu_item == "haaleta" ? ' class="active"' : null }}><a href="{{ url("haaleta") }}">Hääleta</a></li>
					</ul>
				</nav>
			</header>
		</div>
		<div class="main-container">
			<div id="content" class="main wrapper clearfix">
				{{ $content }}
			</div> <!-- #main -->
		</div> <!-- #main-container -->
		<div class="footer-container">
			<footer class="wrapper">
				<h3>&#169; K16</h3>
				<div class="disclaimer">Rakenduses realiseeritud e-valimiste näide on realiseeritud <a href="https://courses.cs.ut.ee/2013/vl/spring/Main/Practices" target="_blank">tehnoloogiate praktiseerimise eesmärgil</a> ning ei ole mõeldud reaalsete e-valimiste korraldamiseks. Kokkulangevused reaalse e-valimiste protsessiga on juhuslikud. Kui valimismaania kestab rohkem kui 2 nädalat palun konsulteergie arsti või apteekriga.</div>
			</footer>
		</div>
		<div id="ajax-loader" class="ajax-loader">
			<img src="{{ asset("img/ajax-loader.gif") }}" alt="Laen..."/>
			Oota natukene, meil on asju leida...
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="{{ asset("js/vendor/jquery-1.9.1.min.js") }}"><\/script>')</script>

		<script src="{{ asset("js/main.js") }}"></script>
	</body>
</html>