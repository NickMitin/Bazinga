<?php

class bmSaveDataObjects extends bmCustomRemoteProcedure
{
	/*FF::AC::CGIPROPERTIES::{*/
	/*FF::AC::CGIPROPERTIES::}*/


	private $dataObjectNames = array();

	public function __construct($application, $parameters = array())
	{
		parent::__construct($application, $parameters);

		if ($this->application->user->type < 100)
		{
			echo 'Недостаточно прав доступа';
			exit;
		}

		$this->dataObjectNames = $this->application->cgi->getGPC('dataObjectName', array());
	}

	public function execute()
	{
		foreach ($this->dataObjectNames as $dataObjectMapId => $dataObjectName)
		{
			if ($dataObjectMapId != 0 || $dataObjectName != '')
			{
				$dataObjectName = trim($dataObjectName);

				$pattern = '/^[a-zA-Z][a-zA-Z0-9]+$/';
				if (preg_match($pattern, $dataObjectName))
				{
					$migration = new bmMigration($this->application->dataLinkWrite);
					$dataObjectMap = new bmDataObjectMap($this->application, array('identifier' => $dataObjectMapId), $migration);
					$dataObjectMap->beginUpdate();

					if ($dataObjectMap->type != 1)
					{
						$dataObjectMap->name = $dataObjectName;
					}

					$dataObjectMap->endUpdate();
					$dataObjectMap->save();
					$migration->generationMigration();
				}
				else
				{
					echo 'Ошибка: имя объекта может состоять из строчных и прописных латинских букв и цифр и должно начинаться с буквы';
				}
			}
		}



		parent::execute();
	}

}

?>
