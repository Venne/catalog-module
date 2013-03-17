<?php

namespace CatalogModule\Entities;

use Venne;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CatalogModule\Repositories\ProductRepository")
 * @ORM\Table(name="catalogProducts")
 */
class ProductEntity extends BaseProductEntity
{

	/**
	 * @var CategoryEntity[]
	 * @ORM\ManyToMany(targetEntity="CategoryEntity", inversedBy="products")
	 * @ORM\JoinTable(name="category_has_product",
	 *      joinColumns={@ORM\JoinColumn(referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(referencedColumnName="id")}
	 *      )
	 */
	protected $categories;

}

