<article>
	<header>
		<p class="hide-printer"><a href="index.php">&#60;&#60; Pealehele</a></p>
		<h1>Hääleta</h1>
		@if(Auth::check())
			<p class="hide-printer">Hääletamiseks kliki sobiva kandidaadi peale.</p>
		@else
			<p class="hide-printer">Soovid hääletada? <a href="{{ url("login") }}">Logi sisse &#62;</a></p>
		@endif
	</header>
	<section>
		<form id="search-form" action="">
			<input type="text" name="name" placeholder="Sisesta kandidaadi nimi" id="search-name">
			<select name="region" id="sorting">
				<option value="-1">Kõik valimisringkonnad</option>
				@foreach($ringkonnad as $ringkond)
					<option value="{{ $ringkond->id }}">{{ e($ringkond->nimetus) }}</option>
				@endforeach
			</select>
			<select name="party">
				<option value="-1">Kõik parteid</option>
				@foreach($parteid as $partei)
					<option value="{{ $partei->id }}">{{ e($partei->nimetus) }}</option>
				@endforeach
			</select>
			<input type="submit" value="Otsi" />
		</form>
		<table id="candidate-list">
			<thead>
				<tr>
					<th>Number</th>
					<th>Nimi</th>
					<th>Ringkond</th>
					<th>Partei</th>
				</tr>
			</thead>
			<tbody>
			@foreach($kandidaadid as $kandidaat)
					<tr>
						<td>{{ $kandidaat->id }}</td>
						<td>
							<a href="{{ url("kandidaadid/info/".$kandidaat->id) }}">{{ $kandidaat->eesnimi }} {{ $kandidaat->perekonnanimi }}</a>
						</td>
						<td>{{ $kandidaat->valimisringkonna_nimi }}</td>
						<td>{{ $kandidaat->partei_nimi }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</section>
</article>