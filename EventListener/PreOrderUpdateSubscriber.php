<?php

namespace Odiseo\Bundle\PreorderBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Odiseo\Bundle\PreorderBundle\Model\PreOrder;
use Odiseo\Bundle\PreorderBundle\Model\PreOrderHistory;

class PreOrderUpdateSubscriber implements EventSubscriber
{
	public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        //$this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof PreOrder)
        {
            if($entity->hasToLog())
            {
                $history = new PreOrderHistory($entity);
                $em = $args->getEntityManager();
                $em->persist($history);
                $em->flush($history);
            }
        }
    }
 }