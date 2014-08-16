<?php

/**
 * Удаление связи `link_post_post`
 *
 * Date: 15.08.2014
 * Time: 08:10:25
 */
class bm1408090225_af extends bmIMigrate
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
					DELETE FROM
						`link_referenceField_dataObjectMap`
					WHERE
						`referenceFieldId` in (
									select
										dof.*
									from
										referenceField dof
										inner join link_referenceMap_referenceField ldd on ldd.referenceFieldId = dof.id
										inner join referenceMap dom on dom.id = ldd.referenceMapId
									where
										dom.name = 'link_post_post'
					)
					");
		$this->execute("
					DELETE FROM
						referenceField
					where
						id in (
								select
									dof.*
								from
									referenceField dof
									inner join link_referenceMap_referenceField ldd on ldd.referenceFieldId = dof.id
									inner join referenceMap dom on dom.id = ldd.referenceMapId
								where
									dom.name = 'link_post_post'
					)
					");
		$this->execute("
					DELETE FROM
						`link_referenceMap_referenceField`
					WHERE
						`referenceMapId` in (select id as referenceMapId from `referenceMap` where name = 'link_post_post')
					");
		$this->execute("
					delete from
						referenceMap
					values
						`name` = 'link_post_post'
					");
		$this->execute("DROP TABLE `link_post_post`;");

		return true;
	}

}