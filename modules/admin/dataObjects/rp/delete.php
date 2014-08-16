<?php

class bmDeleteDataObject extends bmCustomRemoteProcedure
{
	/*FF::AC::CGIPROPERTIES::{*/

	public $dataObjectMapId;

	/*FF::AC::CGIPROPERTIES::}*/


	public function execute()
	{
		if ($this->application->user->type < 100)
		{
			echo 'Недостаточно прав доступа';
			exit;
		}

		if ($this->dataObjectMapId != 0)
		{
			$migration = new bmMigration($this->application->dataLinkWrite);
			$dataObjectMap = new bmDataObjectMap($this->application, array('identifier' => $this->dataObjectMapId), $migration);

			$dataObjectMap->delete();

		}
		unset($dataObjectMap);


		parent::execute();
	}

}

?>
