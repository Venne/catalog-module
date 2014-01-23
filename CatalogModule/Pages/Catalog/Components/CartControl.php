<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Pages\Catalog\Components;

use CatalogModule\Pages\Catalog\ItemRepository;
use CmsModule\Content\Control;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CartControl extends Control
{

	/** @var \Nette\Http\SessionSection */
	protected $sessionSection;

	/** @var ItemRepository */
	protected $productRepository;

	/** @var bool */
	protected static $rendered = false;


	/**
	 * @param \Nette\Http\SessionSection $sessionSection
	 * @param ItemRepository $productRepository
	 */
	public function __construct(\Nette\Http\SessionSection $sessionSection, ItemRepository $productRepository)
	{
		parent::__construct();

		$this->sessionSection = $sessionSection;
		$this->productRepository = $productRepository;
	}


	public function handleDeleteAll()
	{
		unset($this->sessionSection->products);
		$this->presenter->flashMessage($this->translator->translate('Cart has been cleared.'), 'success');
		$this->redirect('this');
	}


	/**
	 * @return ItemRepository
	 */
	public function getItemRepository()
	{
		return $this->productRepository;
	}


	public function renderDefault()
	{
		if (!self::$rendered) {
			self::$rendered = true;
			$this->template->products = $this->sessionSection->products;
		}
	}
}
