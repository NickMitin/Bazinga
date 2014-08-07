<?php

/**
 * Добавление поля `name` объекту `testPost`
 *
 * Date: 07.08.2014
 * Time: 13:24:08
 */
class bm1407417848_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['testPost' => 'name']]);

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
														select id as dataObjectMapId from `dataObjectMap` where name = 'testPost'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("ALTER TABLE
                  `testPost`ADD COLUMN `name`  VARCHAR(255) NOT NULL DEFAULT '';");

		return true;
	}

}