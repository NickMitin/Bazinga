<?php

final class bmDataObjectsPage extends bmAdminPage
{
	/*FF::AC::CGIPROPERTIES::{*/
	/*FF::AC::CGIPROPERTIES::}*/


	function generate()
	{
		parent::generate();
		$output = '';
		$dataObjectMaps = $this->application->data->dataObjectMaps;
		$objectList = '';
		foreach ($dataObjectMaps as $dataObjectMap)
		{
			$readonly = $dataObjectMap->type == 1 ? 'readonly="readonly"' : '';
			$delete = $dataObjectMap->type == 1 ? '' : $this->application->getTemplate('/admin/dataObjects/delete');
			eval('$delete  = "' . $delete . '";');
			eval('$objectList .= "' . $this->application->getTemplate('/admin/dataObjects/dataObject') . '";');
			//$dataObjectMap->generateFields();
		}
		eval('$newDataObjectMap = "' . $this->application->getTemplate('/admin/dataObjects/newDataObject') . '";');
		eval('$output = "' . $this->application->getTemplate('/admin/dataObjects/dataObjects') . '";');
		return $output;

	}
}

?>