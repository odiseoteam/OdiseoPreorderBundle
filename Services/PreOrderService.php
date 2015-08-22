<?php

namespace Odiseo\Bundle\PreorderBundle\Services;

use Odiseo\Bundle\CoreBundle\Services\BaseDbService;

class PreOrderService extends BaseDbService
{
	private $preOrderRepository;
	
	public function __construct($em , $preOrderRepository)
	{
		parent::__construct($em);

		$this->preOrderRepository = $preOrderRepository;
	}
	
	public function getMainRepository()
    {
		return $this->preOrderRepository;		
	}
	
	public function findPreorderByBuyerAndProduct($buyerId, $productId)
    {
		return $this->findOneByKeysValues(array('buyer' => $buyerId, 'product' => $productId));
	}
	
	public function findVendorPreorders($vendorId)
    {
		return $this->preOrderRepository->findAllByVendor($vendorId);
	}
	
	public function findBuyerPreorders($buyerId)
    {
		return $this->findByKeyValue( 'buyer' , $buyerId);
	}
}