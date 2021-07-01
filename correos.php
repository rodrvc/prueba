te?<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'WsServer');


class CorreosShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main()
	{
		$time = (strtotime(date('Y-m-d H:i:s')))-(3600*150); // hace dos hora atras
		$desde = date('Y-m-d H:i:s',$time);
		$hasta =date('Y-m-d H:i:s',time() - 300);
		$reporte = '=============================='.PHP_EOL.'===== '.$desde.' => '.date('H:i:s').' ===== '.PHP_EOL;

		if ($verificacionEmail = $this->verificacion_email($desde, $hasta))
		{
			$reporte.='- NOTIFICACIONES'.PHP_EOL.$verificacionEmail.PHP_EOL;
		}
		exit;
	}

private function verificacion_email($desde, $hasta)
	{
		Configure::write('debug',2);
		error_reporting(8);

		App::import('Component', 'Carro');
		$carro =& new CarroComponent(null);
		$carro->initialize($this->Controller); 

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
			print_r(compact('desde','hasta'));
			//exit;
		$respuesta = ' == inicio busqueda de compras por notificar =='.PHP_EOL;
		echo ( date('Y-m-d H:i:s'));
		$options = array(
			'conditions' => array(
				'Compra.estado' => 1,
				'Compra.mail_compra' => 0,
				'Compra.created >' => $desde,
				'Compra.created <' => $hasta,
				'Compra.local ' => 0

				//'Compra.verificado' => 1
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

			$respuesta.='- SIN COMPRAS'.PHP_EOL;
			die('sin compras');
			return $respuesta;
		}
		pr($compras);
		$i =0;
		foreach ($compras as $compra)
		{
			pr('Enviado : '.$i++);
			// color: #0080c0
			$mensaje = '
			<h2 style="color:#000;">Estimado(a) '.$compra['Usuario']['nombre'].'</h2>
			<h3 style="color:#000;font-weight:normal;">Muchas gracias por tu orden y bienvenid@ a la familia
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
						<td width="15%" style="color:#000;text-align:left;font-size:small;"><img src="https://s3.amazonaws.com/andain-sckechers/img/Producto/'.$producto['id'].'/mini_'.$producto['foto'].'" alt="" /></td>
						<td width="25%" style="color:#000;text-align:left;font-size:x-small;"><b>'.$producto['nombre'].'</b><br><i>'.$producto['codigo'].'</i></td>
						<td width="20%" style="color:#000;text-align:left;font-size:x-small;">'.$producto['Color']['nombre'].'<br><i>'.$producto['Color']['codigo'].'</i></td>
						<td width="12%" style="color:#000;text-align:left;font-size:x-small;">talla: '.$carro->talla($producto['ProductosCompra']['talla']).'</td>
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
	Información Importante: <br>
-	Tu compra ha sido recepcionada. En los próximos 4 días hábiles tu orden pasará por un proceso de revisión y armado en nuestro Centro de Distribución. Los pedidos realizados durante fines de semana y feriados comienzan a procesarse al siguiente día hábil.<br>
-	Una vez tu pedido sea entregado a la compañía de transporte, recibirás el número de seguimiento vía email y podras revisarlo en tu perfil de usuario. El plazo de entrega es hasta 12 días hábiles, aunque en periodos normales este plazo puede ser menor. <br>
-	La boleta será enviada junto con tu compra. Es importante que la conserves, ya que es el documento que permite hacer valer tus derechos de garantía legal.<br>
-	Puedes revisar siempre el estatus de tu pedido en www.skechers.cl en tu perfil de usuario.<br>
-	Si necesitas comunicarte con nosotros puedes contactarnos en el correo ventas@skechers.com o través de nuestro chat online en www.skechers.cl<br><br>


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

				$respuesta.=' + notificacion compra #'.$compra['Compra']['id'].' OK'.PHP_EOL;
				if ($this->generar_archivo_USA($compra['Compra']['id']))
				{
					$respuesta.=' + archivo USA compra #'.$compra['Compra']['id'].' OK'.PHP_EOL;
				}
				else
				{
					$respuesta.=' + archivo USA compra #'.$compra['Compra']['id'].' ERROR'.PHP_EOL;
				}
			}
			else
			{
				$respuesta.=' + notificacion compra #'.$compra['Compra']['id'].' ERROR'.PHP_EOL;
			}
		}
		return $respuesta;
	}

	private function notificar_error($mensaje = null)
	{
		if ($mensaje)
		{
			$destinatario = 'solanger@skechers.com';

			$copias = array(
			//	'ehenriquez@andain.cl',
		//		'eduardohenriquez@gmail.com',
				'sdelvillar@andain.cl',
		//		'pyanez@skechers.com',
				'rsilva@skechers.com',
	//			'cherrera@skechers.com'
			);

			App::import('Component', 'Email');
			$email =& new EmailComponent(null);
			$email->initialize($this->Controller);

			$email->smtpOptions = array(
			'port' => '587',
					'timeout' => '30',
					'auth' => true,
					'host' => 'mail.smtp2go.com',
					'username' => 'noreply@skechers-chile.cl',
					'password' => 'Andain5546'
			);
			$this->Controller->set('mensaje', $mensaje);
			// DATOS DESTINATARIO (CLIENTE)

			$email->to = $destinatario;
			$email->bcc	= $copias;

			$email->subject = '[Skechers - Tienda en linea] Notificacion Proceso Log';
			$email->from = 'Skechers <noreply@skechers-chile.cl>';
			$email->replyTo = 'noreply@skechers-chile.cl';
			$email->sendAs = 'html';
			$email->template	= 'mensaje';
			$email->delivery = 'smtp';
			if ($email->send())
			{
				return true;
			}
		}
		return false;
	}

	private function notificar_compra($compra_id = null, $destinatario = null, $mensaje = null)
	{
		if ($compra_id && $destinatario && $mensaje)
		{
			App::import('Model','Compra');
			$CompraOBJ = new Compra();
			App::import('Component', 'Carro');
			$carro =& new CarroComponent(null);
			$carro->initialize($this->Controller); 

			$options = array(
				'conditions' => array(
					'Compra.id' => $compra_id
				),
				'fields' => array(
					'Compra.id'
				),
				'recursive' => -1
			);
			if (! $compra = $CompraOBJ->find('first',$options))
			{
				return false;
			}

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
			// DATOS DESTINATARIO (CLIENTE)
			$copias = array(
				'ecom@skechers.com'
			);
			$email->to = $destinatario;
			$email->bcc	= $copias;

			$email->subject = '[Skechers - Tienda en linea] Compra #' . $compra_id;
			$email->from = 'Skechers <noreply@skechers-chile.cl>';
			$email->replyTo = 'noreply@skechers-chile.cl';
			$email->sendAs = 'html';
			$email->template	= 'mensaje';
			$email->delivery = 'smtp';
			if ($email->send())
			{

				$save = array(
					'id' => $compra_id,
					'mail_compra' => 1
				);
				$CompraOBJ->save($save);
				return true;
			}else{
				print_r($email);
				die();
			}
		}
		return false;
	}

	private function generar_archivo_USA($compra_id = null)
	{
		return true;
		if (! $compra_id)
		{
			return false;
		}
		App::import('Model','Compra');
		$CompraOBJ = new Compra();
			App::import('Component', 'Carro');
		$carro =& new CarroComponent(null);
		$carro->initialize($this->Controller); 

		$ayer = strtotime(date('d-m-Y', strtotime(date('d-m-Y H:i:s')))) - (60*60*24*1);

		$productos = array();
		$options = array(
			'conditions' => array(
				'Compra.id ' => $compra_id
			),
			'fields' => array(
				'Compra.id',
				'Compra.subtotal',
				'Compra.descuento',
				'Compra.total',
				'Compra.estado',
				'Compra.despacho_id',
				'Despacho.id',
				'Despacho.entrega',
				'Direccion.id',
				'Direccion.calle',
				'Direccion.numero',
				'Direccion.depto',
				'Direccion.telefono',
				'Direccion.celular',
				'Direccion.otras_indicaciones',
				'Direccion.retiro_id',
				'Comuna.id',
				'Comuna.nombre',
				'Comuna.codigo',
				'Region.id',
				'Region.nombre',
				'Region.codigo',
				'UsuarioDespacho.id',
				'UsuarioDespacho.nombre',
				'UsuarioDespacho.apellido_paterno',
				'UsuarioDespacho.apellido_materno',
				'UsuarioDespacho.email',
			),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'sitio_despachos',
					'alias' => 'Despacho',
					'type' => 'LEFT',
					'conditions' => array(
						'Despacho.id = Compra.despacho_id'
					)
				),
				array(
					'table' => 'sitio_direcciones',
					'alias' => 'Direccion',
					'type' => 'LEFT',
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
				array(
					'table' => 'sitio_regiones',
					'alias' => 'Region',
					'type' => 'LEFT',
					'conditions' => array(
						'Region.id = Comuna.region_id'
					)
				),
				array(
					'table' => 'sitio_usuarios',
					'alias' => 'UsuarioDespacho',
					'type' => 'LEFT',
					'conditions' => array(
						'UsuarioDespacho.id = Direccion.usuario_id'
					)
				),
			)
		);

		if (! $compra = $CompraOBJ->find('first',$options))
		{
			return false;
		}


		$options = array(
			'conditions' => array(
				'ProductosCompra.compra_id' => $compra['Compra']['id']
			),
			'fields' => array(
				'ProductosCompra.id',
				'ProductosCompra.descuento_id',
				'ProductosCompra.talla',
				'ProductosCompra.valor',
				'ProductosCompra.sku',
				'Producto.id',
				'Producto.nombre',
				'Producto.precio',
				'Producto.codigo',
				'Producto.codigo_completo',
				'Producto.division',
				'Color.id',
				'Color.nombre',
				'Color.codigo',
				'Categoria.id',
				'Categoria.nombre',
				'Descuento.id',
				'Descuento.nombre',
				'Descuento.tipo',
				'Descuento.descuento',
			),
			'joins' => array(
				array(
					'table' => 'sitio_productos',
					'alias' => 'Producto',
					'type' => 'INNER',
					'conditions' => array(
						'Producto.id = ProductosCompra.producto_id'
					)
				),
				array(
					'table' => 'sitio_descuentos',
					'alias' => 'Descuento',
					'type' => 'LEFT',
					'conditions' => array(
						'Descuento.id = ProductosCompra.descuento_id'
					)
				),
				array(
					'table' => 'sitio_colores',
					'alias' => 'Color',
					'type' => 'LEFT',
					'conditions' => array(
						'Color.id = Producto.color_id'
					)
				),
				array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'LEFT',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id'
					)
				),
			)
		);
		$productos = $CompraOBJ->ProductosCompra->find('all',$options);
		$datos_compras = $datos_despachos = array();
		if($compra && $productos)
		{
			foreach ($productos as $producto)
			{
				$precio_venta = $producto['ProductosCompra']['valor'];
				if ($producto['Descuento']['id'])
				{
					$descontar = 0;
					if ($producto['Descuento']['tipo'] == 'POR')
					{

						if ($producto['Descuento']['descuento'])
						{
							$descontar = ($producto['ProductosCompra']['valor'] * $producto['Descuento']['descuento']) / 100;
							if ( ($descontar % 10) > 0 )// redondea descuento
								$descontar = (((int)($descontar/10))*10)+10;
							else
								$descontar = ((int)($descontar/10))*10;
							if($producto['Descuento']['descuento'] ==100)
								$descontar = $producto['ProductosCompra']['valor'] -1;
						}
					}
					elseif ($producto['Descuento']['descuento'])
					{
						$descontar = $producto['Descuento']['descuento'];
					}
					$precio_venta = $precio_venta-$descontar;
					if ($precio_venta <= 0)
						$precio_venta = 0;
				}

				$data = array(
					'estilo' 		=> $producto['Producto']['codigo'],
					'precio_venta'	=> $precio_venta,
					'talla'			=> $carro->talla($producto['ProductosCompra']['talla']),
					'cantidad'		=> 1,
					'color'			=> $producto['Color']['codigo'],
					'fecha'			=> date('m/d/Y',$ayer),
					'fecha_canc'	=>'',
					'precio_retail'	=> $producto['ProductosCompra']['valor'],
					'PO'			=> $compra['Compra']['id'],
					'ACC'			=> '10998',
					'TERM_CODE'		=> '81',
					'Shipper_code'	=> 'SR',
					'Sales_Rep1'	=> '100',
					'celular'		=> $compra['Direccion']['celular'],
					'Sales_Rep2'	=> '',
					'precio_promo'	=> '',
					'event_code'	=> '',
					'tienda'		=> '00017',
					'dept_code'		=> '',
					'DIV_CODE'		=> $producto['Producto']['division'],
					'PROMO_CODE'	=>'',
					'Special_Inst'	=>'',
					'SKK'			=> $producto['ProductosCompra']['sku'],
				);
				array_push($datos_compras,$data);

				$direccion_texto = '';
				$depto_texto ='';
				if (isset($compra['Direccion']['calle']) && $compra['Direccion']['calle'])
				{
					$direccion_texto = $compra['Direccion']['calle'];
					$depto_texto = $compra['Direccion']['numero'];
					if(isset($compra['Direccion']['depto']) && trim($compra['Direccion']['depto']) !='')
						$depto_texto .= ' D '.$compra['Direccion']['depto'];
					if(isset($compra['Direccion']['retiro_id']) && $compra['Direccion']['retiro_id'] )
						$direccion_texto = 'CXP '.$direccion_texto;	
				}

				$codigo_comuna ='ST';
				if(isset($compra['Comuna']['codigo']) && trim($compra['Comuna']['codigo']) !='')
					$codigo_comuna = $compra['Comuna']['codigo'];
				$nombre = str_replace('_',' ',Inflector::slug($compra['UsuarioDespacho']['nombre'].' '.$compra['UsuarioDespacho']['apellido_paterno'].' '.$compra['UsuarioDespacho']['apellido_materno']));
				if(isset($compra['Despacho']['entrega']) && $compra['Despacho']['entrega'] !='')
					$nombre = $compra['Despacho']['entrega'];

				$data = array(
					'PO'			=> $compra['Compra']['id'],
					'direccion'		=> str_replace('_',' ',Inflector::slug($direccion_texto)),
					'depto'		=> str_replace('_',' ',Inflector::slug($depto_texto)),
					'pais'			=> 'CHL',

					'comuna'		=> str_replace('_',' ',Inflector::slug($compra['Comuna']['nombre'])),
					'nombre'		=> $nombre,
					'comuna2'		=> $codigo_comuna,
					'email'			=> $compra['UsuarioDespacho']['email'],
					'city'			=> $compra['Region']['codigo']
				);
				array_push($datos_despachos,$data);
			}

				$basedir = DS.'var'.DS.'www'.DS.'archivos'.DS.'prod'.DS;
				$backupdir = DS.'var'.DS.'www'.DS.'archivos'.DS.'backup'.DS;
				//$basedir = DS.'var'.DS.'www'.DS.'archivos'.DS.'prod'.DS;
				//$backupdir = DS.'var'.DS.'www'.DS.'archivos'.DS.'backup'.DS;



			// else
				// $basedir = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'desarrollo'.DS.'webroot'.DS.'archivos'.DS.'DEV'.DS;

			if (! is_dir($basedir))
				@mkdir($basedir, 0755, true);
			if (! is_dir($backupdir))
				@mkdir($backupdir, 0755, true);
			//      GUARDAMOS EL ARCHIVO DE LOS PRODUCTOS
			$nombre = '10998_'.$compra['Compra']['id'].'.csv';
			$fp = fopen($basedir.$nombre, 'w+');
			$fp2 = fopen($backupdir.$nombre, 'w+');
			$contador =1;
			$linea = '';
			foreach($datos_compras as $dato_compra)
			{
				$linea = $dato_compra['PO'].'|'.$dato_compra['ACC'].'|'.$dato_compra['tienda'].'|'.$dato_compra['PROMO_CODE'].'|'.$dato_compra['TERM_CODE'].'|'.$dato_compra['Shipper_code'].'|'.$dato_compra['Sales_Rep1'].'|'.$dato_compra['fecha'].'|'.$dato_compra['Special_Inst'].'|'.$dato_compra['SKK'].'|'.$dato_compra['DIV_CODE'].'|'.$dato_compra['estilo'].'|'.$dato_compra['color'].'|'.$dato_compra['precio_venta'].'|'.$dato_compra['talla'].'|'.$dato_compra['cantidad'].'|'.$dato_compra['fecha'].'||||||'.$dato_compra['celular'].'||'.$dato_compra['precio_retail'].'|||';
				if(count($datos_compras) > $contador++)
				{
					$linea.='
';
				}
				fwrite($fp,$linea);
				fwrite($fp2,$linea);

			}
			fclose($fp);
			fclose($fp2);

			//      GUARDAMOS EL ARCHIVO DE LOS ADDRESS
			$nombre = '10998_'.$compra['Compra']['id'].'_address.csv';
			$fp = fopen($basedir.$nombre, 'w+');
			$fp2 = fopen($backupdir.$nombre, 'w+');
			$cabecera ='PO	|NOMBRE CLIENTE|	ADDRESS1|	ADDRESS2|	ADDRESS3|	STATE| CITY	|	COUNTRY|
			';
			$linea ='';
			foreach($datos_despachos as $datos_despacho)
			{
				$linea = $datos_despacho['PO'].'|'.trim(utf8_decode((($datos_despacho['nombre'])))).'|'.utf8_decode($datos_despacho['direccion']).'|'.utf8_decode($datos_despacho['depto']).'| |'.utf8_decode($datos_despacho['comuna']).'|ST| |'.utf8_decode($datos_despacho['pais']).'';
			}
			fwrite($fp,$linea);
			fclose($fp);
			fwrite($fp2,$linea);
			fclose($fp2);
			return true;
			
		}
		return false;
	}
}
function prx($data)
{
	pr($data); exit;
}
