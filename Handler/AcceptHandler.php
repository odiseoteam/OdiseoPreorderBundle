<?php

namespace Odiseo\Bundle\PreorderBundle\Handler;

class AcceptHandler extends PreOrderHandler
{
	public function processPreOrder($preOrder, $action)
    {
		if ($action == "aceptar")
        {
			$this->preOrderManager->acceptPreOrder($preOrder);
		}else
        {
			$this->nextPreOrderHandler->processPreOrder($preOrder, $action);
		}
	}		
}