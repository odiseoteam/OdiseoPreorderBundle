<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class ContractFormType extends AbstractResourceType
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
				'label' => 'odiseo.preorder.contract.restrictions',
				'attr' => array(
					'class' => 'tinymce'
				)
		    ))
			->add('price', 'sylius_money', array(
				'required'  => 'true',
				'label' => 'odiseo.preorder.contract.price'
			))
		    ->add('details', 'textarea', array(
				'required'  => 'true',
				'label' => 'odiseo.preorder.contract.details',
				'attr' => array(
					'class' => 'tinymce'
				)
		    ))
		    ->add('artIncluded', 'checkbox', array(
				'required'  => 'true',
				'label' => 'odiseo.preorder.contract.art_included',
				'required' => false
		    ))
		    ->add('productionIncluded', 'checkbox', array(
				'required'  => 'true',
				'label' => 'odiseo.preorder.contract.production_included',
				'required' => false
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

    public function getName()
    {
        return 'odiseo_preorder_contract';
    }
}
