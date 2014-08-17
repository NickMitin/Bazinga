<?php

final class bmCMSIndexPage extends bmCMSPage
{

	/**
	 * @return string
	 */
	function generateGET()
	{
		
		return $this->renderTemplate('index.twig', $this->templateVars);
	}
}

?>
