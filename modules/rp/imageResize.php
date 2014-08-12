<?php


class bmImageResize extends bmCustomRemoteProcedure
{
	/*FF::AC::CGIPROPERTIES::{*/

	public $fileName;

	/*FF::AC::CGIPROPERTIES::}*/

	use bmImageResizeModule;


	public function execute()
	{//projectRoot
		$this->type = BM_RP_TYPE_CUSTOM;

		$this->returnTo = $this->resize($this->fileName);

		parent::execute();
	}
} 