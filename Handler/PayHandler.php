<?php

namespace Odiseo\Bundle\PreorderBundle\Handler;

class PayHandler extends PreOrderHandler
{
	public function processPreOrder($preOrder, $action)
    {
		if ($action == "pagar")
        {
			if ($preOrder->isUnderBuyerControl())
            {
				$this->preOrderManager->payPreOrder($preOrder);
			}
		}else
        {
			$this->nextPreOrderHandler->processPreOrder($preOrder, $action);
		}
	}
}