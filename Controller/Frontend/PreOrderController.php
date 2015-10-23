<?php

namespace Odiseo\Bundle\PreorderBundle\Controller\Frontend;

use Odiseo\Bundle\MessagingBundle\Model\Thread;
use Odiseo\Bundle\PreorderBundle\Event\PreOrderEvent;
use Odiseo\Bundle\PreorderBundle\Model\PreOrderInterface;
use Odiseo\Bundle\PreorderBundle\Services\PreOrderManagerService;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Odiseo\Bundle\PreorderBundle\Form\Type\PreOrderFormType;
use Symfony\Component\HttpFoundation\JsonResponse;

class PreOrderController extends ResourceController
{    	
	public function showPreorderButtonAction($productId, $buyerId)
	{
		$productService = $this->get('odiseo_product.service.product');
		$preOrderService = $this->get('odiseo_preorder.service.preorder');
		$preOrder = $preOrderService->getMainRepository()->findLastByBuyerAndProduct($buyerId, $productId);
		$product = $productService->findOneById($productId);
		
		return $this->getButtonFormResponse($product, $buyerId, $preOrder);
	}
	
	public function getButtonFormResponse($product, $buyerId, $preOrder)
	{
		$userId = $this->get('security.context')->getToken()->getUser()->getId();

		if($userId == $product->getVendor()->getId())
		{
			$form = $this->get('form.factory')->create('odiseo_preorder_show_vendor_buttons');
			
			return $this->render('OdiseoPreorderBundle:Frontend/Preorder/Partial:vendor_buttons.html.twig', array(
				'form' => $form->createView(), 
				'id' => $product->getId(), 
				'buyerId' => $buyerId,
				'preOrder' => $preOrder
			));
		}
		else
		{
            $form = $this->get('form.factory')->create('odiseo_preorder_show_buyer_buttons');

			return $this->render('OdiseoPreorderBundle:Frontend/Preorder/Partial:buyer_buttons.html.twig', array(
				'form' => $form->createView(), 
				'id' => $product->getId(), 
				'product' => $product,
				'preOrder' => $preOrder
			));
		}
	}

    /** Show and send request */
    public function showSendRequestAction($productId)
    {
        $form = $this->get('form.factory')->create('odiseo_preorder_request');

        return $this->render('OdiseoPreorderBundle:Frontend/Preorder/Partial:show_send_request.html.twig', array(
            'productId' => $productId,
            'form' => $form->createView()
        ));
    }

    public function sendRequestAction(Request $request)
    {
        $productId = $request->get('id');

        $form = $this->get('form.factory')->create('odiseo_preorder_request');
		$formHandler = $this->container->get('odiseo_preorder.form.handler.request');

        if ($preorder = $formHandler->process($form, $request, $productId))
        {
			$productService = $this->get('odiseo_product.service.product');
			$threadService = $this->get('odiseo_messaging.service.thread_message');

			/** @var Thread $thread */
			$product = $productService->findOneById($productId);
			$userFrom = $this->get('security.context')->getToken()->getUser();
			$userTo = $product->getVendor();

			$thread = $threadService->searchThreadByCreatorAndTopic($userFrom->getId(), $product->getId());
			$thread = $this->sendMessage($thread, $userFrom, $userTo, $product, 'I want buy your media to be used for: "'.$preorder->getUsedFor().'"');

            return $this->redirect($this->generateUrl('odiseo_messaging_list', array(
				'threadId' => $thread->getId()
			)));
        }

        return $this->redirect($this->generateUrl('odiseo_product_show', array('id' => $productId)));
    }

	public function sendMessage($thread, $userFrom, $userTo, $product, $messageBody)
	{
		$composer = $this->get('fos_message.composer');
		$sender = $this->get('fos_message.sender');

		if($thread)
		{
			$message = $composer->reply($thread)
				->setSender($userFrom)
				->getMessage()
			;
		}else
		{
			$message = $composer->newThread()
				->setSubject('Preorder of product')
				->setSender($userFrom)
				->addRecipient($userTo)
				->getMessage()
			;
			$thread = $message->getThread();
			$thread->setTopic($product);
		}

		$message->setBody($messageBody);

		$sender->send($message);

		return $thread;
	}

    /** Show and send contract */
	public function showSendContractAction(Request $request)
	{
		$productId = $request->get('id');
		$buyerId = $request->get('buyerId');

		$preOrder = $this->get('odiseo_preorder.service.preorder')->getMainRepository()->findLastByBuyerAndProduct($buyerId , $productId);

		$form = $this->get('form.factory')->create('odiseo_preorder_contract', $preOrder);

		$productService = $this->get('odiseo_product.service.product');
		$product = $productService->findOneById($productId);

		return new JsonResponse(array(
			'error' => false,
			'html' => $this->renderView('OdiseoPreorderBundle:Frontend/Preorder/Partial:show_send_contract.html.twig', array(
                'product' => $product,
                'buyerId' => $buyerId,
                'preOrder' => $preOrder,
                'form' => $form->createView()
            ))
        ));
	}
	
	public function sendContractAction(Request $request)
	{
        $form = $this->get('form.factory')->create('odiseo_preorder_contract');
		$handler = $this->get('odiseo_preorder.form.handler.contract');

		/** @var PreOrderInterface $preOrder */
		if($preOrder = $handler->process($form))
		{
			$threadService = $this->get('odiseo_messaging.service.thread_message');

			$product = $preOrder->getProduct();
			$userFrom = $this->get('security.context')->getToken()->getUser();
			$userTo = $preOrder->getBuyer();

			$thread = $threadService->searchThreadByCreatorAndTopic($userTo->getId(), $product->getId());
			$thread = $this->sendMessage($thread, $userFrom, $userTo, $product, 'Contract sent.');

			$this->get('event_dispatcher')->dispatch(PreOrderEvent::PRE_ORDER_CONTRACT_SENT , new PreOrderEvent($preOrder) );

			return new JsonResponse(array(
				'error' => false,
				'html' => $this->renderView('OdiseoPreorderBundle:Frontend/Preorder/Partial:contract_sent.html.twig', array(
					'preOrder' => $preOrder
				))
			));
		}else
        {
			return new JsonResponse(array(
				'error' => false,
				'html' => $this->renderView('OdiseoPreorderBundle:Frontend/Preorder/Partial:show_send_contract.html.twig', array(
					'product' => $preOrder->getProduct()->getId(),
					'buyerId' => $preOrder->getBuyer()->getId(),
					'preOrder' => $preOrder,
					'form' => $form->createView()
				))
			));
		}
	}

    /** Details of preorder */
	public function detailAction(Request $request)
	{
		$id = $request->get('id');
		$preorderService = $this->get('odiseo_preorder.service.preorder');
		$preorder = $preorderService->findOneById($id);

		return new JsonResponse(array(
			'error' => false,
			'html' => $this->renderView('OdiseoPreorderBundle:Frontend/Preorder/Partial:detail.html.twig', array(
				'preorder' => $preorder,
				'product' => $preorder->getProduct()
			))
		));
	}

	public function acceptPopAction(Request $request)
	{
		$id = $request->get('id');
		$preorderService = $this->get('odiseo_preorder.service.preorder');
        /** @var PreOrderInterface $preorder */
		$preorder = $preorderService->findOneById($id);

        if($preorder->getBuyer()->getId() == $this->getUser()->getId())
        {
		    $preorderEvent = new PreOrderEvent($preorder);
		    $this->get('event_dispatcher')->dispatch(PreOrderEvent::PRE_ORDER_ACCEPT_POP, $preorderEvent);
        }

		$referer = $request->headers->get('referer');
		return $this->redirect($referer);
	}

    public function declineAction(Request $request)
    {
        $id = $request->get('id');
        $preorderService = $this->get('odiseo_preorder.service.preorder');
        $preorderManager = $this->get('odiseo_preorder.service.preorder_manager');

        /** @var PreOrderInterface $preorder */
        $preorder = $preorderService->findOneById($id);
        $user = $this->getUser();

        if(in_array($user->getId(), array($preorder->getBuyer()->getId(), $preorder->getVendor()->getId())))
        {
            $preorderManager->manage($preorder, PreOrderManagerService::ACTION_DECLINE);

            $preorderEvent = new PreOrderEvent($preorder);
            $this->get('event_dispatcher')->dispatch(PreOrderEvent::PRE_ORDER_CONTRACT_DECLINE, $preorderEvent);

            $threadService = $this->get('odiseo_messaging.service.thread_message');

            $product = $preorder->getProduct();
            $userBuyer = ($user->getId() == $preorder->getBuyer()->getId())?$user:$preorder->getBuyer();
            $userVendor = ($user->getId() == $preorder->getVendor()->getId())?$user:$preorder->getVendor();

            $thread = $threadService->searchThreadByCreatorAndTopic($userBuyer->getId(), $product->getId());
            $thread = $this->sendMessage($thread, $user, ($user->getId() == $userBuyer->getId())?$userVendor:$userBuyer, $product, 'Contract declined.');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

	public function getSelectedAction($form)
    {
		$action = "";
		if ($form->has('enviar') != null && $form->get('enviar')->isClicked())
			$action = "enviar";
		
		if ($form->has('guardar') != null && $form->get('guardar')->isClicked())
			$action = "guardar";
		
		if ($form->has('pagar') != null && $form->get('pagar')->isClicked())
			$action = "pagar";
		
		if ($form->has('editar') != null && $form->get('editar')->isClicked())
			$action = "editar";
		
		if ($form->has('aceptar') != null && $form->get('aceptar')->isClicked())
			$action = "aceptar";
		
		if ($form->has('verificar') != null && $form->get('verificar')->isClicked())
			$action = "verificar";
		
		if ($form->has('finalizar') != null && $form->get('finalizar')->isClicked())
			$action = "finalizar";

        if ($form->has('decline') != null && $form->get('decline')->isClicked())
            $action = "decline";

		return $action;
	}
}
