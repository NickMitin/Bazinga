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
}

?>
