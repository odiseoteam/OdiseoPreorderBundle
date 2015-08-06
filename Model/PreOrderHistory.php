<?php

namespace Odiseo\Bundle\PreorderBundle\Model;

use DateTime;
use Odiseo\Bundle\PreorderBundle\Model\PreOrderHistoryInterface;

/**
 * PreOrderHistoryInterface
 */
class PreOrderHistory implements PreOrderHistoryInterface
{
	private $id;
	private $dateCreated;
	private $preOrder;
	private $state;
	private $details;
	private $restrictions;
	private $canVendorControl;
	private $canBuyerControl;
	private $isEditable ;
	private $dateFrom;
	private $dateTo;
	private $usedFor;
	private $artIncluded ;
	private $productionIncluded;
	private $termsAndConditions;
	private $artDate;

    public function __construct($preOrder)
    {
    	$this->dateCreated = new DateTime('now');
    	$this->preOrder = $preOrder;
    	$this->state = $preOrder->getState()->getName();
    	$this->details = $preOrder->getDetails();
    	$this->restrictions = $preOrder->getRestrictions();
    	$this->canVendorControl = $preOrder->getCanVendorControl();
    	$this->canBuyerControl = $preOrder->getCanBuyerControl();
    	$this->isEditable = $preOrder->getIsEditable();
    	$this->dateFrom = $preOrder->getDateFrom();
    	$this->dateTo = $preOrder->getDateTo();
    	$this->usedFor = $preOrder->getUsedFor();
    	$this->artIncluded = $preOrder->getArtIncluded();
    	$this->productionIncluded = $preOrder->getProductionIncluded();
    	$this->termsAndConditions = $preOrder->getTermsAndConditions();
    	$this->artDate = $preOrder->getArtDate();
    }
}

