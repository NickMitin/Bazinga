<?php

/**
 * Добавление поля `name` объекту `section`
 * Добавление поля `url` объекту `section`
 *
 * Date: 14.08.2014
 * Time: 14:37:24
 */
class bm1408027044_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['section' => 'name'], ['section' => 'url']]);

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
														select id as dataObjectMapId from `dataObjectMap` where name = 'section'
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
														select id as dataObjectMapId from `dataObjectMap` where name = 'section'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("ALTER TABLE
                  `section`ADD COLUMN `name`  VARCHAR(255) NOT NULL DEFAULT '', ADD COLUMN `url`  VARCHAR(255) NOT NULL DEFAULT '';");

		return true;
	}

}