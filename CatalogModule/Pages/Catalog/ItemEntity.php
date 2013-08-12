<?php

namespace CatalogModule\Pages\Catalog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use GalleryModule\Pages\Gallery\AbstractItemEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CatalogModule\Pages\Catalog\ItemRepository")
 * @ORM\Table(name="catalogProduct")
 */
class ItemEntity extends AbstractItemEntity
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
	 *      joinColumns={@ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")}
	 *      )
	 */
	protected $categories;

	/**
	 * @var TypeEntity[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="TypeEntity",mappedBy="product", cascade={"all"}, orphanRemoval=true)
	 */
	protected $types;

	/**
	 * @var LabelEntity[]
	 * @ORM\ManyToMany(targetEntity="LabelEntity")
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


	public function startup()
	{
		parent::startup();

		$this->types = new ArrayCollection;
		$this->labels = new ArrayCollection;
		$this->getRoute()->setPublished(FALSE);
	}


	/**
	 * @param CategoryEntity $category
	 */
	public function setCategory($category)
	{
		if ($category == $this->category) {
			return;
		}

		$this->category = $category;
		$this->route->setParent($category ? $category->route : $this->page->mainRoute);
	}


	/**
	 * @return CategoryEntity
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
	 * @return LabelEntity[]
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
		return -round(100 - ($this->price / ($this->rrp / 100)));
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
	 * @return ArrayCollection|array
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
