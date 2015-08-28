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
	protected $param3;
	protected $itemId;
	protected $cmsModulePath = '/';


	use bmFlashSession;

	/**
	 * @param $application
	 * @param array $parameters
	 */
	public function __construct($application, $parameters = array())
	{
		parent::__construct($application, $parameters);
		if (!$this->isAdminUser() && !($this instanceof bmCMSLoginPage))
		{
			header("Location: /cms/login/?back=" . urlencode(join('/', $this->application->generator->pathSections)));
			exit;
		}

		$this->initTwig();
		$this->loadCmsConfig();

		if (!($this instanceof bmCMSLoginPage))
		{
			$this->filterCmsStructureWithPermissions();
			$pathSections = $this->application->generator->pathSections;
			if (array_key_exists(1, $pathSections))
			{
				$firstLevelConfigElement = $this->cmsConfig['structure'][$pathSections[1]];
				$this->cmsModulePath = $pathSections[1];
				if (array_key_exists('sections', $firstLevelConfigElement))
				{
					if (array_key_exists(2, $pathSections) && $pathSections[2] && $firstLevelConfigElement['sections'][$pathSections[2]])
					{
						$this->cmsModulePath .= '/' . $pathSections[2];
					}

					if (count($pathSections) == 2)
					{
						header('location: ./' . (array_keys($firstLevelConfigElement['sections'])[0]) . "/", true);
					}
				}
			}

			$this->templateVars['cmsStructure'] = $this->cmsConfig['structure'];
			$this->templateVars['currentPathSections'] = $this->application->generator->pathSections;
		}

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
				'debug' => true,
				'cache' => C_CACHE_TEMPLATES ? (projectRoot . 'generated/templateCache') : null,
			]
		);
		$this->twig->addExtension(new Twig_Extension_Debug());
	}

	/**
	 * @param $moduleConfigPath
	 *
	 * @return mixed
	 */
	protected function includeConfig($moduleConfigPath)
	{
		$lastFoundConfigPath = projectRoot . 'conf/cms/' . $moduleConfigPath . '/module.json';

		return json_decode(file_get_contents($lastFoundConfigPath), true);
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
	 * @return bool
	 */
	protected function isAdminUser()
	{
		if ($this->application->user->type >= BM_USER_TYPE_ADMIN)
		{
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	protected function checkPermissions()
	{
		if ($this->application->user->type >= BM_USER_TYPE_SUPERUSER)
		{
			return true;
		}
		$acl = $this->application->user->acl ? json_decode($this->application->user->acl, true) : [];
		if ($acl && array_key_exists($this->cmsModulePath, $acl))
		{
			if (!array_key_exists('/', $acl))
			{
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 *
	 */
	protected function filterCmsStructureWithPermissions()
	{
		if ($this->application->user->type >= BM_USER_TYPE_SUPERUSER)
		{
			return true;
		}
		$acl = $this->application->user->acl ? json_decode($this->application->user->acl, true) : [];
		if (!$acl)
		{
			$this->cmsConfig['structure'] = [];
		}
		foreach ($this->cmsConfig['structure'] as $sectionName => $data)
		{
			if (!array_key_exists($sectionName, $acl))
			{
				unset($this->cmsConfig['structure'][$sectionName]);
			}
			else
			{
				if (array_key_exists('sections', $this->cmsConfig['structure'][$sectionName]))
				{
					{
						foreach ($this->cmsConfig['structure'][$sectionName]['sections'] as $subSectionName => $data)
						{
							if (!array_key_exists($sectionName . '/' . $subSectionName, $acl))
							{
								unset($this->cmsConfig['structure'][$sectionName]['sections'][$subSectionName]);
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @param $map
	 * @param $object
	 * @param null $link
	 * @param null $parentObject
	 *
	 * @return mixed
	 */
	private function _recursionCloneObject(&$map, $object, $link = null, $parentObject = null)
	{
		$excludeField = ['identifier'];
		$objectClone = null;
		//Клонируем объект
		if (array_key_exists('clone', $map) && $map['clone'])
		{
			$objectName = 'bm' . ucfirst($object->objectName);
			$objectClone = new $objectName($this->application);

			foreach ($objectClone->map as $key => $param)
			{
				if (in_array($key, $excludeField))
				{
					// Убираем поля которые уникальны для каждого объекта
					continue;
				}

				// Поля которые должны генериться сами
				if (array_key_exists('excludedFields', $map) && is_array($map['excludedFields']) && in_array($key, $map['excludedFields']))
				{
					$objectClone->{$key} = $param['defaultValue'];
				}
				else
				{
					// Переносим поля
					$objectClone->{$key} = $object->{$key};
				}
			}

			//Сохраняем чтобы получить id
			$objectClone->save();
		}

		// Клонируем связи
		if ($parentObject)
		{
			if ($map['dataObject'] == 'image' && $link)
			{
				$obj = $objectClone ? $objectClone : $object;
				$obj->addLinkObject($link['object'], $parentObject->identifier, $link['group']);
			}
			elseif ($map['dataObject'] == 'file' && $link)
			{
				$obj = $objectClone ? $objectClone : $object;
				$obj->addLinkObject($link['object'], $parentObject->identifier, $link['group']);
			}
			elseif ($link)
			{
				//не доделали пока :(
			}
			else
			{
				$objId = $objectClone ? $objectClone->identifier : $object->identifier;
				$function = 'add' . ucfirst($map['dataObject']);
				$param = [
					$map['dataObject'] . 'Id' => $objId,
				];
				$parentObject->$function($param);
			}
		}


		if (array_key_exists('children', $map) && is_array($map['children']) && array_key_exists('clone', $map) && $map['clone'])
		{
			foreach ($map['children'] as $newMap)
			{
				if ($newMap['dataObject'] == 'image')
				{
					$method = 'getObjectImagesGroups';
				}
				elseif ($newMap['dataObject'] == 'file')
				{
					$method = 'getObjectFilesGroups';
				}
				else
				{
					$method = 'get' . ucfirst($newMap['dataObject']) . 's';
				}

				$listLink = $object->$method();
				if ($listLink)
				{
					foreach ($listLink as $link)
					{
						$linkMap = [];
						if ($link instanceof bmDataObject)
						{
							$obj = $link;
						}
						else
						{
							$obj = $link->{$newMap['dataObject']};

							foreach ($link as $key => $val)
							{
								if ($key == $newMap['dataObject'] || $key == $map['dataObject'])
								{
									continue;
								}
								$linkMap[$key] = $val;
							}
						}


						$this->_recursionCloneObject(
							$newMap,
							$obj,
							$linkMap,
							$objectClone ? $objectClone : $object
						);

					}
				}
			}
		}

		return $objectClone ? $objectClone->identifier : $object->identifier;

	}

	/**
	 * @return string
	 */
	private function _cloneObject()
	{
		$output = new stdClass();
		$output->id = $this->_recursionCloneObject($this->moduleConfig["cloneMap"], $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId));

		return json_encode($output);
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		if (!$this->checkPermissions())
		{
			return null;
		}

		switch ($_SERVER['REQUEST_METHOD'])
		{
			case "GET":
				if (array_key_exists('archives', $_GET))
				{
					$param = explode('-', $_GET['archives']);
					if (count($param) === 3 && $param[1] === 'archives')
					{
						return $this->archivesFiles($param[0], $param[2]);
					}
				}
				else
				{
					if (method_exists($this, 'generateGET'))
					{
						$generate = $this->generateGET();
						if ($generate)
						{
							return $generate;
						}
					}
				}
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
				if (array_key_exists('ajaxCms', $_POST))
				{
					$method = "_" . $_POST['ajaxCms'];
					unset($_POST['ajaxCms']);

					return $this->$method();
				}
				if (array_key_exists('images_file', $_POST))
				{
					return $this->addImage($_FILES);
				}
				elseif (array_key_exists('files_file', $_POST))
				{

					return $this->addFiles($_FILES);
				}
				elseif (array_key_exists('status', $_POST))
				{
					if ($this->itemId)
					{
						$object = $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId);
						if ($object)
						{
							$object->{$this->moduleConfig['list']['status']} = 1 - $object->{$this->moduleConfig['list']['status']};
							$object->save();
						}
					}
					if (method_exists($this, 'onAfterChange'))
					{
						$this->onAfterChange();
					}

					return "ok";
				}
				elseif (array_key_exists('event', $_POST) && $_POST['event'] === 'archivesFile')
				{
					return $this->archivesFileRestore();
				}
				elseif ($this->itemId)
				{
					$generate = false;
					if (method_exists($this, 'generatePOST'))
					{
						$generate = $this->generatePOST($this->itemId);
						if (method_exists($this, 'onAfterChange'))
						{
							$this->onAfterChange();
						}
					}

					if (!$generate)
					{
						$this->saveForm(null);
						if (method_exists($this, 'onAfterChange'))
						{
							$this->onAfterChange();
						}

						header('location: ./', true);
						exit;
					}


					return $generate;

				}
				break;
			case "DELETE":
				if ($this->itemId)
				{
					$object = $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId);
					if ($object)
					{
						$object->deleted = 1;
						$object->save();
					}
				}
				if (method_exists($this, 'onAfterChange'))
				{
					$this->onAfterChange();
				}

				return "ok";
				break;
			case "PUT":
				if (method_exists($this, 'generatePUT'))
				{
					$generate = $this->generatePUT();
					if ($generate)
					{
						return $generate;
					}
				}

				return $this->createObject();
				break;
		}
	}

	/**
	 * @return array
	 */
	protected function archivesFileRestore()
	{
		$object = $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId);

		$type = $_POST['type'];
		$group = $_POST['group'];
		$fileId = $_POST['fileId'];

		switch ($type)
		{
			case 'image':
				$object->deleteObjectImages($group, intval($fileId)); // Удаляем прежню картинку
			case 'images':
				$image = new bmImage($this->application, ['identifier' => intval($fileId)]);
				$image->deleted = BM_C_DELETE_OBJECT - 1;
				break;
			case 'files':
				$file = new bmFile($this->application, ['identifier' => intval($fileId)]);
				$file->deleted = BM_C_DELETE_OBJECT - 1;
				break;
		}

		return ['respons' => true];
	}

	/**
	 * @param $files
	 *
	 * @return string
	 */
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

				$imageField = $this->moduleConfig['form']['fields'][$_POST['group']];
				if (array_key_exists('sizes', $imageField))
				{
					$sizes = array_map('trim', explode(",", trim($imageField['sizes'])));
					foreach ($sizes as $size)
					{
						$errors->getImg($_POST['group'], $size, true);
					}
				}

				$this->application->rsync();

				$return = [
					'url' => $errors->getImg($_POST['group'], '80x80', true),
					'caption' => $errors->caption,
					'id' => $errors->identifier,
				];

				return json_encode($return);
			}
		}
	}

	/**
	 * @param $files
	 *
	 * @return string
	 */
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
				$this->application->rsync();

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
		$this->templateVars['listConfig'] = @$this->moduleConfig['list'];
		$this->templateVars['columns'] = $this->getFields(@$this->moduleConfig['list']['columns'], $this->moduleConfig);
		$order = @$this->moduleConfig['list']['order'] ?: [];
		$this->templateVars['objects'] = @$this->application->data->getObjectsByType($this->moduleConfig['dataObject'], [], $order, true);

		return $this->renderTemplate('list.twig', $this->templateVars);
	}

	/**
	 * @param $include
	 *
	 * @return array
	 */
	protected function displayFormNested($include)
	{
		$config = $this->includeConfig($include);
		$templateVars = [
			'objectName' => $config['dataObject'],
			'fields' => $this->getFields(@$config['form']['fields'], $config)
		];

		return $templateVars;
	}


	/**
	 * @param string $templateName
	 *
	 * @return bool|string
	 */
	protected function displayForm($templateName = 'form.twig', $form = null)
	{
		if (!$form)
		{
			$form = @$this->moduleConfig['form'];
		}
		$object = $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId);
		if ($object)
		{
			$this->templateVars['objectName'] = $this->moduleConfig['dataObject'];
			$this->templateVars['objectData'] = $object;

			$this->templateVars['fields'] = $this->getFields(@$form['fields'], $this->moduleConfig);
			foreach ($this->templateVars['fields'] as $field => $fieldInfo)
			{
				if ($fieldInfo['type'] == 'custom')
				{
					$method = 'formElement' . ucfirst($field);
					$class = 'bmCMC' . ucfirst($field);
					if (method_exists($this, $method))
					{
						$this->templateVars['fields'][$field]['html'] = $this->{$method}($fieldInfo, $object);
					}
					elseif (class_exists($class))
					{
						$class = new $class($fieldInfo, $object);
						$this->templateVars['fields'][$field]['include'] = $class->template;
						$this->templateVars['fields'][$field]['params'] = $class->getParams();
					}
					else
					{
						$this->templateVars['fields'][$field]['html'] = 'не доделали пока :(';
					}
				}

				if ($fieldInfo['type'] == 'form')
				{
					$this->templateVars['fields'][$field] = array_merge($this->templateVars['fields'][$field], $this->displayFormNested($fieldInfo['include']));
				}

				if ($fieldInfo['type'] == 'relation')
				{
					$relationMagicFieldName = $fieldInfo['to'] . 'Ids';
					$this->templateVars['fields'][$field]['bindedItems'] = $object->{$relationMagicFieldName}; // todo: что с кешированием тут?
					$items = $this->application->data->getObjectsByType($fieldInfo['to']);
					$this->templateVars['fields'][$field]['items'] = [];
					foreach ($items as $item)
					{
						$itemArray = $item->toArray();
						if (!array_key_exists('name', $itemArray))
						{
							if ($fieldInfo['name'])
							{
								$itemArray['name'] = '';
								foreach ($fieldInfo['name'] as $fieldToBuildName)
								{
									$itemArray['name'] .= (" " . $itemArray[$fieldToBuildName]);
								}
								$itemArray['name'] = trim($itemArray['name']);
							}
						}
						$method = 'formElementFilterCallback' . ucfirst($field);
						if (@$fieldInfo['filterCallback'] && method_exists($this, $method))
						{
							if ($this->{$method}($item))
							{
								$this->templateVars['fields'][$field]['items'][$item->identifier] = $itemArray['name'];
							}
						}
						else
						{
							$this->templateVars['fields'][$field]['items'][$item->identifier] = $itemArray['name'];
						}
					}

				}
			}
		}
		else
		{
			return false;
		}

		$this->templateVars['cloneMap'] = array_key_exists('cloneMap', $this->moduleConfig);
		$this->templateVars['errors'] = $this->getFlash("errors");
		$this->templateVars['formSaved'] = $this->getFlash('formSaved');

		return $this->renderTemplate($templateName, $this->templateVars);
	}

	/**
	 * @param $group
	 * @param $type
	 *
	 * @return bool|string
	 */
	protected function archivesFiles($group, $type)
	{

		$object = $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId);
		if ($object)
		{
			$this->templateVars['objectName'] = $this->moduleConfig['dataObject'];
			$this->templateVars['objectData'] = $object;
			$this->templateVars['group'] = $group;
			$this->templateVars['type'] = $type;

			$this->templateVars['fields'] = $this->getFields(@$this->moduleConfig['form']['fields'], $this->moduleConfig);
		}
		else
		{
			return false;
		}

		$this->templateVars['errors'] = $this->getFlash("errors");

		return $this->renderTemplate('archivesFiles.twig', $this->templateVars);
	}

	/**
	 *
	 */
	protected function saveForm($redirectTo = "./")
	{
		$object = $this->application->data->getObjectById($this->moduleConfig['dataObject'], $this->itemId);

		if ($object)
		{
			$fields = $this->getFields(@$this->moduleConfig['form']['fields'], $this->moduleConfig);
			//todo: бежать по полям из формы
			foreach ($fields as $field => $fieldInfo)
			{
				$value = @$_POST['cms-form-item'][$field];
				switch ($fieldInfo['type'])
				{
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
								$imageIsMain = $_POST['cms-image-main'][$value] == $key;
								$imageIsRemove = intval($_POST['cms-image-remove'][$value][$key]) == 1;
								$image = new bmImage($this->application, ['identifier' => $imageId]);
								if ($imageIsRemove)
								{
									$image->delete();
								}
								else
								{
									$image->caption = $imageCaption;
									$image->isMain = $imageIsMain;
								}
							}
						}

						break;
					case 'custom':
					case 'include':

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
								(!is_array($relationsFromForm) && intval($relationsFromForm) && intval($relationsFromForm) == $item->identifier)
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
						// Для площади можно вводить дробные значения с точкой и с запятой
						if ($field == "square")
						{
							$value = str_replace(",", ".", $value);
						}
						$object->{$field} = trim($value);

				}

				// todo: бежать не по ПОСТу, а по маппингу

			}

			$this->setFlash('formSaved', 'Изменения сохранены');
		}

		if ($redirectTo != null)
		{
			header('location: ' . $redirectTo, true);
			exit;
		}
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
	protected function getFields($list, $moduleConfig)
	{
		$className = 'bm' . ucfirst($moduleConfig['dataObject']);
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
				if (isset($moduleConfig['titles'][$field]))
				{
					$fields[$field]['title'] = $moduleConfig['titles'][$field];
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
