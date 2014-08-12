<?php

/**
 *
 * Date: 06.08.2014
 * Time: 23:49:35
 */
class bm1407368975_link_file_object extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects(['link_file_object']);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([]);

		if ($messenge !== true)
		{
			return $messenge;
		}

		//link_file_object
		$this->execute("
			CREATE TABLE IF NOT EXISTS `link_file_object` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		return true;
	}

}