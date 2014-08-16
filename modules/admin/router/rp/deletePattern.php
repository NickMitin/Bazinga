<?php


class bmDeleteRoutePattern extends bmCustomRemoteProcedure
{
	/*FF::AC::CGIPROPERTIES::{*/

	public $pattern;

	/*FF::AC::CGIPROPERTIES::}*/

	protected $routes;
	protected $confFilePath;

	protected $fieldTypes = array(
		BM_VT_INTEGER => 'BM_VT_INTEGER',
		BM_VT_FLOAT => 'BM_VT_FLOAT',
		BM_VT_DATETIME => 'BM_VT_DATETIME',
		BM_VT_STRING => 'BM_VT_STRING',
		BM_VT_TEXT => 'BM_VT_TEXT',
		BM_VT_PASSWORD => 'BM_VT_PASSWORD',
		BM_VT_IMAGE => 'BM_VT_IMAGE',
		BM_VT_FILE => 'BM_VT_FILE'
	);


	public function __construct($application, $parameters = array())
	{
		$this->type = BM_RP_TYPE_JSON;
		$this->output = new stdClass();
		$this->output->result = '';


		$this->confFilePath = projectRoot . '/conf/generator.conf';
		$this->routes = array();
		if ($application->user->type >= 100)
		{
			require($this->confFilePath);
		}


		parent::__construct($application, $parameters);
	}


	protected function saveRoutes()
	{
		$routesArray = '';
		$routesCount = count($this->routes);
		$routesCounter = 0;


		// 1. Create new 'generator.conf' file.
		//ksort($this->routes);
		foreach ($this->routes as $index => $route)
		{
			if (mb_strlen($index) > 0)
			{
				$routesArrayItemParameters = '';

				if (array_key_exists('parameters', $route))
				{
					if (count($route['parameters']) > 0)
					{
						$routesArrayItemParameters = array();
						foreach ($route['parameters'] as $routeKey => $routeValue)
						{
							$routesArrayItemParameters[] = "'{$routeKey}' => {$this->fieldTypes[$routeValue]}";
						}

						if (count($routesArrayItemParameters) > 0)
						{
							$routesArrayItemParameters = implode(",\n      ", $routesArrayItemParameters);
							$routesArrayItemParameters = ",\n    'parameters' => array\n    (\n      {$routesArrayItemParameters}\n    )";
						}
					}
				}

				$routesArray .= "\n  '{$index}' => array\n  (\n    'route' => '{$route['route']}',\n    'class' => '{$route['class']}'{$routesArrayItemParameters}\n  )";

				if ($routesCounter < ($routesCount - 1))
				{
					$routesArray .= ",";
				}
				$routesArray .= "\n  \n  ";

				$routesCounter++;
			}
		}
		$routesArray .= "\n";


		// 2. Update changed controller
		$confFileContent = '';
		eval('$confFileContent = "' . $this->application->getTemplate('admin/code/generator/generator') . '";');
		file_put_contents($this->confFilePath, $confFileContent);
	}

	protected function updateRouteControllerFile($routePattern, $extendedClassName = '')
	{
		$classProperties = '';

		if (array_key_exists($routePattern, $this->routes))
		{
			if (array_key_exists('parameters', $this->routes[$routePattern]))
			{
				if (is_array($this->routes[$routePattern]['parameters']))
				{
					foreach ($this->routes[$routePattern]['parameters'] as $classPropertyName => $classPropertyType)
					{
						if (mb_strlen($classPropertyName) > 0)
						{
							$classProperties .= "public \${$classPropertyName};\n	";
						}
					}
				}
			}
		}

		$classProperties = "/*FF::AC::CGIPROPERTIES::{*/\n	\n	" . $classProperties . "\n	/*FF::AC::CGIPROPERTIES::}*/";


		$content = '';
		$md5Content = md5($content);

		$controllerFilePath = projectRoot . trim($this->routes[$routePattern]['route'], ' /');
		if (!is_dir($controllerFilePath))
		{
			mkdir(dirname($controllerFilePath), 0755, true);
		}


		if (is_file($controllerFilePath))
		{
			$content = file_get_contents($controllerFilePath);
			$md5Content = md5($content);
			$content = preg_replace('/\/\*FF::AC::CGIPROPERTIES::\{\*\/(.+)\/\*FF::AC::CGIPROPERTIES::\}\*\//ism', $classProperties, $content);

			if ($extendedClassName != '')
			{
				// replace parent class as extended
				$content = preg_replace(
					'/(class [a-zA-Z_]{1,}[a-zA-Z0-9_]{0,})( extends [a-zA-Z_]{1,}[a-zA-Z0-9_]{0,})?/ism', 'class ' .
					$this->routes[$routePattern]['class'] . ' extends ' . $extendedClassName, $content
				);
			}
		}
		else
		{
			$genUserName = $this->application->user->fullName;
			$genDatetime = date("H:i:s, Y-m-d");
			$genDatetime2 = date("Y-m-d_H-i-s");

			$routeClass = $this->routes[$routePattern]['class'];
			$rpHelperBlock = '';
			$pageHelperBlock = '';

			$blankTemplateName = $this->application->getFileName(basename($this->routes[$routePattern]['route']));
			$blankTemplateFileName = $blankTemplateName . '.html';
			$blankTemplatePath = projectRoot . '/templates/view/' . $blankTemplateName . '.html';

			eval('$pageHelperBlock = "' . $this->application->getTemplate('/admin/code/router/pageHelperBlock') . '";');


			$parentClass = $extendedClassName;

			if (mb_strpos($parentClass, 'Page') !== false)
			{
				$entryFunctionName = 'generate';
				$return = 'return ';

				// Create blank template file only when file doesn't exist.
				if (!file_exists($blankTemplatePath))
				{
					file_put_contents($blankTemplatePath, '');
				}
			}
			else
			{
				eval('$rpHelperBlock = "' . $this->application->getTemplate('/admin/code/router/rpHelperBlock') . '";');
				$pageHelperBlock = '';
				$entryFunctionName = 'execute';
				$return = '';
			}

			eval('$content = "' . $this->application->getTemplate('/admin/code/router/controllerTemplate') . '";');
		}


		// do not touch the file if nothing has changed
		$md5ContentAfter = md5($content);

		if ($md5Content != $md5ContentAfter)
		{
			file_put_contents($controllerFilePath, $content);
		}
	}

	public function getComplexRoutes()
	{
		$complexRoutesTmp = array();
		$complexRoutes = array();


		foreach ($this->routes as $routePattern => $routeData)
		{
			if (!array_key_exists($routeData['route'], $complexRoutesTmp))
			{
				$complexRoutesTmp[$routeData['route']] = array();
			}

			$complexRoutesTmp[$routeData['route']][] = $routePattern;
		}

		foreach ($complexRoutesTmp as $routePath => $routePatterns)
		{
			if (count($routePatterns) >= 2)
			{
				array_push($complexRoutes, $routePath);
			}
		}


		return $complexRoutes;
	}


	public function execute()
	{
		$this->output->result = '';


		if ($this->application->user->type >= 100)
		{
			require($this->confFilePath);

			if (array_key_exists($this->pattern, $this->routes))
			{
				$this->output->result = 'ok';

				unset($this->routes[$this->pattern]);

				$this->saveRoutes();
			}
		}


		return parent::execute();
	}
}

?>
