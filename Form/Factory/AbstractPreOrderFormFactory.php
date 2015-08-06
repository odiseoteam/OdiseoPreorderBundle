<?php

namespace Odiseo\Bundle\PreorderBundle\Form\Factory;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;

abstract class AbstractPreOrderFormFactory
{
    protected $formFactory;
    protected $formType;
    protected $formName;
    protected $modelClass;

    public function __construct(FormFactoryInterface $formFactory, AbstractType $formType, $formName, $modelClass)
    {
        $this->formFactory = $formFactory;
        $this->formType = $formType;
        $this->formName = $formName;
        $this->modelClass = $modelClass;
    }
    
    protected function createModelInstance()
    {
    	$class = $this->modelClass;
    
    	return new $class();
    }
    
    protected  function create()
    {
    	$model = $this->createModelInstance();
    	return $this->formFactory->createNamed($this->formName, $this->formType, $model);
    }
}