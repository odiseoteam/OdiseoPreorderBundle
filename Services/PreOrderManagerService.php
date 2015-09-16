<?php

namespace Odiseo\Bundle\PreorderBundle\Services;

use Odiseo\Bundle\PreorderBundle\Model\PreOrder;
use Odiseo\Bundle\PreorderBundle\Model\PreOrderState;
use Odiseo\Bundle\PreorderBundle\Handler\CreateHandler;
use Odiseo\Bundle\PreorderBundle\Handler\AcceptHandler;
use Odiseo\Bundle\PreorderBundle\Handler\EditHandler;
use Odiseo\Bundle\PreorderBundle\Handler\SaveHandler;
use Odiseo\Bundle\PreorderBundle\Handler\PayHandler;
use Odiseo\Bundle\PreorderBundle\Handler\VerifyHandler;
use Odiseo\Bundle\PreorderBundle\Handler\FinishHandler;

class PreOrderManagerService
{
	const ACTION_NUEVA = "nueva";
	const ACTION_ENVIAR = "enviar";
	const ACTION_ACEPTAR = "aceptar";	
	
	private $preOrderService;
	private $preOrderStateService;
	private $preOrderHandler;
	
	public function __construct($preOrderService, $preOrderStateService)
	{
		$this->preOrderService = $preOrderService;
		$this->preOrderStateService = $preOrderStateService;
		
		$createHandler = new CreateHandler($this);
		$acceptHandler = new AcceptHandler($this);
		$editHandler = new EditHandler($this);
		$saveHandler = new SaveHandler($this);
		$payHandler = new PayHandler($this);
		$verifyHandler = new VerifyHandler($this); 
		$finishHandler = new FinishHandler($this);
		
		$verifyHandler->setNexHandler($finishHandler);
		$payHandler->setNexHandler($verifyHandler);
		$acceptHandler->setNexHandler($payHandler);
		$saveHandler->setNexHandler($acceptHandler);
		$editHandler->setNexHandler($saveHandler);
		$createHandler->setNexHandler($editHandler);
		
		$this->preOrderHandler = $createHandler;
	}
	
	
	public function manage($preOrder, $action)
	{
		$this->preOrderHandler->processPreOrder($preOrder, $action);
	}
	
	public function sendPreOrderToVendor($preOrder)
	{
		$preOrder->setCanVendorControl(true)->setCanBuyerControl(false);
		$state = $this->preOrderStateService->findOneById(PreOrderState::NUEVA);
		$preOrder->setState($state);
		$preOrder->setIsEditable(false);
		return $this->preOrderService->saveOrUpdate($preOrder);
	}
		
	public function sendPreOrderToBuyer($preOrder)
	{
		$preOrder->setCanVendorControl(false)->setIsEditable(true)->setCanBuyerControl(true);
		$state = $this->preOrderStateService->findOneById(PreOrderState::NUEVA);
		$preOrder->setState($state);
		$this->preOrderService->saveOrUpdate($preOrder);
	}
	
	public function enableEdit($preOrder)
	{
		$preOrder->hasToLog(false);
		$preOrder->setCanVendorControl(true)->setIsEditable(true)->setCanBuyerControl(false);
		$this->preOrderService->saveOrUpdate($preOrder);
	}
	
	public function acceptPreOrder($preOrder)
	{
		if(PreOrderState::STATE_NUEVA == $preOrder->getState()->getName() ||
				PreOrderState::RECHAZADA_BUYER == $preOrder->getState()->getName())
		{
			$preOrder->setIsEditable(true)->setCanVendorControl(false)->setCanBuyerControl(true);
			$state = $this->preOrderStateService->findOneById(PreOrderState::ACEPTADA_VENDOR);
			$preOrder->setState($state);
		}
		$this->preOrderService->saveOrUpdate($preOrder);
	}
	
	public function savePreOrderDescription($preOrder)
	{
		$preOrder->hasToLog(false);
		$this->preOrderService->saveOrUpdate($preOrder);
	}
	
	public function payPreOrder($preOrder)
	{
		$preOrder->setIsEditable(false)->setCanVendorControl(true)->setCanBuyerControl(false);
		$state = $this->preOrderStateService->findOneById(PreOrderState::PAGADA);
		$preOrder->setState($state);
		$this->preOrderService->saveOrUpdate($preOrder);
	}
		
	public function verifyPreOrder($preOrder)
	{
		$preOrder->setIsEditable(false)->setCanVendorControl(true)->setCanBuyerControl(false);
		$state = $this->preOrderStateService->findOneById(PreOrderState::VERIFICADA);
		$preOrder->setState($state);
		$this->preOrderService->saveOrUpdate($preOrder);
	
	}
	
	public function finishPreOrder($preOrder)
	{
		$preOrder->setIsEditable(false)->setCanVendorControl(true)->setCanBuyerControl(false);
		$state = $this->preOrderStateService->findOneById(PreOrderState::FINALIZADA);
		$preOrder->setState($state);
		$this->preOrderService->saveOrUpdate($preOrder);
	}
	
	public function createPreorder($buyer, $product)
	{
		$preOrder = new PreOrder();
		$preOrder->setBuyer($buyer);
		$preOrder->setProduct($product);
		$state = $this->preOrderStateService->findOneById(PreOrderState::NUEVA);
		$preOrder->setState($state);
		$preOrder->setCanBuyerControl(true);
		$preOrder->setCanVendorControl(false);
		$preOrder->setIsEditable(true);
		$preOrder->hasToLog(false);

		return $preOrder;
	}

    public function updatePreorder($oPreorder, $nPreorder)
    {
        $oPreorder->setArtDate($nPreorder->getArtDate()?$nPreorder->getArtDate():$oPreorder->getArtDate());
        $oPreorder->setTermsAndConditions($nPreorder->getTermsAndConditions()?$nPreorder->getTermsAndConditions():$oPreorder->getTermsAndConditions());
        $oPreorder->setProductionIncluded($nPreorder->getProductionIncluded()?$nPreorder->getProductionIncluded():$oPreorder->getProductionIncluded());
        $oPreorder->setArtIncluded($nPreorder->getArtIncluded()?$nPreorder->getArtIncluded():$oPreorder->getArtIncluded());
        $oPreorder->setUsedFor($nPreorder->getUsedFor()?$nPreorder->getUsedFor():$oPreorder->getUsedFor());
        $oPreorder->setDateFrom($nPreorder->getDateFrom()?$nPreorder->getDateFrom():$oPreorder->getDateFrom());
        $oPreorder->setDateTo($nPreorder->getDateTo()?$nPreorder->getDateTo():$oPreorder->getDateTo());
        $oPreorder->setRestrictions($nPreorder->getRestrictions()?$nPreorder->getRestrictions():$oPreorder->getRestrictions());
        $oPreorder->setDetails($nPreorder->getDetails()?$nPreorder->getDetails():$oPreorder->getDetails());
		$oPreorder->setPrice($nPreorder->getPrice());
    }
}