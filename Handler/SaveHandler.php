<?php

namespace Odiseo\Bundle\PreorderBundle\Handler;

class SaveHandler extends PreOrderHandler
{
	public function processPreOrder($preOrder, $action)
    {
		if ($action == "guardar")
        {
			$this->preOrderManager->savePreOrderDescription($preOrder);
		}else
        {
			$this->nextPreOrderHandler->processPreOrder($preOrder, $action);
		}
	}		
}