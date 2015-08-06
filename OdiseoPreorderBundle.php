<?php

namespace Odiseo\Bundle\PreorderBundle;

use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Bundle\ResourceBundle\ResourceBundleInterface;

class OdiseoPreorderBundle extends AbstractResourceBundle
{
	protected $mappingFormat = ResourceBundleInterface::MAPPING_YAML;
	
	public static function getSupportedDrivers()
	{
		return array(
			SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
		);
	}
	
    protected function getModelInterfaces()
    {
        return array(
            'Odiseo\Bundle\PreorderBundle\Model\PreOrderInterface' => 'odiseo_preorder.model.preorder.class',
        	'Odiseo\Bundle\PreorderBundle\Model\PreOrderStateInterface' => 'odiseo_preorder.model.preorder_state.class',
        	'Odiseo\Bundle\PreorderBundle\Model\PreOrderHistoryInterface' => 'odiseo_preorder.model.preorder_history.class'
        );
    }
    
    protected function getModelNamespace()
    {
    	return 'Odiseo\Bundle\PreorderBundle\Model';
    }
}

