<?php

namespace Odiseo\Bundle\PreorderBundle\Model;

/**
 * StatableInterface
 */
interface StatableInterface
{
    public function getName();

    public function setName($name);

    public function getPreviousState();

    public function setPreviousState($previousState);

    public function getNextState();

    public function setNextState($nextState);
}