<?php



class bmSaveRouter extends bmCustomRemoteProcedure
{
	/*FF::AC::CGIPROPERTIES::{*/
	/*FF::AC::CGIPROPERTIES::}*/


  public $route;
  public $routePath;
  public $routeClass;
  public $routeBaseClass;

  public $routeParameterName;
  public $routeParameterType;
  public $newRouteIndexes;
  public $changedRouteIndexes;

  private $fieldTypes = array(
    BM_VT_INTEGER => 'BM_VT_INTEGER',
    BM_VT_FLOAT => 'BM_VT_FLOAT',
    BM_VT_DATETIME => 'BM_VT_DATETIME',
    BM_VT_STRING => 'BM_VT_STRING',
    BM_VT_TEXT => 'BM_VT_TEXT',
    BM_VT_PASSWORD => 'BM_VT_PASSWORD',
    BM_VT_IMAGE => 'BM_VT_IMAGE',
    BM_VT_FILE => 'BM_VT_FILE'
  );
  
  protected function getParameter(&$parameters, $parameter, $defaultValue = null)
	{
		return (array_key_exists($parameter, $parameters))? $parameters[$parameter] : $defaultValue;
	}


  public function __construct($application, $parameters = array())
  {
    parent::__construct($application, $parameters);

    if ($this->application->user->type < 100)
    {
      echo 'Недостаточно прав доступа';
      exit;
    }

    //$this->returnTo = '/admin/router/';

    /**
    * @warn MAX_INPUT_VARS limit bypassed through json-ing POST data.
    */
    $postData = json_decode($_POST['jsonData'], true);
    //var_dump($postData); die();
    
    $this->route = $this->getParameter($postData, 'route', array());
    $this->routePath = $this->getParameter($postData, 'routePath', array());
    $this->routeClass = $this->getParameter($postData, 'routeClass', array());
    $this->routeBaseClass = $this->getParameter($postData, 'routeBaseClassName', array());

    $this->routeParameterName = $this->getParameter($postData, 'routeParameterName', array());
    $this->routeParameterType = $this->getParameter($postData, 'routeParameterType', array());

    $this->newRouteIndexes = $this->getParameter($postData, 'newRouteIndexes', array());
    $this->changedRouteIndexes = $this->getParameter($postData, 'changedRouteIndexes', array());
    
    /**
    * @warn Old code, deprecated for now.
    */
/*
    $this->route = $this->application->cgi->getGPC('route', array());
    $this->routePath = $this->application->cgi->getGPC('routePath', array());
    $this->routeClass = $this->application->cgi->getGPC('routeClass', array());
    $this->routeBaseClass = $this->application->cgi->getGPC('routeBaseClassName', array());

    $this->routeParameterName = $this->application->cgi->getGPC('routeParameterName', array());
    $this->routeParameterType = $this->application->cgi->getGPC('routeParameterType', array());

    $this->newRouteIndexes = $this->application->cgi->getGPC('newRouteIndexes', array());
    $this->changedRouteIndexes = $this->application->cgi->getGPC('changedRouteIndexes', array());
*/
  }

  public function execute()
  {
    $routesArray = '';
    $genUserName = $this->application->user->name;
    $genDatetime = date("H:i:s, Y-m-d");
    $genDatetime2 = date("Y-m-d_H-i-s");

    $routesCount = count($this->route);
    $routesCounter = 0;

      // 0. Prepare complex paths with different parameters in each pattern block.
    $routePaths = array();
    foreach ($this->routePath as $index => $routePath)
    {
			if (!array_key_exists($routePath, $routePaths))
			{
				$routePaths[$routePath] = array();
			}

			$routePaths[$routePath][] = $index;
    }

    $complexRouteParameters = array();
    foreach ($routePaths as $routePath => $indexArray)
    {
    	if (count($indexArray) > 1)
    	{
    		$complexRouteParameters[$routePath] = array();

    		foreach ($indexArray as $key => $index)
    		{
    			if (array_key_exists($index, $this->routeParameterName))
    			{
    				foreach ($this->routeParameterName[$index] as $paramKey => $routeParameterName)
    				{
    					$complexRouteParameters[$routePath][$routeParameterName] = $this->routeParameterType[$index][$paramKey];
    				}
					}
    		}
    	}
		}
		unset($routePaths);  // not useful anymore

    foreach ($complexRouteParameters as $complexRoutePath => $complexRouteParameters)
    {
    	$parameterNames = array_keys($complexRouteParameters);
    	$parameterTypes = array_values($complexRouteParameters);

    	foreach ($this->routePath as $index => $routePath)
    	{
    		if (array_key_exists($index, $this->routeParameterName))
    		{
    			if ($routePath == $complexRoutePath)
    			{
    				$this->routeParameterName[$index] = $parameterNames;
    				$this->routeParameterType[$index] = $parameterTypes;

    				  // Rewrite controller of the complex route path.
    				$this->changedRouteIndexes[$index] = 1;
					}
				}
			}
    }

      // 1. Create 'generator.conf' file.
    foreach ($this->route as $index => $route)
    {
      if ((mb_strlen($route) > 0) && (mb_strlen($this->routeClass[$index]) > 0) && (mb_strlen($this->routePath[$index]) > 0))
      {
        $routesArrayItemParameters = '';
        if (array_key_exists($index, $this->routeParameterName))
        {
          if (count($this->routeParameterName[$index]) > 0)
          {
            $routesArrayItemParameters = array();
            foreach ($this->routeParameterName[$index] as $key => $routeParameterNameAtIndex)
            {
              if (array_key_exists($key, $this->routeParameterType[$index]))
              {
                $routesArrayItemParameters[] = "'{$routeParameterNameAtIndex}' => {$this->fieldTypes[$this->routeParameterType[$index][$key]]}";
              }
            }

            if (count($routesArrayItemParameters) > 0)
            {
              $routesArrayItemParameters = implode(",\n      ", $routesArrayItemParameters);
              $routesArrayItemParameters = ",\n    'parameters' => array\n    (\n      {$routesArrayItemParameters}\n    )";
            }
          }
        }

        $routesArray .= "\n  '{$this->route[$index]}' => array\n  (\n    'route' => '{$this->routePath[$index]}',\n    'class' => '{$this->routeClass[$index]}'{$routesArrayItemParameters}\n  )";

        if ($routesCounter < ($routesCount-1))
        {
          $routesArray .= ",";
        }
        $routesArray .= "\n  \n  ";

        $routesCounter++;
      }
    }


      // 2. Update all changed controllers.
    foreach ($this->changedRouteIndexes as $changedRouteIndex => $routeChanged)
    {
			if ($routeChanged == 1)
			{
				$classProperties = '';
				if (isset($this->routeParameterName[$changedRouteIndex]))
				{
					$classPropertiesArray = $this->routeParameterName[$changedRouteIndex];

	        foreach ($classPropertiesArray as $classPropertyName)
	        {
	          if (mb_strlen($classPropertyName) > 0)
	          {
	            $classProperties .= "public \${$classPropertyName};\n	";
	          }
	        }
				}
				
				$classProperties = "/*FF::AC::CGIPROPERTIES::{*/\n	\n	" . $classProperties . "\n	/*FF::AC::CGIPROPERTIES::}*/";
				
				
				$controllerFilePath = projectRoot . $this->routePath[$changedRouteIndex];
				if (is_file($controllerFilePath))
				{
					$content = file_get_contents($controllerFilePath);
					$content = preg_replace('/\/\*FF::AC::CGIPROPERTIES::\{\*\/(.+)\/\*FF::AC::CGIPROPERTIES::\}\*\//ism', $classProperties, $content);
					
						// replace parent class as extended
					$className = $this->routeClass[$changedRouteIndex];
					$extendedClassName = $this->routeBaseClass[$changedRouteIndex];
					$content = preg_replace('/(class [a-zA-Z_]{1,}[a-zA-Z0-9_]{0,})( extends [a-zA-Z_]{1,}[a-zA-Z0-9_]{0,})?/ism', 'class ' . $className . ' extends ' . $extendedClassName, $content);
					
					file_put_contents($controllerFilePath, $content);
				}
			}
    }


      // 3. Backup previous 'generator.conf' in /conf/generator.conf-backups/
/*
    $confBackupDir = $_SERVER['DOCUMENT_ROOT'] . '/../conf/generator.conf-backups';
    if (!is_dir($confBackupDir))
    {
      mkdir($confBackupDir);
      chmod($confBackupDir, 0775);
    }
*/

    //if (is_dir($confBackupDir))
    {
      //$blameString = '--' . $genDatetime2 . '_rewritten_by_' . str_replace(' ', '_', $genUserName);
      //$backupGeneratorConfPath = $confBackupDir . '/generator.conf' . $blameString;
      $generatorConfPath = $_SERVER['DOCUMENT_ROOT'] . '/../conf/generator.conf';
      //rename($generatorConfPath, $backupGeneratorConfPath);

      //if (!is_file($generatorConfPath))
      {
          // Create new 'generator.conf'
        $confFileContent = '';
        eval('$confFileContent = "' . $this->application->getTemplate('admin/code/generator/generator') . '";');
        file_put_contents($generatorConfPath, $confFileContent);
      }

        // 3. Generate proper files for new routes.
      foreach ($this->newRouteIndexes as $newRouteIndex)
      {
        $routeClass = $this->routeClass[$newRouteIndex];
        $rpHelperBlock = '';
        $pageHelperBlock = '';
        $doCreateBlankTemplate = false;

        $blankTemplateName = $this->application->getFileName(basename($this->routePath[$newRouteIndex]));
        $blankTemplateFileName = $blankTemplateName . '.html';
        $blankTemplatePath = $_SERVER['DOCUMENT_ROOT'] . '/../templates/view/' . $blankTemplateFileName;
        eval('$pageHelperBlock = "' . $this->application->getTemplate('/admin/code/router/pageHelperBlock') . '";');
        
        $parentClass = $this->routeBaseClass[$newRouteIndex];

        if (mb_strpos($parentClass, 'Page') !== false)
        {
        	$entryFunctionName = 'generate';
        	$return = 'return ';
					$doCreateBlankTemplate = true;
        }
        else
        {
          eval('$rpHelperBlock = "' . $this->application->getTemplate('/admin/code/router/rpHelperBlock') . '";');
          $pageHelperBlock = '';
          $entryFunctionName = 'execute';
          $return = '';
        }

        $classProperties = "/*FF::AC::CGIPROPERTIES::{*/\n	\n	";
        if (array_key_exists($newRouteIndex, $this->routeParameterName))
        {
          foreach ($this->routeParameterName[$newRouteIndex] as $classPropertyName)
          {
            if (mb_strlen($classPropertyName) > 0)
            {
              $classProperties .= "public \${$classPropertyName};\n  ";
            }
          }
        }
        $classProperties .= "\n  /*FF::AC::CGIPROPERTIES::}*/\n\n\n  ";

          // Create/rewrite controller class.
        $controllerFilePath = $_SERVER['DOCUMENT_ROOT'] . '/..' . $this->routePath[$newRouteIndex];
        $controllerFileContent = '';
        eval('$controllerFileContent = "' . $this->application->getTemplate('/admin/code/router/controllerTemplate') . '";');

        if (is_file($controllerFilePath))
        {
          //rename($controllerFilePath, $controllerFilePath . '.old');
        }
        else
        {
        	@file_put_contents($controllerFilePath, $controllerFileContent);
        }

          // Create blank template file only when file doesn't exist.
        if ($doCreateBlankTemplate)
        {
          if (is_file($blankTemplatePath))
          {
            //rename($blankTemplatePath, $blankTemplatePath . '.old');
          }
          else
          {
          	@file_put_contents($blankTemplatePath, '');
          }
        }
      }
    }


    parent::execute();
  }
}
?>
