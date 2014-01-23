<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Pages\Catalog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractCategoryEntity extends \BlogModule\Pages\Blog\AbstractCategoryEntity
{

	/**
	 * @var TypeEntity[]
	 * @ORM\OneToMany(targetEntity="TypeEntity", mappedBy="category", cascade={"all"}, orphanRemoval=true)
	 */
	protected $types;


	protected function startup()
	{
		parent::startup();

		$this->types = new ArrayCollection;
	}


	/**
	 * @param TypeEntity[] $types
	 */
	public function setTypes($types)
	{
		$this->types = $types;
	}


	/**
	 * @return TypeEntity[]
	 */
	public function getTypes()
	{
		return $this->types;
	}


	/**
	 * @param array $types
	 */
	public function setTypesAsArray($types)
	{
		$this->types->clear();
		foreach ($types as $type) {
			if ($type) {
				$this->types[] = new TypeEntity($type, $this);
			}
		}
	}


	/**
	 * @return array
	 */
	public function getTypesAsArray()
	{
		$ret = array();
		foreach ($this->types as $type) {
			$ret[] = $type->name;
		}
		return $ret;
	}


	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|array
	 */
	public function getAllTypes()
	{
		if (!$this->parent) {
			return $this->getTypes();
		}

		$ret = $this->parent->getAllTypes();
		foreach ($this->types as $type) {
			$ret->add($type);
		}
		return $ret;
	}
}
