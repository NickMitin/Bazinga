<?php

/**
 * Переименование поля `type1` в `type` у объекта `section`
 *
 * Date: 16.08.2014
 * Time: 23:32:22
 */
class bm1408231942_af extends bmIMigrate
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
						dof.propertyName = 'type',
						dof.fieldName = 'type',
						dof.dataType = 2,
						dof.localName = 'a:6:{s:10:\"nominative\";s:4:\"type\";s:8:\"genitive\";s:4:\"type\";s:6:\"dative\";s:4:\"type\";s:8:\"accusive\";s:4:\"type\";s:8:\"creative\";s:4:\"type\";s:13:\"prepositional\";s:4:\"type\";}',
						dof.defaultValue = 0,
						dof.type = 0
					where
						dof.fieldName = 'type1'
						and dom.name = 'section'
					");
		$this->execute("ALTER TABLE
                  `section`CHANGE `type1` `type`  INT(10) NOT NULL DEFAULT '0';");

		return true;
	}

}