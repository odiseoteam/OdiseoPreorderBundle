<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContractFormType extends AbstractType
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
            ->add('usedFor', 'text', array(
		        'required'  => 'true',
			    'label' => 'odiseo.preorder.contract.used_for',
		    ))
		    ->add('restrictions', 'textarea', array(
				'required'  => 'true',
				'label' => 'odiseo.preorder.contract.restrictions'
		    ))
		    ->add('details', 'textarea', array(
				'required'  => 'true',
				'label' => 'odiseo.preorder.contract.details'
		    ))
		    ->add('artIncluded', 'checkbox', array(
				'required'  => 'true',
				'label' => 'odiseo.preorder.contract.art_included'
		    ))
		    ->add('productionIncluded', 'checkbox', array(
				'required'  => 'true',
				'label' => 'odiseo.preorder.contract.production_included'
		    ))
		    ->add('artDate', 'date', array(
		  	    'widget' => 'single_text',
    		    'label' => 'odiseo.preorder.contract.art_date'
		    ))
		    ->add('termsAndConditions', 'checkbox', array(
				'required' => 'true',
				'label' => 'odiseo.preorder.contract.accept_terms'
		    ))
    	    ->add('submit', 'submit' , array(
                'label' => 'odiseo.preorder.contract.submit'
            ))
        ;
    }
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
            'cascade_validation' => true,
		));
	}

    public function getName()
    {
        return 'odiseo_preorder_contract';
    }
}
