<?php

/**
 * @property bmData data
 */
final class bmApplication extends bmCustomApplication
{
	public function __construct($application, $parameters = array())
	{
		parent::__construct($application, $parameters);

		require_once projectRoot . 'vendor/autoload.php';

		$this->session = new bmSession($this, array());
		$this->user->lastactivity = time();
	}

	public function getFileName($path)
	{
		$name = null;

		$bname = basename($path);
		$i = mb_strrpos($bname, '.');
		if ($i > 0)
		{
			$name = mb_substr($bname, 0, $i);
		}

		return $name;
	}
}

?>
