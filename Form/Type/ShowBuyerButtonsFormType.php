<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Odiseo\Bundle\PreorderBundle\Model\PreOrderState;

class ShowBuyerButtonsFormType extends AbstractType
{
	const SUBMIT_BUTTON_LABEL = "Ready To PreOrder";
	const ALREADY_SUBMIT_LABEL = "Request already sent..";
	const PAY_LABEL = "Pagar";
	
	private $request;
	private $preOrderService;
	private $buyer;

	public function __construct(ContainerInterface $container)
    {
		$this->request = $container->get('request');
		$this->buyer = $container->get('security.context')->getToken()->getUser();
		$this->preOrderService = $container->get('odiseo_preorder.service.preorder');
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $productId = $this->request->get('productId');
        $preOrder = $this->preOrderService->getMainRepository()->findLastByBuyerAndProduct($this->buyer->getId(), $productId);

    	if($preOrder)
    	{
            if (!in_array($preOrder->getState()->getName(), array(
                PreOrderState::PAGADA,
                PreOrderState::VERIFICADA,
                PreOrderState::FINALIZADA,
                PreOrderState::RECHAZADA_VENDOR,
                PreOrderState::RECHAZADA_BUYER
            )))
            {
                $builder->add('decline', 'button', array('label' => 'odiseo.preorder.contract.decline'));
            }

            if(PreOrderState::STATE_ACEPTADA_VENDOR == $preOrder->getState()->getName())
            {
                $builder->add('pay', 'button', array('label' => 'odiseo.preorder.contract.pay'));
            }else if(PreOrderState::PAGADA == $preOrder->getState()->getName())
            {
                $builder->add('accept_pop', 'submit', array('label' => 'odiseo.preorder.contract.accept_pop'));
            }
    	}
    }
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
            'cascade_validation' => true,
		));
	}

    public function getName()
    {
        return 'odiseo_preorder_show_buyer_buttons';
    }
}