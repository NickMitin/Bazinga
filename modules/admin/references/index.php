<?php

final class bmReferencesPage extends bmAdminPage
{
	/*FF::AC::CGIPROPERTIES::{*/
	/*FF::AC::CGIPROPERTIES::}*/


	function generate()
	{
		parent::generate();
		$output = '';
		$referenceMaps = $this->application->data->referenceMaps;
		$objectList = '';

		foreach ($referenceMaps as $referenceMap)
		{
			$readonly = $referenceMap->type == 1 ? 'readonly="readonly"' : '';
			$delete = $referenceMap->type == 1 ? '' : $this->application->getTemplate('/admin/references/delete');
			eval('$delete  = "' . $delete . '";');
			eval('$objectList .= "' . $this->application->getTemplate('/admin/references/reference') . '";');
		}

		eval('$newReferenceMap = "' . $this->application->getTemplate('/admin/references/newReference') . '";');
		eval('$output = "' . $this->application->getTemplate('/admin/references/references') . '";');

		return $output;
	}
}

?>