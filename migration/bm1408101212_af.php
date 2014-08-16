<?php

/**
 * Удаление поля `df` в связи `link_post_section`
 *
 * Date: 15.08.2014
 * Time: 11:13:32
 */
class bm1408101212_af extends bmIMigrate
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
										dof.fieldName = 'df'
										and dom.name = 'link_post_section'
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
													`referenceMapId` = (select id as referenceMapId from `referenceField` where name = 'link_post_section')
								and `referenceFieldId` = @idData
						");
		$this->execute("ALTER TABLE
                  `link_post_section`DROP COLUMN `df`;");

		return true;
	}

}