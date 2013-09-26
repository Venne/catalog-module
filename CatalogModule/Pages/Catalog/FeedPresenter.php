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

use CmsModule\Content\Presenters\PagePresenter;
use Nette\Diagnostics\Debugger;
use Nette\Application\BadRequestException;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FeedPresenter extends PagePresenter
{

	/** @persistent */
	public $type;

	/** @var array */
	public static $types = array(
		'zbozi.cz' => 'Zboží.cz',
		'heureka.cz' => 'Heureka.cz',
	);

	/** @var ItemRepository */
	protected $productRepository;


	/**
	 * @param ItemRepository $productRepository
	 */
	public function __construct(ItemRepository $productRepository)
	{
		parent::__construct();

		$this->productRepository = $productRepository;

		$this->absoluteUrls = true;
		Debugger::$bar = false;
	}


	protected function startup()
	{
		parent::startup();

		if (!$this->type) {
			throw new BadRequestException;
		}

		$this->setView($this->type);
		$this->template->productRepository = $this->productRepository;
		$this->template->page = $this->extendedPage;
		$url = $this->getHttpRequest()->getUrl();
		$this->template->server = $url->scheme . '://'.$url->host;
	}
}
