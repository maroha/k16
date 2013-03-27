<?php

class Haaletajad {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		$sql = <<<EOT
		INSERT INTO `haaletaja` (`ID`, `Eesnimi`, `Perekonnanimi`, `Isikukood`, `Valimisringkonna_ID`) VALUES
		(1, 'Sinine', 'Laev', '2147483647', 1),
		(2, 'Haamer', 'Laud', '2147483222', 1),
		(3, 'Must', 'Laud', '33333333331', 1),
		(4, 'Kollane', 'Laud', '33333333341', 1),
		(5, 'Roheline', 'Pastakas', '33333333332', 2),
		(6, 'Kuum', 'Pirukas', '33333333333', 3),
		(7, 'Onne', 'Seen', '33333333334', 4),
		(8, 'Kattuse', 'Mees', '33333333335', 5),
		(9, 'Roheline', 'Labidas', '33333333336', 6),
		(10, 'Kollane', 'Karu', '33333333337', 7),
		(11, 'Imelik', 'Mees', '33333333338', 8),
		(12, 'Mesine', 'Mees', '33333333339', 9),
		(13, 'Punane', 'Pipar', '33333333310', 10),
		(14, 'Ilus', 'Paev', '33333333311', 1),
		(15, 'Kena', 'Arvuti', '33333333312', 2),
		(16, 'Paaritu', 'Arv', '33333333313', 3),
		(17, 'Paaris', 'Arv', '33333333314', 4),
		(18, 'Kvant', 'Mehanika', '33333333315', 5),
		(19, 'Tugev', 'Tuul', '33333333316', 6),
		(20, 'Punane', 'Laud', '33333333317', 7),
		(21, 'Metsa', 'Koer', '33333333318', 8),
		(22, 'Kiirgus', 'Oht', '33333333319', 9),
		(23, 'Metsa', 'Mees', '33333333320', 10);
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
		DB::query("DELETE FROM haaletaja;");
	}

}