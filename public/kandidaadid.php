<?php $javascript = array("candidates", "list"); $menu_item = "kandidaadid"; include "header.php"; ?>

		<div class="main-container">
			<div class="main wrapper clearfix">
				<article>
					<header>
						<p class="hide-printer"><a href="index.php">&#60;&#60; Pealehele</a></p>
						<h1>Kandidaadid</h1>
						<?php if($_SESSION["logged_in"]) { ?>
						<p class="hide-printer"><a href="kandidaadi_registreerimine.php">Kandideeri &rsaquo;</a></p>
						<?php } else { ?>
						<p class="hide-printer">Soovid kandideerida? <a href="user_switch.php">Logi sisse &#62;</a></p>
						<?php } ?>
					</header>
					<section>
						<?php include "kandidaadid_tabel.php" ?>
					</section>

			</div> <!-- #main -->
		</div> <!-- #main-container -->

		<?php include "footer.php"; ?>
