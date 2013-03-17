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

use Gedmo\Translatable\TranslatableListener;
use Venne;
use Venne\Forms\Form;
use DoctrineModule\Forms\FormFactory;

/**
 * @author pave
 */
class CategoryFormFactory extends FormFactory
{


	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new \CmsModule\Content\Forms\ControlExtensions\ControlExtension(),
			new \FormsModule\ControlExtensions\ControlExtension(),
		));
	}


	public function configure(Form $form)
	{
		$form->addText('name', 'Name');
		$form->addTextArea('description', 'Description')->getControlPrototype()->attrs['class'] = 'input-block-level';
		$form->addFileEntityInput('image', 'Image');
		$form->addManyToOne('parent', 'Parent');
		//$form->addManyToMany('types', 'Types');
		$form->addTags('typesAsArray', 'Types')
			->setDelimiter('[;]+')
			->setJoiner(';');

		$form->addSaveButton('Save');
	}


	public function handleSave(Form $form)
	{
		$data = $form->getValues();
		$form->data->setTypesAsArray($data['typesAsArray']);

		$form->data->locale = $form->presenter->contentLang;

		parent::handleSave($form);
	}
}
