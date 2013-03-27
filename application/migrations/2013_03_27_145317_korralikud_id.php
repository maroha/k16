<?php

class Korralikud_Id {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::query("ALTER TABLE `haaletaja` CHANGE `Isikukood` `Isikukood` varchar(11) NULL AFTER `Perekonnanimi`, COMMENT='';");
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::query("ALTER TABLE `haaletaja` CHANGE `Isikukood` `Isikukood` INT(11) NULL AFTER `Perekonnanimi`, COMMENT='';");
	}

}