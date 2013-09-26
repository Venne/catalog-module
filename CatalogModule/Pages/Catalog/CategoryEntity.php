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
 * @ORM\Entity(repositoryClass="\CatalogModule\Pages\Catalog\CategoryRepository")
 * @ORM\Table(name="catalogCategory")
 */
class CategoryEntity extends \GalleryModule\Pages\Gallery\AbstractCategoryEntity
{

	/**
	 * @var CategoryEntity
	 * @ORM\ManyToOne(targetEntity="CategoryEntity", inversedBy="children")
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $parent;

	/**
	 * @var CategoryEntity[]
	 * @ORM\OneToMany(targetEntity="CategoryEntity", mappedBy="parent")
	 */
	protected $children;

	/**
	 * @var ItemEntity[]
	 * @ORM\OneToMany(targetEntity="ItemEntity", mappedBy="category", cascade={"persist"}, orphanRemoval=true)
	 * @ORM\OrderBy({"position" = "ASC"})
	 */
	protected $items;

	/**
	 * @var ItemEntity[]
	 * @ORM\ManyToMany(targetEntity="ItemEntity", mappedBy="categories")
	 */
	protected $products;

	/**
	 * @var TypeEntity[]
	 * @ORM\OneToMany(targetEntity="TypeEntity", mappedBy="category", cascade={"all"}, orphanRemoval=true)
	 */
	protected $types;


	protected function startup()
	{
		parent::startup();

		$this->children = new ArrayCollection;
		$this->products = new ArrayCollection;
		$this->items = new ArrayCollection;
		$this->types = new ArrayCollection;
	}


	/**
	 * @return CategoryEntity
	 */
	public function getParent()
	{
		return $this->parent;
	}


	/**
	 * @param $parent
	 */
	public function setParent($parent)
	{
		$this->parent = $parent;
		$this->route->setParent($parent ? $parent->getRoute() : $this->page->mainRoute);
	}


	/**
	 * @return CategoryEntity[]
	 */
	public function getChildren()
	{
		return $this->children;
	}


	/**
	 * @param $children
	 */
	public function setChildren($children)
	{
		$this->children = $children;
	}


	/**
	 * @return ItemEntity[]
	 */
	public function getProducts()
	{
		return $this->products;
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
