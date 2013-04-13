<article>
	<header>
		<p class="hide-printer"><a href="{{ url("/") }}">&#60;&#60; Pealehele</a></p>
		<h1>Tulemused</h1>
	</header>
	<section>
		<form method="GET" action="{{ url("tulemused") }}" id="tulemused-filter">
			<label for="filter-region">Piirkond:</label>
			<select id="filter-region" name="region">
				@if($current["region"] == -1)
					<option value="-1" selected>Kõik valimisringkonnad</option>
				@else
					<option value="-1">Kõik valimisringkonnad</option>
				@endif
				@foreach($ringkonnad as $ringkond)
					@if($current["region"] == $ringkond->id)
						<option value="{{ $ringkond->id }}" selected>{{ e($ringkond->nimetus) }}</option>
					@else
						<option value="{{ $ringkond->id }}">{{ e($ringkond->nimetus) }}</option>
					@endif
				@endforeach
			</select>
			<label for="filter-party">Partei:</label>
			<select id="filter-party" name="party">
				@if($current["party"] == -1)
					<option value="-1" selected>Kõik parteid</option>
				@else
					<option value="-1">Kõik parteid</option>
				@endif
				@foreach($parteid as $partei)
					@if($current["party"] == $partei->id)
						<option value="{{ $partei->id }}" selected>{{ e($partei->nimetus) }}</option>
					@else
						<option value="{{ $partei->id }}">{{ e($partei->nimetus) }}</option>
					@endif
				@endforeach
			</select>
			<label>
				@if($current["type"] == "party")
					<input type="radio" name="type" value="party" checked="checked" />
				@else
					<input type="radio" name="type" value="party" />
				@endif
				 Partei
			</label>
			<label>
				@if($current["type"] == "person")
					<input type="radio" name="type" value="person" checked="checked" />
				@else
					<input type="radio" name="type" value="person" />
				@endif
				 Isik
			</label>
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
						@if($current["type"] == "party")
						<th id="results-type">Partei</th>
						@else
						<th id="results-type">Isik</th>
						@endif
						<th>Tulemus</th>
					</tr>
				</thead>
				<tbody id="results-table">
					@foreach($results as $result)
						<tr>
							<th>{{ $result->nimi }}</th>
							<td>
								<div class="result-row" style="width: {{ $result->percent }}%;"></div>
								<div class="result-text">{{ "{$result->votes} ({$result->percent}%)" }}</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</section>
</article>
