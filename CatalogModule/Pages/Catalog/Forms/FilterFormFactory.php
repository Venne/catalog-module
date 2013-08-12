<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Pages\Catalog\Forms;

use Venne;
use Venne\Forms\Form;
use Venne\Forms\FormFactory;
use DoctrineModule\Repositories\BaseRepository;
use FormsModule\ControlExtensions\ControlExtension;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FilterFormFactory extends FormFactory
{

	/** @var BaseRepository */
	protected $labelRepository;


	/**
	 * @param \DoctrineModule\Repositories\BaseRepository $labelRepository
	 */
	public function injectLabelRepository(BaseRepository $labelRepository)
	{
		$this->labelRepository = $labelRepository;
	}


	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new ControlExtension(),
		));
	}


	public function configure(Form $form)
	{
		$form->addText('text', 'Text');
		$form->addText('priceFrom', 'Price from');
		$form->addText('priceTo', 'Price to');
		$form->addCheckboxList('labels', 'Labels')->setItems($this->getLabels(), FALSE);

		$form->addSaveButton('Apply');
	}


	protected function getLabels()
	{
		$ret = array();
		foreach ($this->labelRepository->findAll() as $label) {
			$ret[] = $label->name;
		}
		return $ret;
	}
}
