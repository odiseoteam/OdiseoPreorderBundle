<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PreOrderFormType extends AbstractType
{
	private $preOrder;
	private $user;
	
	public function __construct($preOrder = null, $user = null)
	{
		$this->preOrder = $preOrder;
		$this->user = $user;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('description', 'textarea', array(
    			'required' => true,
    			'label'    => 'odiseo.preorder.contract.description'
    	    ))
        ;
    	
    	$builder->addEventSubscriber(new PreOrderFormTypeSuscriber());
    }
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Odiseo\Bundle\PreorderBundle\Model\PreOrder',
			'cascade_validation' => true,
		));
	}

    public function getName()
    {
        return 'odiseo_preorder';
    }
}
