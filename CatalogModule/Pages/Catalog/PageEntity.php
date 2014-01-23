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

use Doctrine\ORM\Mapping as ORM;
use MailformModule\Pages\Mailform\MailformEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CmsModule\Content\Repositories\PageRepository")
 * @ORM\Table(name="catalog_page")
 */
class PageEntity extends \BlogModule\Pages\Blog\AbstractPageEntity
{

	/**
	 * @ORM\OneToMany(targetEntity="OrderEntity", mappedBy="page", cascade={"persist"}, orphanRemoval=true)
	 */
	protected $order;

	/**
	 * @var CartEntity
	 * @ORM\ManyToOne(targetEntity="CartEntity", cascade={"persist", "remove", "detach"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $orderRoute;

	/**
	 * @var FeedEntity
	 * @ORM\ManyToOne(targetEntity="FeedEntity", cascade={"persist", "remove", "detach"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $feedRoute;

	/**
	 * @var \MailformModule\Pages\Mailform\MailformEntity
	 * @ORM\OneToOne(targetEntity="MailformModule\Pages\Mailform\MailformEntity", cascade={"all"})
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
	protected $vat = 150;


	protected function startup()
	{
		parent::startup();

		$this->orderRoute = $this->createRoute($this->getReflection()->getNamespaceName() . '\CartEntity');
		$this->feedRoute = $this->createRoute($this->getReflection()->getNamespaceName() . '\FeedEntity');

		$this->template = '
Products
--------

{foreach $products as $id=>$orders}
{foreach $orders as $orderId=>$item}
{$item[\'productEntity\']->name} - {$item[\'productEntity\']->category} - {$item[\'sum\']} - {$item[\'typeEntity\']->name}
{/foreach}
{/foreach}
';

		$this->mailform = new MailformEntity;
	}




	public function getOrder()
	{
		return $this->order;
	}


	/**
	 * @return CartEntity
	 */
	public function getOrderRoute()
	{
		return $this->orderRoute;
	}


	/**
	 * @return FeedEntity
	 */
	public function getFeedRoute()
	{
		return $this->feedRoute;
	}


	/**
	 * @return MailformEntity
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
