<?php

/**
 * Добавление поля `text2` объекту `post`
 *
 * Date: 15.08.2014
 * Time: 16:47:51
 */
class bm1408121271_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['post' => 'text2']]);

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
							'text2',
							'text2',
							9,
							'a:6:{s:10:\"nominative\";s:5:\"text2\";s:8:\"genitive\";s:5:\"text2\";s:6:\"dative\";s:5:\"text2\";s:8:\"accusive\";s:5:\"text2\";s:8:\"creative\";s:5:\"text2\";s:13:\"prepositional\";s:5:\"text2\";}',
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
		$this->execute("ALTER TABLE
                  `post`ADD COLUMN `text2`  LONGTEXT NOT NULL DEFAULT '';");

		return true;
	}

}