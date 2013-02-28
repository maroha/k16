<form id="search-form" action="">
	<input type="text" name="name" disabled title="Väljalülitatud kuna vajab serveri-poolset otsingut (või täielikku kandidaatide nimekirja)" placeholder="Sisesta kandidaadi nimi">
	<select name="region" id="sorting">
		<option value="0">Kõik valimisringkonnad</option>
		<option value="1">Valimisringkond 1</option>
		<option value="2">Valimisringkond 2</option>
	</select>
	<select name="party">
		<option value="0">Kõik parteid</option>
		<option value="1">Partei 1</option>
		<option value="2">Partei 2</option>
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
		<tr>
			<td><a href="kandidaadi_vaade.php">0001</a></td>
			<td><a href="kandidaadi_vaade.php">Eesnimi Perekonnanimi</a></td>
			<td><a href="kandidaadi_vaade.php">Valimisringkond</a></td>
			<td><a href="kandidaadi_vaade.php">Partei</a></td>
		</tr>
		<tr>
			<td>0002</td>
			<td>Eesnimi Perekonnanimi</td>
			<td>Valimisringkond</td>
			<td>Partei</td>
		</tr>
	</tbody>
</table>