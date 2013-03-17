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
class CategoriesControl extends SectionControl
{

	/** @var BaseRepository */
	protected $categoryRepository;

	/** @var BaseRepository */
	protected $productRepository;

	/** @var Callback */
	protected $categoryFormFactory;

	/** @var ProductFormFactory */
	protected $productFormFactory;

	/** @persistent */
	public $key;

	/** @persistent */
	public $edit;

	/** @persistent */
	public $productEdit;

	/** @persistent */
	public $categoryCreate;

	/** @persistent */
	public $productCreate;


	public function __construct(BaseRepository $categoryRepository, BaseRepository $productRepository, $categoryFormFactory, ProductFormFactory $productFormFactory)
	{
		parent::__construct();

		$this->categoryRepository = $categoryRepository;
		$this->categoryFormFactory = $categoryFormFactory;
		$this->productRepository = $productRepository;
		$this->productFormFactory = $productFormFactory;
	}


	public function handleCategoryNew()
	{
		$this->invalidateControl('categoryNew');
	}


	public function handleProductNew()
	{
		$this->invalidateControl('productNew');
	}


	public function handleEdit()
	{
		$this->invalidateControl('edit');
	}


	public function handleProductEdit()
	{
		$this->invalidateControl('productEdit');
	}


	protected function createComponentTable()
	{
		$_this = $this;

		$table = new \CmsModule\Components\Table\TableControl;
		$table->setTemplateConfigurator($this->templateConfigurator);
		$table->setRepository($this->categoryRepository);

		// forms
		$form = $table->addForm($this->categoryFormFactory, 'Category', function () use ($_this) {
			$entity = new \CatalogModule\Entities\CategoryEntity($this->entity, '');
			$entity->setParent($this->key ? $this->categoryRepository->find($this->key) : NULL);
			return $entity;
		}, \CmsModule\Components\Table\Form::TYPE_LARGE);

		// navbar
		$table->addButtonCreate('create', 'Create new', $form, 'file');

		$parent = $this->key;
		$page = $this->getEntity();
		$table->setDql(function (\Doctrine\ORM\QueryBuilder $a) use ($parent, $page) {
			$a->andWhere('a.page = :page')->setParameter('page', $page);
			if (!$parent) {
				$a->andWhere('a.parent IS NULL');
			} else {
				$a->andWhere('a.parent = :id')->setParameter('id', $parent);
			}
		});

		$control = $this;
		$table->addColumn('name', 'Name')
			->setWidth('50%')
			->setCallback(function ($entity) use ($control) {
				$html = \Nette\Utils\Html::el('a');
				$html->class = 'ajax';
				$html->attrs['href'] = $control->link('this', array('key' => $entity->id));
				$html->setText($entity->name);
				return $html;
			})
			->setSortable(TRUE)
			->setFilter();
		$table->addColumn('types', 'Types')
			->setWidth('50%')
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
		$this->template->categoryRepository = $this->categoryRepository;

		$this->template->render();
	}


	/**
	 * @return \DoctrineModule\Repositories\BaseRepository
	 */
	public function getCategoryRepository()
	{
		return $this->categoryRepository;
	}


	/**
	 * @return \DoctrineModule\Repositories\BaseRepository
	 */
	public function getProductRepository()
	{
		return $this->productRepository;
	}
}
