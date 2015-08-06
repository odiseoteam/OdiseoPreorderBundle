<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Odiseo\Bundle\PreorderBundle\Model\PreOrderState;

class ShowVendorButtonsFormType extends AbstractType
{	
	private $request;
	private $preOrderService;

	public function __construct(ContainerInterface $container)
    {
		
		$this->request = $container->get('request');
		$this->preOrderService = $container->get('preorder.service');
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$productId = $this->request->get('productId');
    	$buyerId  = $this->request->get('buyerId');
    	$preOrder = $this->preOrderService->findOneByKeysValues(array('product' => $productId , 'buyer' => $buyerId ));
    	$builder->add('decline', 'button' , array('label' => 'odiseo.preorder.contract.decline'));

		if($preOrder)
		{
    		if(PreOrderState::NUEVA == $preOrder->getState()->getName())
            {
                $builder->add('contract', 'button', array('label' => 'odiseo.preorder.contract.send'));
            } else
            {
    			$builder->add('contract', 'button' , array('label' => 'odiseo.preorder.contract.sent', 'disabled' => true));
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
        return 'odiseo_preorder_show_vendor_button';
    }
}
