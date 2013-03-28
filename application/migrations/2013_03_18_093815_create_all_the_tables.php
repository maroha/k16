<?php

class Create_All_The_Tables {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{

DB::query(
"create table haal
(
   ID                   int not null,
   Aeg                  datetime,
   Haaletaja_ID         int,
   Kandidaadi_ID        int,
   primary key (ID)
)");
DB::query(
"create table haaletaja
(
   ID                   int not null,
   Eesnimi              char(15),
   Perekonnanimi        char(15),
   Isikukood            integer(11),
   Valimisringkonna_ID  int,
   primary key (ID)
)");
DB::query(
"create table kandidaat
(
   ID                   int not null,
   Number               int,
   Partei_ID            int,
   Valimisringkonna_ID  int,
   Haaletaja_ID         int,
   Sunnikoht            char(50),
   Elukohaaadress       char(50),
   Haridus              char(50),
   Akadeemiline_kraad   char(50),
   Elukutse             char(50),
   Tookoht              char(50),
   Telefoninumber       integer(12),
   Email                char(50),
   Pilt                 char(50),
   primary key (ID)
);");
DB::query(
"create table partei
(
   ID                   int not null,
   Nimetus              char(50),
   primary key (ID)
);");
DB::query(
"create table valimisringkond
(
   ID                   int not null,
   Nimetus              char(50),
   primary key (ID)
);");
DB::query(
"alter table haal add constraint fk_Haal_2_Haaletaja foreign key (Haaletaja_ID)
      references haaletaja (ID) on delete restrict on update restrict;");
DB::query(
"alter table haal add constraint fk_Haal_2_Kandidaat foreign key (Kandidaadi_ID)
      references kandidaat (ID) on delete restrict on update restrict;
");
DB::query(
"alter table haaletaja add constraint fk_Haaletaja_2_Valimisringkond foreign key (Valimisringkonna_ID)
      references valimisringkond (ID) on delete restrict on update restrict;");
DB::query(
"alter table kandidaat add constraint fk_Kandidaat_2_Haaletaja foreign key (Haaletaja_ID)
      references haaletaja (ID) on delete restrict on update restrict;");
DB::query(
"alter table kandidaat add constraint fk_Kandidaat_2_Partei foreign key (Partei_ID)
      references partei (ID) on delete restrict on update restrict;");
DB::query(
"alter table kandidaat add constraint fk_Kandidaat_2_Valimisringkond foreign key (Valimisringkonna_ID)
      references valimisringkond (ID) on delete restrict on update restrict;");


	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
DB::query(
"drop table haal;");
DB::query(
"drop table kandidaat;");
DB::query(
"drop table haaletaja;");
DB::query(
"drop table partei;");
DB::query(
"drop table valimisringkond;");
	}

}