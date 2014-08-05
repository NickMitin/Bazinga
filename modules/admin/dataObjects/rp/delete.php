<?php

class bmDeleteDataObject extends bmCustomRemoteProcedure
{
	public $dataObjectMapId = 0;

	public function execute()
	{
		if ($this->application->user->type < 100)
		{
			echo 'Недостаточно прав доступа';
			exit;
		}

		if ($this->dataObjectMapId != 0)
		{
			$migration = new bmMigration($this->application->dataLink);
			$dataObjectMap = new bmDataObjectMap($this->application, array('identifier' => $this->dataObjectMapId), $migration);

			$dataObjectMap->delete();
			$migration->generationMigration();
		}

		parent::execute();
	}

}

