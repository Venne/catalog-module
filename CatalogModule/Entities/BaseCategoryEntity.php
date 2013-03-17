<?php

namespace CatalogModule\Entities;

use Venne;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class BaseCategoryEntity extends BaseEntity
{

	const PRODUCT_CLASS = 'CatalogModule\Entities\ProductEntity';

	/**
	 * @var CategoryEntity
	 * @ORM\ManyToOne(targetEntity="CategoryEntity", inversedBy="children")
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $parent;

	/**
	 * @var CategoryEntity[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="CategoryEntity", mappedBy="parent")
	 */
	protected $children;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\ManyToMany(targetEntity="ProductEntity", mappedBy="categories")
	 */
	protected $products;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="ProductEntity", mappedBy="category")
	 */
	protected $mainProducts;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="\CatalogModule\Entities\TypeEntity", mappedBy="category", cascade={"all"}, orphanRemoval=true)
	 */
	protected $types;


	public function __construct(PageEntity $page, $name)
	{
		parent::__construct($page, $name);

		$this->createRoute('Catalog:Category:default');
		$this->setName($name);

		$this->description = '';
		$this->children = new ArrayCollection;
		$this->products = new ArrayCollection;
		$this->mainProducts = new ArrayCollection;
	}


	public function __toString()
	{
		return $this->name;
	}


	/**
	 * @return CategoryEntity
	 */
	public function getParent()
	{
		return $this->parent;
	}


	/**
	 * @param CategoryEntity $parent
	 */
	public function setParent($parent)
	{
		$this->parent = $parent;
		$this->route->setParent($parent ? $parent->getRoute() : $this->page->mainRoute);
	}


	/**
	 * @return CategoryEntity
	 */
	public function getChildren()
	{
		return $this->children;
	}


	/**
	 * @param CategoryEntity $children
	 */
	public function setChildren($children)
	{
		$this->children = $children;
	}


	/**
	 * @return ProductEntity
	 */
	public function getProducts()
	{
		return $this->products;
	}


	public function addProduct($name)
	{
		$class = '\\' . self::PRODUCT_CLASS;
		$this->products[] = new $class($this, $name);
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

