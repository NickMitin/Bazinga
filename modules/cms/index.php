<?php

final class bmCMSIndexPage extends bmCMSPage
{

	/**
	 * @return string
	 */
	function generate()
	{
		return $this->renderTemplate('index.twig', $this->templateVars);
	}
}

?>
