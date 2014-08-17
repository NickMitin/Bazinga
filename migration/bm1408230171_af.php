<?php

/**
 * Переименование поля `name` в `name` у объекта `post`
 * Переименование поля `url` в `url` у объекта `post`
 * Переименование поля `order` в `order` у объекта `post`
 * Переименование поля `active` в `active` у объекта `post`
 * Переименование поля `text` в `text` у объекта `post`
 * Переименование поля `text2` в `text2` у объекта `post`
 *
 * Date: 16.08.2014
 * Time: 23:02:51
 */
class bm1408230171_af extends bmIMigrate
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
						dof.propertyName = 'name',
						dof.fieldName = 'name',
						dof.dataType = 1,
						dof.localName = 'a:6:{s:10:\"nominative\";s:4:\"name\";s:8:\"genitive\";s:4:\"name\";s:6:\"dative\";s:4:\"name\";s:8:\"accusive\";s:4:\"name\";s:8:\"creative\";s:4:\"name\";s:13:\"prepositional\";s:4:\"name\";}',
						dof.defaultValue = '',
						dof.type = 0
					where
						dof.fieldName = 'name'
						and dom.name = 'post'
					");
		$this->execute("
					update
						dataObjectField dof
						inner join link_dataObjectMap_dataObjectField ldd on ldd.dataObjectFieldId = dof.id
						inner join dataObjectMap dom on dom.id = ldd.dataObjectMapId
					set
						dof.propertyName = 'url',
						dof.fieldName = 'url',
						dof.dataType = 1,
						dof.localName = 'a:6:{s:10:\"nominative\";s:3:\"url\";s:8:\"genitive\";s:3:\"url\";s:6:\"dative\";s:3:\"url\";s:8:\"accusive\";s:3:\"url\";s:8:\"creative\";s:3:\"url\";s:13:\"prepositional\";s:3:\"url\";}',
						dof.defaultValue = '',
						dof.type = 0
					where
						dof.fieldName = 'url'
						and dom.name = 'post'
					");
		$this->execute("
					update
						dataObjectField dof
						inner join link_dataObjectMap_dataObjectField ldd on ldd.dataObjectFieldId = dof.id
						inner join dataObjectMap dom on dom.id = ldd.dataObjectMapId
					set
						dof.propertyName = 'order',
						dof.fieldName = 'order',
						dof.dataType = 2,
						dof.localName = 'a:6:{s:10:\"nominative\";s:5:\"order\";s:8:\"genitive\";s:5:\"order\";s:6:\"dative\";s:5:\"order\";s:8:\"accusive\";s:5:\"order\";s:8:\"creative\";s:5:\"order\";s:13:\"prepositional\";s:5:\"order\";}',
						dof.defaultValue = 0,
						dof.type = 0
					where
						dof.fieldName = 'order'
						and dom.name = 'post'
					");
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
		$this->execute("
					update
						dataObjectField dof
						inner join link_dataObjectMap_dataObjectField ldd on ldd.dataObjectFieldId = dof.id
						inner join dataObjectMap dom on dom.id = ldd.dataObjectMapId
					set
						dof.propertyName = 'text',
						dof.fieldName = 'text',
						dof.dataType = 9,
						dof.localName = 'a:6:{s:10:\"nominative\";s:4:\"text\";s:8:\"genitive\";s:4:\"text\";s:6:\"dative\";s:4:\"text\";s:8:\"accusive\";s:4:\"text\";s:8:\"creative\";s:4:\"text\";s:13:\"prepositional\";s:4:\"text\";}',
						dof.defaultValue = '',
						dof.type = 0
					where
						dof.fieldName = 'text'
						and dom.name = 'post'
					");
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
						dof.fieldName = 'text2'
						and dom.name = 'post'
					");
		$this->execute("ALTER TABLE
                  `post`CHANGE `name` `name`  VARCHAR(255) NOT NULL DEFAULT '', CHANGE `url` `url`  VARCHAR(255) NOT NULL DEFAULT '', CHANGE `order` `order`  INT(10) NOT NULL DEFAULT '0', CHANGE `active` `active`  INT(10) NOT NULL DEFAULT '1', CHANGE `text` `text`  LONGTEXT NOT NULL DEFAULT '', CHANGE `text2` `text2`  LONGTEXT NOT NULL DEFAULT '';");

		return true;
	}

}