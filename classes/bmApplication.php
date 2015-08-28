<?php

/**
 * @property bmData data
 * @property  bmCGI cgi
 * @property  bmCacheLink cacheLink
 */
final class bmApplication extends bmCustomApplication
{
	/**
	 * @var Twig_Environment
	 */
	protected $twig;

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

	public function initTwig()
	{
		$loader = new Twig_Loader_Filesystem(projectRoot . 'templates/site');
		$this->twig = new Twig_Environment(
			$loader, [
				'debug' => true,
				'cache' => C_CACHE_TEMPLATES ? (projectRoot . 'generated/templateCache') : null,
			]
		);
		$this->twig->addExtension(new Twig_Extension_Debug());
	}

	function renderTemplate($templateName, $templateParams = [])
	{
		if (!$this->twig)
		{
			$this->initTwig();
		}

		$modifyTimes['main.js'] = filemtime(projectRoot . "/www/scripts/site/main.js");
		$modifyTimes['main.css'] = filemtime(projectRoot . "/www/styles/site/main.css");

		$templateParams['modifyTimes'] = $modifyTimes;
		$templateParams['serverName'] = C_SESSION_COOKIE_DOMAIN;

		return $this->twig->render($templateName, $templateParams);
	}


}

?>
