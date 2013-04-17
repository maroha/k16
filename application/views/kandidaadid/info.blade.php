<a href="{{ url("kandidaadid") }}">&#60;&#60; Kandidaatide nimekirja</a>
<h1>{{ e($kandidaat->eesnimi) }} {{ e($kandidaat->perekonnanimi) }}</h1>

<div class="left-sidebar">
	<img src="{{ asset($kandidaat->pilt) }}" />
	E-mail: {{ e($kandidaat->email) }} <br/>
	Telefon: {{ e($kandidaat->telefoninumber) }}
</div>
<div class="right-content">
	@if(Auth::check())
		@if(Auth::user()->valimisringkonna_id == $kandidaat->valimisringkonna_id)
			<form method="POST" action="{{ url("kandidaadid/haaleta") }}" class="vote-form float-right">
				<input type="hidden" name="kandidaat" value="{{ $kandidaat->id }}" />
				<button class="button-large">Hääleta</button>
				@if($juba_haaletanud)
					<p>Uuesti hääletamine tühistab teie eelmise hääle!</p>
				@endif
			</form>
		@else
			<span class="float-right">Erinev valimispiirkond</span>
		@endif
	@else
		<span class="float-right">Hääletamiseks peate olema sisselogitud</span>
	@endif
	<h2>Kandidaat number: #{{ e($kandidaat->id) }}</h2>
	<table>
		<tbody>
			<tr><!--
				<th>Sünniaeg</th>
				<td>1.10.1956</td>
			</tr>-->
			<tr>
				<th>Sünnikoht</th>
				<td>{{ e($kandidaat->sunnikoht) }}</td>
			</tr>
			<tr>
				<th>Haridus</th>
				<td>{{ e($kandidaat->haridus) }}</td>
			</tr>
			<tr>
				<th>Akadeemiline kraad</th>
				<td>{{ e($kandidaat->akadeemiline_kraad) }}</td>
			</tr>
			<tr>
				<th>Elukutse</th>
				<td>{{ e($kandidaat->elukutse) }}</td>
			</tr>
			<tr>
				<th>Töökoht</th>
				<td>{{ e($kandidaat->tookoht) }}</td>
			</tr>
			<tr>
				<th>Erakond</th>
				<td>{{ e($kandidaat->partei_nimetus) }}</td>
			</tr>
		</tbody>
	</table>
</div>