<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Odiseo\Bundle\PreorderBundle\Services\PreOrderManagerService;
use Symfony\Component\Form\FormInterface;

class ContractFormHandler
{
	protected $serviceContainer;
	protected $request;
	protected $productService;
	protected $buyer;
	protected $composer;
	protected $deliveryService;
	protected $threadService;
	protected $preOrderService;
	
    public function __construct(ContainerInterface $container)
    {
    	$this->container = $container;
    	$this->preOrderService = $container->get("odiseo_preorder.service.preorder");
    	$this->request = $container->get('request');
    	$this->preOrderManager = $container->get('odiseo_preorder.service.preorder_manager');
    	$this->productService = $container->get('odiseo_product.service.product');
    	$this->buyer = $container->get('security.context')->getToken()->getUser();
    }
    
    public function process(FormInterface $form)
    {
    	$form->submit($this->request);

    	if ($form->isValid())
        {
    		return $this->processValidForm($form);
    	}

    	return false;
    }
    
    public function processValidForm(FormInterface $form)
    {
    	$buyerId = $this->request->get('buyerId');
    	$productId = $this->request->get('productId');

    	$preOrder = $form->getData();
    	$savedPreOrder = $this->preOrderService->getMainRepository()->findLastByBuyerAndProduct($buyerId, $productId);

		$this->preOrderManager->updatePreorder($savedPreOrder, $preOrder);
    	$this->preOrderManager->manage($savedPreOrder, PreOrderManagerService::ACTION_ACEPTAR);

    	return $savedPreOrder;
    }
}
