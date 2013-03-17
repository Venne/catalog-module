<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Components;

use Venne;
use Nette\Callback;
use CmsModule\Content\Control;
use Nette\Application\UI\Multiplier;
use DoctrineModule\Repositories\BaseRepository;
use CatalogModule\Forms\ProductfrontFormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CatalogControl extends Control
{

	/** @var \Nette\Http\SessionSection */
	protected $sessionSection;

	/** @var ProductfrontFormFactory */
	protected $formFactory;

	/** @var BaseRepository */
	protected $productRepository;

	/** @var bool */
	protected static $rendered = false;


	/**
	 * @param BaseRepository $categoryRepository
	 */
	public function __construct(\Nette\Http\SessionSection $sessionSection, ProductfrontFormFactory $formFactory, BaseRepository $productRepository)
	{
		parent::__construct();

		$this->sessionSection = $sessionSection;
		$this->formFactory = $formFactory;
		$this->productRepository = $productRepository;
	}


	public function handleDeleteAll()
	{
		unset($this->sessionSection->products);
		$this->presenter->flashMessage($this->template->translate('Cart has been cleared.'), 'success');
		$this->redirect('this');
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
	 * @return \DoctrineModule\Repositories\BaseRepository
	 */
	public function getProductRepository()
	{
		return $this->productRepository;
	}


	/**
	 * @return \Nette\Application\UI\Multiplier
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
	 * @param \CatalogModule\Entities\ProductEntity $entity
	 */
	public function renderProduct(\CatalogModule\Entities\ProductEntity $entity, $mode = NULL)
	{
		$this->template->action = 'product';
		$this->template->entity = $entity;
		$this->template->form = $this['product'][$entity->id];
		$this->template->mode = $mode;

		$this->template->render();
	}


	public function renderCart()
	{
		if (!self::$rendered) {
			self::$rendered = true;
			$this->template->action = 'cart';
			$this->template->products = $this->sessionSection->products;

			$this->template->render();
		}
	}
}
