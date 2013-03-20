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
"create table Haal
(
   ID                   int not null,
   Aeg                  datetime,
   Haaletaja_ID         int,
   Kandidaadi_ID        int,
   primary key (ID)
)");
DB::query(
"create table Haaletaja
(
   ID                   int not null,
   Eesnimi              char(15),
   Perekonnanimi        char(15),
   Isikukood            integer(11),
   Valimisringkonna_ID  int,
   primary key (ID)
)");
DB::query(
"create table Kandidaat
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
"create table Partei
(
   ID                   int not null,
   Nimetus              char(50),
   primary key (ID)
);");
DB::query(
"create table Valimisringkond
(
   ID                   int not null,
   Nimetus              char(50),
   primary key (ID)
);");
DB::query(
"alter table Haal add constraint fk_Haal_2_Haaletaja foreign key (Haaletaja_ID)
      references Haaletaja (ID) on delete restrict on update restrict;");
DB::query(
"alter table Haal add constraint fk_Haal_2_Kandidaat foreign key (Kandidaadi_ID)
      references Kandidaat (ID) on delete restrict on update restrict;
");
DB::query(
"alter table Haaletaja add constraint fk_Haaletaja_2_Valimisringkond foreign key (Valimisringkonna_ID)
      references Valimisringkond (ID) on delete restrict on update restrict;");
DB::query(
"alter table Kandidaat add constraint fk_Kandidaat_2_Haaletaja foreign key (Haaletaja_ID)
      references Haaletaja (ID) on delete restrict on update restrict;");
DB::query(
"alter table Kandidaat add constraint fk_Kandidaat_2_Partei foreign key (Partei_ID)
      references Partei (ID) on delete restrict on update restrict;");
DB::query(
"alter table Kandidaat add constraint fk_Kandidaat_2_Valimisringkond foreign key (Valimisringkonna_ID)
      references Valimisringkond (ID) on delete restrict on update restrict;");


	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
DB::query(
"drop table Haal;");
DB::query(
"drop table Kandidaat;");
DB::query(
"drop table Haaletaja;");
DB::query(
"drop table Partei;");
DB::query(
"drop table Valimisringkond;");
	}

}