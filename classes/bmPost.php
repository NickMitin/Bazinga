<?php

/**
 * Copyright (c) 2014, "The Blind Mice Studio"
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

/**
 * Class bmPost
 *
 * @property int $identifier
 *  default = 0
 * @property int $deleted
 *  default = 0
 * @property string $name
 *  default = null
 * @property string $url
 *  default = null
 * @property int $order
 *  default = null
 * @property int $active
 *  default = 1
 * @property string $text
 *  default = null
 * @property string $text2
 *  default = null
 *
 */
final class bmPost extends bmDataObject
{
	public function __construct($application, $parameters = array())
	{
		/*FF::AC::MAPPING::{*/

      $this->objectName = 'post';
      $this->map = array_merge($this->map, array(
				'name' => array(
					'fieldName' => 'name',
					'dataType' => BM_VT_STRING,
					'defaultValue' => ''
				),
				'url' => array(
					'fieldName' => 'url',
					'dataType' => BM_VT_STRING,
					'defaultValue' => ''
				),
				'order' => array(
					'fieldName' => 'order',
					'dataType' => BM_VT_INTEGER,
					'defaultValue' => 0
				),
				'active' => array(
					'fieldName' => 'active',
					'dataType' => BM_VT_INTEGER,
					'defaultValue' => 1
				),
				'text' => array(
					'fieldName' => 'text',
					'dataType' => BM_VT_TEXT,
					'defaultValue' => ''
				),
				'text2' => array(
					'fieldName' => 'text2',
					'dataType' => BM_VT_TEXT,
					'defaultValue' => ''
				)
      ));

      /*FF::AC::MAPPING::}*/

		parent::__construct($application, $parameters);
	}

	public function __get($propertyName)
	{
		$this->checkDirty();

		switch ($propertyName)
		{
			/*FF::AC::TOP::GETTER::{*/
        
        /*FF::AC::GETTER_CASE::section::{*/
        case 'sectionIds':
          if (!array_key_exists('sectionIds', $this->properties))
          {
            $this->properties['sectionIds'] = $this->getSections(false);
          }
          return $this->properties['sectionIds'];
        break;
        case 'sections':
          return $this->getSections();
        break;
        /*FF::AC::GETTER_CASE::section::}*/
        /*FF::AC::GETTER_CASE::tag::{*/
        case 'tagIds':
          if (!array_key_exists('tagIds', $this->properties))
          {
            $this->properties['tagIds'] = $this->getTags(false);
          }
          return $this->properties['tagIds'];
        break;
        case 'tags':
          return $this->getTags();
        break;
        /*FF::AC::GETTER_CASE::tag::}*/
 
        /*FF::AC::TOP::GETTER::}*/
			default:
				return parent::__get($propertyName);
				break;
		}
	}

	/*FF::AC::TOP::REFERENCE_FUNCTIONS::{*/
    
    /*FF::AC::REFERENCE_FUNCTIONS::section::{*/        
        
    /**
	 * @param bool $load
	 * @param bool $param
	 *  $param['where'] Условия отбора
	 *  $param['order'] Условия сортировки
	 *  $param['group'] Условия гуперовки
	 *  $param['having'] Условия гуперовки
	 *  $param['offset']
	 *  $param['limit']
	 *
	 * @return array|bool
	 */
	public function getSections($load = true, $param = null)
	{
		$paramObject = [
			'object' => 'section',
			'fields' => [],
			'objects' => [],
			'param' => $param,
		];
		return parent::getMethodObjects("link_post_section", $paramObject, $load);
	}

    /**
	 * $param['sectionId']
	 *
	 * @param $param
	 *
	 * @return $this
	 */
	public function addSection($param)
	{
		return parent::addMethodObject('section', $param);
	}

	/**
	 * @param $sectionId
	 *
	 * @return $this
	 */
	public function removeSection($sectionId)
	{
		return parent::removeMethodObject('section', $sectionId, false);
	}

	/**
	 * @return $this
	 */
	public function removeSections()
	{
		return parent::removesMethodObject('section');
	}

	/**
	 * @return $this
	 */
	protected function saveSections()
	{
		$link = "link_post_section";
		$param = [
			'object' => 'section',
			'fields' => [],
			'objects' => [],
		];
		return parent::saveMethodObject($link, $param);
	}
    
    /*FF::AC::REFERENCE_FUNCTIONS::section::}*/

    /*FF::AC::REFERENCE_FUNCTIONS::tag::{*/        
        
    /**
	 * @param bool $load
	 * @param bool $param
	 *  $param['where'] Условия отбора
	 *  $param['order'] Условия сортировки
	 *  $param['group'] Условия гуперовки
	 *  $param['having'] Условия гуперовки
	 *  $param['offset']
	 *  $param['limit']
	 *
	 * @return array|bool
	 */
	public function getTags($load = true, $param = null)
	{
		$paramObject = [
			'object' => 'tag',
			'fields' => [],
			'objects' => [],
			'param' => $param,
		];
		return parent::getMethodObjects("link_post_tag", $paramObject, $load);
	}

    /**
	 * $param['tagId']
	 *
	 * @param $param
	 *
	 * @return $this
	 */
	public function addTag($param)
	{
		return parent::addMethodObject('tag', $param);
	}

	/**
	 * @param $tagId
	 *
	 * @return $this
	 */
	public function removeTag($tagId)
	{
		return parent::removeMethodObject('tag', $tagId, false);
	}

	/**
	 * @return $this
	 */
	public function removeTags()
	{
		return parent::removesMethodObject('tag');
	}

	/**
	 * @return $this
	 */
	protected function saveTags()
	{
		$link = "link_post_tag";
		$param = [
			'object' => 'tag',
			'fields' => [],
			'objects' => [],
		];
		return parent::saveMethodObject($link, $param);
	}
    
    /*FF::AC::REFERENCE_FUNCTIONS::tag::}*/


    /*FF::AC::TOP::REFERENCE_FUNCTIONS::}*/

	/*FF::AC::DELETE_FUNCTION::{*/        
        
    public function delete()
    {
      $this->removeSections();

      $this->removeTags();

      
      
      
      
      $this->application->cacheLink->delete($this->objectName . '_' . $this->properties['identifier']); 
      
      $sql = "DELETE FROM 
                `post` 
              WHERE 
                `id` = " . $this->properties['identifier'] . ";
              ";
      
      $this->application->dataLink->query($sql);
    }
    
    /*FF::AC::DELETE_FUNCTION::}*/
}

?>