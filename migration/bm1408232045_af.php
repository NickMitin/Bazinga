<?php

/**
 * Переименование поля `caption` в `caption1` у объекта `image`
 *
 * Date: 16.08.2014
 * Time: 23:34:05
 */
class bm1408232045_af extends bmIMigrate
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
						and dom.name = 'image'
					");
		$this->execute("ALTER TABLE
                  `image`CHANGE `caption` `caption1`  VARCHAR(255) NOT NULL DEFAULT '';");

		return true;
	}

}