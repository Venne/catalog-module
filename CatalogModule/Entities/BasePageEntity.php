<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Entities;

use Venne;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;
use MailformModule\Entities\MailformEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class BasePageEntity extends \CmsModule\Content\Entities\PageEntity
{

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @ORM\OneToMany(targetEntity="CategoryEntity",mappedBy="page")
	 */
	protected $categories;

	/**
	 * @ORM\OneToOne(targetEntity="\CmsModule\Content\Entities\DirEntity", cascade={"all"})
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $dir;

	/**
	 * @ORM\OneToMany(targetEntity="\CatalogModule\Entities\OrderEntity", mappedBy="page", cascade={"persist"}, orphanRemoval=true)
	 */
	protected $order;

	/**
	 * @var RouteEntity
	 * @ORM\ManyToOne(targetEntity="\CmsModule\Content\Entities\RouteEntity", cascade={"persist", "remove", "detach"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $orderRoute;

	/**
	 * @var RouteEntity
	 * @ORM\ManyToOne(targetEntity="\CmsModule\Content\Entities\RouteEntity", cascade={"persist", "remove", "detach"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $feedRoute;

	/**
	 * @var \MailformModule\Entities\MailformEntity
	 * @ORM\OneToOne(targetEntity="MailformModule\Entities\MailformEntity", cascade={"all"})
	 */
	protected $mailform;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $template;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $vat;


	public function __construct()
	{
		parent::__construct();

		$this->mainRoute->type = 'Catalog:Category:default';
		$this->vat = 150;

		$this->dir = new \CmsModule\Content\Entities\DirEntity();
		$this->dir->setInvisible(true);
		$this->dir->setName('catalogPage');

		$this->routes[] = $this->orderRoute = new \CmsModule\Content\Entities\RouteEntity;
		$this->orderRoute->setTitle('Cart');
		$this->orderRoute->setParent($this->mainRoute);
		$this->orderRoute->setType('Catalog:Order:default');
		$this->orderRoute->setLocalUrl('cart');
		$this->orderRoute->setPage($this);

		$this->routes[] = $this->feedRoute = new \CmsModule\Content\Entities\RouteEntity;
		$this->feedRoute->setTitle('Feed');
		$this->feedRoute->setParent($this->mainRoute);
		$this->feedRoute->setType('Catalog:Feed:default');
		$this->feedRoute->setLocalUrl('feed.xml');
		$this->feedRoute->setPage($this);

		$this->template = '
Products
--------

{foreach $products as $id=>$orders}
{foreach $orders as $orderId=>$item}
{$item[\'productEntity\']->name} - {$item[\'productEntity\']->category} - {$item[\'sum\']} - {$item[\'typeEntity\']->name}
{/foreach}
{/foreach}
';

		$this->mailform = new MailformEntity();
	}


	/**
	 * @return CategoryEntity
	 */
	public function getCategories()
	{
		return $this->categories;
	}


	public function getDir()
	{
		return $this->dir;
	}


	public function getOrder()
	{
		return $this->order;
	}


	/**
	 * @return \CatalogModule\Entities\RouteEntity
	 */
	public function getOrderRoute()
	{
		return $this->orderRoute;
	}


	/**
	 * @return \CatalogModule\Entities\RouteEntity
	 */
	public function getFeedRoute()
	{
		return $this->feedRoute;
	}


	/**
	 * @return \MailformModule\Entities\MailformEntity
	 */
	public function getMailform()
	{
		return $this->mailform;
	}


	/**
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}


	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}


	/**
	 * @param int $vat
	 */
	public function setVat($vat)
	{
		$this->vat = $vat * 10.0;
	}


	/**
	 * @return float
	 */
	public function getVat()
	{
		return floatval($this->vat) / 10.0;
	}
}
