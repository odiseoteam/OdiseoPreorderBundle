<?php

namespace Odiseo\Bundle\PreorderBundle\Services;

use Odiseo\Bundle\EcommerceBundle\Services\BaseDbService;

class PreOrderStateService extends BaseDbService
{
	private $preOrderStateRepository;
	
	public function __construct($em , $preOrderStateRepository)
	{
		parent::__construct($em);
		$this->preOrderStateRepository = $preOrderStateRepository;
	}
	
	/** TODO REMOVE THIS METHOD **/
	public function findOneById($name)
	{
		return $this->preOrderStateRepository->findOneByName($name);
	}
	
	protected function getMainRepository() {
		return $this->preOrderStateRepository;		
	}
}