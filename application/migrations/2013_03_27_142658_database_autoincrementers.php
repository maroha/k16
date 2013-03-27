<?php

class Database_Autoincrementers {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::query("ALTER TABLE `haal` CHANGE `ID` `ID` int(11) NOT NULL AUTO_INCREMENT FIRST, COMMENT='';");
		DB::query("ALTER TABLE `haaletaja` CHANGE `ID` `ID` int(11) NOT NULL AUTO_INCREMENT FIRST, COMMENT='';");
		DB::query("ALTER TABLE `kandidaat` CHANGE `ID` `ID` int(11) NOT NULL AUTO_INCREMENT FIRST, COMMENT='';");
		DB::query("ALTER TABLE `partei` CHANGE `ID` `ID` int(11) NOT NULL AUTO_INCREMENT FIRST, COMMENT='';");
		DB::query("ALTER TABLE `valimisringkond` CHANGE `ID` `ID` int(11) NOT NULL AUTO_INCREMENT FIRST, COMMENT='';");
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::query("ALTER TABLE `haal` CHANGE `ID` `ID` int(11) NOT NULL FIRST, COMMENT='';");
		DB::query("ALTER TABLE `haaletaja` CHANGE `ID` `ID` int(11) NOT NULL FIRST, COMMENT='';");
		DB::query("ALTER TABLE `kandidaat` CHANGE `ID` `ID` int(11) NOT NULL FIRST, COMMENT='';");
		DB::query("ALTER TABLE `partei` CHANGE `ID` `ID` int(11) NOT NULL FIRST, COMMENT='';");
		DB::query("ALTER TABLE `valimisringkond` CHANGE `ID` `ID` int(11) NOT NULL FIRST, COMMENT='';");
	}

}