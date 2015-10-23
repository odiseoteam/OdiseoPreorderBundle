<?php

namespace Odiseo\Bundle\PreorderBundle\Handler;

class DeclineHandler extends PreOrderHandler
{
	public function processPreOrder($preOrder, $action)
    {
		if ($action == "decline")
        {
			$this->preOrderManager->declinePreOrder($preOrder);
		}else
        {
            $this->nextPreOrderHandler->processPreOrder($preOrder, $action);
		}
	}		
	
}