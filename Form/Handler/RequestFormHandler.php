<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Odiseo\Bundle\PreorderBundle\Services\PreOrderManagerService;
use Symfony\Component\HttpFoundation\Request;

class RequestMediaFormHandler
{    
	protected $container;
	protected $preOrderService;
	protected $preOrderManager;
	protected $productService;
	protected $buyer;
    
    public function __construct( ContainerInterface $container)
    {
    	$this->container = $container;
    	$this->preOrderService = $container->get("preorder.service");
    	$this->preOrderManager = $container->get('preorder.manager.service');
    	$this->productService = $container->get('odiseo.product.service');
    	$this->buyer = $container->get('security.context')->getToken()->getUser();
    }
    
    public function process(FormInterface $form, Request $request, $productId)
    {
    	$form->bind($request);
    	if ($form->isValid()) 
    	{
    		return $this->processValidForm($form, $productId);
    	}
    	
    	return false;
    }
    
    public function processValidForm(FormInterface $form, $productId)
    {
    	$buyerId = $this->buyer->getId();
    	$product = $this->productService->findOneById($productId);
    	
    	$savedPreOrder = $this->preOrderService->findPreorderByBuyerAndProduct($buyerId , $productId);    	
    	if($savedPreOrder == null)
    	{    	
    		$savedPreOrder = $this->preOrderManager->createOrder($this->buyer, $product);
    	}
    	
    	$preOrder = $form->getData();
    	$savedPreOrder->update($preOrder);
    	
    	$this->preOrderManager->manage($preOrder, PreOrderManagerService::ACTION_NUEVA);
    	
    	return true;
    }
}
