<?php

/**
 *
 * Date: 06.08.2014
 * Time: 23:44:34
 */
class bm1407368674_file_sistem extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([]);

		if ($messenge !== true)
		{
			return $messenge;
		}

		$this->execute("update `dataObjectMap` set `type`=1 where `name` = 'file'");

		return true;
	}

}