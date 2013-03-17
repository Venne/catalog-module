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
use Nette\Diagnostics\Debugger;
use Nette\Application\BadRequestException;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FeedPresenter extends \CmsModule\Content\Presenters\PagePresenter
{

	/** @persistent */
	public $type;

	/** @var array */
	public static $types = array(
		'zbozi.cz' => 'Zboží.cz',
		'heureka.cz' => 'Heureka.cz',
	);

	/** @var BaseRepository */
	protected $productRepository;


	/**
	 * @param \DoctrineModule\Repositories\BaseRepository $productRepository
	 */
	public function __construct(BaseRepository $productRepository)
	{
		parent::__construct();

		$this->productRepository = $productRepository;

		$this->absoluteUrls = true;
		Debugger::$bar = false;
	}


	public function startup()
	{
		parent::startup();

		if (!$this->type) {
			throw new BadRequestException;
		}

		$this->setView($this->type);
		$this->template->productRepository = $this->productRepository;
		$this->template->page = $this->page;
		$url = $this->getHttpRequest()->getUrl();
		$this->template->server = $url->scheme . '://'.$url->host;
	}
}
