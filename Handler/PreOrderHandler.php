<?php

namespace Odiseo\Bundle\PreorderBundle\Handler;

abstract class PreOrderHandler
{
	protected $nextPreOrderHandler;
	protected $preOrderManager;
	
	public function __construct($preOrderManager)
	{
		$this->preOrderManager = $preOrderManager;
	}
	
	public function setNexHandler($preOrderHandler)
    {
		$this->nextPreOrderHandler  = $preOrderHandler;
	}
	
	public abstract function processPreOrder($preOrder, $action);
}