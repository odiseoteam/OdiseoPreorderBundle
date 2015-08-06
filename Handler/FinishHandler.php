<?php

namespace Odiseo\Bundle\PreorderBundle\Handler;

class FinishHandler extends PreOrderHandler
{
	public function processPreOrder($preOrder, $action)
    {
		if ($action == "finalizar")
        {
			$this->preOrderManager->finishPreOrder($preOrder);
		}else
        {
            $this->nextPreOrderHandler->processPreOrder($preOrder, $action);
		}
	}		
	
}