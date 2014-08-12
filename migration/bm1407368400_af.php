<?php

/**
 * Создание объекта `image`
 *
 * Date: 06.08.2014
 * Time: 23:40:00
 */
class bm1407368400_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects(['image']);
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
						('image',
						 0)
				");
		$this->execute("CREATE TABLE IF NOT EXISTS `image` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`), `deleted` tinyint(1) unsigned) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		return true;
	}

}