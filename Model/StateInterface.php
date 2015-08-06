<?php

namespace Odiseo\Bundle\PreorderBundle\Model;

/**
 * StateInterface
 */
interface StateInterface extends StatableInterface
{
    public function getId();

    public function setId($id);
}