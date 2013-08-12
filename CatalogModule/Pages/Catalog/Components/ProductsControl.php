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

use CatalogModule\Pages\Catalog\Forms\ProductFormFactory;
use CatalogModule\Pages\Catalog\ItemRepository;
use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use CmsModule\Content\Components\RouteItemsControl;
use CmsModule\Content\SectionControl;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ProductsControl extends SectionControl
{

	/** @var ItemRepository */
	protected $productRepository;

	/** @var Callback */
	protected $productFormFactory;


	/**
	 * @param ItemRepository $productRepository
	 * @param ProductFormFactory $productFormFactory
	 */
	public function __construct(ItemRepository $productRepository, ProductFormFactory $productFormFactory)
	{
		parent::__construct();

		$this->productRepository = $productRepository;
		$this->productFormFactory = $productFormFactory;
	}


	protected function createComponentTable()
	{
		$adminControl = new RouteItemsControl($this->productRepository, $this->getEntity());
		$admin = $adminControl->getTable();
		$table = $admin->getTable();


		$repository = $this->productRepository;
		$entity = $this->entity;
		$form = $admin->createForm($this->productFormFactory, 'Blog', function () use ($repository, $entity) {
			return $repository->createNew(array($entity));
		}, \CmsModule\Components\Table\Form::TYPE_FULL);

		$admin->connectFormWithAction($form, $table->getAction('edit'), $admin::MODE_PLACE);

		// Toolbar
		$toolbar = $admin->getNavbar();
		$toolbar->addSection('new', 'Create', 'file');
		$admin->connectFormWithNavbar($form, $toolbar->getSection('new'), $admin::MODE_PLACE);

		$table->addAction('delete', 'Delete')
			->getElementPrototype()->class[] = 'ajax';
		$admin->connectActionAsDelete($table->getAction('delete'));

		return $adminControl;
	}


	public function render()
	{
		$this->template->render();
	}
}
