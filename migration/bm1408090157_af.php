<?php

/**
 * Добавление связи `link_post_tag`
 *
 * Date: 15.08.2014
 * Time: 08:09:17
 */
class bm1408090157_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects(['link_post_tag']);
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
						referenceMap
						(`name`, `type`)
					values
						('link_post_tag',
						 0)
				");
		$this->execute("CREATE TABLE `link_post_tag` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		return true;
	}

}