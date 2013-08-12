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

use Venne;
use Nette\Callback;
use DoctrineModule\Repositories\BaseRepository;
use CatalogModule\Entities\CategoryRouteEntity;
use Nette\Utils\Paginator;
use Kdyby\Extension\Forms\BootstrapRenderer\BootstrapRenderer;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class BasePresenter extends \CmsModule\Content\Presenters\PagePresenter
{

	/** @persistent */
	public $order = 'price';

	/** @persistent */
	public $labels = array();

	/**
	 * @var int
	 * @persistent
	 */
	public $itemsPerPage = 20;

	/**
	 * @var int
	 * @persistent
	 */
	public $page;

	/**
	 * @var string
	 * @persistent
	 */
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


	protected function createComponentVp()
	{
		$vp = new \CmsModule\Components\VisualPaginator;
		$pg = $vp->getPaginator();
		$pg->setItemsPerPage($this->itemsPerPage);
		return $vp;
	}

}
