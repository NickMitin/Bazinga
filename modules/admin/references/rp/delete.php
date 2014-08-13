<?php

class bmDeleteReference extends bmCustomRemoteProcedure
{
	public $referenceMapId = 0;

	public function execute()
	{
		if ($this->application->user->type < 100)
		{
			echo 'Недостаточно прав доступа';
			exit;
		}

		if ($this->referenceMapId != 0)
		{
			$migration = new bmMigration($this->application->dataLink);
			$referenceMap = new bmReferenceMap($this->application, array('identifier' => $this->referenceMapId), $migration);

			$referenceMap->delete();
			$migration->generationMigration();
		}

		parent::execute();
	}

}

?>
