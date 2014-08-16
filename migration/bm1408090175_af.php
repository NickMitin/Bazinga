<?php

/**
 * Добавление поля `post` в связь `link_post_tag`
 * Добавление поля `tag` в связь `link_post_tag`
 *
 * Date: 15.08.2014
 * Time: 08:09:35
 */
class bm1408090175_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['link_post_tag' => 'post'], ['link_post_tag' => 'tag']]);

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
									select id as referenceMapId from `referenceMap` where name = 'link_post_tag'
								) p1,
								( select max(id) as referenceFieldId from referenceField ) p2


				");
		$this->execute("
					insert ignore into
						referenceField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`)
					values
						(
							'tag',
							'tag',
							5,
							'a:6:{s:10:\"nominative\";s:3:\"tag\";s:8:\"genitive\";s:3:\"tag\";s:6:\"dative\";s:3:\"tag\";s:8:\"accusive\";s:3:\"tag\";s:8:\"creative\";s:3:\"tag\";s:13:\"prepositional\";s:3:\"tag\";}',
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
                  `link_post_tag`ADD COLUMN `postId`  INT(10) UNSIGNED, ADD COLUMN `tagId`  INT(10) UNSIGNED;");
		$this->execute("ALTER TABLE
                  `link_post_tag`ADD INDEX `postId` (`postId`), ADD INDEX `tagId` (`tagId`);");

		return true;
	}

}