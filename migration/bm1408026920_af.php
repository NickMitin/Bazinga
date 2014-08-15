<?php

/**
 * Создание объекта `section`
 *
 * Date: 14.08.2014
 * Time: 14:35:20
 */
class bm1408026920_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects(['section']);
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
						('section',
						 0)
				");
		$this->execute("CREATE TABLE IF NOT EXISTS `section` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`), `deleted` tinyint(1) unsigned) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		return true;
	}

}