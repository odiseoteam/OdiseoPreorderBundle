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
		$this->preOrderService = $container->get('odiseo_preorder.service.preorder');
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$productId = $this->request->get('productId');
    	$buyerId  = $this->request->get('buyerId');
    	$preOrder = $this->preOrderService->getMainRepository()->findLastByBuyerAndProduct($buyerId, $productId);

		if($preOrder)
		{
			if (!in_array($preOrder->getState()->getName(), array(
				PreOrderState::PAGADA,
				PreOrderState::VERIFICADA,
				PreOrderState::FINALIZADA
			)))
			{
				$builder->add('decline', 'button', array('label' => 'odiseo.preorder.contract.decline'));

				$sendText = PreOrderState::NUEVA == $preOrder->getState()->getName() ? 'send' : 'modify';
				$builder->add('contract', 'button', array('label' => 'odiseo.preorder.contract.' . $sendText));
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
        return 'odiseo_preorder_show_vendor_buttons';
    }
}
