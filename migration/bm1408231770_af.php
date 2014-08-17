<?php

/**
 * Добавление поля `as` объекту `post`
 *
 * Date: 16.08.2014
 * Time: 23:29:30
 */
class bm1408231770_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['post' => 'as']]);

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
							'as',
							'as',
							1,
							'a:6:{s:10:\"nominative\";s:2:\"as\";s:8:\"genitive\";s:2:\"as\";s:6:\"dative\";s:2:\"as\";s:8:\"accusive\";s:2:\"as\";s:8:\"creative\";s:2:\"as\";s:13:\"prepositional\";s:2:\"as\";}',
							'sad',
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
                  `post`ADD COLUMN `as`  VARCHAR(255) NOT NULL DEFAULT 'sad';");

		return true;
	}

}