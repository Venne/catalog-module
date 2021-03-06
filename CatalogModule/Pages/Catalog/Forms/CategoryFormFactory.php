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
		$route = $form->addOne('route');

		$group = $form->addGroup();
		$form->addText('name', 'Name');

		$route->setCurrentGroup($group);
		$route->addTextArea('description', 'Description')->getControlPrototype()->attrs['class'] = 'input-block-level';
		$route->addFileEntityInput('photo', 'Photo');
		$form->addManyToOne('parent', 'Parent');
		//$form->addManyToMany('types', 'Types');
		$form->addTags('typesAsArray', 'Types')
			->setDelimiter('[;]+')
			->setJoiner(';');

		$form->addSaveButton('Save');
	}
}
