<?php

namespace CatalogModule\Entities;

use Venne;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CatalogModule\Repositories\OrderRepository")
 * @ORM\Table(name="catalogOrder")
 */
class OrderEntity extends \DoctrineModule\Entities\IdentifiedEntity implements \DoctrineModule\Entities\IEntity
{

	/**
	 * @var OrderItemsEntity
	 * @ORM\OneToMany(targetEntity="OrderItemsEntity", mappedBy="order")
	 */
	protected $items;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $surname;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $street;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $psc;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $city;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $phone;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $email;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $firm;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $ico;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $dic;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $fax;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $isOrder;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $distribution;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $payment;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $comment;

	/**
	 * @var PageEntity
	 * @ORM\ManyToOne(targetEntity="PageEntity")
	 * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $page;


	public function getItems()
	{
		return $this->items;
	}


	public function setItems($items)
	{
		$this->items = $items;
	}


	public function getName()
	{
		return $this->name;
	}


	public function setName($name)
	{
		$this->name = $name;
	}


	public function getSurname()
	{
		return $this->surname;
	}


	public function setSurname($surname)
	{
		$this->surname = $surname;
	}


	public function getStreet()
	{
		return $this->street;
	}


	public function setStreet($street)
	{
		$this->street = $street;
	}


	public function getPsc()
	{
		return $this->psc;
	}


	public function setPsc($psc)
	{
		$this->psc = $psc;
	}


	public function getCity()
	{
		return $this->city;
	}


	public function setCity($city)
	{
		$this->city = $city;
	}


	public function getPhone()
	{
		return $this->phone;
	}


	public function setPhone($phone)
	{
		$this->phone = $phone;
	}


	public function getEmail()
	{
		return $this->email;
	}


	public function setEmail($email)
	{
		$this->email = $email;
	}


	public function getFirm()
	{
		return $this->firm;
	}


	public function setFirm($firm)
	{
		$this->firm = $firm;
	}


	public function getIco()
	{
		return $this->ico;
	}


	public function setIco($ico)
	{
		$this->ico = $ico;
	}


	public function getDic()
	{
		return $this->dic;
	}


	public function setDic($dic)
	{
		$this->dic = $dic;
	}


	public function getFax()
	{
		return $this->fax;
	}


	public function setFax($fax)
	{
		$this->fax = $fax;
	}


	public function getIsOrder()
	{
		return $this->isOrder;
	}


	public function setIsOrder($isOrder)
	{
		$this->isOrder = $isOrder;
	}


	public function getDistribution()
	{
		return $this->distribution;
	}


	public function setDistribution($distribution)
	{
		$this->distribution = $distribution;
	}


	public function getPayment()
	{
		return $this->payment;
	}


	public function setPayment($payment)
	{
		$this->payment = $payment;
	}


	public function getComment()
	{
		return $this->comment;
	}


	public function setComment($comment)
	{
		$this->comment = $comment;
	}


	public function getPage()
	{
		return $this->page;
	}


	public function setPage($page)
	{
		$this->page = $page;
	}
}

