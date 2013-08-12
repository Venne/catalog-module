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

use CatalogModule\Pages\Catalog\Forms\LabelFormFactory;
use CatalogModule\Pages\Catalog\LabelRepository;
use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use CmsModule\Content\SectionControl;
use Grido\DataSources\Doctrine;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class LabelControl extends SectionControl
{

	/** @var LabelRepository */
	protected $repository;

	/** @var LabelFormFactory */
	protected $labelFormFactory;


	/**
	 * @param LabelRepository $repository
	 * @param LabelFormFactory $labelFormFactory
	 */
	public function __construct(LabelRepository $repository, LabelFormFactory $labelFormFactory)
	{
		parent::__construct();

		$this->repository = $repository;
		$this->labelFormFactory = $labelFormFactory;
	}


	protected function createComponentTable()
	{
		$admin = new AdminGrid($this->repository);

		// columns
		$table = $admin->getTable();
		$table->setModel(new Doctrine($this->repository->createQueryBuilder('a')));

		$table->addColumn('name', 'Name')
			->setSortable()
			->getCellPrototype()->width = '100%';
		$table->getColumn('name')
			->setFilter()->setSuggestion();

		$form = $admin->createForm($this->labelFormFactory, 'Label', NULL, \CmsModule\Components\Table\Form::TYPE_LARGE);

		// Toolbar
		$toolbar = $admin->getNavbar();
		$toolbar->addSection('new', 'Create', 'file');
		$admin->connectFormWithNavbar($form, $toolbar->getSection('new'));

		// actions
		$table->addAction('edit', 'Edit')
			->getElementPrototype()->class[] = 'ajax';

		$admin->connectFormWithAction($form, $table->getAction('edit'));

		$table->addAction('delete', 'Delete')
			->getElementPrototype()->class[] = 'ajax';
		$admin->connectActionAsDelete($table->getAction('delete'));

		return $admin;
	}


	public function render()
	{
		$this->template->render();
	}
}
