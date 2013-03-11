<?php $javascript = array("candidates", "register"); include "header.php"; ?>


		<script type="text/javascript" src="js/validform.js"></script>


		<div class="main-container">
			<div class="main wrapper clearfix">
				<p><a href="kandidaadid.php">&#60;&#60; Kandidaatide nimekirja</a></p>
				<article>
					<header>
						<h1>Kandidaadi registreerimine</h1>
					</header>
					<section>
						<form class="form-horizontal" action="/k16/public/kandidaadi_registreerimine.php" name="myForm" onsubmit="return(validate());">
							<div class="form-row">
								<label class="form-label" for="register-firstname">Eesnimi</label>
								<div class="form-field">
									<input type="text" size="30" name="firstname" id="register-firstname" />
								</div>
							</div>
							<div class="form-row">
								<label class="form-label" for="register-lastname">Perekonnanimi</label>
								<div class="form-field">
									<input type="text" size="30" name="lastname" id="register-lastname" />
								</div>
							</div>
							<div class="form-row">
								<label class="form-label" for="register-birthplace">Sünnikoht</label>
								<div class="form-field">
									<input type="text" size="30" name="birthplace" id="register-birthplace" />
								</div>
							</div>
							<div class="form-row">
								<label class="form-label" for="register-idnumber">Isikukood</label>
								<div class="form-field">
									<input type="text" size="30" name="idnumber" id="register-idnumber" />
									<p class="form-help">(Peidetud)</p>
								</div>
							</div>
							<div class="form-row">
								<label class="form-label" for="register-address">Elukoha aadress</label>
								<div class="form-field">
									<input type="text" size="30" name="address" id="register-address" />
									<p class="form-help">(Peidetud)</p>
								</div>
							</div>
							<div class="form-row">
								<label class="form-label" for="register-party">Erakond</label>
								<div class="form-field">
									<select name="party" id="register-party">
										<option value="0">Valige erakond</option>
										<option value="1">Erakond 1</option>
										<option value="2">Erakond 2</option>
									</select>
								</div>
							</div>
							<div class="form-row">
								<label class="form-label" for="register-party">Piirkond</label>
								<div class="form-field">
									<select name="piirkond" id="register-piirkond">
										<option value="0">Valige piirkond</option>
										<option value="1">Piirkond 1</option>
										<option value="2">Piirkond 2</option>
									</select>
								</div>
							</div>

							<div class="form-row">
								<label class="form-label" for="register-haridus">Haridus</label>
								<div class="form-field">
									<input type="text" size="30" name="haridus" id="register-haridus" />
								</div>
							</div>
							<div class="form-row">
								<label class="form-label" for="register-academic-degree">Akadeemiline kraad</label>
								<div class="form-field">
									<input type="text" size="30" name="academicdegree" id="register-academic-degree" />
								</div>
							</div>
							<div class="form-row">
								<label class="form-label" for="register-occupation">Elukutse</label>
								<div class="form-field">
									<input type="text" size="30" name="occupation" id="register-occupation" />
								</div>
							</div>
	
							<div class="form-row">
								<label class="form-label" for="register-work">Töökoht</label>
								<div class="form-field">
									<input type="text" size="30" name="work" id="register-work" />
								</div>
							</div>
							<div class="form-row">
								<label class="form-label" for="register-phone">Telefoninumber</label>
								<div class="form-field">
									<input type="text" size="30" name="phone" id="register-phone" />
								</div>
							</div>
														<div class="form-row">
								<label class="form-label" for="register-email">E-mail</label>
								<div class="form-field">
									<input type="text" size="30" name="email" id="register-email" />
								</div>
							</div>
							<div class="form-row">
								<label class="form-label" for="register-occupation">Pilt</label>
								<div class="form-field">
									<input type="file" name="picture" size="20" />
								</div>
							</div>
							<div class="form-buttons">
								
							
							
							 <tr>
								<td align="right"></td>
								<td><input type="submit" value="Salvestamine" /></td>
							</tr>		
							</div>
							
						</form>
					</section>
				</article>

			</div> <!-- #main -->
		</div> <!-- #main-container -->

		<?php include "footer.php"; ?>
