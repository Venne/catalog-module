<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Presenters;

use Venne;
use DoctrineModule\Repositories\BaseRepository;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ProductPresenter extends CategoryPresenter
{

	/**
	 * @return \CatalogModule\Entities\ProductEntity
	 */
	public function getProduct()
	{
		return $this->productRepository->findOneBy(array('route' => $this->route->id));
	}
}
