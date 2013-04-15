<?php

class Fb_Id {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::query("ALTER TABLE `haaletaja` CHANGE `Isikukood` `Fb_Id` varchar(20) COLLATE 'utf8_general_ci' NULL AFTER `Perekonnanimi`;");
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::query("ALTER TABLE `haaletaja` CHANGE `Fb_Id` `Isikukood` varchar(11) COLLATE 'utf8_general_ci' NULL AFTER `Perekonnanimi`;");
	}

}