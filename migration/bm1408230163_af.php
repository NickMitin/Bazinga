<?php

/**
 * Переименование поля `password` в `password` у объекта `user`
 * Переименование поля `type` в `type` у объекта `user`
 *
 * Date: 16.08.2014
 * Time: 23:02:43
 */
class bm1408230163_af extends bmIMigrate
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
						dof.localName = 'a:6:{s:10:\"nominative\";s:8:\"password\";s:8:\"genitive\";s:8:\"password\";s:6:\"dative\";s:8:\"password\";s:8:\"accusive\";s:8:\"password\";s:8:\"creative\";s:8:\"password\";s:13:\"prepositional\";s:8:\"password\";}',
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
						dof.localName = 'a:6:{s:10:\"nominative\";s:4:\"type\";s:8:\"genitive\";s:4:\"type\";s:6:\"dative\";s:4:\"type\";s:8:\"accusive\";s:4:\"type\";s:8:\"creative\";s:4:\"type\";s:13:\"prepositional\";s:4:\"type\";}',
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