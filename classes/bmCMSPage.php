<?php

abstract class bmCMSPage extends bmHTMLPage
{
	/**
	 * @var Twig_Environment
	 */
	protected $twig;

	protected $cmsConfig = [];
	protected $moduleConfig = [];

	protected $templateVars = [];

	protected $param1;
	protected $param2;
	protected $itemId;


	use bmFlashSession;

	/**
	 * @param $application
	 * @param array $parameters
	 */
	public function __construct($application, $parameters = array())
	{

		parent::__construct($application, $parameters);

		$this->initTwig();
		$this->loadCmsConfig();

		if (@$this->cmsConfig['structure'][$this->application->generator->pathSections[1]]['sections'] && count($this->application->generator->pathSections) == 2)
		{
			header('location: ./' . (array_keys($this->cmsConfig['structure'][$this->application->generator->pathSections[1]]['sections'])[0]) . "/", true);
		}

		$this->templateVars['cmsStructure'] = $this->cmsConfig['structure'];
		$this->templateVars['currentPathSections'] = $this->application->generator->pathSections;

	}

	/**
	 * @param $templateName
	 * @param $templateParams
	 *
	 * @return string
	 */
	protected function renderTemplate($templateName, $templateParams)
	{
		return $this->twig->render($templateName, $templateParams);
	}

	/**
	 *
	 */
	private function initTwig()
	{
		$loader = new Twig_Loader_Filesystem(projectRoot . 'templates/cms');
		$this->twig = new Twig_Environment(
			$loader, [
				'cache' => C_CACHE_TEMPLATES ? (projectRoot . 'generated/templateCache') : null,
			]
		);
	}

	/**
	 *
	 */
	protected function loadCmsConfig()
	{
		$this->cmsConfig = json_decode(file_get_contents(projectRoot . 'conf/cms/cms.json'), true);

		//try to load module config
		$moduleConfigPath = '';
		$lastFoundConfigPath = '';

		$lastFoundIteratorValue = 0;
		for ($iterator = 1; $iterator <= 2; $iterator++)
		{
			if (@$this->application->generator->pathSections[$iterator])
			{
				$moduleConfigPath .= ($this->application->generator->pathSections[$iterator] . '/');
				if (is_file(projectRoot . 'conf/cms/' . $moduleConfigPath . 'module.json'))
				{
					$lastFoundConfigPath = projectRoot . 'conf/cms/' . $moduleConfigPath . 'module.json';
					$lastFoundIteratorValue = $iterator;
				}
			}

		}
		if ($lastFoundConfigPath)
		{
			$this->moduleConfig = json_decode(file_get_contents($lastFoundConfigPath), true);
		}

		if ($lastFoundIteratorValue == 1)
		{
			$this->itemId = $this->param1;
		}
		elseif ($lastFoundIteratorValue == 2)
		{
			$this->itemId = $this->param2;
		}
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		switch ($_SERVER['REQUEST_METHOD'])
		{
			case "GET":
				if ($this->itemId)
				{
					return $this->displayForm();
				}
				else
				{
					return $this->displayList();
				}
				break;
			case "POST":
				if (array_key_exists('images_file', $_POST))
				{

					return $this->addImage($_FILES);
				}
				elseif (array_key_exists('files_file', $_POST))
				{

					return $this->addFiles($_FILES);
				}
				elseif ($this->itemId)
				{
					return $this->saveForm();
				}
				break;
			case "PUT":
				return $this->createObject();
				break;
		}
	}

	protected function addImage($files)
	{
		$object = $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId);

		if (array_key_exists('images', $files))
		{
			$errors = $object->addObjectImage($_POST['group'], $files['images']);
			if ($errors && is_string($errors))
			{
				return json_encode(['error' => $errors]);
			}
			elseif ($errors)
			{
				if ($_POST['multiple'] == 'image')
				{
					$object->deleteObjectImages($_POST['group'], $errors->identifier);
				}
				$return = [
					'url' => $errors->getImg($_POST['group'], '200x200', true),
					'caption' => $errors->caption,
					'id' => $errors->identifier,
				];
				return json_encode($return);
			}
		}
	}

	protected function addFiles($files)
	{
		$object = $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId);

		if (array_key_exists('images', $files))
		{
			$errors = $object->addObjectFile($_POST['group'], $files['images']);
			if ($errors && is_string($errors))
			{
				return json_encode(['error' => $errors]);
			}
			elseif ($errors)
			{
				$return = [
					'url' => $errors->getFile($_POST['group']),
					'caption' => $errors->caption,
					'id' => $errors->identifier,
				];
				return json_encode($return);
			}
		}
	}

	/**
	 *
	 */
	protected function displayList()
	{
		$this->templateVars['columns'] = $this->getFields(@$this->moduleConfig['list']['columns']);
		$this->templateVars['objects'] = @$this->application->data->getObjectsByType($this->moduleConfig['dataObject'], [], [], true);

		return $this->renderTemplate('list.twig', $this->templateVars);
	}


	/**
	 *
	 */
	protected function displayForm()
	{

		$object = $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId);
		if ($object)
		{
			$this->templateVars['objectName'] = $this->moduleConfig['dataObject'];
			$this->templateVars['objectData'] = $object;

			$this->templateVars['fields'] = $this->getFields(@$this->moduleConfig['form']['fields']);
			foreach ($this->templateVars['fields'] as $field => $fieldInfo)
			{
				if ($fieldInfo['type'] == 'custom')
				{
					$method = 'formElement' . ucfirst($field);
					if (method_exists($this, $method))
					{
						$this->templateVars['fields'][$field]['html'] = $this->{$method}($fieldInfo, $object);
					} else {
						$this->templateVars['fields'][$field]['html'] = 'не доделали пока :(';
					}
				}

				if ($fieldInfo['type'] == 'relation')
				{
					$relationMagicFieldName = $fieldInfo['to'] . 'Ids';
					$this->templateVars['fields'][$field]['bindedItems'] = $object->{$relationMagicFieldName}; // todo: что с кешированием тут?
					$items = $this->application->data->getObjectsByType($fieldInfo['to']);
					$this->templateVars['fields'][$field]['items'] = [];
					foreach ($items as $item)
					{
						$this->templateVars['fields'][$field]['items'][$item->identifier] = $item->name;
					}

				}
			}
		}
		else
		{
			return false;
		}

		$this->templateVars['errors'] = $this->getFlash("errors");

		return $this->renderTemplate('form.twig', $this->templateVars);
	}

	/**
	 *
	 */
	protected function saveForm()
	{
		$object = $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId);

		if ($object)
		{
			$fields = $this->getFields(@$this->moduleConfig['form']['fields']);
			//todo: бежать по полям из формы
			foreach ($fields as $field => $fieldInfo)
			{
				$value = @$_POST['cms-form-item'][$field];
				switch ($fieldInfo['type'])
				{
					case 'password':
						continue;
						break;
					case 'checkbox':
						$object->{$field} = !!intval($value);
						break;
					case 'images':
					case 'image':
						if (
							array_key_exists('cms-image-id', $_POST)
							&& array_key_exists($field, $_POST['cms-image-id'])
						)
						{
							foreach ($_POST['cms-image-id'][$value] as $key => $imageId)
							{
								$imageId = intval($imageId);
								$imageCaption = $_POST['cms-image-caption'][$value][$key];
								$imageIsRemove = intval($_POST['cms-image-remove'][$value][$key]) == 1;
								$image = new bmImage($this->application, ['identifier' => $imageId]);
								if ($imageIsRemove)
								{
									$image->delete();
								}
								else
								{
									$image->caption = $imageCaption;
								}
							}
						}

						break;
					case 'files':
						if (
							array_key_exists('cms-file-id', $_POST)
							&& array_key_exists($field, $_POST['cms-file-id'])
						)
						{
							foreach ($_POST['cms-file-id'][$value] as $key => $imageId)
							{
								$imageId = intval($imageId);
								$imageCaption = $_POST['cms-file-caption'][$value][$key];
								$imageIsRemove = intval($_POST['cms-file-remove'][$value][$key]) == 1;
								$image = new bmFile($this->application, ['identifier' => $imageId]);
								if ($imageIsRemove)
								{
									$image->delete();
								}
								else
								{
									$image->caption = $imageCaption;
								}
							}
						}

						break;
					case 'relation':
						$relationsFromForm = @$_POST['cms-form-item-relation'][$field];
						$relationMagicFieldName = $fieldInfo['to'] . 'Ids';
						$bindedItems = $object->{$relationMagicFieldName};
						$items = $this->application->data->getObjectsByType($fieldInfo['to']);
						$itemsToAdd = [];
						$itemsToRemove = [];
						foreach ($items as $item)
						{
							if (
								(is_array($relationsFromForm) && array_key_exists($item->identifier, $relationsFromForm) && $relationsFromForm[$item->identifier])
								||
								(intval($relationsFromForm) && intval($relationsFromForm) == $item->identifier)
							)
							{
								if (!in_array($item->identifier, $bindedItems))
								{
									$itemsToAdd[] = $item->identifier;
								}
							}
							else
							{
								if (in_array($item->identifier, $bindedItems))
								{
									$itemsToRemove[] = $item->identifier;
								}
							}
						}
						// detect ref table name
						$variant1 = 'link_' . $this->moduleConfig['dataObject'] . '_' . $fieldInfo['to'];
						$variant2 = 'link_' . $fieldInfo['to'] . '_' . $this->moduleConfig['dataObject'];

						if ($this->application->dataLink->select('SHOW TABLES LIKE "' . $variant1 . '"')->rowCount())
						{
							$linkTable = $variant1;
						}
						elseif ($this->application->dataLink->select('SHOW TABLES LIKE "' . $variant2 . '"')->rowCount())
						{
							$linkTable = $variant2;
						}
						else
						{
							trigger_error('No link table for relation'); // такого быть не должно теоретически
						}

						$foreignField = $fieldInfo['to'] . 'Id'; //todo: знаю, что не всегда корректно. Потом переделаем )
						$selfField = $this->moduleConfig['dataObject'] . 'Id'; //todo: знаю, что не всегда корректно. Потом переделаем )

						if ($itemsToRemove)
						{
							$this->application->dataLink->query('DELETE FROM ' . $linkTable . ' WHERE ' . $foreignField . ' IN (' . join(',', $itemsToRemove) . ') AND ' . $selfField . '=' . $this->itemId);
						}
						if ($itemsToAdd)
						{
							foreach ($itemsToAdd as $itemId)
							{
								$this->application->dataLink->query('INSERT ' . $linkTable . ' (' . $selfField . ', ' . $foreignField . ')  VALUES(' . $this->itemId . ', ' . $itemId . ')');
							}
						}

					default:
						$object->{$field} = trim($value);

				}

				// todo: бежать не по ПОСТу, а по маппингу

			}
		}

		header('location: ./', true);
		exit;
	}

	/**
	 * @return string
	 */
	protected function createObject()
	{
		$className = 'bm' . ucfirst($this->moduleConfig['dataObject']);
		$object = new $className($this->application);
		return json_encode(['id' => $object->identifier]);
	}

	/**
	 * Получение полноценного списка полей, с заполненной метаинформацией
	 * @return array
	 */
	protected function getFields($list)
	{
		$className = 'bm' . ucfirst($this->moduleConfig['dataObject']);
		$object = new $className($this->application, array('readonly' => true));

		if (isset($list) && is_array($list))
		{
			if (bmTools::isAssoc($list))
			{
				$fields = $list;
			}
			else
			{
				$fields = array_flip($list);
			}
		}
		else
		{
			$fields = array_flip(array_keys($object->map));
			// нам в форме эти поля совсем ни к чему!
			unset($fields['identifier']);
			unset($fields['deleted']);
		}
		foreach ($fields as $field => $fieldInfo)
		{
			if (!is_array($fields[$field]))
			{
				$fields[$field] = [];
			}
			if (!isset($fields[$field]['title']))
			{
				if (isset($this->moduleConfig['titles'][$field]))
				{
					$fields[$field]['title'] = $this->moduleConfig['titles'][$field];
				}
				else
				{
					$fields[$field]['title'] = $field;
				}

			}
			if (!array_key_exists('type', $fields[$field]))
			{
				$fields[$field]['type'] = $this->getFieldTextType($object->map[$field]['dataType']);
			}
		}

		return $fields;
	}

	/**
	 * @param $dataType
	 *
	 * @return string
	 */
	private function getFieldTextType($dataType)
	{
		switch ($dataType)
		{
			case BM_VT_PASSWORD:
				return "password";
				break;
			case BM_VT_TEXT:
				return "text";
				break;
			case BM_VT_DATETIME:
				return "datetime";
				break;

		}
	}


}

?>
