<?php

/**
 * Удаление поля `pos` в связи `link_post_tag`
 * Удаление поля `qwe` в связи `link_post_tag`
 *
 * Date: 15.08.2014
 * Time: 23:42:03
 */
class bm1408146123_af extends bmIMigrate
{

	public function up()
	{
		$messenge = $this->validateObjects([]);
		if ($messenge !== true)
		{
			return $messenge;
		}

		$messenge = $this->validateFields([]);

		if ($messenge !== true)
		{
			return $messenge;
		}

		// $this->execute("insert ignore into ....");
		// $this->execute("update ....");
		$this->execute("
						set @idData = (
									select dof.id from
										referenceField dof
										inner join link_referenceMap_referenceField ldd on ldd.referenceFieldId = dof.id
										inner join referenceMap dom on dom.id = ldd.referenceMapId
									where
										dof.fieldName = 'pos'
										and dom.name = 'link_post_tag'
						)");
		$this->execute("
										DELETE FROM
													`link_referenceField_dataObjectMap`
												WHERE
													`referenceFieldId` = @idData
										");
		$this->execute("
										delete from
											`referenceField`
										where
											`id` = @idData
											");
		$this->execute("
												DELETE FROM
													`link_referenceMap_referenceField`
												WHERE
													`referenceMapId` = (select id as referenceMapId from `referenceField` where name = 'link_post_tag')
								and `referenceFieldId` = @idData
						");
		$this->execute("
						set @idData = (
									select dof.id from
										referenceField dof
										inner join link_referenceMap_referenceField ldd on ldd.referenceFieldId = dof.id
										inner join referenceMap dom on dom.id = ldd.referenceMapId
									where
										dof.fieldName = 'qwe'
										and dom.name = 'link_post_tag'
						)");
		$this->execute("
										DELETE FROM
													`link_referenceField_dataObjectMap`
												WHERE
													`referenceFieldId` = @idData
										");
		$this->execute("
										delete from
											`referenceField`
										where
											`id` = @idData
											");
		$this->execute("
												DELETE FROM
													`link_referenceMap_referenceField`
												WHERE
													`referenceMapId` = (select id as referenceMapId from `referenceField` where name = 'link_post_tag')
								and `referenceFieldId` = @idData
						");
		$this->execute("ALTER TABLE
                  `link_post_tag`DROP COLUMN `pos`, DROP COLUMN `qwe`;");

		return true;
	}

}