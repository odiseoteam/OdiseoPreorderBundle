<?php

namespace Odiseo\Bundle\PreorderBundle\Event;

use Odiseo\Bundle\PreorderBundle\Model\PreOrderInterface;
use Symfony\Component\EventDispatcher\Event;

class PreOrderEvent extends Event
{
	/**
	 * PRE_ORDER_REQUEST_SENT
	 * @var string
	 */
	const PRE_ORDER_REQUEST_SENT = 'preorder.request.sent';
	const PRE_ORDER_CONTRACT_SENT = 'preorder.contract.sent';
	const PRE_ORDER_ACCEPT_POP = 'preorder.accept_pop';
	const PRE_ORDER_ACCEPTED = 'preorder.state.accepted';
	const PRE_ORDER_ABOUT_EXPIRATION = 'preorder.about.expiration';

	/**
	 * The preorder
	 * @var PreOrderInterface
	 */
	private $preOrder;

	public function __construct(PreOrderInterface $preOrder)
	{
		$this->preOrder = $preOrder;
	}

	/**
	 * Returns the preorder
	 *
	 * @return PreOrderInterface
	 */
	public function getPreOrder()
	{
		return $this->preOrder;
	}
}