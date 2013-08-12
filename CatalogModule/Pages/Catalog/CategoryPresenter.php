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

use BlogModule\Pages\Blog\AbstractRoutePresenter;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CategoryPresenter extends AbstractRoutePresenter
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

	/** @var ItemRepository */
	private $repository;

	/** @var CategoryRepository */
	private $categoryRepository;


	/**
	 * @param ItemRepository $repository
	 */
	public function injectRepository(ItemRepository $repository)
	{
		$this->repository = $repository;
	}


	/**
	 * @param CategoryRepository $categoryRepository
	 */
	public function injectCategoryRepository(CategoryRepository $categoryRepository)
	{
		$this->categoryRepository = $categoryRepository;
	}


	/**
	 * @return CategoryRepository
	 */
	public function getCategoryRepository()
	{
		return $this->categoryRepository;
	}


	/**
	 * @return ItemRepository
	 */
	protected function getRepository()
	{
		return $this->repository;
	}


	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}


	protected function getQueryBuilder()
	{
		$qb = $this->getRepository()->createQueryBuilder('a');

		if ($this->extendedRoute instanceof RouteEntity) {
			$qb
				->andWhere('a.category IS NULL');
		} else {
			$qb
				->leftJoin('a.categories', 'c')
				->andWhere('a.category = :category OR c.id = :category')->setParameter('category', $this->extendedRoute->id);
		}

		return $qb;
	}


	public function getCategories()
	{
		if ($this->extendedRoute instanceof RouteEntity) {
			return $this->categoryRepository->findBy(array('parent' => NULL));
		}

		return $this->categoryRepository->findBy(array('parent' => $this->extendedRoute));
	}
}
