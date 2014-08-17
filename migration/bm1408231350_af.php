<?php

/**
 * Добавление поля `ww` объекту `user`
 *
 * Date: 16.08.2014
 * Time: 23:22:30
 */
class bm1408231350_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['user' => 'ww']]);

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
							'ww',
							'ww',
							2,
							'a:6:{s:10:\"nominative\";s:2:\"ww\";s:8:\"genitive\";s:2:\"ww\";s:6:\"dative\";s:2:\"ww\";s:8:\"accusive\";s:2:\"ww\";s:8:\"creative\";s:2:\"ww\";s:13:\"prepositional\";s:2:\"ww\";}',
							15,
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'user'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("ALTER TABLE
                  `user`ADD COLUMN `ww`  INT(10) NOT NULL DEFAULT '15';");

		return true;
	}

}