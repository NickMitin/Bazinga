<?php

/**
 * Удаление поля `image` у объекта `testPost`
 *
 * Date: 07.08.2014
 * Time: 14:13:35
 */
class bm1407420815_af extends bmIMigrate
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

		// $this->execute("insert ignore into ....");
		// $this->execute("update ....");
		$this->execute("
					set @idData = (
						select dof.id from
									dataObjectField dof
									inner join link_dataObjectMap_dataObjectField ldd on ldd.dataObjectFieldId = dof.id
									inner join dataObjectMap dom on dom.id = ldd.dataObjectMapId
								where
									dof.fieldName = 'image'
									and dom.name = 'testPost'
					)");
		$this->execute("
						delete from
							dataObjectField
						where
							`id` = @idData
						");
		$this->execute("
											DELETE FROM
												`link_dataObjectMap_dataObjectField`
											WHERE
												`dataObjectMapId` = (select id as dataObjectMapId from `dataObjectMap` where name = 'testPost')
							and `dataObjectFieldId` = @idData
					");
		$this->execute("ALTER TABLE
                  `testPost`DROP COLUMN `image`;");

		return true;
	}

}