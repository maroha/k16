<?php

class Just_In_Case_Optimisations {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Question: What do you call last second optimisations?
		DB::query("ALTER TABLE `haaletaja`
ADD INDEX `Eesnimi_Perekonnanimi` (`Eesnimi`, `Perekonnanimi`),
ADD INDEX `Eesnimi` (`Eesnimi`);");
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		// Answer: The part where you forget reverting migrations
		DB::query("ALTER TABLE `haaletaja`
DROP INDEX `Eesnimi_Perekonnanimi`,
DROP INDEX `Eesnimi`;");
	}

}