<?php

/**
 * Добавление поля `image` объекту `testPost`
 *
 * Date: 07.08.2014
 * Time: 13:43:46
 */
class bm1407419026_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['testPost' => 'image']]);

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
							'image',
							'image',
							7,
							'a:6:{s:10:\"nominative\";s:5:\"image\";s:8:\"genitive\";s:5:\"image\";s:6:\"dative\";s:5:\"image\";s:8:\"accusive\";s:5:\"image\";s:8:\"creative\";s:5:\"image\";s:13:\"prepositional\";s:5:\"image\";}',
							'',
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'testPost'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("ALTER TABLE
                  `testPost`ADD COLUMN `image`  VARCHAR(255) NOT NULL DEFAULT '';");

		return true;
	}

}