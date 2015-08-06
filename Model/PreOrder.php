<?php

namespace Odiseo\Bundle\PreorderBundle\Model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Odiseo\Bundle\PreorderBundle\Model\PreOrderInterface;

class PreOrder implements PreOrderInterface
{	
    private $id;
    private $dateCreated;
    private $state;
    private $buyer;
    private $product;
    private $details;
    private $restrictions;
    private $canVendorControl;
    private $canBuyerControl;
    private $isEditable;
    private $history;
    private $logHistory = true;
    private $dateFrom;
    private $dateTo;
    private $usedFor;
    private $artIncluded;
    private $productionIncluded;
    private $termsAndConditions;
    private $artDate;   

    public function __construct()
    {
    	$this->dateCreated = new DateTime('now');
    	$this->history = new ArrayCollection();
    }
    
	public function getId() 
	{
		return $this->id;
	}
	
	public function setId($id) 
	{
		$this->id = $id;
		return $this;
	}
	
	public function getDateCreated() 
	{
		return $this->dateCreated;
	}
	
	public function setDateCreated($dateCreated) 
	{
		$this->dateCreated = $dateCreated;
		return $this;
	}
	
	public function getState() 
	{
		return $this->state;
	}
	
	public function setState($state) 
	{
		$this->state = $state;
		return $this;
	}
	
	public function getVendor() 
	{
		return $this->product->getVendor();
	}
	
	public function getBuyer() 
	{
		return $this->buyer;
	}
	
	public function setBuyer($buyer) 
	{
		$this->buyer = $buyer;
		return $this;
	}
	
	public function getProduct() 
	{
		return $this->product;
	}
	
	public function setProduct($product) 
	{
		$this->product = $product;
		return $this;
	}

	public function getCanVendorControl() 
	{
		return $this->canVendorControl;
	}

	public function setCanVendorControl($canVendorControl) 
	{
		$this->canVendorControl = $canVendorControl;
		return $this;
	}
	
	public function getCanBuyerControl() 
	{
		return $this->canBuyerControl;
	}
	
	public function setCanBuyerControl($canBuyerControl) 
	{
		$this->canBuyerControl = $canBuyerControl;
		return $this;
	}
	
	public function getIsEditable() 
	{
		return $this->isEditable;
	}
	
	public function setIsEditable($isEditable) 
	{
		$this->isEditable = $isEditable;
		return $this;
	}
	
	public function getHistory() 
	{
		return $this->history;
	}
	public function setHistory($history) 
	{
		$this->history = $history;
		return $this;
	}
	
	public function isUnderBuyerControl()
	{
		return $this->getCanBuyerControl();
	}
	
	public function isUnderVendorControl()
	{
		return $this->getCanVendorControl();
	}
	
	public function isEditable()
	{
		return $this->getIsEditable();
	}
	
	public function __toString()
	{
		return strval($this->id);
	}
	
	public function hasToLog($hasToLog = null)
	{
		if (is_null($hasToLog))
		{
			return $this->logHistory;
		}
		else
		{
			return $this->logHistory = $hasToLog;
		}
	}
	
	public function getDetails() 
	{
		return $this->details;
	}
	
	public function setDetails($details) 
	{
		$this->details = $details;
		return $this;
	}
	
	public function getRestrictions() 
	{
		return $this->restrictions;
	}
	
	public function setRestrictions($restrictions)
	{
		$this->restrictions = $restrictions;
		return $this;
	}
	
	public function getDateFrom()
	{
		return $this->dateFrom;
	}
	
	public function setDateFrom($dateFrom) 
	{
		$this->dateFrom = $dateFrom;
		return $this;
	}
	
	public function getDateTo() 
	{
		return $this->dateTo;
	}
	
	public function setDateTo($dateTo) 
	{
		$this->dateTo = $dateTo;
		return $this;
	}
	
	public function getUsedFor() 
	{
		return $this->usedFor;
	}
	
	public function setUsedFor($usedFor) {
		$this->usedFor = $usedFor;
		return $this;
	}
	
	public function getArtIncluded() 
	{
		return $this->artIncluded;
	}
	
	public function setArtIncluded($artIncluded) 
	{
		$this->artIncluded = $artIncluded;
		return $this;
	}
	
	public function getProductionIncluded() 
	{
		return $this->productionIncluded;
	}
	
	public function setProductionIncluded($productionIncluded) 
	{
		$this->productionIncluded = $productionIncluded;
		return $this;
	}
	
	public function getTermsAndConditions() 
	{
		return $this->termsAndConditions;
	}
	
	public function setTermsAndConditions($termsAndConditions)
	{
		$this->termsAndConditions = $termsAndConditions;
		return $this;
	}
	
	public function getArtDate() 
	{
		return $this->artDate;
	}
	
	public function setArtDate($artDate) 
	{
		$this->artDate = $artDate;
		return $this;
	}
	
	public function update($otherPreorder)
	{
		$this->artDate = $otherPreorder->getArtDate()?$otherPreorder->getArtDate():$this->artDate;
		$this->termsAndConditions = $otherPreorder->getTermsAndConditions()?$otherPreorder->getTermsAndConditions():$this->termsAndConditions;
		$this->productionIncluded = $otherPreorder->getProductionIncluded()?$otherPreorder->getProductionIncluded():$this->productionIncluded;
		$this->artIncluded = $otherPreorder->getArtIncluded()?$otherPreorder->getArtIncluded():$this->artIncluded;
		$this->userFor = $otherPreorder->getUsedFor()?$otherPreorder->getUsedFor():$this->usedFor;
		$this->dateFrom = $otherPreorder->getDateFrom()?$otherPreorder->getDateFrom():$this->dateFrom;
		$this->dateTo = $otherPreorder->getDateTo()?$otherPreorder->getDateTo():$this->dateTo;
		$this->restrictions = $otherPreorder->getRestrictions()?$otherPreorder->getRestrictions():$this->restrictions;
		$this->details = $otherPreorder->getDetails()?$otherPreorder->getDetails():$this->details;
	}
}