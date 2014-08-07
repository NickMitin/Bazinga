<?php


class bmImageResize extends bmCustomRemoteProcedure
{
	/*FF::AC::CGIPROPERTIES::{*/

	public $fileName;

	/*FF::AC::CGIPROPERTIES::}*/

	private $allowedDimensions = array(
		'h100',
	);

	public function execute()
	{//projectRoot
		$this->type = BM_RP_TYPE_CUSTOM;

		$fileNew = rtrim(documentRoot, '/') . BM_C_IMAGE_FOLDER . $this->fileName;
		$fileNew = rtrim(documentRoot, '/') . BM_C_IMAGE_FOLDER . $this->fileName;
		$file = explode('/', $this->fileName);
		$fileName = array_pop($file);
		array_pop($file);
		$size = array_pop($file);

		$file = implode('/', $file);

		$folder = rtrim(documentRoot, '/') . BM_C_IMAGE_FOLDER . $file . '/' . $size . '/' . mb_substr($fileName, 0, 2) . '/';

		$originFile  = rtrim(documentRoot, '/') . BM_C_IMAGE_FOLDER . $file . '/originals/' . mb_substr($fileName, 0, 2) . '/' . $fileName;
		$url  = BM_C_IMAGE_FOLDER . $file . '/' . $size . '/' . mb_substr($fileName, 0, 2) . '/' . $fileName;


		if (in_array($size, $this->allowedDimensions) && file_exists($originFile))
		{
			$imageObject = new bmImageResizeModule($originFile);
			$size = explode('x', $size);
			$width =  $height = false;

			if (!is_dir($folder))
			{
				mkdir($folder, 0777, true);
			}

			if (count($size) > 1)
			{
				$width = $size[0];
				$height = $size[1];
			}
			else
			{
				$modificator = mb_substr($size[0], 0, 1);
				switch ($modificator)
				{
					case 'h':
						$width = false;
						$height = mb_substr($size[0], 1);
						break;
					case 'w':
						$width = mb_substr($size[0], 1);
						$height = false;
						break;
					default:
						$width = $size[0];
						$height = false;
						break;
				}
			}
			$fileName = pathinfo($fileName)['filename'];
			$imageObject->resize($width, $height);
			$imageObject->save($folder, $fileName, false, true);
			$this->returnTo = $url;
		}
		else
		{
			$this->returnTo = '';
		}


		parent::execute();
	}
} 