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
use Nette\Callback;
use DoctrineModule\Repositories\BaseRepository;
use CatalogModule\Entities\CategoryEntity;
use CatalogModule\Forms\FilterFormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CategoryPresenter extends BasePresenter
{

	/** @var BaseRepository */
	protected $categoryRepository;

	/** @var BaseRepository */
	protected $productRepository;

	/** @var \Nette\Http\SessionSection */
	protected $sessionSection;

	/** @var Callback */
	protected $catalogControlFactory;

	/** @var FilterFormFactory */
	protected $filterFormFactory;


	/**
	 * @param \DoctrineModule\Repositories\BaseRepository $categoryRepository
	 * @param \DoctrineModule\Repositories\BaseRepository $productRepository
	 * @param \Nette\Http\SessionSection $sessionSection
	 * @param \Nette\Callback $catalogControlFactory
	 */
	public function __construct(BaseRepository $categoryRepository, BaseRepository $productRepository, \Nette\Http\SessionSection $sessionSection, \Nette\Callback $catalogControlFactory)
	{
		parent::__construct();

		$this->categoryRepository = $categoryRepository;
		$this->productRepository = $productRepository;
		$this->sessionSection = $sessionSection;
		$this->catalogControlFactory = $catalogControlFactory;
	}


	/**
	 * @param \CatalogModule\Forms\FilterFormFactory $filterFormFactory
	 */
	public function injectFilterFormFactory(FilterFormFactory $filterFormFactory)
	{
		$this->filterFormFactory = $filterFormFactory;
	}


	public function startup()
	{
		parent::startup();

		if ($this->isAjax()) {
			$this->invalidateControl('productsItems');
			$this->invalidateControl('paginatorBefore');
			$this->invalidateControl('paginatorAfter');
		}
	}


	public function handleNext()
	{
		$this->validateControl('productsItems');
		$this->invalidateControl('productItemList');
		$this->template->hidePaginator = TRUE;

		if (!$this->isAjax()) {
			$this->redirect('this');
		}

		$this->payload->url = $this->link('this');
	}


	/**
	 * @param CategoryEntity $category
	 * @return array|CategoryEntity[]
	 */
	public function getSiblings(CategoryEntity $category)
	{
		return $this->categoryRepository->findBy(array('parent' => $category->parent));
	}


	/**
	 * @param \CmsModule\Content\Entities\RouteEntity $route
	 * @return CategoryEntity
	 */
	public function getCategoryByRoute(\CmsModule\Content\Entities\RouteEntity $route)
	{
		return $this->categoryRepository->findOneBy(array('route' => $route->id));
	}


	/**
	 * @param \CatalogModule\Entities\CategoryEntity $category
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getDQLProductsByCategory(CategoryEntity $category = NULL)
	{
		$dql = $this->getDQLProductsByCategoryWithoutOrder($category)
			->orderBy(($this->order == 'price' ? 'a.price' : 'a.name'), 'ASC');

		return $dql;
	}


	/**
	 * @param \CatalogModule\Entities\CategoryEntity $category
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getDQLProductsByCategoryWithoutOrder(CategoryEntity $category = NULL)
	{
		$dql = $this->productRepository->createQueryBuilder('a');

		if ($category) {
			$dql = $dql
				->leftJoin('a.categories', 'c')
				->where('a.category = :category')
				->setParameter('category', $category->id);
		} else {
			$dql = $dql
				->where('a.category = :category')
				->setParameter('category', NULL);
		}

		return $dql;
	}


	/**
	 * @param \CatalogModule\Entities\CategoryEntity $category
	 * @return array
	 */
	public function getProductsByCategory(CategoryEntity $category = NULL)
	{
		return $this->getDQLProductsByCategory($category)
			->setMaxResults($this->itemsPerPage)
			->setFirstResult($this['vp']->getPaginator()->getOffset())
			->getQuery()->getResult();
	}


	/**
	 * @param \CatalogModule\Entities\CategoryEntity $category
	 * @return array
	 */
	public function countProductsByCategory(CategoryEntity $category = NULL)
	{
		return $this->getDQLProductsByCategory($category)
			->select('count(a)')
			->getQuery()->getSingleScalarResult();
	}


	public function renderDefault()
	{
		$this->template->categoryEntity = $this->getCategoryByRoute($this->route);
	}


	protected function createComponentCatalog()
	{
		return $this->catalogControlFactory->invoke();
	}


	protected function createComponentVp()
	{
		$vp = parent::createComponentVp();
		$pg = $vp->getPaginator();
		$pg->setItemCount($this->getDQLProductsByCategoryWithoutOrder($this->getCategoryByRoute($this->route))->select("COUNT(a.id)")->getQuery()->getSingleScalarResult());
		return $vp;
	}


	protected function createComponentFilterForm()
	{
		$form = $this->filterFormFactory->invoke();
		$form->onSuccess[] = $this->filterFormSuccess;
		$form->setDefaults(array(
			'labels' => $this->labels,
		));
		return $form;
	}


	public function filterFormSuccess(Venne\Forms\Form $form)
	{
		$this->redirect('this', array(
			'order' => 'extended',
			'labels' => $form['labels']->getValue(),
		));
	}
}
