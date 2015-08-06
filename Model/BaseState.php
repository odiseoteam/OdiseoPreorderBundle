<?php

namespace Odiseo\Bundle\PreorderBundle\Model;

/**
 * BaseState
 */
abstract class BaseState implements StateInterface
{
    /**
     * @var integer
     */
    protected $id;
    protected $name;
    protected $previousState;
    protected $nextState;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
	{
		$this->id = $id;
    }
    
	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	public function getPreviousState()
	{
		return $this->previousState;
	}

	public function setPreviousState($previousState)
	{
		$this->previousState = $previousState;
		return $this;
	}

	public function getNextState()
	{
		return $this->nextState;
	}

	public function setNextState($nextState)
	{
		$this->nextState = $nextState;
		return $this;
	}
}