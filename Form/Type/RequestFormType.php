<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RequestFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('dateFrom', 'date', array(
		        'widget' => 'single_text',
    		    'label' => 'odiseo.preorder.contract.date_from'
		    ))
            ->add('dateTo', 'date', array(
			    'widget' => 'single_text',
    		    'label' => 'odiseo.preorder.contract.date_to'
		    ))
            ->add('usedFor', 'textarea', array(
		        'required'  => 'true',
			    'label' => 'odiseo.preorder.contract.used_for',
		    ))
		;    
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
        return 'odiseo_preorder_request';
    }
}