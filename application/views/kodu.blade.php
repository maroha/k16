<article class="head-unit">
	<h1>Teretulemast valimistele!</h1>
	@if(Auth::check())
		<p><a href="{{ url("haaleta") }}">Hääleta &rsaquo;</a></p>
		<p><a href="{{ url("kandidaadid/registeeri") }}">Kandideeri &rsaquo;</a></p>
		<p><a href="{{ url("tulemused") }}">Vaata tulemusi &rsaquo;</a></p>
	@else
		<p>Hääletamiseks või kandideerimiseks <a href="{{ url("login") }}">logi sisse &rsaquo;</a></p>
		<p><a href="{{ url("tulemused") }}">Tulemused</a> on nähtavad ilma sisse logimata.</p>
	@endif
</article>
