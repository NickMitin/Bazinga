<?php

/**
 * Переименование поля `type` в `type1` у объекта `section`
 *
 * Date: 16.08.2014
 * Time: 23:32:15
 */
class bm1408231935_af extends bmIMigrate
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
						dof.propertyName = 'type1',
						dof.fieldName = 'type1',
						dof.dataType = 2,
						dof.localName = 'a:6:{s:10:\"nominative\";s:5:\"type1\";s:8:\"genitive\";s:5:\"type1\";s:6:\"dative\";s:5:\"type1\";s:8:\"accusive\";s:5:\"type1\";s:8:\"creative\";s:5:\"type1\";s:13:\"prepositional\";s:5:\"type1\";}',
						dof.defaultValue = 0,
						dof.type = 0
					where
						dof.fieldName = 'type'
						and dom.name = 'section'
					");
		$this->execute("ALTER TABLE
                  `section`CHANGE `type` `type1`  INT(10) NOT NULL DEFAULT '0';");

		return true;
	}

}