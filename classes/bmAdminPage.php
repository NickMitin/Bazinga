<?php

/**
 * Created by PhpStorm.
 * User: vir-mir
 * E-mail: virmir49@gmail.com
 * Date: 16.08.14
 * Time: 4:23
 */
abstract class bmAdminPage extends bmHTMLPage
{
	public function __construct($application, $parameters = array())
	{
		parent::__construct($application, $parameters);

		if ($this->application->user->type < 100)
		{
			//echo 'Недостаточно прав доступа';

			echo '';

			header('HTTP/1.1 404 Not Found');
			header('Status: 404 Not Found');

			exit;
		}
		$this->addCSS('admin/global');
	}
}