<?php

/**
 * Создание объекта `file`
 *
 * Date: 08.08.2014
 * Time: 21:05:15
 */
class bm1407531915_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects(['file']);
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
						('file',
						 0)
				");
		$this->execute("CREATE TABLE IF NOT EXISTS `file` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`), `deleted` tinyint(1) unsigned) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		return true;
	}

}