<?php

/**
 * Создание объекта `testPost`
 *
 * Date: 07.08.2014
 * Time: 13:23:12
 */
class bm1407417792_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects(['testPost']);
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
						('testPost',
						 0)
				");
		$this->execute("CREATE TABLE IF NOT EXISTS `testPost` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`), `deleted` tinyint(1) unsigned) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		return true;
	}

}