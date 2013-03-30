<a href="{{ url("kandidaadid") }}">&#60;&#60; Kandidaatide nimekirja</a>
<h1>{{ e($kandidaat->eesnimi) }} {{ e($kandidaat->perekonnanimi) }}</h1>

<div class="left-sidebar">
	<img src="{{ asset("img/isik_isikuline.jpg") }}" />
	E-mail: {{ e($kandidaat->email) }} <br/>
	Telefon: {{ e($kandidaat->telefoninumber) }}
</div>
<div class="right-content">
	<button class="button-large float-right">Hääleta</button>
	<h2>Kandidaat number: #{{ e($kandidaat->number) }}</h2>  
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