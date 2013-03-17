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

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class LabelControl extends SectionControl
{

	/** @var BaseRepository */
	protected $labelRepository;

	/** @var Callback */
	protected $labelFormFactory;


	/**
	 * @param \DoctrineModule\Repositories\BaseRepository $labelRepository
	 * @param null $labelFormFactory
	 */
	public function __construct(BaseRepository $labelRepository, $labelFormFactory)
	{
		parent::__construct();

		$this->labelRepository = $labelRepository;
		$this->labelFormFactory = $labelFormFactory;
	}


	protected function createComponentTable()
	{
		$table = new \CmsModule\Components\Table\TableControl;
		$table->setTemplateConfigurator($this->templateConfigurator);
		$table->setRepository($this->labelRepository);

		// forms
		$form = $table->addForm($this->labelFormFactory, 'Label');

		// navbar
		$table->addButtonCreate('create', 'Create new', $form, 'file');

		$table->addColumn('name', 'Name')
			->setWidth('100%')
			->setSortable(TRUE)
			->setFilter();

		$table->addActionEdit('edit', 'Edit', $form);
		$table->addActionDelete('delete', 'Delete');

		// global actions
		$table->setGlobalAction($table['delete']);

		return $table;
	}


	public function render()
	{
		$this['table']->render();
	}
}
