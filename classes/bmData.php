<?php

/*
  * Copyright (c) 2009, "The Blind Mice Studio"
  * All rights reserved.
  * 
  * Redistribution and use in source and binary forms, with or without
  * modification, are permitted provided that the following conditions are met:
  * - Redistributions of source code must retain the above copyright
  *   notice, this list of conditions and the following disclaimer.
  * - Redistributions in binary form must reproduce the above copyright
  *   notice, this list of conditions and the following disclaimer in the
  *   documentation and/or other materials provided with the distribution.
  * - Neither the name of the "The Blind Mice Studio" nor the
  *   names of its contributors may be used to endorse or promote products
  *   derived from this software without specific prior written permission.

  * THIS SOFTWARE IS PROVIDED BY "The Blind Mice Studio" ''AS IS'' AND ANY
  * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  * DISCLAIMED. IN NO EVENT SHALL "The Blind Mice Studio" BE LIABLE FOR ANY
  * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
  * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
  * 
  */

final class bmData extends bmFFData
{
	public function __construct($application, $parameters = array())
	{
		parent::__construct($application, $parameters);
	}

	public function __get($propertyName)
	{
		switch ($propertyName)
		{
			default:
				return parent::__get($propertyName);
				break;
		}
	}

	/**
	 * @param $objectName
	 * @param array $filter
	 * @param array $order
	 *
	 * @param bool $returnAsArray
	 *
	 * @return bmDataObject[]
	 */
	public function getObjectsByType($objectName, $filter = [], $order = [], $returnAsArray = false)
	{
		$cacheKey = '';
		/*		if ($returnAsArray)
				{
					$cacheKey = $objectName . "_" . serialize($filter) . "_" . serialize($order) . "_" . intval($returnAsArray);
					$result = $this->application->cacheLink->get($cacheKey);
					if ($result)
					{
						return $result;
					}
				}*/
		$result = [];
		$className = 'bm' . ucfirst($objectName);
		$object = new $className($this->application, array('readonly' => true));

		$fieldsSQL = $object->fieldsToSQL(); //todo: только те поля, которые нужны

		$additionalWhere = "";
		if ($filter)
		{
			$additionalWhere = join(" AND", $filter);
		}
		if (count($filter) == 1)
		{
			$additionalWhere = " AND " . $additionalWhere;
		}

		if (is_array($order))
		{
			$order = join(", ", $order);
		}
		$orderBy = $order ?: "`identifier`";
		$sql = "SELECT " . $fieldsSQL . " FROM `" . $objectName . "` WHERE deleted = 0 {$additionalWhere} ORDER BY " . $orderBy;

		$qObjects = $this->application->dataLink->select($sql);

		while ($object = $qObjects->nextObject())
		{
			$result[] = $returnAsArray ? get_object_vars($object) : new $className($this->application, get_object_vars($object));
		}

		$qObjects->free();

		if ($cacheKey)
		{
			$this->application->cacheLink->set($cacheKey, $result);
		}

		return $result;
	}

	/**
	 * @param $objectName
	 * @param $id
	 *
	 * @return null | bmDataObject
	 */
	public function getObjectById($objectName, $id)
	{
		/** @var bmDataObject $object */
		$className = 'bm' . ucfirst($objectName);
		$object = new $className($this->application, array('readonly' => true));

		$foundObjectId = $object->getObjectIdByField('id', $id);

		if ($foundObjectId)
		{
			return new $className($this->application, array('identifier' => $foundObjectId));
		}

		return null;
	}

	/**
	 * @param $objectName
	 * @param $field
	 * @param $value
	 *
	 * @return bmDataObject|null
	 */
	public function getObjectByField($objectName, $field, $value)
	{
		return $this->getObjectById($objectName, $this->application->getObjectIdByFieldName($objectName, $field, $value));
	}

	public function getObjectByFields($objectName, $fields)
	{
		return $this->getObjectById($objectName, $this->application->getObjectIdByFieldNames($objectName, $fields));
	}
}

?>