<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'WsServer');

class ComprasShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() 
	{
		$time = (strtotime(date('Y-m-d H:i:s')))-(3600*2); // hace dos hora atras
		$desde = date('Y-m-d H:i:s',$time);

		$reporte = '=============================='.PHP_EOL.'===== '.$desde.' => '.date('H:i:s').' ===== '.PHP_EOL;

		if ($tbk_log = $this->procesar_logs())
		{
			$reporte.='- TBK VALIDACION'.PHP_EOL.$tbk_log.PHP_EOL;
		}
		else
		{
			$reporte.='- TBK ERROR'.PHP_EOL;
		}
		prx($reporte);
/*
		if ($verificacionEmail = $this->verificacion_email($desde))
		{
			$reporte.='- NOTIFICACIONES'.PHP_EOL.$verificacionEmail.PHP_EOL;
		}
		else
		{
			$reporte.='- NOTIFICACIONES ERROR'.PHP_EOL;
		}

		$this->generarLog($reporte);
		*/
		exit;
	}

	private function generarLog($texto = null)
	{
		if (! $texto)
			return false;
		$folder = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'store'.DS.'webroot'.DS.'archivos'.DS.'CORREOS_COMPRA'.DS;
		if (! is_dir($folder))
		{
			@mkdir($folder, 0777, true);
		}
		$filename_log = $folder."log_".date('Y-m-d').".txt";
		$fp = fopen($filename_log,"a");
		fwrite($fp,$texto.PHP_EOL);
		fclose($fp);
		return true;
	}

	private function procesar_logs()
	{

		/**
		*	LEE ARCHIVO LOG TBK
		*	busca errores en transacciones
		*	cambia estado de la compra con problemas
		*	notifica por email de la compra con problemas
		*/
		$limite = 1; // archivos hacia atras que verificara
		$dia = date('Y-m-d');
		// $dia = '2015-12-17';// forzando valor

		$dia = strtotime($dia);
		$dias = array(
			date('md',$dia)
		);
		// generar listado de archivos
		if ($limite)
		{
			for ($x = 0; $x < $limite; $x++)
			{
				// 86400 = cantidad de segundos por dia
				$dia-=86400;
				array_push($dias, date('md',$dia));
			}
			$dias = array_reverse($dias);
		}
		if (! $dias)
		{
			return false;
		}
		$respuesta = ' == inicio lectura de archivos tbk =='.PHP_EOL;
		$registros = array();
		foreach ($dias as $dia) // recorrer listado de archivos log que se leeran
		{
			$archivo_remoto = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'webpay'.DS.'cgi-bin'.DS.'log'.DS.'tbk_bitacora_TR_NORMAL_'.$dia.'.log';
			$archivo = DS.'home'.DS.'ubuntu'.DS.'logs'.DS.'tbk_bitacora_TR_NORMAL_'.$dia.'.log';
			$connection = ssh2_connect('www.andain.cl', 22);
			ssh2_auth_password($connection, 'root', 'Andain2');
			ssh2_scp_recv($connection, $archivo_remoto, $archivo);
			sleep(2);
			
			if (! file_exists($archivo)) // verifica si existe archivo
			{
				die('no esta');
				$respuesta.= ' - archivo: '.$archivo.' NO EXISTE'.PHP_EOL;
				continue;
			}

			$respuesta.= ' - archivo: '.$archivo.' OK'.PHP_EOL;
			// leer archivo y genera listado de registros
			$handle = fopen($archivo, 'r');
			while($datos = fgetcsv($handle, 0, ';'))
			{
				$registro = array();
				foreach ($datos as $dato)
				{
					if ($dato == reset($datos))
					{
						$registro = array_merge($registro,array('ESTADO' => trim($dato)));
						continue;
					}
					if (! $dato = explode('=',trim($dato)))
					{
						continue;
					}
					$registro = array_merge($registro,array($dato[0] => trim($dato[1])));
				}
				if (count($registro) == count($datos))
				{
					array_push($registros,$registro);
				}
			}
		}
		if (! $registros)
		{
			return false;
		}

		App::import('Model','Compra');
		$CompraOBJ = new Compra();

		$respuesta = ' == inicio verificacion TBK =='.PHP_EOL;

		foreach ($registros as $registro)
		{
			// validacion de campos requeridos
			if (! isset($registro['ESTADO']))
			{
				continue;
			}
			if (! isset($registro['TBK_ORDEN_COMPRA']))
			{
				continue;
			}
			if (! $registro['TBK_ORDEN_COMPRA'])
			{
				continue;
			}
			if (! isset($registro['TBK_RESPUESTA']))
			{
				continue;
			}

			$mensaje = false;
			// verificar compra
			$options = array(
				'conditions' => array(
					'Compra.id' => $registro['TBK_ORDEN_COMPRA']
				),
				'fields' => array(
					'Compra.id',
					'Compra.mail_compra',
					'Compra.estado',
					'Compra.verificado'
				)
			);
			if ($compra = $CompraOBJ->find('first', $options))
			{
				if ($compra['Compra']['verificado'])
				{
					continue;
				}
				$update = array(
					'id' => $compra['Compra']['id'],
					'verificado' => 1
				);
				// verificacion sobre compras realizadas y finalizadas exitosamente
				if ($registro['ESTADO'] == 'ACK' && $registro['TBK_RESPUESTA'] == '0')
				{
					if ($compra['Compra']['estado'] == 1)// COMPRA OK
					{
						$respuesta.= '  - COMPRA '.$registro['TBK_ORDEN_COMPRA'].' VALIDACION EXITOSA'.PHP_EOL;
					}
					else
					{
						$respuesta.= '  - COMPRA '.$registro['TBK_ORDEN_COMPRA'].' ERROR EN EL ESTADO DE LA COMPRA ['.$compra['Compra']['estado'].']'.PHP_EOL;
						$mensaje = '<h3>Error en compra Numero '.$registro['TBK_ORDEN_COMPRA'].'</h3><p>La transaccion asociada a la compra fue aprobada, pero en el sistema posee un estado distinto a PAGADO ['.$compra['Compra']['estado'].'].</p><p>Por favor revise a la brevedad !</p>';
					}
				}
				else
				{
					if ($compra['Compra']['estado'] == 1)
					{
						$update['estado'] = 5;
						$mensaje = '<h3>Error en transaccion Numero '.$registro['TBK_RESPUESTA'].'</h3><p>Se detecto un error en el log tbk asociado a una compra en el sistema.</p>';
						// identificar posible error en transaccion
						if ($registro['ESTADO'] == 'ACK')
						{
							if ($registro['TBK_RESPUESTA'] != '0')// rechazada
							{
								$respuesta.= '  - COMPRA '.$registro['TBK_ORDEN_COMPRA'].' RECHAZADA'.PHP_EOL;
								$mensaje.= '<p>La transacción se encuentra rechazada y el sitio Web ha enviado al servidor Webpay el mensaje de acuse de recibo con el texto “ACEPTADO”.</p>';
								$mensaje.= '<p>El monto de la transacción no se encuentra cargado en la cuenta del cliente debido a que la transacción fue rechazada por el Banco Emisor o la marca de la tarjeta.</p>';
							}
							else
							{
								$respuesta.= '  - COMPRA '.$registro['TBK_ORDEN_COMPRA'].' ERROR DESCONOCIDO'.PHP_EOL;
								$mensaje.= '<p>El error no a sido identificado y se desconoce el problema por el cual se genero.</p>';
							}
						}
						else
						{
							if ($registro['TBK_RESPUESTA'] == '0')
							{
								$respuesta.= '  - COMPRA '.$registro['TBK_ORDEN_COMPRA'].' REVERSADA'.PHP_EOL;
								$mensaje.= '<p>La transacción se encuentra autorizada, pero ha sido reversada automáticamente debido a que el sitio Web no ha enviado el mensaje de acuse de recibo con el texto “ACEPTADO”.</p>';
								$mensaje.= '<p>El monto de la transacción se encuentra cargado y reversado en la cuenta del cliente, pero la reversa solo liberará el cupo o fondos de la cuenta dentro de un plazo máximo de 72 horas, dependiendo de la política de actualización de reversas del Banco Emisor.</p>';
							}
							else
							{
								$respuesta.= '  - COMPRA '.$registro['TBK_ORDEN_COMPRA'].' REVERSADA'.PHP_EOL;
								$mensaje.= '<p>La transacción se encuentra rechazada, pero el sitio Web del comercio no ha enviado al servidor Webpay el mensaje de acuse de recibo con el texto “ACEPTADO”.</p>';
								$mensaje.= '<p>El monto de la transacción no se encuentra cargado en la cuenta del cliente debido a que la transacción fue rechazada por el Banco Emisor o la marca de la tarjeta.</p>';
							}
						}

						$mensaje.='<p>Como prevensión el estado de la compra a sido actualizado a PENDIENTE.</p>';
						if ($compra['Compra']['mail_compra'])
						{
							$mensaje.='<p>EL CLIENTE HA RECIBIDO UN CORREO NOTIFICANDO LA FINALIZACION EXITOSA DE LA TRANSACCION !!!</p>';
						}
						else
						{
							$mensaje.='<p>El cliente no ha sido notificado aun, se recomienda gestionar este caso a la brevedad ya que el correo de notificacion debe ser enviado dentro de un plazo de 24 horas.</p>';
						}
					}
				}

				$CompraOBJ->save($update);
			}
			else
			{
				$respuesta.= '  - COMPRA '.$registro['TBK_ORDEN_COMPRA'].' INVALIDA'.PHP_EOL;
				// no se encontro compra
				$this->notificar_error('<h3>Error en compra Numero '.$registro['TBK_ORDEN_COMPRA'].'</h3><p>Se detecto un error en el log tbk, pero la compra no a sido encontrada en el sistema.</p><p>Por favor revise a la brevedad !</p>');
			}

			if ($mensaje)
			{
				if ($this->notificar_error($mensaje))
				{
					$respuesta.= '    - notificacion interna OK'.PHP_EOL;
				}
				else
				{
					$respuesta.= '    - notificacion interna ERROR'.PHP_EOL;
				}
			}
		}
		return $respuesta;
	}

	private function verificacion_email($desde)
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
				'Compra.mail_compra' => 0,
				'Compra.created >=' => $desde,
				'Compra.verificado' => 1
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
			),
		);

		if (! $compras = $CompraOBJ->find('all',$options))
		{
			$respuesta.='- SIN COMPRAS'.PHP_EOL;
			return $respuesta;
		}

		foreach ($compras as $compra)
		{
			// color: #0080c0
			$mensaje = '
			<h2 style="color:#000;">Estimado(a) '.$compra['Usuario']['nombre'].'</h2>
			<h3 style="color:#000;font-weight:normal;">Tu número de orden es: <b>'.$compra['Compra']['id'].'</b></h3>
			<hr>
			<p style="color:#4d4d4d;">
				Estamos verificando tu información de pago. En caso de existir cualquier problema o dificultad relativa al medio de pago utilizado (u otra circunstancia grave que impida procesar la orden de compra), la orden deberá ser anulada.
			</p>
			<p style="color:#4d4d4d;">
				Tu orden será despachada en un máximo de 15 días hábiles.
			</p>
			<p style="color:#4d4d4d;">
				Tu Boleta se emitirá de forma electrónica y será enviada al mail que has registrado.  Conserva tu boleta para efectos de hacer valer tus derechos de garantía legal.  
			</p>
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
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>EMAIL:</b></td>
					<td style="color:#4d4d4d;font-size:small;">'.$compra['Usuario']['email'].'</td>
				</tr>
			</table>
			<br>
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
				'ehenriquez@andain.cl',
				'eduardohenriquez@gmail.com',
				'sdelvillar@andain.cl',
				'rsilva@skechers.com'
			);

			App::import('Component', 'Email');
			$email =& new EmailComponent(null);
			$email->initialize($this->Controller);    
	
			$email->smtpOptions = array(
				'port' => '25',
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
		return true;
		if ($compra_id && $destinatario && $mensaje)
		{
			App::import('Model','Compra');
			$CompraOBJ = new Compra();

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
				'port' => '25',
				'timeout' => '30',
				'auth' => true,
				'host' => 'skechers-chile.cl',
				'username' => 'noreply@skechers-chile.cl',
				'password' => 'andainandain'
			);
			$this->Controller->set('mensaje', $mensaje);
			// DATOS DESTINATARIO (CLIENTE)
			$copias = array(
				//'ventas@skechers-chile.cl',
				'solanger@skechers.com',
				//'pyanez@skechers.com',
				//'store383@skechers.com',
				//'cherrera@skechers.cl',
				'ehenriquez@andain.cl',
				// 'sdelvillar@andain.cl',
				'rsilv@skechers.com',
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
			}
		}
		return false;
	}

	private function generar_archivo_USA($compra_id = null)
	{
		if (! $compra_id)
		{
			return false;
		}

		App::import('Model','Compra');
		$CompraOBJ = new Compra();

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
				'Despacho.retiro',
				'Direccion.id',
				'Direccion.calle',
				'Direccion.numero',
				'Direccion.depto',
				'Direccion.telefono',
				'Direccion.celular',
				'Direccion.otras_indicaciones',
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
					'talla'			=> $producto['ProductosCompra']['talla'],
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
				if (isset($compra['Direccion']['calle']) && $compra['Direccion']['calle'])
				{
					$direccion_texto = $compra['Direccion']['calle'].' '.$compra['Direccion']['numero'];
					if(isset($direccion['Direccion']['depto']) && trim($direccion['Direccion']['depto']) !='')
						$direccion_texto .= ' DP '.$direccion['Direccion']['depto'];
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

			}

			// if (true)
				$basedir = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'webroot'.DS.'archivos'.DS.'PROD'.DS;
			// else
				// $basedir = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'desarrollo'.DS.'webroot'.DS.'archivos'.DS.'DEV'.DS;

			if (! is_dir($basedir))
				@mkdir($basedir, 0755, true);

			//      GUARDAMOS EL ARCHIVO DE LOS PRODUCTOS
			$nombre = '10998_'.$compra['Compra']['id'].'.csv';
			$fp = fopen($basedir.$nombre, 'w+');
			$contador =1;
			$linea = '';
			foreach($datos_compras as $dato_compra)
			{
				$linea = $dato_compra['PO'].'|'.$dato_compra['ACC'].'|'.$dato_compra['tienda'].'|'.$dato_compra['PROMO_CODE'].'|'.$dato_compra['TERM_CODE'].'|'.$dato_compra['Shipper_code'].'|'.$dato_compra['Sales_Rep1'].'|'.$dato_compra['fecha'].'|'.$dato_compra['Special_Inst'].'|'.$dato_compra['SKK'].'|'.$dato_compra['DIV_CODE'].'|'.$dato_compra['estilo'].'|'.$dato_compra['color'].'|'.$dato_compra['precio_venta'].'|'.$dato_compra['talla'].'|'.$dato_compra['cantidad'].'|'.$dato_compra['fecha'].'||||||||'.$dato_compra['precio_retail'].'|||';
				if(count($datos_compras) > $contador++)
				{
					$linea.='
';
				}
				fwrite($fp,$linea);
			}
			fclose($fp);

			//      GUARDAMOS EL ARCHIVO DE LOS ADDRESS
			$nombre = '10998_'.$compra['Compra']['id'].'_address.csv';
			$fp = fopen($basedir.$nombre, 'w+');
			$cabecera ='PO	|NOMBRE CLIENTE|	ADDRESS1|	ADDRESS2|	ADDRESS3|	STATE| CITY	|	COUNTRY|
			';
			$linea ='';
			foreach($datos_despachos as $datos_despacho)
			{
				$linea = $datos_despacho['PO'].'|'.trim(utf8_decode((($datos_despacho['nombre'])))).'|'.utf8_decode($datos_despacho['direccion']).'|'.utf8_decode($datos_despacho['email']).'| |'.utf8_decode($datos_despacho['comuna']).'|'.utf8_decode($datos_despacho['comuna2']).'| |'.utf8_decode($datos_despacho['pais']).'';
			}
			fwrite($fp,$linea);
			fclose($fp);
			return true;
		}
		return false;
	}
}
function prx($data)
{
	pr($data); exit;
}
