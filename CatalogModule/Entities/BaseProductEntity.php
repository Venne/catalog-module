<?php

namespace CatalogModule\Entities;

use Venne;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\MappedSuperclass
 */
class BaseProductEntity extends BaseEntity
{

	/**
	 * @var CategoryEntity
	 * @ORM\ManyToOne(targetEntity="CategoryEntity", inversedBy="mainProducts")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $category;

	/**
	 * @var CategoryEntity[]
	 * @ORM\ManyToMany(targetEntity="CategoryEntity", inversedBy="products")
	 * @ORM\JoinTable(
	 *      joinColumns={@ORM\JoinColumn(referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(referencedColumnName="id")}
	 *      )
	 */
	protected $categories;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection|TypeEntity[]
	 * @ORM\OneToMany(targetEntity="\CatalogModule\Entities\TypeEntity",mappedBy="product", cascade={"all"}, orphanRemoval=true)
	 */
	protected $types;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection|LabelEntity[]
	 * @ORM\ManyToMany(targetEntity="\CatalogModule\Entities\LabelEntity")
	 * @ORM\JoinTable(
	 *      joinColumns={@ORM\JoinColumn(referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(referencedColumnName="id", unique=true)}
	 *      )
	 **/
	protected $labels;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $price;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $rrp;

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $inStock;


	public function __construct(PageEntity $pageEntity, $name)
	{
		parent::__construct($pageEntity, $name);

		$this->createRoute('Catalog:Product:default');
		$this->setName($name);

		$this->description = '';
		$this->types[] = new TypeEntity('default', $this);
		$this->labels = new ArrayCollection;
	}


	/**
	 * @param \CatalogModule\Entities\CategoryEntity $category
	 */
	public function setCategory(CategoryEntity $category = NULL)
	{
		if ($category == $this->category) {
			return;
		}

		$this->category = $category;
		$this->route->setParent($category ? $category->route : $this->page->mainRoute);
	}


	/**
	 * @return \CatalogModule\Entities\CategoryEntity
	 */
	public function getCategory()
	{
		return $this->category;
	}


	/**
	 * @return CategoryEntity[]
	 */
	public function getCategories()
	{
		return $this->categories;
	}


	/**
	 * @param $labels
	 */
	public function setLabels($labels)
	{
		$this->labels = $labels;
	}


	/**
	 * @return LabelEntity[]|\Doctrine\Common\Collections\ArrayCollection
	 */
	public function getLabels()
	{
		return $this->labels;
	}


	/**
	 * @param CategoryEntity[] $category
	 */
	public function setCategories($categories)
	{
		$this->categories = $categories;
	}


	public function setPrice($price)
	{
		$this->price = $price * 1000;
	}


	public function getPrice()
	{
		return floatval($this->price) / 1000;
	}


	public function setRrp($rrp)
	{
		if (!$rrp) {
			$this->rrp = NULL;
			return;
		}

		$this->rrp = $rrp * 1000;
	}


	public function getRrp()
	{
		if ($this->rrp === NULL) {
			return NULL;
		}

		return floatval($this->rrp) / 1000;
	}


	public function getSavedPercent()
	{
		return round(100 - ($this->price / ($this->rrp / 100)));
	}


	public function getSaved()
	{
		return $this->rrp - $this->price;
	}


	/**
	 * @param int $inStock
	 */
	public function setInStock($inStock)
	{
		$this->inStock = $inStock === '' ? NULL : $inStock;
	}


	/**
	 * @return int
	 */
	public function getInStock()
	{
		return $this->inStock;
	}


	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|array
	 */
	public function getAllTypes()
	{
		if (!$this->category) {
			return $this->getTypes();
		}

		$ret = $this->category->getAllTypes();
		foreach ($this->types as $type) {
			$ret->add($type);
		}
		return $ret;
	}
}

