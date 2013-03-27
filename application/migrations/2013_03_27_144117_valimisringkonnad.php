<?php

class Valimisringkonnad {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		$sql = "INSERT INTO `valimisringkond` (`ID`, `Nimetus`) VALUES (NULL, 'Valimisringkond 1'), (NULL, 'Valimisringkond 2'), (NULL, 'Valimisringkond 3'), (NULL, 'Valimisringkond 4'), (NULL, 'Valimisringkond 5'), (NULL, 'Valimisringkond 6'), (NULL, 'Valimisringkond 7'), (NULL, 'Valimisringkond 8'), (NULL, 'Valimisringkond 9'), (NULL, 'Valimisringkond 10');";
		DB::query($sql);
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::query("DELETE FROM `valimisringkond ;");
	}

}