<?php

/**
 * Переименование поля `status` в `status` у объекта `textPage`
 *
 * Date: 16.08.2014
 * Time: 23:36:40
 */
class bm1408232200_af extends bmIMigrate
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
						dof.propertyName = 'status',
						dof.fieldName = 'status',
						dof.dataType = 2,
						dof.localName = 'a:6:{s:10:\"nominative\";s:6:\"status\";s:8:\"genitive\";s:6:\"status\";s:6:\"dative\";s:6:\"status\";s:8:\"accusive\";s:6:\"status\";s:8:\"creative\";s:6:\"status\";s:13:\"prepositional\";s:6:\"status\";}',
						dof.defaultValue = 0,
						dof.type = 1
					where
						dof.fieldName = 'status'
						and dom.name = 'textPage'
					");
		$this->execute("ALTER TABLE
                  `textPage`CHANGE `status` `status`  INT(10) NOT NULL DEFAULT '0';");

		return true;
	}

}