<?php

ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);

$controllersClassesMap = [];
function pawAutoload($className)
{
	global $controllersClassesMap;

	// cache classfiles in classes dir
	if (count($controllersClassesMap) == 0)
	{
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(projectRoot . '/classes/', FilesystemIterator::SKIP_DOTS));
		foreach ($iterator as $path)
		{
			if (substr($path->getFileName(), -4) == ".php")
			{
				$controllersClassesMap[substr($path->getFileName(), 0, -4)] = $path->getPathname();
			}
		}
	}

	if (array_key_exists($className, $controllersClassesMap))
	{
		require_once($controllersClassesMap[$className]);
	}
	else
	{
		if (file_exists(projectRoot . '/lib/' . $className . '.php'))
		{
			require_once(projectRoot . '/lib/' . $className . '.php');
		}
	}
}

mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

if (!isset($_SERVER['HTTP_USER_AGENT']))
{
  $_SERVER['HTTP_USER_AGENT'] = 'N/A';
}
                                    
header('cache-control: no-cache', true);
header('content-type: text/html; charset=utf-8', true);

require($_SERVER['DOCUMENT_ROOT'] . '/../conf/application.conf');
                                  
$application = new bm%upperCaseProjectName%(null, array('debug' => false));

?>