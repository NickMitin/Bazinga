<?php

/**
 * Добавление поля `name` объекту `image`
 * Добавление поля `fileName` объекту `image`
 * Добавление поля `size` объекту `image`
 * Добавление поля `width` объекту `image`
 * Добавление поля `height` объекту `image`
 * Добавление поля `caption` объекту `image`
 *
 * Date: 07.08.2014
 * Time: 00:17:34
 */
class bm1407370654_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([['image' => 'name'], ['image' => 'fileName'], ['image' => 'size'], ['image' => 'width'], ['image' => 'height'], ['image' => 'caption']]);

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
														select id as dataObjectMapId from `dataObjectMap` where name = 'image'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("
					insert ignore into
						dataObjectField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`, `type`)
					values
						(
							'fileName',
							'fileName',
							1,
							'a:6:{s:10:\"nominative\";s:8:\"fileName\";s:8:\"genitive\";s:8:\"fileName\";s:6:\"dative\";s:8:\"fileName\";s:8:\"accusive\";s:8:\"fileName\";s:8:\"creative\";s:8:\"fileName\";s:13:\"prepositional\";s:8:\"fileName\";}',
							'',
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'image'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("
					insert ignore into
						dataObjectField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`, `type`)
					values
						(
							'size',
							'size',
							2,
							'a:6:{s:10:\"nominative\";s:4:\"size\";s:8:\"genitive\";s:4:\"size\";s:6:\"dative\";s:4:\"size\";s:8:\"accusive\";s:4:\"size\";s:8:\"creative\";s:4:\"size\";s:13:\"prepositional\";s:4:\"size\";}',
							0,
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'image'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("
					insert ignore into
						dataObjectField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`, `type`)
					values
						(
							'width',
							'width',
							2,
							'a:6:{s:10:\"nominative\";s:5:\"width\";s:8:\"genitive\";s:5:\"width\";s:6:\"dative\";s:5:\"width\";s:8:\"accusive\";s:5:\"width\";s:8:\"creative\";s:5:\"width\";s:13:\"prepositional\";s:5:\"width\";}',
							0,
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'image'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("
					insert ignore into
						dataObjectField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`, `type`)
					values
						(
							'height',
							'height',
							2,
							'a:6:{s:10:\"nominative\";s:6:\"height\";s:8:\"genitive\";s:6:\"height\";s:6:\"dative\";s:6:\"height\";s:8:\"accusive\";s:6:\"height\";s:8:\"creative\";s:6:\"height\";s:13:\"prepositional\";s:6:\"height\";}',
							0,
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'image'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("
					insert ignore into
						dataObjectField
						(`propertyName`, `fieldName`, `dataType`, `localName`, `defaultValue`, `type`)
					values
						(
							'caption',
							'caption',
							1,
							'a:6:{s:10:\"nominative\";s:7:\"caption\";s:8:\"genitive\";s:7:\"caption\";s:6:\"dative\";s:7:\"caption\";s:8:\"accusive\";s:7:\"caption\";s:8:\"creative\";s:7:\"caption\";s:13:\"prepositional\";s:7:\"caption\";}',
							'',
							0
						)");
		$this->execute("
											INSERT IGNORE INTO
												`link_dataObjectMap_dataObjectField`
												select p1.dataObjectMapId, p2.dataObjectFieldId from
													(
														select id as dataObjectMapId from `dataObjectMap` where name = 'image'
								) p1,
								( select max(id) as dataObjectFieldId from dataObjectField ) p2


				");
		$this->execute("ALTER TABLE
                  `image`ADD COLUMN `name`  VARCHAR(255) NOT NULL DEFAULT '', ADD COLUMN `fileName`  VARCHAR(255) NOT NULL DEFAULT '', ADD COLUMN `size`  INT(10) NOT NULL DEFAULT '0', ADD COLUMN `width`  INT(10) NOT NULL DEFAULT '0', ADD COLUMN `height`  INT(10) NOT NULL DEFAULT '0', ADD COLUMN `caption`  VARCHAR(255) NOT NULL DEFAULT '';");

		return true;
	}

}