<?php

final class bmCMSLoginPage extends bmCMSPage
{

	public function generate()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if ($this->application->login($this->application->cgi->getGPC('login', ''), $this->application->cgi->getGPC('password', '')))
			{
				$back = urldecode($this->application->cgi->getGPC('back', '')) ?: 'cms';
				header("Location: /{$back}/");
			}
			else
			{
				header("Location: /cms/login/?back=" . urldecode($this->application->cgi->getGPC('back', '')) . '&login=' . urldecode($this->application->cgi->getGPC('login', '')));

			}
			exit;
		}
		else
		{
			if ($this->application->cgi->getGPC('back', ''))
			{
				$templateVars['backUrl'] = urldecode($this->application->cgi->getGPC('back', ''));
			}

			$templateVars['login'] = $this->application->cgi->getGPC('login', '');

			return $this->renderTemplate('loginPage.twig', $templateVars);
		}


	}
}

?>
