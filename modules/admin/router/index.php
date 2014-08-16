<?php


final class bmRouterPage extends bmAdminPage
{
	/*FF::AC::CGIPROPERTIES::{*/


	/*FF::AC::CGIPROPERTIES::}*/


	private $routes = array();
	private $fieldTypes = array(
		BM_VT_STRING => 'Текст',
		BM_VT_INTEGER => 'Целое число',
		BM_VT_FLOAT => 'Число с плавающей точкой',
		BM_VT_DATETIME => 'Дата и/или время',
		BM_VT_TEXT => 'Длинный текст',
		BM_VT_PASSWORD => 'Пароль',
		BM_VT_IMAGE => 'Картинка',
		BM_VT_FILE => 'Файл'
	);
	private $abstractClasses = array();


	function generate()
	{
		parent::generate();
		$result = '';

		$this->abstractClasses = $this->application->getAbstractClasses();


		require(projectRoot . '/conf/generator.conf');
		ksort($this->routes);


		foreach ($this->routes as $route => $routeData)
		{
			$parentClass = '';

			if (array_key_exists('route', $routeData))
			{
				if (is_file(projectRoot . $routeData['route']))
				{
					$fileContents = file_get_contents(projectRoot . $routeData['route']);

					$matches = array();
					preg_match('~class [a-zA-Z_]{1,}[a-zA-Z0-9_]{0,} extends ([a-zA-Z_]{1,}[a-zA-Z0-9_]{0,})~', $fileContents, $matches);

					if (isset($matches[1]))
					{
						$parentClass = $matches[1];
					}
				}
			}

			$parentClassId = array_search($parentClass, $this->abstractClasses);
			$this->routes[$route]['parentClass'] = ($parentClassId !== false) ? $parentClassId : -1;
		}


		$routesJSON = json_encode($this->routes);
		$fieldTypesJSON = json_encode($this->fieldTypes);
		$abstractClassesJSON = json_encode($this->abstractClasses);
		$settingsJSON = '{}';

		$routerSettingsFilePath = projectRoot . '/conf/admin.router.json';
		if (file_exists($routerSettingsFilePath))
		{
			$settingsJSON = @file_get_contents($routerSettingsFilePath);
		}

		eval('$result = "' . $this->application->getTemplate('/admin/router/router') . '";');


		return $result;
	}
}

?>
