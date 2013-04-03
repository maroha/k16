<?php

class Disable_Number_And_Give_Pics {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::query("ALTER TABLE `kandidaat` DROP `Number`, COMMENT='';");
		DB::query("UPDATE `kandidaat` SET `Pilt` = ? WHERE `Pilt` = NULL", array("img/isik_isikuline.jpg"));
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::query("ALTER TABLE `kandidaat` ADD `Number` int(11) NULL AFTER `ID`, COMMENT='';");
		DB::query("UPDATE `kandidaat` SET `Pilt` = NULL");
	}

}