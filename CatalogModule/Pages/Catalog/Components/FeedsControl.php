<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Pages\Catalog\Components;

use CatalogModule\Pages\Catalog\FeedPresenter;
use CmsModule\Content\SectionControl;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FeedsControl extends SectionControl
{

	public function render()
	{
		$this->template->feeds = FeedPresenter::$types;
		$this->template->page = $this->getExtendedPage();

		$zal = $this->presenter->absoluteUrls;
		$this->presenter->absoluteUrls = true;

		$this->template->render();

		$this->presenter->absoluteUrls = $zal;
	}
}
