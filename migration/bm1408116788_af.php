<?php

/**
 * Добавление поля `text` объекту `post`
 *
 * Date: 15.08.2014
 * Time: 15:33:08
 */
class bm1408116788_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['post' => 'text']]);

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
							'text',
							'text',
							9,
							'a:6:{s:10:\"nominative\";s:4:\"text\";s:8:\"genitive\";s:4:\"text\";s:6:\"dative\";s:4:\"text\";s:8:\"accusive\";s:4:\"text\";s:8:\"creative\";s:4:\"text\";s:13:\"prepositional\";s:4:\"text\";}',
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
                  `post`ADD COLUMN `text`  LONGTEXT NOT NULL DEFAULT '';");

		return true;
	}

}