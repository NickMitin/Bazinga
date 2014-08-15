<?php

/**
 * Добавление поля `name` объекту `post`
 * Добавление поля `url` объекту `post`
 * Добавление поля `order` объекту `post`
 * Добавление поля `active` объекту `post`
 *
 * Date: 14.08.2014
 * Time: 14:36:39
 */
class bm1408026999_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['post' => 'name'], ['post' => 'url'], ['post' => 'order'], ['post' => 'active']]);

		if ($messenge !== true)
		{
			return $messenge;
		}

		// $this->execute("insert ignore into ....");
		// $this->execute("update ....");
		$this->execute("
					insert ignore into
						dataObjectField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`, `type`)
					values
						(
							'name',
							'name',
							1,
							'a:6:{s:10:\"nominative\";s:4:\"name\";s:8:\"genitive\";s:4:\"name\";s:6:\"dative\";s:4:\"name\";s:8:\"accusive\";s:4:\"name\";s:8:\"creative\";s:4:\"name\";s:13:\"prepositional\";s:4:\"name\";}',
							'',
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'post'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("
					insert ignore into
						dataObjectField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`, `type`)
					values
						(
							'url',
							'url',
							1,
							'a:6:{s:10:\"nominative\";s:3:\"url\";s:8:\"genitive\";s:3:\"url\";s:6:\"dative\";s:3:\"url\";s:8:\"accusive\";s:3:\"url\";s:8:\"creative\";s:3:\"url\";s:13:\"prepositional\";s:3:\"url\";}',
							'',
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'post'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("
					insert ignore into
						dataObjectField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`, `type`)
					values
						(
							'order',
							'order',
							2,
							'a:6:{s:10:\"nominative\";s:5:\"order\";s:8:\"genitive\";s:5:\"order\";s:6:\"dative\";s:5:\"order\";s:8:\"accusive\";s:5:\"order\";s:8:\"creative\";s:5:\"order\";s:13:\"prepositional\";s:5:\"order\";}',
							0,
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'post'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("
					insert ignore into
						dataObjectField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`, `type`)
					values
						(
							'active',
							'active',
							2,
							'a:6:{s:10:\"nominative\";s:6:\"active\";s:8:\"genitive\";s:6:\"active\";s:6:\"dative\";s:6:\"active\";s:8:\"accusive\";s:6:\"active\";s:8:\"creative\";s:6:\"active\";s:13:\"prepositional\";s:6:\"active\";}',
							0,
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'post'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("ALTER TABLE
                  `post`ADD COLUMN `name`  VARCHAR(255) NOT NULL DEFAULT '', ADD COLUMN `url`  VARCHAR(255) NOT NULL DEFAULT '', ADD COLUMN `order`  INT(10) NOT NULL DEFAULT '0', ADD COLUMN `active`  INT(10) NOT NULL DEFAULT '0';");

		return true;
	}

}