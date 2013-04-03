<?php

class Haaled {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		$sql = "INSERT INTO `haal` (`Aeg`, `Haaletaja_ID`, `Kandidaadi_ID`) VALUES ('2000-01-01 00:01:00', 1, 2), ('2000-01-01 00:02:00', 2, 2), ('2000-01-01 00:03:00', 3, 2), ('2000-01-01 00:04:00', 4, 2), ('2000-01-01 00:05:00', 5, 2), ('2000-01-01 00:06:00', 6, 2), ('2000-01-01 00:07:00', 7, 2), ('2000-01-01 00:08:00', 8, 2), ('2000-01-01 00:09:00', 9, 2), ('2000-01-01 00:10:00', 9, 2);";
		DB::query($sql);
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::query("DELETE FROM `haal`;");
	}

}