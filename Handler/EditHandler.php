<?php

namespace Odiseo\Bundle\PreorderBundle\Handler;

class EditHandler extends PreOrderHandler
{
	public function processPreOrder($preOrder, $action)
    {
		if ($action == "editar")
        {
			$this->preOrderManager->enableEdit($preOrder);
		}else
        {
			$this->nextPreOrderHandler->processPreOrder($preOrder, $action);
		}
	}		
}