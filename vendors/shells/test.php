<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'WsServer');

class TestShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() {
		App::import('Model','Compra');
		$CompraOBJ = new Compra();
		$time = (strtotime(date('Y-m-d H:i:s')))-(3600*(24*15));
		$desde = date('Y-m-d H:i:s',$time);
		$reporte = ':D';

		Usuario
		Pago
		Despacho
		Direccion
		Productos
		$options = array(
			'conditions' => array(
				'Compra.estado' => 1,
				'Compra.mail_compra' => 0,
				'Compra.created >=' => $desde
			),
			'fields' => array(
				'Compra.id'
			),
			'contain' => array(
				'Producto'
			),
			'joins' => array(
				array(
					'table' => 'sitio_usuarios',
					'alias' => 'Usuario',
					'type' => 'INNER',
					'conditions' => array(
						'Usuario.id = Compra.usuario_id'
					)
				),
				array(
					'table' => 'sitio_pagos',
					'alias' => 'Pago',
					'type' => 'LEFT',
					'conditions' => array(
						
					)
				),
				array(
					'table' => '',
					'alias' => '',
					'type' => '',
					'conditions' => array(
						
					)
				),
				array(
					'table' => '',
					'alias' => '',
					'type' => '',
					'conditions' => array(
						
					)
				),
				array(
					'table' => '',
					'alias' => '',
					'type' => '',
					'conditions' => array(
						
					)
				),
				array(
					'table' => '',
					'alias' => '',
					'type' => '',
					'conditions' => array(
						
					)
				)
			)
		);
		if (! $compras = $CompraOBJ->find('all',$options))
			exit;

		foreach ($compras as $compra)
		{
			pr($compra);
		}
		exit;
		
	}
}
function prx($data)
{
	pr($data); exit;
}