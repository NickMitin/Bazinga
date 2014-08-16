<?php

/**
 * Добавление поля `df` в связь `link_post_section`
 *
 * Date: 15.08.2014
 * Time: 10:27:06
 */
class bm1408098426_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['link_post_section' => 'df']]);

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
							'df',
							'df',
							2,
							'a:6:{s:10:\"nominative\";s:2:\"df\";s:8:\"genitive\";s:2:\"df\";s:6:\"dative\";s:2:\"df\";s:8:\"accusive\";s:2:\"df\";s:8:\"creative\";s:2:\"df\";s:13:\"prepositional\";s:2:\"df\";}',
							0
						)");
		$this->execute("
						INSERT IGNORE INTO
							`link_referenceMap_referenceField`
							select p1.referenceMapId, p2.referenceFieldId, 4 as referenceFieldType from
								(
									select id as referenceMapId from `referenceMap` where name = 'link_post_section'
								) p1,
								( select max(id) as referenceFieldId from referenceField ) p2


				");
		$this->execute("ALTER TABLE
                  `link_post_section`ADD COLUMN `df`  INT(10) NOT NULL DEFAULT '0';");

		return true;
	}

}