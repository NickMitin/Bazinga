<?php

/**
 *
 * Date: 06.08.2014
 * Time: 23:58:16
 */
class bm1407369496_link_file_object_fields extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([
				['link_file_object' => 'fileId'],
				['link_file_object' => 'objectId'],
				['link_file_object' => 'object'],
				['link_file_object' => 'group'],
			]);

		if ($messenge !== true)
		{
			return $messenge;
		}

		$this->execute("ALTER TABLE
                  `link_file_object`
                  ADD COLUMN `fileId` INT(10) NOT NULL DEFAULT '0',
                  ADD COLUMN `objectId` INT(10) NOT NULL DEFAULT '0',
                  ADD COLUMN `object` VARCHAR(255) NOT NULL DEFAULT '',
                  ADD COLUMN `group`  VARCHAR(255) NOT NULL DEFAULT '';");

		$this->execute("ALTER TABLE
                  `link_file_object`
                  ADD INDEX `fileId` (`fileId`),
                  ADD INDEX `object` (`object`),
                  ADD INDEX `objectId` (`objectId`);");


		return true;
	}

}