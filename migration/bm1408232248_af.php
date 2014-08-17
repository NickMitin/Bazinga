<?php

/**
 * Переименование поля `url` в `url` у объекта `tag`
 *
 * Date: 16.08.2014
 * Time: 23:37:28
 */
class bm1408232248_af extends bmIMigrate
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
						dof.propertyName = 'url1',
						dof.fieldName = 'url',
						dof.dataType = 1,
						dof.localName = 'a:6:{s:10:\"nominative\";s:3:\"url\";s:8:\"genitive\";s:3:\"url\";s:6:\"dative\";s:3:\"url\";s:8:\"accusive\";s:3:\"url\";s:8:\"creative\";s:3:\"url\";s:13:\"prepositional\";s:3:\"url\";}',
						dof.defaultValue = '',
						dof.type = 0
					where
						dof.fieldName = 'url'
						and dom.name = 'tag'
					");
		$this->execute("ALTER TABLE
                  `tag`CHANGE `url` `url`  VARCHAR(255) NOT NULL DEFAULT '';");

		return true;
	}

}