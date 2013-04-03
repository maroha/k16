<?php

class Add_Yksik {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::query("INSERT INTO `partei` (`ID`, `Nimetus`) VALUES ('11', 'Üksikkandidaat');");
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::query("DELETE FROM `partei` WHERE (`Nimetus` = 'Üksikkandidaat' COLLATE utf8_bin);");
	}

}