<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'WsServer');

class CorreosTestShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main()
	{
		$time = (strtotime(date('Y-m-d H:i:s')))-(3600*100); // hace dos hora atras
		$desde = date('Y-m-d H:i:s',$time);
		$hasta =date('Y-m-d H:i:s');
		$reporte = '=============================='.PHP_EOL.'===== '.$desde.' => '.date('H:i:s').' ===== '.PHP_EOL;

		if ($verificacionEmail = $this->verificacion_email($desde, $hasta))
		{
			$reporte.='- NOTIFICACIONES'.PHP_EOL.$verificacionEmail.PHP_EOL;
		}
		exit;
	}

private function verificacion_email($desde, $hasta)
	{
		if (! $desde)
		{
			return false;
		}
		if (! strtotime($desde))
		{
			return false;
		}
		App::import('Model','Compra');
		$CompraOBJ = new Compra();
		$respuesta = ' == inicio busqueda de compras por notificar =='.PHP_EOL;
		$options = array(
			'conditions' => array(
				'Compra.estado' => 1,
			//	'Compra.mail_compra' => 0,
				'Compra.id' => 354705
			),
			'fields' => array(
				'Compra.id',
				'Compra.subtotal',
				'Compra.iva',
				'Compra.neto',
				'Compra.descuento',
				'Compra.total',
				'Compra.valor_despacho',
				'Compra.created',
				'Usuario.id',
				'Usuario.nombre',
				'Usuario.apellido_paterno',
				'Usuario.apellido_materno',
				'Usuario.rut',
				'Usuario.email',
				'Pago.numeroTarjeta',
				'Direccion.id',
				'Direccion.calle',
				'Direccion.numero',
				'Direccion.depto',
				'Direccion.telefono',
				'Direccion.celular',
				'Comuna.nombre',
				//'Region.nombre',
			),
			'contain' => array(
				'Producto' => array(
					'fields' => array(
						'Producto.id',
						'Producto.nombre',
						'Producto.codigo',
						'Producto.codigo_completo',
						'Producto.foto',
						'Producto.color_id'
					),
					'Color' => array(
						'fields' => array(
							'Color.id',
							'Color.nombre',
							'Color.codigo'
						)
					)
				)
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
						'Pago.compra_id = Compra.id'
					)
				),
				array(
					'table' => 'sitio_despachos',
					'alias' => 'Despacho',
					'type' => 'INNER',
					'conditions' => array(
						'Despacho.id = Compra.despacho_id'
					)
				),
				array(
					'table' => 'sitio_direcciones',
					'alias' => 'Direccion',
					'type' => 'INNER',
					'conditions' => array(
						'Direccion.id = Despacho.direccion_id'
					)
				),
				array(
					'table' => 'sitio_comunas',
					'alias' => 'Comuna',
					'type' => 'LEFT',
					'conditions' => array(
						'Comuna.id = Direccion.comuna_id'
					)
				),
				//array(
				//	'table' => 'sitio_regiones',
				//	'alias' => 'Region',
				//	'type' => 'LEFT',
				//	'conditions' => array(
				//		'Region.id = Comuna.region_id'
				//	)
				//)
			)
		);

		

		if (! $compras = $CompraOBJ->find('all',$options))
		{
			prx('error');
			$respuesta.='- SIN COMPRAS'.PHP_EOL;
			return $respuesta;
		}




		foreach ($compras as $compra)
		{
			// color: #0080c0
			$mensaje = '
			<h2 style="color:#000;">Estimado(a) '.$compra['Usuario']['nombre'].'</h2>
			<h3 style="color:#000;font-weight:normal;">Muchas gracias por tu orden y bienvenida/a a la familia
Skechers. <br><br><b> Tu numero de orden es: '.$compra['Compra']['id'].'</b></h3>
			<hr>

	<table width="100%" style="border: 1px solid #0080c0;">
				<tr>
					<th colspan="6" style="color:#000;text-align:left;">DATOS DE COMPRA</th>
				</tr>';
			if ($compra['Producto'])
			{
				foreach ($compra['Producto'] as $producto)
				{
					$mensaje.='
					<tr>
						<td width="15%" style="color:#000;text-align:left;font-size:small;"><img src="http://www.skechers.cl/img/Producto/'.$producto['id'].'/mini_'.$producto['foto'].'" alt="" /></td>
						<td width="25%" style="color:#000;text-align:left;font-size:x-small;"><b>'.$producto['nombre'].'</b><br><i>'.$producto['codigo'].'</i></td>
						<td width="20%" style="color:#000;text-align:left;font-size:x-small;">'.$producto['Color']['nombre'].'<br><i>'.$producto['Color']['codigo'].'</i></td>
						<td width="12%" style="color:#000;text-align:left;font-size:x-small;">talla: '.$producto['ProductosCompra']['talla'].'</td>
						<td width="13%" style="color:#000;text-align:left;font-size:x-small;">cantidad: 1</td>
						<td width="15%" style="color:#000;text-align:right;font-size:small;">'.number_format($producto['ProductosCompra']['valor'],0,',','.').'</td>
					</tr>';
				}
			}
			$mensaje.='
				<tr>
					<td colspan="6"><hr></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4" style="color:#0080c0;text-align:left;font-size:x-small;">SUBTOTAL</td>
					<td style="color:#0080c0;text-align:right;font-size:small;">'.number_format($compra['Compra']['subtotal'],0,',','.').'</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4" style="color:#0080c0;text-align:left;font-size:x-small;">IVA (19%)</td>
					<td style="color:#0080c0;text-align:right;font-size:small;">'.number_format($compra['Compra']['iva'],0,',','.').'</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4" style="color:#0080c0;text-align:left;font-size:x-small;">DESCUENTO</td>
					<td style="color:#0080c0;text-align:right;font-size:small;">'.number_format($compra['Compra']['descuento'],0,',','.').'</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4" style="color:#0080c0;text-align:left;font-size:x-small;">DESPACHO A DOMICILIO</td>
					<td style="color:#0080c0;text-align:right;font-size:small;">'.number_format($compra['Compra']['valor_despacho'],0,',','.').'</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4" style="color:#0080c0;text-align:left;font-size:x-small;"><b>TOTAL</b></td>
					<td style="color:#0080c0;text-align:right;font-size:small;"><b>'.number_format($compra['Compra']['total'],0,',','.').'</b></td>
				</tr>
			</table>
<br><br>		
	Algunas informaciones importantes: <br>
-	Tu pago está siendo verificado. De no poder concretarse, la compra será anulada.<br>
-	Tu compra será entregada al servicio de despacho en un máximo de XXX días.<br>
-	La boleta será enviada junto con tu compra. Es importante que la conserves, ya que es el documento que permite hacer valer tus derechos de garantía legal.<br>
-	Si necesitas comunicarte con nosotros puedes contactarnos en el correo ventas@skechers.com y nos comunicaremos a la brevedad<br><br>


			<hr>
			<table width="100%">
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>REALIZADA EL:</b></td>
					<td style="color:#4d4d4d;font-size:small;">'.date('d-m-Y',strtotime($compra['Compra']['created'])).'</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>NOMBRE:</b></td>
					<td style="color:#4d4d4d;font-size:small;">'.$compra['Usuario']['nombre'].' '.$compra['Usuario']['apellido_paterno'].' '.$compra['Usuario']['apellido_materno'].'</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>RUT:</b></td>
					<td style="color:#4d4d4d;font-size:small;">'.$compra['Usuario']['rut'].'</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>NÚMERO DE TARJETA:</b></td>
					<td style="color:#4d4d4d;font-size:small;">XXXX- XXXX- XXXX - '.$compra['Pago']['numeroTarjeta'].'</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>DIRECCIÓN DE DESPACHO:</b></td>
					<td style="color:#4d4d4d;font-size:small;">'.$compra['Direccion']['calle'].(($compra['Direccion']['numero'])?' #'.$compra['Direccion']['numero']:'').(($compra['Direccion']['depto'])?', '.$compra['Direccion']['depto']:'').(($compra['Comuna']['nombre'])?' - '.$compra['Comuna']['nombre']:'').'</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>FONO CONTACTO:</b></td>
					<td style="color:#4d4d4d;font-size:small;">';
			if ($compra['Direccion']['telefono'])
			{
				$mensaje.=$compra['Direccion']['telefono'];
				if ($compra['Direccion']['celular'])
					$mensaje.=' | '.$compra['Direccion']['celular'];
			}
			elseif ($compra['Direccion']['celular'])
			{
				$mensaje.=$compra['Direccion']['celular'];
			}
			$mensaje.='</td>
				</tr>
			</table>
			<br>
		
			';
			if ($this->notificar_compra($compra['Compra']['id'], $compra['Usuario']['email'], $mensaje))
			{
					echo 'ok';
			
			}
			else
			{
				echo 'error';
			}
		}
		return $respuesta;
	}



	private function notificar_compra($compra_id = null, $destinatario = null, $mensaje = null)
	{
		if ($compra_id && $destinatario && $mensaje)
		{
			// CONFIGURACION DEL EMAIL
			App::import('Component', 'Email');
			$email =& new EmailComponent(null);
			$email->initialize($this->Controller);

			$email->smtpOptions = array(
		            'port' => '587',
					'timeout' => '30',
					'auth' => true,
					'host' => 'mail.smtp2go.com',
					'username' => 'noresponder@skechers-chile.cl',
					'password' => 'eXV6M2k1cWp4Yzcw'
			);
			$this->Controller->set('mensaje', $mensaje);
			$email->to = 'sebastian@expbox.cl';
			$email->subject = 'Venta sebastian';
			$email->from = 'Skechers <noreply@skechers-chile.cl>';
			$email->replyTo = 'noreply@skechers-chile.cl';
			$email->sendAs = 'html';
			$email->template	= 'mensaje';
			$email->delivery = 'smtp';
			if ($email->send())
			{
				print_r($email);
				die();
				return true;
			}
		}
		return false;
	}

	
}
function prx($data)
{
	pr($data); exit;
}
