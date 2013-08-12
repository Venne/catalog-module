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
use MailformModule\Forms\ControlExtensions\MailformExtension;
use DoctrineModule\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class MailformFormFactory extends FormFactory
{

	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new \CmsModule\Content\ControlExtension(),
			new \FormsModule\ControlExtensions\ControlExtension(),
			new MailformExtension(),
		));
	}


	/**
	 * @param Form $form
	 */
	public function configure(Venne\Forms\Form $form)
	{
		$form->addGroup('Template');
		$form->addTextArea('template', 'Product template')->getControlPrototype()->attrs['class'] = 'input-block-level';

		$form->addMailform('mailform');

		$form->addSaveButton('Save');
	}

}
