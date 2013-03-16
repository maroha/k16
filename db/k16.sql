/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     16.03.2013 15:18:27                          */
/*==============================================================*/


drop table if exists Haal;

drop table if exists Haaletaja;

drop table if exists Kandidaat;

drop table if exists Partei;

drop table if exists Valimisringkond;

/*==============================================================*/
/* Table: Haal                                                  */
/*==============================================================*/
create table Haal
(
   ID                   int not null,
   Aeg                  datetime,
   Haaletaja_ID         int,
   Kandidaadi_ID        int,
   primary key (ID)
);

/*==============================================================*/
/* Table: Haaletaja                                             */
/*==============================================================*/
create table Haaletaja
(
   ID                   int not null,
   Eesnimi              char(15),
   Perekonnanimi        char(15),
   Isikukood            integer(11),
   Valimisringkonna_ID  int,
   primary key (ID)
);

/*==============================================================*/
/* Table: Kandidaat                                             */
/*==============================================================*/
create table Kandidaat
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
);

/*==============================================================*/
/* Table: Partei                                                */
/*==============================================================*/
create table Partei
(
   ID                   int not null,
   Nimetus              char(50),
   primary key (ID)
);

/*==============================================================*/
/* Table: Valimisringkond                                       */
/*==============================================================*/
create table Valimisringkond
(
   ID                   int not null,
   Nimetus              char(50),
   primary key (ID)
);

alter table Haal add constraint fk_Haal_2_Haaletaja foreign key (Haaletaja_ID)
      references Haaletaja (ID) on delete restrict on update restrict;

alter table Haal add constraint fk_Haal_2_Kandidaat foreign key (Kandidaadi_ID)
      references Kandidaat (ID) on delete restrict on update restrict;

alter table Haaletaja add constraint fk_Haaletaja_2_Valimisringkond foreign key (Valimisringkonna_ID)
      references Valimisringkond (ID) on delete restrict on update restrict;

alter table Kandidaat add constraint fk_Kandidaat_2_Haaletaja foreign key (Haaletaja_ID)
      references Haaletaja (ID) on delete restrict on update restrict;

alter table Kandidaat add constraint fk_Kandidaat_2_Partei foreign key (Partei_ID)
      references Partei (ID) on delete restrict on update restrict;

alter table Kandidaat add constraint fk_Kandidaat_2_Valimisringkond foreign key (Valimisringkonna_ID)
      references Valimisringkond (ID) on delete restrict on update restrict;

