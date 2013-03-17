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
use DoctrineModule\Forms\FormFactory;
use Venne\Forms\Form;

/**
 * @author pave
 */
class TypeFormFactory extends FormFactory
{


	public function configure(Form $form)
	{
		$form->addText('name', 'Name');
		$form->addSaveButton('Save');
	}
}
