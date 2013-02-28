<?php $javascript = array("candidates", "vote"); $menu_item = "haaleta"; include "header.php"; ?>

		<div class="main-container">
			<div class="main wrapper clearfix">
				<article>
					<header>
						<p class="hide-printer"><a href="index.php">&#60;&#60; Pealehele</a></p>
						<h1>Hääleta</h1>
						<?php if($_SESSION["logged_in"]) { ?>
						<p class="hide-printer">Hääletamiseks kliki sobiva kandidaadi peale.</p>
						<?php } else { ?>
						<p class="hide-printer">Soovid hääletada? <a href="user_switch.php">Logi sisse &#62;</a></p>
						<?php } ?>
					</header>
					<section>
						<?php include "kandidaadid_tabel.php" ?>
					</section>

			</div> <!-- #main -->
		</div> <!-- #main-container -->

		<?php include "footer.php"; ?>
