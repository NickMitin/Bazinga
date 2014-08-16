<?php

/**
 * Переименование поля `active` в `active` у объекта `post`
 *
 * Date: 15.08.2014
 * Time: 08:01:32
 */
class bm1408089692_af extends bmIMigrate
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
						dof.propertyName = 'active',
						dof.fieldName = 'active',
						dof.dataType = 2,
						dof.localName = 'a:6:{s:10:\"nominative\";s:6:\"active\";s:8:\"genitive\";s:6:\"active\";s:6:\"dative\";s:6:\"active\";s:8:\"accusive\";s:6:\"active\";s:8:\"creative\";s:6:\"active\";s:13:\"prepositional\";s:6:\"active\";}',
						dof.defaultValue = 1,
						dof.type = 0
					where
						dof.fieldName = 'active'
						and dom.name = 'post'
					");
		$this->execute("ALTER TABLE
                  `post`CHANGE `active` `active`  INT(10) NOT NULL DEFAULT '1';");

		return true;
	}

}