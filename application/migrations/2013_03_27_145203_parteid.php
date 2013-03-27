<?php

class Parteid {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		$sql = "INSERT INTO `partei` (`ID`, `Nimetus`) VALUES (NULL, 'Partei 1'), (NULL, 'Partei 2'), (NULL, 'Partei 3'), (NULL, 'Partei 4'), (NULL, 'Partei 5'), (NULL, 'Partei 6'), (NULL, 'Partei 7'), (NULL, 'Partei 8'), (NULL, 'Partei 9'), (NULL, 'Partei 10');";
		DB::query($sql);
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::query("DELETE FROM `partei`;");
	}

}