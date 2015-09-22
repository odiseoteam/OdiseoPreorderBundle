<?php

namespace Odiseo\Bundle\PreorderBundle\EventListener;

use Odiseo\Bundle\ProductBundle\Events\ReviewEventListenerInterface;
use Sylius\Component\Resource\Event\ResourceEvent;

class PreCreationReviewEventListener implements ReviewEventListenerInterface
{
	private $productRankingService;
	private $productService;
	private $preorderService;

	public function __construct($productRankingService, $productService, $preorderService)
	{
		$this->productRankingService = $productRankingService;
		$this->productService = $productService;
		$this->preorderService = $preorderService;
	}

	public function onNewReview(ResourceEvent $event)
	{
		$review = $event->getSubject();
		$preOrders = $this->preorderService->findPreordersByBuyerAndProduct($review->getUser()->getId(), $review->getProduct()->getId());
		foreach($preOrders as $index => $preOrder){
			$preOrder->setReviewPending(false);
			$this->preorderService->update($preOrder);
		}
	}

	public function onPreReviewCreation(ResourceEvent $event)
	{
		$review = $event->getSubject();
		$preOrders = $this->preorderService->findPreordersByBuyerAndProduct($review->getUser()->getId(), $review->getProduct()->getId());
		foreach($preOrders as $index => $preOrder){
			if($preOrder->isReviewPending()){
				return;
			}
		}
		$event->stopPropagation();
	}
}