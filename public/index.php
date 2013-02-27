<?php include "header.php"; ?>

		<div class="main-container">
			<div class="main wrapper clearfix">
				<article class="head-unit">
					<h1>Teretulemast valimistele!</h1>
					<?php if($_SESSION["logged_in"]) { ?>
					<p><a href="haaleta.php">Hääleta &rsaquo;</a></p>
					<p><a href="kandidaadi_registreerimine.php">Kandideeri &rsaquo;</a></p>
					<p><a href="tulemused.php">Vaata tulemusi &rsaquo;</a></p>
					<?php } else { ?>
					<p>Hääletamiseks või kandideerimiseks <a href="user_switch.php">logi sisse &rsaquo;</a></p>
					<p><a href="tulemused.php">Tulemused</a> on nähtavad ilma sisse logimata.</p>
					<?php } ?>
				</article>

			</div> <!-- #main -->
		</div> <!-- #main-container -->

		<?php include "footer.php"; ?>
