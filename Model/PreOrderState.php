<?php

namespace Odiseo\Bundle\PreorderBundle\Model;

/**
 * PreOrderState
 */
class PreOrderState extends BaseState implements PreOrderStateInterface
{
	const NUEVA = "nueva";
	const ACEPTADA_VENDOR = "aceptada_vendor";
	const ACEPTADA_BUYER= "aceptada_buyer";
	const PAGADA = "pagada";
	const VERIFICADA = "verificada";
	const FINALIZADA = "finalizada";
	const RECHAZADA_VENDOR = "rechazada_vendor";
	const RECHAZADA_BUYER = "rechazada_buyer";
	
	const STATE_NUEVA = "nueva";
	const STATE_ACEPTADA_VENDOR = "aceptada_vendor";
	const STATE_ACEPTADA_BUYER = "aceptada_buyer";
	const STATE_PAGADA = "pagada";
	const STATE_VERIFICADA = "verificada";
	const STATE_FINALIZADA = "finalizada";
	const STATE_RECHAZADA_VENDOR = "rechazada_vendor";
	const STATE_RECHAZADA_BUYER = "rechazada_buyer";
}

