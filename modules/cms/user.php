<?php

final class bmCMSUserPage extends bmCMSPage
{

	protected function generatePOST($itemId)
	{
		if ($itemId)
		{
			$user = new bmUser($this->application, ['identifier' => $itemId]);
			$user->acl = json_encode($_POST['ACL']);
			$user->save();
		}
	}

	protected function formElementAcl($fieldInfo, $object)
	{
		$this->templateVars['structure'] = $this->cmsConfig['structure'];
		$this->templateVars['acl'] = $object->acl ? json_decode($object->acl) : [];

		return $this->renderTemplate('userAcl.twig', $this->templateVars);
	}

}

?>

