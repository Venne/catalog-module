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

use CatalogModule\Pages\Catalog\Forms\ProductfrontFormFactory;
use CatalogModule\Pages\Catalog\ItemEntity;
use CatalogModule\Pages\Catalog\ItemRepository;
use CmsModule\Content\Control;
use Nette\Application\UI\Multiplier;
use Nette\Callback;
use Nette\Http\SessionSection;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ProductControl extends Control
{

	/** @var SessionSection */
	protected $sessionSection;

	/** @var ProductfrontFormFactory */
	protected $formFactory;

	/** @var ItemRepository */
	protected $productRepository;

	/** @var bool */
	protected static $rendered = false;


	/**
	 * @param SessionSection $sessionSection
	 * @param ProductfrontFormFactory $formFactory
	 * @param ItemRepository $productRepository
	 */
	public function __construct(SessionSection $sessionSection, ProductfrontFormFactory $formFactory, ItemRepository $productRepository)
	{
		parent::__construct();

		$this->sessionSection = $sessionSection;
		$this->formFactory = $formFactory;
		$this->productRepository = $productRepository;
	}




	public function handleAdd($id, $sum, $values)
	{
		$this->template->showAdd = $id;
		$this->invalidateControl('add');
	}


	public function addProductToCart($id, $sum = 1, $values = array())
	{
		$this->sessionSection->products[$id][] = array(
			'sum' => $sum,
			'values' => $values,
		);
	}


	/**
	 * @return ItemRepository
	 */
	public function getItemRepository()
	{
		return $this->productRepository;
	}


	/**
	 * @return Multiplier
	 */
	protected function createComponentProduct()
	{
		$control = $this;
		$factory = $this->formFactory;
		$repository = $this->productRepository;
		return new Multiplier(function ($name) use ($control, $factory, $repository) {
			$form = $factory->invoke($repository->find($name));
			$form->onSuccess[] = function ($form) use ($control) {
				$control->addProductToCart($form->name, $form->values['sum'], array('type' => $form->values['type']));
				$control->redirect('add!', array('id' => $form->name, 'sum' => $form->values['sum'], 'values' => $form->values['type']));
			};
			$form->onError[] = function ($form) use ($control) {
				$control->template->showError = $form->name;
			};
			return $form;
		});
	}


	/**
	 * @param ItemEntity $entity
	 * @param null $mode
	 */
	public function render(ItemEntity $entity, $mode = NULL)
	{
		$this->template->entity = $entity;
		$this->template->mode = $mode;

		$this->template->render();
	}
}
