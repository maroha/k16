<?php $menu_item = "haaleta"; include "header.php"; ?>

		<div class="main-container">
			<div class="main wrapper clearfix">
				<article>
					<header>
						<p><a href="index.php">&#60;&#60; Pealehele</a></p>
						<?php if($_SESSION["logged_in"]) { ?>
						<p>Hääletamiseks kliki sobiva kandidaadi peale.</p>
						<?php } else { ?>
						<p>Soovid hääletada? <a href="user_switch.php">Logi sisse &#62;</a></p>
						<?php } ?>
					</header>
					<section>
						<form action="">
							<input type="text" name="kandidaadiNimi" placeholder="Sisesta kandidaadi nimi">
							<select name="valimisringkond">
								<option value="0">Kõik valimisringkonnad</option>
								<option value="1">Valimisringkond 1</option>
								<option value="2">Valimisringkond 2</option>
							</select>
							<select name="partei">
								<option value="0">Kõik parteid</option>
								<option value="1">Partei 1</option>
								<option value="2">Partei 2</option>
							</select>
							<input id="otsiKandidaati" type="submit" value="Otsi" />
						</form>
						<table>
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
					</section>

			</div> <!-- #main -->
		</div> <!-- #main-container -->

		<?php include "footer.php"; ?>
