<?php include "header.php"; ?>

		<div class="main-container">
			<div class="main wrapper clearfix">
				<article>
					<header>
						<p><a href="index.php">&#60;&#60; Tagasi pealehele</a></p>
						<p>Soovid kandideerida? <a href="#">Logi sisse &#62;</a></p>
					</header>
					<section>
						<form action="">
							<input type="text" name="kandidaadiNimi" placeholder="Sisesta kandidaadi nimi">
							<select name="valimisringkond">
								<option value="koikValimisringkonnad">Kõik valimisringkonnad</option>
								<option value="valimisringkond1">Valimisringkond 1</option>
								<option value="valimisringkond2">Valimisringkond 2</option>
							</select>
							<select name="partei">
								<option value="koikParteid">Kõik parteid</option>
								<option value="partei1">Partei 1</option>
								<option value="partei2">Partei 2</option>
							</select>
							<input id="otsiKandidaati" type="submit" value="Otsi" />
						</form>
						<table>
							<tr>
								<th>Number</th>
								<th>Nimi</th>
								<th>Ringkond</th>
								<th>Partei</th>
							</tr>
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
						</table>
					</section>
					
			</div> <!-- #main -->
		</div> <!-- #main-container -->

		<?php include "footer.php"; ?>
