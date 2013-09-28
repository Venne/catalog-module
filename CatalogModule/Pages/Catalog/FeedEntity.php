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

use CmsModule\Content\Entities\ExtendedRouteEntity;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity
 * @ORM\Table(name="catalog_feed")
 */
class FeedEntity extends ExtendedRouteEntity
{

	protected function startup()
	{
		parent::startup();

		$this->getRoute()
			->setLocalUrl('feed.xml')
			->setTitle('Feed')
			->setPublished(TRUE);
	}
}
