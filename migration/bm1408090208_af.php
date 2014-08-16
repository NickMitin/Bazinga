<?php

/**
 * Добавление связи `link_post_post`
 *
 * Date: 15.08.2014
 * Time: 08:10:08
 */
class bm1408090208_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects(['link_post_post']);
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
						('link_post_post',
						 0)
				");
		$this->execute("CREATE TABLE `link_post_post` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		return true;
	}

}