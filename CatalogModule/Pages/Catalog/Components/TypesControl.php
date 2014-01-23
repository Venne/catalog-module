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

use CatalogModule\Pages\Catalog\Forms\TypeFormFactory;
use CatalogModule\Pages\Catalog\TypeRepository;
use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use CmsModule\Content\SectionControl;
use Grido\DataSources\Doctrine;

/**
 * @author pave
 */
class TypesControl extends SectionControl
{

	/** @var TypeRepository */
	protected $typeRepository;

	/** @var TypeFormFactory */
	protected $typeFormFactory;

	/** @persistent */
	public $key;

	/** @persistent */
	public $edit;


	public function __construct(TypeRepository $typeRepository, TypeFormFactory $typeFormFactory)
	{
		parent::__construct();

		$this->typeRepository = $typeRepository;
		$this->typeFormFactory = $typeFormFactory;
	}


	public function handleEdit()
	{
		$this->invalidateControl('edit');
	}


	protected function createComponentTable()
	{
		$admin = new AdminGrid($this->typeRepository);

		// columns
		$table = $admin->getTable();
		$table->setModel(new Doctrine($this->typeRepository->createQueryBuilder('a')));

		$table->addColumnText('name', 'Name')
			->setSortable()
			->getCellPrototype()->width = '100%';
		$table->getColumn('name')
			->setFilterText()->setSuggestion();

		$table->addAction('edit', 'Edit')
			->getElementPrototype()->class[] = 'ajax';

		$form = $admin->createForm($this->typeFormFactory, 'Type');

		$admin->connectFormWithAction($form, $table->getAction('edit'));

		// Toolbar
		$toolbar = $admin->getNavbar();
		$toolbar->addSection('new', 'Create', 'file');
		$admin->connectFormWithNavbar($form, $toolbar->getSection('new'));

		$table->addAction('delete', 'Delete')
			->getElementPrototype()->class[] = 'ajax';
		$admin->connectActionAsDelete($table->getAction('delete'));

		return $admin;
	}

	protected function createComponentTable2()
	{
		$table = new \CmsModule\Components\Table\TableControl;
		$table->setTemplateConfigurator($this->templateConfigurator);
		$table->setRepository($this->typeRepository);

		// forms
		$form = $table->addForm($this->typeFormFactory, 'Type');

		// navbar
		$table->addButtonCreate('create', 'Create new', $form, 'file');

		$table->addColumn('name', 'Name', '50%')
			->setSortable(TRUE)
			->setFilter();

		$table->addActionEdit('edit', 'Edit', $form);
		$table->addActionDelete('delete', 'Delete');

		return $table;
	}


	public function render()
	{
		$this->template->render();
	}
}
