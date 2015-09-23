<?php

namespace Odiseo\Bundle\PreorderBundle\Model;

/**
 * PreOrderInterface
 */
interface PreOrderInterface
{
    public function getId();

    public function setId($id);

    public function getDateCreated();

    public function setDateCreated($dateCreated);

    public function getState();

    public function setState($state);

    public function getVendor();

    public function getBuyer();

    public function setBuyer($buyer);

    public function getProduct();

    public function setProduct($product);

    public function getCanVendorControl();

    public function setCanVendorControl($canVendorControl);

    public function getCanBuyerControl();

    public function setCanBuyerControl($canBuyerControl);

    public function getIsEditable();

    public function setIsEditable($isEditable);

    public function getHistory();

    public function setHistory($history);

    public function isUnderBuyerControl();

    public function isUnderVendorControl();

    public function isEditable();

    public function hasToLog($hasToLog = null);

    public function getDetails();

    public function setDetails($details);

    public function getRestrictions();

    public function setRestrictions($restrictions);

    public function getDateFrom();

    public function setDateFrom($dateFrom);

    public function getDateTo();

    public function setDateTo($dateTo);

    public function getUsedFor();

    public function setUsedFor($usedFor);

    public function getArtIncluded();

    public function setArtIncluded($artIncluded);

    public function getProductionIncluded();

    public function setProductionIncluded($productionIncluded);

    public function getTermsAndConditions();

    public function setTermsAndConditions($termsAndConditions);

    public function getArtDate();

    public function setArtDate($artDate);

    public function getPrice();

    public function setPrice($price);

    public function isReviewPending();

    public function setReviewPending($reviewPending);
}