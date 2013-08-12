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

use CatalogModule\Pages\Catalog\CategoryEntity;
use DoctrineModule\Forms\FormFactory;
use Venne;
use Venne\Forms\Form;

/**
 * @author pave
 */
class ProductFormFactory extends FormFactory
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
		$_this = $this;
		$route = $form->addOne('route');

		$group = $form->addGroup();
		$form->addText('name', 'Name');
		$form->addText('price', 'Price')
			->setDefaultValue(0)
			->addCondition($form::FILLED)
			->addRule($form::FLOAT);
		$form->addText('rrp', 'RRP')
			->addCondition($form::FILLED)
			->addRule($form::FLOAT);
		$form->addTextArea('notation', 'Description')->getControlPrototype()->attrs['class'] = 'input-block-level';

		$route->setCurrentGroup($group);
		$route->addFileEntityInput('photo', 'Image');
		$form->addText('inStock', 'In stock')
			->addCondition($form::FILLED)
			->addRule($form::FLOAT);

		$form->addGroup('');
		$form->addManyToOne('category', 'Main category');
		$form->addManyToMany('categories', 'Next categories');

		$form->addGroup();
		$form->addManyToMany('labels', 'Labels');

		$tags = $form->addContainer('tags');
		$tags->setCurrentGroup($group = $form->addGroup());

		$form->addGroup('Types');
		$form->addTags('typesAsArray', 'Types')
			->setSuggestCallback(function() use ($_this, $form) {
				$ret = array();

				foreach ($_this->getTagsFromCategories($form) as $tag) {
					$ret[$tag] = $tag;
				}

				return $ret;
			})
			->setDelimiter('[;]+')
			->setJoiner(';');

		$form->addGroup();
		$form->addSaveButton('Save');
	}


	public function getTagsFromCategories($form)
	{
		$ret = array();

		$entity = $form->data;
		$categories = (array)$entity->getCategories();
		if ($entity->category) {
			$categories[] = $entity->category;
		}
		foreach ($categories as $category) {
			while ($category instanceof CategoryEntity) {
				$ret = array_merge($ret, $category->getTypesAsArray());
				$category = $category->getParent();
			}
		}

		return $ret;
	}
}
