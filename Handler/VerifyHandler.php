<?php

namespace Odiseo\Bundle\PreorderBundle\Handler;

class VerifyHandler extends PreOrderHandler
{
	public function processPreOrder($preOrder, $action)
    {
        if ($action == "verificar")
        {
            $this->preOrderManager->verifyPreOrder($preOrder);
        } else
        {
            $this->nextPreOrderHandler->processPreOrder($preOrder, $action);
        }
    }
}