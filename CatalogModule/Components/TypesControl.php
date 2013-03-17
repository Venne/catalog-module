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
 * @author pave
 */
class TypesControl extends SectionControl
{

	/** @var BaseRepository */
	protected $typeRepository;

	/** @var Callback */
	protected $typeFormFactory;

	/** @persistent */
	public $key;

	/** @persistent */
	public $edit;


	function __construct(BaseRepository $typeRepository, $typeFormFactory)
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
