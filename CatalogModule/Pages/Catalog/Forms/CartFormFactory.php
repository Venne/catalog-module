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
use Venne\Forms\FormFactory;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CartFormFactory extends FormFactory
{


	public function configure(Form $form)
	{
		$form->addSaveButton('Continue to order')->getControlPrototype()->class[] = 'btn-primary';
	}
}
