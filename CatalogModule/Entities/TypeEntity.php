<?php

namespace CatalogModule\Entities;

use Venne;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CatalogModule\Repositories\TypeRepository")
 * @ORM\Table(name="types")
 */
class TypeEntity extends \DoctrineModule\Entities\IdentifiedEntity
{

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var CategoryEntity
	 * @ORM\ManyToOne(targetEntity="CategoryEntity")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $category;

	/**
	 * @var CategoryEntity
	 * @ORM\ManyToOne(targetEntity="ProductEntity")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $product;


	public function __construct($name, $parent)
	{
		$this->name = $name;

		if ($parent instanceof ProductEntity) {
			$this->product = $parent;
		} else if ($parent instanceof CategoryEntity) {
			$this->category = $parent;
		} else if (is_object($parent)) {
			throw new \Nette\InvalidArgumentException('Second argument must be instance of ProductEntity OR CategoryEntity. ' . get_class($parent) . 'is given.');
		}
	}


	public function __toString()
	{
		return $this->name;
	}


	public function getName()
	{
		return $this->name;
	}


	public function setName($name)
	{
		$this->name = $name;
	}
}

