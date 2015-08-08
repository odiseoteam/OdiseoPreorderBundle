<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Odiseo\Bundle\PreorderBundle\Services\PreOrderManagerService;

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
    	$this->preOrderService = $container->get("preorder.service");
    	$this->request = $container->get('request');
    	$this->preOrderManager = $container->get('preorder.manager.service');
    	$this->productService = $container->get('odiseo_product.service.product');
    	$this->buyer = $container->get('security.context')->getToken()->getUser();
    }
    
    public function process(Form $form)
    {
    	$form->bind($this->request);

    	if ($form->isValid())
        {
    		return $this->processValidForm($form);
    	}

    	return false;
    }
    
    public function processValidForm(Form $form)
    {
    	$buyerId = $this->request->get('buyerId');
    	$productId = $this->request->get('productId');
    	$preOrder = $form->getData();
    	$savedPreOrder = $this->preOrderService->findPreorderByBuyerAndProduct($buyerId , $productId );
    	$savedPreOrder->update($preOrder);
    	$this->preOrderManager->manage($savedPreOrder, PreOrderManagerService::ACTION_ACEPTAR);

    	return true;
    }
}
