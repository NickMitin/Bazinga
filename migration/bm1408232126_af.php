<?php

/**
 * Переименование поля `caption1` в `caption` у объекта `file`
 *
 * Date: 16.08.2014
 * Time: 23:35:26
 */
class bm1408232126_af extends bmIMigrate
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
						dof.propertyName = 'caption',
						dof.fieldName = 'caption',
						dof.dataType = 1,
						dof.localName = 'a:6:{s:10:\"nominative\";s:7:\"caption\";s:8:\"genitive\";s:7:\"caption\";s:6:\"dative\";s:7:\"caption\";s:8:\"accusive\";s:7:\"caption\";s:8:\"creative\";s:7:\"caption\";s:13:\"prepositional\";s:7:\"caption\";}',
						dof.defaultValue = '',
						dof.type = 0
					where
						dof.fieldName = 'caption1'
						and dom.name = 'file'
					");
		$this->execute("ALTER TABLE
                  `file`CHANGE `caption1` `caption`  VARCHAR(255) NOT NULL DEFAULT '';");

		return true;
	}

}