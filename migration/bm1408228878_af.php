<?php

/**
 * Переименование поля `password` в `password` у объекта `user`
 * Переименование поля `type` в `type` у объекта `user`
 *
 * Date: 16.08.2014
 * Time: 22:41:18
 */
class bm1408228878_af extends bmIMigrate
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
					update
						dataObjectField dof
						inner join link_dataObjectMap_dataObjectField ldd on ldd.dataObjectFieldId = dof.id
						inner join dataObjectMap dom on dom.id = ldd.dataObjectMapId
					set
						dof.propertyName = 'password',
						dof.fieldName = 'password',
						dof.dataType = 6,
						dof.localName = '',
						dof.defaultValue = 0,
						dof.type = 1
					where
						dof.fieldName = 'password'
						and dom.name = 'user'
					");
		$this->execute("
					update
						dataObjectField dof
						inner join link_dataObjectMap_dataObjectField ldd on ldd.dataObjectFieldId = dof.id
						inner join dataObjectMap dom on dom.id = ldd.dataObjectMapId
					set
						dof.propertyName = 'type',
						dof.fieldName = 'type',
						dof.dataType = 2,
						dof.localName = '',
						dof.defaultValue = 0,
						dof.type = 1
					where
						dof.fieldName = 'type'
						and dom.name = 'user'
					");
		$this->execute("ALTER TABLE
                  `user`CHANGE `password` `password`  VARCHAR(255) NOT NULL DEFAULT '0', CHANGE `type` `type`  INT(10) NOT NULL DEFAULT '0';");

		return true;
	}

}