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
		$this->preOrderService = $container->get('preorder.service');
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$idProduct = $this->request->get('productId');
    	$preOrder = $this->preOrderService->findOneByKeysValues(array(
            'product' => $idProduct,
            'buyer' => $this->buyer->getId()
        ));

    	if($preOrder)
    	{
	    	if( PreOrderState::STATE_ACEPTADA_VENDOR ==  $preOrder->getState()->getName())
	    	{    		
	    		$builder->add('pay', 'button' , array('label' => 'odiseo.preorder.contract.pay'));
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