<?php

class Kandidaatid {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		$sql = <<<EOT
		INSERT INTO `kandidaat` (`ID`, `Number`, `Partei_ID`, `Valimisringkonna_ID`, `Haaletaja_ID`, `Sunnikoht`, `Elukohaaadress`, `Haridus`, `Akadeemiline_kraad`, `Elukutse`, `Tookoht`, `Telefoninumber`, `Email`, `Pilt`) VALUES
		(1, 1, 1, 1, 1, 'Tapa', 'Mets', 'kesk', 'mesi', 'mesinik', 'aed', 12322222, 'abc@abc.com', NULL),
		(2, 2, 2, 2, 2, 'Parnu', 'Tartu mnt', 'korgem', 'arhitektuur', 'tantsija', 'tantsivaljak', 1234124, 'aaaaa@abc.com', NULL),
		(3, 3, 3, 3, 3, 'Laev', 'Narva', 'kesk', 'majandus', 'kirjanik', 'kodu', 234214, 'aabdd@abc.com', NULL),
		(4, 4, 4, 5, 4, 'Polva', 'Polva', 'fuusik', 'PhD', 'tuuma fuusik', 'fuusika hoone', 21321344, 'fff@abc.com', NULL),
		(5, 5, 4, 6, 7, 'Haapsalu', 'kirik', 'teoloogia', 'PhD', 'kiriku jahataja', 'kirik', 1231234, 'kjask@abc.com', NULL),
		(6, 6, 6, 6, 8, 'Poltsamaa', 'Poltsamaa', 'kesk', 'kesk', 'tuletorjuja', 'Maja', 23213234, 'lkal@abcs.com', NULL),
		(7, 7, 7, 7, 13, 'Moskva', 'Tverskaja', 'kesk', 'ei ole', 'keevitaja', 'laev', 2342355, 'lkasd@abcd.com', NULL),
		(8, 8, 8, 8, 11, 'Tallinn', 'uus koht', 'korgem', 'Msc', 'bioloog', 'kodu', 78712312, 'jkja@jjasd.com', NULL),
		(9, 9, 9, 9, 14, 'Viru', 'Viru', 'pohi', 'ei ole', 'opilane', 'kool', 8787123, 'kjaksjd@abc.com', NULL),
		(10, 10, 5, 7, 11, 'sdfsdf', 'sdfsdf', 'asdaa', 'dfgd', 'dfgd', 'dfgdg', 345435, 'asdda@yas.com', NULL),
		(11, 20, 7, 8, 4, 'Tapa', 'Rapla', 'kesk', 'ei ole', 'kunstnik', 'kodu', 324234, 'asdffff@yax.ru', NULL),
		(12, 11, 6, 8, 12, 'Kose', 'Kase', 'korg', 'PhD', 'elektrik', 'kodu', 989324, 'jkajsd@yad.com', NULL),
		(13, 12, 5, 6, 7, 'lkjkjkkk', 'kkkkad', 'kesk', 'kesk', 'laulja', 'teater', 423411, 'lkka@yad.com', NULL),
		(14, 13, 4, 8, 11, 'sdfsdff', 'Kase', 'kesk', 'ei ole', 'elanik', 'kodu', 3242344, 'asddaadd@yalll.com', NULL),
		(15, 14, 5, 7, 14, 'Ujula', 'Ujula', 'kesk', 'ei ole', 'sportlane', 'ujula', 324234, 'iioiq@uiausd.com', NULL),
		(16, 15, 4, 7, 8, 'Virumaa', 'Virumaa', 'kesk', 'ei ole', 'pollumees', 'pold', 32424, 'kjaksd@iajd.com', NULL),
		(17, 16, 6, 9, 13, 'Kuused', 'akjkjasd', 'kesk', 'ei ole', 'puusepp', 'kodu', 324235, 'klasd@yall.com', NULL),
		(18, 17, 9, 5, 14, 'Tallinn', 'Tallinn', 'kesk', 'ei ole', 'kellasepp', 'kodu', 2342555, 'klaksd@iiias.com', NULL),
		(19, 18, 7, 9, 15, 'Tartu', 'Tartu', 'kesk', 'ei ole', 'raudsepp', 'kodu', 345345, 'kjkajsd@yakkd.com', NULL),
		(20, 19, 4, 1, 4, 'Elva', 'Elva', 'kesk', 'ei ole', 'keevitaja', 'kodu', 1241245, 'klklak@yaniii.com', NULL);
EOT;
		DB::query($sql);
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::query("DELETE FROM kandidaat;");
	}

}