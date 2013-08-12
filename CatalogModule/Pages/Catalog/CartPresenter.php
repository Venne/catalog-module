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

use CatalogModule\Pages\Catalog\Forms\CartFormFactory;
use CatalogModule\Pages\Catalog\Forms\ProductfrontFormFactory;
use MailformModule\Components\MailControl;
use MailformModule\Forms\MailformfrontFormFactory;
use Nette\Diagnostics\Debugger;
use Nette\Localization\ITranslator;
use Nette\Mail\Message;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CartPresenter extends BasePresenter
{

	/** @persistent */
	public $step = 1;

	/** @var OrderRepository */
	protected $orderRepository;

	/** @var CategoryRouteEntity */
	protected $orderEntity;

	/** @var ItemRepository */
	protected $productRepository;

	/** @var \Nette\Http\SessionSection */
	protected $sessionSection;

	/** @var ProductfrontFormFactory */
	protected $formFactory;

	/** @var TypeRepository */
	protected $typeRepository;

	/** @var MailformfrontFormFactory */
	protected $orderFormFactory;

	/** @var CartFormFactory */
	protected $cartFormFactory;

	/** @var ITranslator */
	protected $translator;


	public function __construct($orderFormFactory, OrderRepository $orderRepository, ItemRepository $productRepository, TypeRepository $typeRepository, \Nette\Http\SessionSection $sessionSection, ProductfrontFormFactory $formFactory)
	{
		parent::__construct();

		$this->orderFormFactory = $orderFormFactory;
		$this->orderRepository = $orderRepository;
		$this->sessionSection = $sessionSection;
		$this->productRepository = $productRepository;
		$this->formFactory = $formFactory;
		$this->typeRepository = $typeRepository;
	}


	/**
	 * @param CartFormFactory $cartFormFactory
	 */
	public function injectCartFormFactory(CartFormFactory $cartFormFactory)
	{
		$this->cartFormFactory = $cartFormFactory;
	}


	/**
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function injectTranslator(ITranslator $translator)
	{
		$this->translator = $translator;
	}


	public function handleDeleteAll()
	{
		unset($this->sessionSection->products);
		$this->redirect('this');
	}


	public function renderDefault()
	{
		$this->template->products = $this->getProducts();

		if (count($this->template->products) == 0) {
			if ($this->step == 2) {
				$this->redirect('this', array('step' => 1));
			} elseif ($this->step == 1) {
				$this->flashMessage($this->translator->translate('Cart is empty.'), 'info', true);
			}
		}
	}


	public function handleDeleteProduct($id, $orderId)
	{
		unset($this->sessionSection->products[$id][$orderId]);
		if (count($this->sessionSection->products[$id]) == 0) {
			unset($this->sessionSection->products[$id]);
		}

		$this->flashMessage($this->translator->translate('Product has been removed from cart.'), 'success');
		$this->redirect('this');
	}


	protected function createComponentCartForm()
	{
		$form = $this->cartFormFactory->invoke();
		$form->onSuccess[] = function ($form) {
			$form->presenter->redirect('this', array('step' => '2'));
		};
		return $form;
	}


	protected function createComponentOrderForm()
	{
		/** @var $control MailControl */
		$control = $this->orderFormFactory->invoke($this->extendedPage->mailform);
		$control['form']->getSaveButton()->caption = 'Complete order';
		$control['form']->getSaveButton()->getControlPrototype()->class[] = 'btn-primary';
		$control->onSendMessage[] = $this->formSend;
		$control->onSendCopyMessage[] = $this->formSend;
		$control->onSuccess[] = $this->orderFormSuccess;
		return $control;
	}


	public function formSend(MailControl $control, Message $mail)
	{
		$mail->setBody($mail->getBody() . "\n\n" . $this->getMessageHeader());
	}


	public function orderFormSuccess(MailControl $control)
	{
		unset($this->sessionSection->products);
		$this->flashMessage('Message has been sent', 'success');
		$this->redirect('this', array('step' => 3));
	}


	/**
	 * @return string
	 */
	protected function getMessageHeader()
	{
		$template = $this->createTemplate('Nette\Templating\Template');
		$template->setSource($this->extendedPage->getTemplate());
		$template->products = $this->getProducts();
		return $template->__toString();
	}


	/**
	 * @return array
	 */
	public function getProducts()
	{
		$res = array();
		if ($products = $this->sessionSection->products) {
			foreach ($products as $id => $orders) {
				$res[$id] = array();
				foreach ($orders as $orderId => $order) {

					$productEntity = $this->productRepository->find($id);
					if (!$productEntity) {
						$this->flashMessage('Některý z produktů, který jste měl v košíku, již neexistuje.', 'error');
						continue;
					}

					if ($order['values']['type']) {
						$typeEntity = $this->typeRepository->find($order['values']['type']);
						if (!$typeEntity) {
							$this->flashMessage('Některý z modelů produktu, který jste měl v košíku, již neexistuje.', 'error');
							continue;
						}
					}

					$res[$id][$orderId] = array('productEntity' => $productEntity, 'typeEntity' => $typeEntity, 'sum' => $order['sum']);
				}
			}
		}
		return $res;
	}
}
