<?php

namespace Odiseo\Bundle\PreorderBundle\Controller\Frontend;

use Odiseo\Bundle\MessagingBundle\Model\Thread;
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
			$composer = $this->get('fos_message.composer');
			$sender = $this->get('fos_message.sender');
			$threadService = $this->get('odiseo_messaging.service.thread_message');

			$user = $this->get('security.context')->getToken()->getUser();
			$product = $productService->findOneById($productId);

			/** @var Thread $thread */
			$thread = $threadService->searchThreadByCreatorAndTopic($user->getId(), $product->getId());

			if($thread)
			{
				$message = $composer->reply($thread)
					->setSender($user)
					->getMessage()
				;
			}else
			{
				$message = $composer->newThread()
					->setSubject('Preorder of product')
					->setSender($user)
					->addRecipient($product->getVendor())
					->getMessage()
				;
				$thread = $message->getThread();
				$thread->setTopic($product);
			}

			$message->setBody('I want buy your media to be used for: "'.$preorder->getUsedFor().'"');

			$sender->send($message);

            return $this->redirect($this->generateUrl('odiseo_messaging_list', array(
				'threadId' => $thread->getId()
			)));
        }

        return $this->redirect($this->generateUrl('odiseo_product_show', array('id' => $productId)));
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
		
		if($preOrder = $handler->process($form))
		{
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
		
		return $action;
	}
}
