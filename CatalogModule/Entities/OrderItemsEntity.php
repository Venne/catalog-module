<?php

namespace CatalogModule\Entities;

use Venne;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\DoctrineModule\Repositories\BaseRepository")
 * @ORM\Table(name="catalogOrderItems")
 */
class OrderItemsEntity implements \DoctrineModule\Entities\IEntity
{

	/**
	 * @var OrderEntity
	 * @ORM\ManyToOne(targetEntity="OrderEntity")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 * @ORM\Id
	 */
	protected $order;

	/**
	 * @var ProductEntity
	 * @ORM\ManyToOne(targetEntity="ProductEntity")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 * @ORM\Id
	 */
	protected $product;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sum;


	public function getOrder()
	{
		return $this->order;
	}


	public function setOrder($order)
	{
		$this->order = $order;
	}


	public function getProduct()
	{
		return $this->product;
	}


	public function setProduct($product)
	{
		$this->product = $product;
	}


	public function getSum()
	{
		return $this->sum;
	}


	public function setSum($sum)
	{
		$this->sum = $sum;
	}
}
