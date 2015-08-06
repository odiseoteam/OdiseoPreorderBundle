<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Odiseo\Bundle\PreorderBundle\Model\PreOrderState;

class PreOrderFormTypeSuscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array (
            FormEvents::POST_SET_DATA => 'onPostSetData',
        );
    }

    public function onPostSetData(FormEvent $event)
    {
    	$preOrder = $event->getData();
    	$form = $event->getForm();
    	
    	if(null != $preOrder && $preOrder->getId() != null)
    	{
	    	if ($preOrder->isUnderBuyerControl())
            {
	    		if ($preOrder->getState()->getName() == PreOrderState::STATE_NUEVA || $preOrder->getState()->getName() == PreOrderState::STATE_ENVIADA )
                {
	    			$form->add('enviar', 'submit' , array('label' => 'odiseo.preorder.contract.send'));
	    			$form->add('guardar', 'submit' , array('label' => 'odiseo.preorder.contract.save'));
	    		}else if ($preOrder->getState()->getName() == PreOrderState::STATE_ACEPTADA )
                {
	    			$form->add('pagar', 'submit' , array('label' => 'odiseo.preorder.contract.pay'));
	    			$this->disabledDescription($form);
	    		}
	    	}else
            {
	    		if ( $preOrder->getState()->getName() == PreOrderState::STATE_ENVIADA)
                {
	    			if ($preOrder->isEditable())
	    			{
	    				$form->add('guardar', 'submit' , array('label' => 'odiseo.preorder.contract.save'));
	    				$form->add('enviar', 'submit' , array('label' => 'odiseo.preorder.contract.send'));
	    			}
	    			else{
	    				$form->add('editar', 'submit' , array('label' => 'odiseo.preorder.contract.edit'));
	    				$form->add('aceptar', 'submit' , array('label' => 'odiseo.preorder.contract.accept'));
	    				$this->disabledDescription($form);
	    			}
	    		}else if ( $preOrder->getState()->getName() == PreOrderState::STATE_PAGADA )
                {
	    			$form->add('verificar', 'submit' , array('label' => 'odiseo.preorder.contract.verify'));
	    			$this->disabledDescription($form);
	    		}
	    	}
    	}
    	else
    	{
    		$form->add('guardar', 'submit' , array('label' => 'odiseo.preorder.contract.save'));
    		$form->add('enviar', 'submit' , array('label' => 'odiseo.preorder.contract.send'));
    	}

    }

    public function disabledDescription($form)
    {
    	$form->add('description', 'textarea', array(
            'required' => true,
            'disabled' => true,
            'label'    => 'odiseo.preorder.contract.description'
    	));
    }

    public function onPostsSubmit(FormEvent $event)
    {
//         $user = $event->getData();
//         $form = $event->getForm();

//         if (!$user) {
//             return;
//         }

//         // Check whether the user has chosen to display his email or not.
//         // If the data was submitted previously, the additional value that
//         // is included in the request variables needs to be removed.
//         if (true === $user['show_email']) {
//             $form->add('email', 'email');
//         } else {
//             unset($user['email']);
//             $event->setData($user);
//         }
    }
}