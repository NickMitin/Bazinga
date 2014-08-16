<?php

/**
 * Добавление поля `post` в связь `link_post_section`
 * Добавление поля `section` в связь `link_post_section`
 *
 * Date: 15.08.2014
 * Time: 08:08:44
 */
class bm1408090124_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['link_post_section' => 'post'], ['link_post_section' => 'section']]);

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
							'post',
							'post',
							5,
							'a:6:{s:10:\"nominative\";s:4:\"post\";s:8:\"genitive\";s:4:\"post\";s:6:\"dative\";s:4:\"post\";s:8:\"accusive\";s:4:\"post\";s:8:\"creative\";s:4:\"post\";s:13:\"prepositional\";s:4:\"post\";}',
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
		$this->execute("
					insert ignore into
						referenceField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`)
					values
						(
							'section',
							'section',
							5,
							'a:6:{s:10:\"nominative\";s:7:\"section\";s:8:\"genitive\";s:7:\"section\";s:6:\"dative\";s:7:\"section\";s:8:\"accusive\";s:7:\"section\";s:8:\"creative\";s:7:\"section\";s:13:\"prepositional\";s:7:\"section\";}',
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
                  `link_post_section`ADD COLUMN `postId`  INT(10) UNSIGNED, ADD COLUMN `sectionId`  INT(10) UNSIGNED;");
		$this->execute("ALTER TABLE
                  `link_post_section`ADD INDEX `postId` (`postId`), ADD INDEX `sectionId` (`sectionId`);");

		return true;
	}

}