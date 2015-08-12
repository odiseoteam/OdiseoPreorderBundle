<?php

namespace Odiseo\Bundle\PreorderBundle\Services;

use Odiseo\Bundle\CoreBundle\Services\BaseDbService;

class PreOrderHistoryService extends BaseDbService
{
	private $preOrderHistoryRepository;
	
	public function __construct($em, $preOrderHistoryRepository)
	{
		parent::__construct($em);
		$this->preOrderHistoryRepository = $preOrderHistoryRepository;
	}
	
	protected function getMainRepository()
    {
		return $this->preOrderHistoryRepository;		
	}
}