<?php

/**
 * Переименование поля `text2` в `text3` у объекта `post`
 *
 * Date: 16.08.2014
 * Time: 23:52:41
 */
class bm1408233161_af extends bmIMigrate
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
						dof.propertyName = 'text3',
						dof.fieldName = 'text3',
						dof.dataType = 9,
						dof.localName = 'a:6:{s:10:\"nominative\";s:5:\"text3\";s:8:\"genitive\";s:5:\"text3\";s:6:\"dative\";s:5:\"text3\";s:8:\"accusive\";s:5:\"text3\";s:8:\"creative\";s:5:\"text3\";s:13:\"prepositional\";s:5:\"text3\";}',
						dof.defaultValue = '',
						dof.type = 0
					where
						dof.fieldName = 'text2'
						and dom.name = 'post'
					");
		$this->execute("ALTER TABLE
                  `post`CHANGE `text2` `text3`  LONGTEXT NOT NULL DEFAULT '';");

		return true;
	}

}