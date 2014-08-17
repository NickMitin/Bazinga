<?php

/**
 * Переименование поля `caption` в `caption1` у объекта `file`
 *
 * Date: 16.08.2014
 * Time: 23:35:19
 */
class bm1408232119_af extends bmIMigrate
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
						dof.propertyName = 'caption1',
						dof.fieldName = 'caption1',
						dof.dataType = 1,
						dof.localName = 'a:6:{s:10:\"nominative\";s:8:\"caption1\";s:8:\"genitive\";s:8:\"caption1\";s:6:\"dative\";s:8:\"caption1\";s:8:\"accusive\";s:8:\"caption1\";s:8:\"creative\";s:8:\"caption1\";s:13:\"prepositional\";s:8:\"caption1\";}',
						dof.defaultValue = '',
						dof.type = 0
					where
						dof.fieldName = 'caption'
						and dom.name = 'file'
					");
		$this->execute("ALTER TABLE
                  `file`CHANGE `caption` `caption1`  VARCHAR(255) NOT NULL DEFAULT '';");

		return true;
	}

}