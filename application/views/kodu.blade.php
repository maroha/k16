<article class="head-unit">
	<h1>Teretulemast valimistele!</h1>
	@if(Auth::check())
		<p><a href="haaleta.php">Hääleta &rsaquo;</a></p>
		<p><a href="kandidaadi_registreerimine.php">Kandideeri &rsaquo;</a></p>
		<p><a href="tulemused.php">Vaata tulemusi &rsaquo;</a></p>
	@else
		<p>Hääletamiseks või kandideerimiseks <a href="{{ url("login") }}">logi sisse &rsaquo;</a></p>
		<p><a href="tulemused.php">Tulemused</a> on nähtavad ilma sisse logimata.</p>
	@endif
</article>
