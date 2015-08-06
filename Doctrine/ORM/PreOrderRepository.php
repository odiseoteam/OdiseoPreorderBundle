<?php

namespace Odiseo\Bundle\PreorderBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * PreOrderRepository
 */ 
class PreOrderRepository extends EntityRepository
{
    /**
     * @param $vendorId
     * @return QueryBuilder
     */
    public function findAllByVendorQuery($vendorId)
    {
        return $this->createQueryBuilder('po')
            ->leftJoin('po.product', 'p')
            ->leftJoin('p.vendor', 'v')
            ->where('v.id=:vendorId')
            ->setParameter('vendorId', $vendorId)
            ->getQuery();
    }

    /**
     * @param $vendorId
     */
    public function findAllByVendor($vendorId)
    {
        return $this->findAllByVendorQuery($vendorId)->getResult();
    }
}
