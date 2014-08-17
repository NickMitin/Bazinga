<?php

/**
 * Добавление поля `type` объекту `section`
 *
 * Date: 16.08.2014
 * Time: 23:31:30
 */
class bm1408231890_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['section' => 'type']]);

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
							'type',
							'type',
							2,
							'a:6:{s:10:\"nominative\";s:4:\"type\";s:8:\"genitive\";s:4:\"type\";s:6:\"dative\";s:4:\"type\";s:8:\"accusive\";s:4:\"type\";s:8:\"creative\";s:4:\"type\";s:13:\"prepositional\";s:4:\"type\";}',
							0,
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
                  `section`ADD COLUMN `type`  INT(10) NOT NULL DEFAULT '0';");

		return true;
	}

}