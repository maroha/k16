<article>
	<header>
		<p class="hide-printer"><a href="{{ url("/") }}">&#60;&#60; Pealehele</a></p>
		<h1>Tulemused</h1>
	</header>
	<section>
		<form>
			<label for="filter-region">Piirkond:</label>
			<select id="filter-region" name="region">
				<option value="-1">Kõik valimisringkonnad</option>
				@foreach($ringkonnad as $ringkond)
					<option value="{{ $ringkond->id }}">{{ e($ringkond->nimetus) }}</option>
				@endforeach
			</select>
			<label for="filter-party">Partei:</label>
			<select id="filter-party" name="party">
				<option value="-1">Kõik parteid</option>
				<option value="0">Üksikkandidaat</option>
				@foreach($parteid as $partei)
					<option value="{{ $partei->id }}">{{ e($partei->nimetus) }}</option>
				@endforeach
			</select>
			<label><input type="radio" name="type" value="party" checked="checked" /> Partei</label>
			<label><input type="radio" name="type" value="person" /> Isik</label>
			<button id="submit" type="submit">Värskenda</button>
		</form>
		<div class="row-left">
			<img src="img/map.png" alt="Kaart" />
			<small class="attrib">Kaart <a href="http://commons.wikimedia.org/wiki/File:Estonia_location_map.svg">CC-by-sa Wikipedia</a></small>
		</div>
		<div class="row-right">
			<table class="sortable">
				<thead>
					<tr>
						<th>Partei</th>
						<th>Tulemus</th>
					</tr>
				</thead>
				<tbody>
					@foreach($results as $result)
						<tr>
							<th>{{ $result->nimi }}</th>
							<td>
								<div class="result-row" style="width: 10%;"></div>
								<div class="result-text">{{ $result->votes }}</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</section>
</article>
