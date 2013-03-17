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
use Venne\Forms\Form;
use DoctrineModule\Forms\FormFactory;

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
		$form->addGroup()->setOption('class', 'span6');
		$form->addText('name', 'Name');
		$form->addText('price', 'Price')
			->setDefaultValue(0)
			->addCondition($form::FILLED)
			->addRule($form::FLOAT);
		$form->addText('rrp', 'RRP')
			->addCondition($form::FILLED)
			->addRule($form::FLOAT);
		$form->addTextArea('description', 'Description')->getControlPrototype()->attrs['class'] = 'input-block-level';
		$form->addFileEntityInput('image', 'Image');
		$form->addText('inStock', 'In stock')
			->addCondition($form::FILLED)
			->addRule($form::FLOAT);

		$form->addGroup('')->setOption('class', 'span6 pull-right');
		$form->addManyToOne('category', 'Main category');
		$form->addManyToMany('categories', 'Next categories');

		$form->addGroup()->setOption('class', 'span6 pull-right');
		$form->addManyToMany('labels', 'Labels');

		$tags = $form->addContainer('tags');
		$tags->setCurrentGroup($group = $form->addGroup());
		$group->setOption('class', 'span6');
		$i = 0;
		foreach ($this->getTagsFromCategories($form) as $key => $name) {
			$tag = $tags->addContainer('tag_' . $i++);
			$tag->setCurrentGroup($tags->getCurrentGroup());
			$tag->addCheckbox('use', $name);
			$tag->addHidden('name')->setValue($name);
		}

		$form->addTags('typesAsArray', 'Types')
			->setDelimiter('[;]+')
			->setJoiner(';')
			->addRule($form::FILLED);

		$form->addGroup()->setOption('class', 'span12 pull-right');
		$form->addSaveButton('Save');
	}


	protected function getTagsFromCategories($form)
	{
		$ret = array();
		/** @var $entity \CatalogModule\Entities\ProductEntity */
		$entity = $form->data;
		$categories = (array)$entity->getCategories();
		foreach ($categories as $category) {
			while ($category instanceof \CatalogModule\Entities\CategoryEntity) {
				$ret = array_merge($ret, $category->getTypesAsArray());
				$category = $category->getParent();
			}
		}

		return $ret;
	}


	public function handleSave(Form $form)
	{
		$data = $form->getValues();
		$form->data->setTypesAsArray($data['typesAsArray']);

		parent::handleSave($form);
	}
}
