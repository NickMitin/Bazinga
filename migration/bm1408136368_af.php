<?php

/**
 * Добавление поля `qwe` в связь `link_post_tag`
 *
 * Date: 15.08.2014
 * Time: 20:59:28
 */
class bm1408136368_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['link_post_tag' => 'qwe']]);

		if ($messenge !== true)
		{
			return $messenge;
		}

		// $this->execute("insert ignore into ....");
		// $this->execute("update ....");
		$this->execute("
					insert ignore into
						referenceField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`)
					values
						(
							'qwe',
							'qwe',
							2,
							'a:6:{s:10:\"nominative\";s:3:\"qwe\";s:8:\"genitive\";s:3:\"qwe\";s:6:\"dative\";s:3:\"qwe\";s:8:\"accusive\";s:3:\"qwe\";s:8:\"creative\";s:3:\"qwe\";s:13:\"prepositional\";s:3:\"qwe\";}',
							0
						)");
		$this->execute("
						INSERT IGNORE INTO
							`link_referenceMap_referenceField`
							select p1.referenceMapId, p2.referenceFieldId, 4 as referenceFieldType from
								(
									select id as referenceMapId from `referenceMap` where name = 'link_post_tag'
								) p1,
								( select max(id) as referenceFieldId from referenceField ) p2


				");
		$this->execute("ALTER TABLE
                  `link_post_tag`ADD COLUMN `qwe`  INT(10) NOT NULL DEFAULT '0';");

		return true;
	}

}