<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/global.php');
$now = new bmDateTime('now');    // чтобы поставилась таймзона из конфига

$parsedUrl = parse_url($_SERVER['REQUEST_URI']);
if ($_SERVER['REQUEST_METHOD'] == 'GET' && strpos($parsedUrl['path'], '.') === false && strpos($parsedUrl['path'], '/images/content/') === false && substr($parsedUrl['path'], -1) != '/')
{
	if (isset($parsedUrl['query']))
	{
		$parsedUrl['query'] = '?' . $parsedUrl['query'];
	}
	else
	{
		$parsedUrl['query'] = '';
	}
	header('Location: ' . $parsedUrl['path'] . '/' . $parsedUrl['query'], true, 301);
	exit;
}

print $application->generator->generate($_SERVER['REQUEST_URI']);
