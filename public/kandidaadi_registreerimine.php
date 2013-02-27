<?php include "header.php"; ?>

		<div class="main-container">
			<div class="main wrapper clearfix">
				<p><a href="kandidaadid.php">&#60;&#60; Kandidaatide nimekirja</a></p>
				<article>
					<div align="center">
					<h3>Kandidaadi registreerimine</h3>
					<TABLE>
					<TR><TD>Eesnimi</TD><TD><INPUT type="text" size="25" placeholder="Sisesta eesnimi" name="firstname">
					</TR>
					<TR><TD>Perekonnanimi</TD><TD><INPUT type="text" size="25" placeholder="Sisesta perekonnanimi" name="lastname">
					</TR>
					<TR><TD>Sünnikoht</TD><TD><INPUT type="text" size="25" placeholder="Linn, Riik" name="birthplace">
					</TR>
					<TR><TD>Isikukood</TD><TD><INPUT type="text" size="25" placeholder="Isikukood" name="idnum">
					</TR>
					<TR><TD>Erakond</TD><TD><INPUT type="text" size="25" placeholder="Erakond" name="party">
					</TR>
					 <TR><TD>Elukoha aadress</TD><TD><INPUT type="text" size="25" placeholder="Tänav, maja nr, korter, linn" name="adress">
					</TR>
					 <TR><TD>Haridus</TD><TD><INPUT type="text" size="25" placeholder="Sisesta haridus" name="education">
					</TR>
					<TR><TD>Teaduslik kraad</TD><TD><INPUT type="text" size="25" placeholder="Teaduslik kraad" name="sciensegrade">
					</TR>
					<TR><TD>Elukutse<TD><INPUT type="text" size="25" placeholder="Sisesta elukutse" name="ocupation">
					</TR>
					<TR><TD>Töökoht</TD><TD><INPUT type="text" size="25" placeholder="Sisesta töökoht" name="work">
					</TR>
					<TR><TD>E-mail</TD><TD><INPUT type="text" size="25" placeholder="E-mail" name="mail">
					</TR>
					<TR><TD>Kontakttelefon</TD><TD><INPUT type="text" size="25" placeholder="Number" name="number">
					</TR>
					<TR><TD>Pilt</TD><TD><input type="submit" name="button" id="button" value="Vali pilt">
					</TR>
					</TABLE>
					<input type="submit" name="button" id="button" value="Registreeru">
					</div>
				</article>

			</div> <!-- #main -->
		</div> <!-- #main-container -->

		<?php include "footer.php"; ?>
