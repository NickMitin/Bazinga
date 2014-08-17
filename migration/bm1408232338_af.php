<?php

/**
 * Создание объекта `test`
 *
 * Date: 16.08.2014
 * Time: 23:38:58
 */
class bm1408232338_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects(['test']);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([]);

		if ($messenge !== true)
		{
			return $messenge;
		}

		// $this->execute("insert ignore into ....");
		// $this->execute("update ....");
		$this->execute("
					insert ignore into
						dataObjectMap
						(`name`, `type`)
					values
						('test',
						 0)
				");
		$this->execute("CREATE TABLE IF NOT EXISTS `test` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`), `deleted` tinyint(1) unsigned) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		return true;
	}

}