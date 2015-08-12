<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Odiseo\Bundle\PreorderBundle\Services\PreOrderManagerService;
use Symfony\Component\HttpFoundation\Request;

class RequestFormHandler
{    
	protected $container;
	protected $preOrderService;
	protected $preOrderManager;
	protected $productService;
	protected $buyer;
    
    public function __construct(ContainerInterface $container)
    {
    	$this->container = $container;
    	$this->preOrderService = $container->get("odiseo_preorder.service.preorder");
    	$this->preOrderManager = $container->get('odiseo_preorder.service.preorder_manager');
    	$this->productService = $container->get('odiseo_product.service.product');
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
    	
    	$oldPreorder = $this->preOrderService->findPreorderByBuyerAndProduct($buyerId, $productId);
    	if($oldPreorder == null)
    	{
            $oldPreorder = $this->preOrderManager->createPreorder($this->buyer, $product);
    	}

    	$preOrder = $form->getData();

        $this->preOrderManager->updatePreorder($oldPreorder, $preOrder);
        $this->preOrderManager->manage($oldPreorder, PreOrderManagerService::ACTION_NUEVA);

    	return true;
    }
}
