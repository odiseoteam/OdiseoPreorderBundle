<?php

namespace Odiseo\Bundle\PreorderBundle\Handler;

class CreateHandler extends PreOrderHandler
{
	public function processPreOrder($preOrder, $action)
    {
		if ($action == "nueva")
        {
			$this->preOrderManager->sendPreOrderToVendor($preOrder);
		}else
        {
			$this->nextPreOrderHandler->processPreOrder($preOrder, $action);
		}
	}
}