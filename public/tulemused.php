<?php include "header.php"; ?>

		<div class="main-container">
			<div class="main wrapper clearfix">
				<p><a href="index.php">&#60;&#60; Tagasi pealehele</a></p>
				<div class="row-left">
					<img src="img/map.png" alt="Kaart" />
					<small class="attrib">Kaart <a href="http://commons.wikimedia.org/wiki/File:Estonia_location_map.svg">CC-by-sa Wikipedia</a></small>
				</div>
				<div class="row-right">
					<form>
						<label>Piirkond:</label>
						<select id="filter-region" >
							<option value="0">Kogu Eesti</option>

						</select>
					</form>
					<table>
						<thead>
							<tr>
								<th>Partei</th>
								<th>Tulemus</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Partei</th>
								<td>
									<div class="result-row" style="width: 50%;"></div>
									<div class="result-text">500,000 (25%)</div>
								</td>
							</tr>
							<tr>
								<th>Partei</th>
								<td>
									<div class="result-row" style="width: 40%;"></div>
									<div class="result-text">400,000 (20%)</div>
								</td>
							</tr>
							<tr>
								<th>Partei</th>
								<td>
									<div class="result-row" style="width: 30%;"></div>
									<div class="result-text">300,000 (15%)</div>
								</td>
							</tr>
							<tr>
								<th>Partei</th>
								<td>
									<div class="result-row" style="width: 20%;"></div>
									<div class="result-text">200,000 (10%)</div>
								</td>
							</tr>
							<tr>
								<th>Partei</th>
								<td>
									<div class="result-row" style="width: 10%;"></div>
									<div class="result-text">100,000 (5%)</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div> <!-- #main -->
		</div> <!-- #main-container -->

		<?php include "footer.php"; ?>
