-- Adminer 3.6.3 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `haal`;
CREATE TABLE `haal` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Aeg` datetime DEFAULT NULL,
  `Haaletaja_ID` int(11) DEFAULT NULL,
  `Kandidaadi_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Haal_2_Haaletaja` (`Haaletaja_ID`),
  KEY `fk_Haal_2_Kandidaat` (`Kandidaadi_ID`),
  CONSTRAINT `fk_Haal_2_Haaletaja` FOREIGN KEY (`Haaletaja_ID`) REFERENCES `haaletaja` (`ID`),
  CONSTRAINT `fk_Haal_2_Kandidaat` FOREIGN KEY (`Kandidaadi_ID`) REFERENCES `kandidaat` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `haal` (`ID`, `Aeg`, `Haaletaja_ID`, `Kandidaadi_ID`) VALUES
(1, '2000-01-01 00:01:00',  1,  2),
(2, '2000-01-01 00:02:00',  2,  2),
(3, '2000-01-01 00:03:00',  3,  2),
(4, '2000-01-01 00:04:00',  4,  2),
(5, '2000-01-01 00:05:00',  5,  2),
(6, '2000-01-01 00:06:00',  6,  2),
(7, '2000-01-01 00:07:00',  7,  2),
(8, '2000-01-01 00:08:00',  8,  2),
(9, '2000-01-01 00:09:00',  9,  2),
(10,  '2000-01-01 00:10:00',  9,  2);

DROP TABLE IF EXISTS `haaletaja`;
CREATE TABLE `haaletaja` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Eesnimi` char(15) DEFAULT NULL,
  `Perekonnanimi` char(15) DEFAULT NULL,
  `Fb_Id` varchar(20) DEFAULT NULL,
  `Valimisringkonna_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Haaletaja_2_Valimisringkond` (`Valimisringkonna_ID`),
  CONSTRAINT `fk_Haaletaja_2_Valimisringkond` FOREIGN KEY (`Valimisringkonna_ID`) REFERENCES `valimisringkond` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `haaletaja` (`ID`, `Eesnimi`, `Perekonnanimi`, `Fb_Id`, `Valimisringkonna_ID`) VALUES
(1, 'Sinine', 'Laev', '2147483647', 1),
(2, 'Haamer', 'Laud', '2147483222', 1),
(3, 'Must', 'Laud', '33333333331',  1),
(4, 'Kollane',  'Laud', '33333333341',  1),
(5, 'Roheline', 'Pastakas', '33333333332',  2),
(6, 'Kuum', 'Pirukas',  '33333333333',  3),
(7, 'Onne', 'Seen', '33333333334',  4),
(8, 'Kattuse',  'Mees', '33333333335',  5),
(9, 'Roheline', 'Labidas',  '33333333336',  6),
(10,  'Kollane',  'Karu', '33333333337',  7),
(11,  'Imelik', 'Mees', '33333333338',  8),
(12,  'Mesine', 'Mees', '33333333339',  9),
(13,  'Punane', 'Pipar',  '33333333310',  10),
(14,  'Ilus', 'Paev', '33333333311',  1),
(15,  'Kena', 'Arvuti', '33333333312',  2),
(16,  'Paaritu',  'Arv',  '33333333313',  3),
(17,  'Paaris', 'Arv',  '33333333314',  4),
(18,  'Kvant',  'Mehanika', '33333333315',  5),
(19,  'Tugev',  'Tuul', '33333333316',  6),
(20,  'Punane', 'Laud', '33333333317',  7),
(21,  'Metsa',  'Koer', '33333333318',  8),
(22,  'Kiirgus',  'Oht',  '33333333319',  9),
(23,  'Metsa',  'Mees', '33333333320',  10);

DROP TABLE IF EXISTS `kandidaat`;
CREATE TABLE `kandidaat` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Partei_ID` int(11) DEFAULT NULL,
  `Valimisringkonna_ID` int(11) DEFAULT NULL,
  `Haaletaja_ID` int(11) DEFAULT NULL,
  `Sunnikoht` char(50) DEFAULT NULL,
  `Elukohaaadress` char(50) DEFAULT NULL,
  `Haridus` char(50) DEFAULT NULL,
  `Akadeemiline_kraad` char(50) DEFAULT NULL,
  `Elukutse` char(50) DEFAULT NULL,
  `Tookoht` char(50) DEFAULT NULL,
  `Telefoninumber` int(12) DEFAULT NULL,
  `Email` char(50) DEFAULT NULL,
  `Pilt` char(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Kandidaat_2_Haaletaja` (`Haaletaja_ID`),
  KEY `fk_Kandidaat_2_Partei` (`Partei_ID`),
  KEY `fk_Kandidaat_2_Valimisringkond` (`Valimisringkonna_ID`),
  CONSTRAINT `fk_Kandidaat_2_Haaletaja` FOREIGN KEY (`Haaletaja_ID`) REFERENCES `haaletaja` (`ID`),
  CONSTRAINT `fk_Kandidaat_2_Partei` FOREIGN KEY (`Partei_ID`) REFERENCES `partei` (`ID`),
  CONSTRAINT `fk_Kandidaat_2_Valimisringkond` FOREIGN KEY (`Valimisringkonna_ID`) REFERENCES `valimisringkond` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `kandidaat` (`ID`, `Partei_ID`, `Valimisringkonna_ID`, `Haaletaja_ID`, `Sunnikoht`, `Elukohaaadress`, `Haridus`, `Akadeemiline_kraad`, `Elukutse`, `Tookoht`, `Telefoninumber`, `Email`, `Pilt`) VALUES
(1, 1,  1,  1,  'Tapa', 'Mets', 'kesk', 'mesi', 'mesinik',  'aed',  12322222, 'abc@abc.com',  NULL),
(2, 2,  2,  2,  'Parnu',  'Tartu mnt',  'korgem', 'arhitektuur',  'tantsija', 'tantsivaljak', 1234124,  'aaaaa@abc.com',  NULL),
(3, 3,  3,  3,  'Laev', 'Narva',  'kesk', 'majandus', 'kirjanik', 'kodu', 234214, 'aabdd@abc.com',  NULL),
(4, 4,  5,  4,  'Polva',  'Polva',  'fuusik', 'PhD',  'tuuma fuusik', 'fuusika hoone',  21321344, 'fff@abc.com',  NULL),
(5, 4,  6,  7,  'Haapsalu', 'kirik',  'teoloogia',  'PhD',  'kiriku jahataja',  'kirik',  1231234,  'kjask@abc.com',  NULL),
(6, 6,  6,  8,  'Poltsamaa',  'Poltsamaa',  'kesk', 'kesk', 'tuletorjuja',  'Maja', 23213234, 'lkal@abcs.com',  NULL),
(7, 7,  7,  13, 'Moskva', 'Tverskaja',  'kesk', 'ei ole', 'keevitaja',  'laev', 2342355,  'lkasd@abcd.com', NULL),
(8, 8,  8,  11, 'Tallinn',  'uus koht', 'korgem', 'Msc',  'bioloog',  'kodu', 78712312, 'jkja@jjasd.com', NULL),
(9, 9,  9,  14, 'Viru', 'Viru', 'pohi', 'ei ole', 'opilane',  'kool', 8787123,  'kjaksjd@abc.com',  NULL),
(10,  5,  7,  11, 'sdfsdf', 'sdfsdf', 'asdaa',  'dfgd', 'dfgd', 'dfgdg',  345435, 'asdda@yas.com',  NULL),
(11,  7,  8,  4,  'Tapa', 'Rapla',  'kesk', 'ei ole', 'kunstnik', 'kodu', 324234, 'asdffff@yax.ru', NULL),
(12,  6,  8,  12, 'Kose', 'Kase', 'korg', 'PhD',  'elektrik', 'kodu', 989324, 'jkajsd@yad.com', NULL),
(13,  5,  6,  7,  'lkjkjkkk', 'kkkkad', 'kesk', 'kesk', 'laulja', 'teater', 423411, 'lkka@yad.com', NULL),
(14,  4,  8,  11, 'sdfsdff',  'Kase', 'kesk', 'ei ole', 'elanik', 'kodu', 3242344,  'asddaadd@yalll.com', NULL),
(15,  5,  7,  14, 'Ujula',  'Ujula',  'kesk', 'ei ole', 'sportlane',  'ujula',  324234, 'iioiq@uiausd.com', NULL),
(16,  4,  7,  8,  'Virumaa',  'Virumaa',  'kesk', 'ei ole', 'pollumees',  'pold', 32424,  'kjaksd@iajd.com',  NULL),
(17,  6,  9,  13, 'Kuused', 'akjkjasd', 'kesk', 'ei ole', 'puusepp',  'kodu', 324235, 'klasd@yall.com', NULL),
(18,  9,  5,  14, 'Tallinn',  'Tallinn',  'kesk', 'ei ole', 'kellasepp',  'kodu', 2342555,  'klaksd@iiias.com', NULL),
(19,  7,  9,  15, 'Tartu',  'Tartu',  'kesk', 'ei ole', 'raudsepp', 'kodu', 345345, 'kjkajsd@yakkd.com',  NULL),
(20,  4,  1,  4,  'Elva', 'Elva', 'kesk', 'ei ole', 'keevitaja',  'kodu', 1241245,  'klklak@yaniii.com',  NULL);

DROP TABLE IF EXISTS `laravel_migrations`;
CREATE TABLE `laravel_migrations` (
  `bundle` varchar(50) NOT NULL,
  `name` varchar(200) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`bundle`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `laravel_migrations` (`bundle`, `name`, `batch`) VALUES
('application', '2013_03_18_093815_create_all_the_tables',  1),
('application', '2013_03_27_142658_database_autoincrementers',  1),
('application', '2013_03_27_144117_valimisringkonnad',  1),
('application', '2013_03_27_145203_parteid',  1),
('application', '2013_03_27_145317_korralikud_id',  1),
('application', '2013_03_27_153222_haaletajad', 1),
('application', '2013_03_29_191330_kandidaat',  1),
('application', '2013_03_30_080021_haaled', 1),
('application', '2013_04_03_082129_disable_number_and_give_pics', 1),
('application', '2013_04_03_093135_add_yksik',  1),
('application', '2013_04_15_080850_fb_id',  1);

DROP TABLE IF EXISTS `partei`;
CREATE TABLE `partei` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nimetus` char(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `partei` (`ID`, `Nimetus`) VALUES
(1, 'Partei 1'),
(2, 'Partei 2'),
(3, 'Partei 3'),
(4, 'Partei 4'),
(5, 'Partei 5'),
(6, 'Partei 6'),
(7, 'Partei 7'),
(8, 'Partei 8'),
(9, 'Partei 9'),
(10,  'Partei 10'),
(11,  'Üksikkandidaat');

DROP TABLE IF EXISTS `valimisringkond`;
CREATE TABLE `valimisringkond` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nimetus` char(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `valimisringkond` (`ID`, `Nimetus`) VALUES
(1, 'Valimisringkond 1'),
(2, 'Valimisringkond 2'),
(3, 'Valimisringkond 3'),
(4, 'Valimisringkond 4'),
(5, 'Valimisringkond 5'),
(6, 'Valimisringkond 6'),
(7, 'Valimisringkond 7'),
(8, 'Valimisringkond 8'),
(9, 'Valimisringkond 9'),
(10,  'Valimisringkond 10');

-- 2013-04-22 10:28:57
