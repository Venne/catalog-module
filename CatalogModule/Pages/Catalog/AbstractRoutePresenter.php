<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Pages\Catalog;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractRoutePresenter extends \BlogModule\Pages\Blog\AbstractRoutePresenter
{

	/** @persistent */
	public $itemsPerPage = 10;

	/** @persistent */
	public $order = 'price';

	/** @persistent */
	public $show;

	/**
	 * @var array
	 */
	public static $modes = array(
		NULL => 'Cards',
		'table' => 'Table',
	);

	const MODE_CARD = NULL;

	const MODE_TABLE = 'table';


	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}

}
