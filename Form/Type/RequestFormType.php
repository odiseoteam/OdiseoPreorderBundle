<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class RequestFormType extends AbstractResourceType
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

    public function getName()
    {
        return 'odiseo_preorder_request';
    }
}