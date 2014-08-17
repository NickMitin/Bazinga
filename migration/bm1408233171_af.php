<?php

/**
 * Переименование поля `text3` в `text2` у объекта `post`
 *
 * Date: 16.08.2014
 * Time: 23:52:51
 */
class bm1408233171_af extends bmIMigrate
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
						dof.propertyName = 'text2',
						dof.fieldName = 'text2',
						dof.dataType = 9,
						dof.localName = 'a:6:{s:10:\"nominative\";s:5:\"text2\";s:8:\"genitive\";s:5:\"text2\";s:6:\"dative\";s:5:\"text2\";s:8:\"accusive\";s:5:\"text2\";s:8:\"creative\";s:5:\"text2\";s:13:\"prepositional\";s:5:\"text2\";}',
						dof.defaultValue = '',
						dof.type = 0
					where
						dof.fieldName = 'text3'
						and dom.name = 'post'
					");
		$this->execute("ALTER TABLE
                  `post`CHANGE `text3` `text2`  LONGTEXT NOT NULL DEFAULT '';");

		return true;
	}

}