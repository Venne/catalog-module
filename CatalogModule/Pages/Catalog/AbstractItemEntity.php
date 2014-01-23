<?php

namespace CatalogModule\Pages\Catalog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AbstractItemEntity extends \BlogModule\Pages\Blog\AbstractArticleEntity
{

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


	protected function startup()
	{
		parent::startup();

		$this->types = new ArrayCollection;
		$this->labels = new ArrayCollection;
		$this->getRoute()->setPublished(FALSE);
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
