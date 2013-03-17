<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Components;

use Venne;
use CmsModule\Content\SectionControl;
use DoctrineModule\Repositories\BaseRepository;
use Nette\Callback;
use CatalogModule\Presenters\FeedPresenter;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FeedsControl extends SectionControl
{

	public function render()
	{
		$this->template->feeds = FeedPresenter::$types;
		$this->template->page = $this->getEntity();

		$zal = $this->presenter->absoluteUrls;
		$this->presenter->absoluteUrls = true;

		$this->template->render();

		$this->presenter->absoluteUrls = $zal;
	}
}
