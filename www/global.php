<?php
$controllersClassesMap = [];
function pawAutoload($className)
{
	global $controllersClassesMap;

	// cache classfiles in classes dir
	if (count($controllersClassesMap) == 0)
	{
		// use own recursive iterator, because SPL-one isn't work on virtual-mounted-systems
		$iterator = function ($rootDirectory, $allowedExtensions, $filesArray = array()) use (&$iterator)
		{
			$directoryContent = scandir($rootDirectory);
			foreach ($directoryContent as $key => $filename)
			{
				$path = realpath($rootDirectory . '/' . $filename);

				if ($filename != '.' && $filename != '..' && is_readable($path))
				{
					if (is_file($path))
					{
						$dotPosition = strrpos($filename, '.');
						$ext = substr($filename, $dotPosition + 1);
						if (in_array($ext, $allowedExtensions))
						{
							$filesArray[substr($filename, 0, $dotPosition)] = $path;
						}
					}
					elseif (is_dir($path))
					{
						$filesArray = $iterator($path, $allowedExtensions, $filesArray);
					}
				}
			}
			return $filesArray;
		};
		$controllersClassesMap = $iterator(projectRoot . "classes", ['php']);
	}

	if (array_key_exists($className, $controllersClassesMap))
	{
		/** @noinspection PhpIncludeInspection */
		require_once($controllersClassesMap[$className]);
	}
	else
	{
		if (file_exists(projectRoot . '/lib/' . $className . '.php'))
		{
			require_once(projectRoot . '/lib/' . $className . '.php');
		}
		elseif (file_exists(projectRoot . '/migration/' . $className . '.php'))
		{
			require_once(projectRoot . '/migration/' . $className . '.php');
		}
	}
}

//todo: обсудить - допускаем ли мы такие штуки, или нахер
function pre($data, $flush = true)
{
	bmTools::pre($data, $flush);
}

spl_autoload_register('pawAutoload', true);

mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

if (!isset($_SERVER['HTTP_USER_AGENT']))
{
	$_SERVER['HTTP_USER_AGENT'] = 'N/A';
}

header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('content-type: text/html; charset=utf-8', true);

require($_SERVER['DOCUMENT_ROOT'] . '/../conf/application.conf');
require($_SERVER['DOCUMENT_ROOT'] . '/../conf/local.conf');


if (!defined('BM_CONSOLE'))
{
	$application = new bmApplication(null);
}


