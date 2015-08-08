<?php

namespace Odiseo\Bundle\PreorderBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Odiseo\Bundle\PreorderBundle\Form\Type\PreOrderFormType;
use Symfony\Component\HttpFoundation\JsonResponse;

class PreOrderController extends Controller
{    	
	public function showPreorderButtonAction($productId, $buyerId)
	{
		$productService = $this->get('odiseo_product.service.product');
		$preOrderService = $this->get('preorder.service');
		$preOrder = $preOrderService->findPreorderByBuyerAndProduct($buyerId, $productId);
		$product = $productService->findOneById($productId);
		
		return $this->getForm($product, $buyerId, $preOrder);
	}
	
	public function getForm($product , $buyerId , $preOrder)
	{
		$userId = $this->get('security.context')->getToken()->getUser()->getId();
		$factory = null;
		if($userId == $product->getVendor()->getId())
		{
			$factory = $this->container->get('odiseo_preorder.show_vendor_buttons_form.factory');
			$form = $factory->create();
			return $this->render('OdiseoPreorderBundle:Frontend/Preorder/Partial:vendor_buttons.html.twig', array( 'form' => $form->createView(), 'id' => $product->getId(), 'buyerId' => $buyerId));
		}
		else
		{
			$factory = $this->container->get('odiseo_preorder.show_buyer_buttons_form.factory');
			$form = $factory->create();
			return $this->render('OdiseoPreorderBundle:Frontend/Preorder/Partial:buyer_buttons.html.twig', array( 'form' => $form->createView(), 'id' => $product->getId(), 'product' => $product));
		}
	}

    /** Show and send request */
    public function showSendRequestAction($productId)
    {
        $factory = $this->container->get('odiseo_preorder.request_media.form.factory');
        $form = $factory->create();

        return $this->render('OdiseoPreorderBundle:Frontend/Preorder/Partial:show_send_request.html.twig', array(
            'productId' => $productId,
            'form' => $form->createView()
        ));
    }

    public function sendRequestAction(Request $request)
    {
        $id = $request->get('id');
        $factory = $this->container->get('odiseo_preorder.request_media.form.factory');
        $formHandler = $this->container->get('odiseo_preorder.request_media.form.handler');
        $form = $factory->create();

        if ($formHandler->process($form, $request, $id))
        {
            return $this->redirect($this->generateUrl('odiseo_message_list', array('productId' => $id)));
        }

        return $this->redirect($this->generateUrl('odiseo_product_show', array('id' => $id)));
    }

    /** Show and send contract */
	public function showSendContractAction(Request $request)
	{
		$productId = $request->get('id');
		$buyerId = $request->get('buyerId');
		$factory = $this->container->get('odiseo_preorder.media_contract_form.factory');
		$form = $factory->create();
		
		$preOrder = $this->get('preorder.service')->findPreorderByBuyerAndProduct($buyerId , $productId );
		
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
		$id = $request->get('productId');
		$creatorId = $request->get('buyerId');
		$factory = $this->container->get('odiseo_preorder.media_contract_form.factory');
		$form = $factory->create();		
		$handler = $this->get('odiseo_preorder.media_contract.handler');
		
		if($handler->process($form))
		{
			return $this->redirect($this->generateUrl('odiseo_message_list', array(
                'productId' => $id,
                'creatorId' =>  $creatorId
            )));
		}else
        {}
	}

    /** Details of preorder */
	public function detailAction(Request $request)
	{
		$id = $request->get('id');
		$preOrderService = $this->get('preorder.service');
		$preOrder = $preOrderService->findOneById($id);
		$user = $this->get('security.context')->getToken()->getUser();
	
		$form = $this->createForm(new PreOrderFormType($preOrder , $user), $preOrder , array('action' => $this->generateUrl('odiseo_preorder_detail',  array('id' => $id))));
		if($request->isMethod('POST'))
		{
			$form->handleRequest($request);
			if ($form->isValid())
			{
				$managerService = $this->get('preorder.manager.service');
				$newDescription = $form->getData()->getDescription();
				$preOrder->setDescription($newDescription);
				$managerService->manage($preOrder , $this->getSelectedAction($form));
			}
			else {}
		}

		$form = $this->createForm(new PreOrderFormType(), $preOrder, array(
            'action' => $this->generateUrl('odiseo_preorder_detail',  array(
                'id' => $id
            ))
        ));

		return $this->render('OdiseoPreorderBundle:Frontend/Preorder:detail.html.twig', array(
            'preorder' => $preOrder,
            'product' => $preOrder->getProduct(),
            'form' => $form->createView()
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
