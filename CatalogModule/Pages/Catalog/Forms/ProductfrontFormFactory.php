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

use DoctrineModule\Forms\FormFactory;
use Venne\Forms\Form;

/**
 * @author pave
 */
class ProductfrontFormFactory extends FormFactory
{

	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$types = array();
		foreach ($form->data->types as $entity) {
			$types[$entity->id] = (string)$entity;
		}

		$c = count($types);
		if ($c === 0) {
			$typeControl = $form->addHidden('type')->setDefaultValue(NULL);
		} elseif ($c === 1) {
			$typeControl = $form->addHidden('type')->setDefaultValue(key($types));
			$typeControl->addRule($form::FILLED);
		} else {
			$typeControl = $form->addSelect('type', 'Type', $types);
			$typeControl->addRule($form::FILLED);
		}
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
