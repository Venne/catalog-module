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
use CmsModule\Content\SectionControl;
use DoctrineModule\Repositories\BaseRepository;
use Nette\Callback;
use CatalogModule\Forms\ProductFormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ProductsControl extends SectionControl
{


	/** @var BaseRepository */
	protected $productRepository;

	/** @var ProductFormFactory */
	protected $productFormFactory;


	public function __construct($productRepository, ProductFormFactory $productFormFactory)
	{
		parent::__construct();

		$this->productRepository = $productRepository;
		$this->productFormFactory = $productFormFactory;
	}


	protected function createComponentTable()
	{
		$_this = $this;

		$table = new \CmsModule\Components\Table\TableControl;
		$table->setTemplateConfigurator($this->templateConfigurator);
		$table->setRepository($this->productRepository);

		// forms
		$form = $table->addForm($this->productFormFactory, 'Product', function () use ($_this) {
			return new \CatalogModule\Entities\ProductEntity($_this->entity, '');
		}, \CmsModule\Components\Table\Form::TYPE_FULL);

		// navbar
		$table->addButtonCreate('create', 'Create new', $form, 'shopping-cart');

		$table->addColumn('name', 'Name')
			->setWidth('30%')
			->setSortable(TRUE)
			->setFilter();
		$table->addColumn('description', 'Description')
			->setWidth('40%')
			->setSortable(TRUE)
			->setFilter();
		$table->addColumn('types', 'Types')
			->setWidth('30%')
			->setCallback(function ($entity) {
				return implode(', ', $entity->types->toArray());
			});

		$table->addActionEdit('edit', 'Edit', $form);
		$table->addActionDelete('delete', 'Delete');

		// global actions
		$table->setGlobalAction($table['delete']);

		return $table;
	}


	public function render()
	{
		$this->template->render();
	}
}
