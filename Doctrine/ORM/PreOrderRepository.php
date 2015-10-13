<?php

namespace Odiseo\Bundle\PreorderBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Odiseo\Bundle\PreorderBundle\Model\PreOrderState;
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
            ->innerJoin('po.product', 'p')
            ->leftJoin('p.vendor', 'v')
            ->where('v.id=:vendorId')
            ->andWhere('p.deletedAt IS NULL')
            ->setParameter('vendorId', $vendorId)
            ->getQuery()
        ;
    }

    /**
     * @param $vendorId
     */
    public function findAllByVendor($vendorId)
    {
        return $this->findAllByVendorQuery($vendorId)->getResult();
    }

    /**
     * @param $buyerId
     * @return QueryBuilder
     */
    public function findAllByBuyerQuery($buyerId)
    {
        return $this->createQueryBuilder('po')
            ->innerJoin('po.product', 'p')
            ->leftJoin('po.buyer', 'b')
            ->where('b.id=:buyerId')
            ->andWhere('p.deletedAt IS NULL')
            ->setParameter('buyerId', $buyerId)
            ->getQuery()
            ;
    }

    /**
     * @param $buyerId
     */
    public function findAllByBuyer($buyerId)
    {
        return $this->findAllByBuyerQuery($buyerId)->getResult();
    }

    /**
     * @param $buyerId
     * @param $productId
     *
     * @return QueryBuilder
     */
    public function findLatestByBuyerAndProductQuery($buyerId, $productId)
    {
        return $this->createQueryBuilder($this->getAlias())
            ->leftJoin($this->getAlias().'.buyer', 'b')
            ->leftJoin($this->getAlias().'.product', 'p')
            ->andWhere('b.id = :buyerId')
            ->andWhere('p.id = :productId')
            ->andWhere('p.deletedAt IS NULL')
            ->setParameter('buyerId', $buyerId)
            ->setParameter('productId', $productId)
            ->orderBy($this->getAlias().'.dateCreated', 'DESC')
            ->getQuery()
            ;
    }
    /**
     * @param $buyerId
     * @param $productId
     */
    public function findLastByBuyerAndProduct($buyerId, $productId)
    {
        return $this->findLatestByBuyerAndProductQuery($buyerId, $productId)
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param $buyerId
     * @param $productId
     *
     * @return QueryBuilder
     */
    public function findNewByBuyerAndProductQuery($buyerId, $productId)
    {
        return $this->createQueryBuilder($this->getAlias())
            ->leftJoin($this->getAlias().'.buyer', 'b')
            ->leftJoin($this->getAlias().'.product', 'p')
            ->leftJoin($this->getAlias().'.state', 's')
            ->andWhere('b.id = :buyerId')
            ->andWhere('p.id = :productId')
            ->andWhere('p.deletedAt IS NULL')
            ->andWhere('s.name = :stateName')
            ->setParameter('buyerId', $buyerId)
            ->setParameter('productId', $productId)
            ->setParameter('stateName', PreOrderState::NUEVA)
            ->getQuery()
        ;
    }

    /**
     * @param $buyerId
     * @param $productId
     */
    public function findNewByBuyerAndProduct($buyerId, $productId)
    {
        return $this->findNewByBuyerAndProductQuery($buyerId, $productId)->getResult();
    }

    /**
     * @param $buyerId
     * @param $productId
     */
    public function findOneNewByBuyerAndProduct($buyerId, $productId)
    {
        return $this->findNewByBuyerAndProductQuery($buyerId, $productId)->setMaxResults(1)->getOneOrNullResult();
    }
}
