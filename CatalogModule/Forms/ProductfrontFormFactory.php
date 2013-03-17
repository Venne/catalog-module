<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Forms;

use Venne;
use Venne\Forms\FormFactory;
use Venne\Forms\Form;
use DoctrineModule\Repositories\BaseRepository;

/**
 * @author pave
 */
class ProductfrontFormFactory extends FormFactory
{

	/** @var BaseRepository */
	protected $repository;


	/**
	 * @param BaseRepository $repository
	 */
	public function __construct(BaseRepository $repository)
	{
		$this->repository = $repository;
	}


	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$types = array();
		foreach ($this->repository->findBy(array('product' => $form->data->id)) as $entity) {
			$types[$entity->id] = (string)$entity;
		}

		if (count($types) === 1) {
			$typeControl = $form->addHidden('type')->setDefaultValue(key($types));
		} else {
			$typeControl = $form->addSelect('type', 'Type', $types);
		}
		$typeControl->addRule($form::FILLED);
		$form->addText('sum', 'Sum')
			->addRule($form::FILLED)
			->addRule($form::INTEGER)
			->setDefaultValue(1);
		$form->addSubmit('order', 'Order');
	}


	public function handleBeforeRender(Form $form)
	{
		if ($form->isSubmitted() && !$form['sum']->value) {
			$form['sum']->setValue(1);
		}
	}


	public function handleSave(Form $form)
	{
		if (!$form['sum']->value) {
			$form['sum']->setValue(1);
		}
	}
}
