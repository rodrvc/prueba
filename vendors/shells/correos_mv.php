<?php
App::import('Core', array('Router','Controller'));
App::import('Component', 'WsServer');

class CorreosMvShell extends Shell {
	var $Controller = null;
	var	$store = 11081;


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
		echo ( date('Y-m-d H:i:s'));
		$options = array(
			'conditions' => array(
				'Compra.estado' => 1,
				'Compra.mail_compra' => 0,
				'Compra.verificado' => 1, 
				'Compra.created >' => $desde,
				'Compra.created <' => $hasta,
				'Compra.local >' => 0
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
				'Direccion.id',
				'Direccion.calle',
				'Direccion.numero',
				'Direccion.depto',
				'Direccion.telefono',
				'Direccion.celular',
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
				)
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
				Tu Boleta será enviada junto con tu compra.  Conserva tu boleta para efectos de hacer valer tus derechos de garantía legal.
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
		return true;
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
		$CompraOBJ = new Compra();
		$save = array(
					'id' => $compra_id,
					'mail_compra' => 1
				);
				$CompraOBJ->save($save);
		return true;
		
	}

	private function generar_archivo_USA($compra_id = null)
	{
		if (! $compra_id)
		{
			return false;
		}
		Configure::write('debug',2);
		error_reporting(8);

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
				'Compra.cod_despacho',
				'Compra.mv_cliente',
				'Compra.mv_orden',
				'Compra.mv_numero1',
				'Compra.ip',
				'Despacho.id',
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
		echo 'aqui';
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
				$store= $this->store;
				if($compra['Compra']['ip'] == 'dafiti')
					$store = 10956;
		
				$data = array(
					'estilo' 		=> $producto['Producto']['codigo'],
					'precio_venta'	=> $precio_venta,
					'talla'			=> $producto['ProductosCompra']['talla'],
					'cantidad'		=> 1,
					'color'			=> $producto['Color']['codigo'],
					'despacho'		=> $compra['Compra']['mv_numero1'],
					'fecha'			=> date('m/d/Y',$ayer),
					'fecha_canc'	=>'',
					'precio_retail'	=> $producto['ProductosCompra']['valor'],
					'PO'			=> $compra['Compra']['mv_orden'],
					'ACC'			=> $store,
					'TERM_CODE'		=> '81',
					'Shipper_code'	=> 'SR',
					'Sales_Rep1'	=> '107',
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
				}

				$codigo_comuna ='ST';
				if(isset($compra['Comuna']['codigo']) && trim($compra['Comuna']['codigo']) !='')
					$codigo_comuna = $compra['Comuna']['codigo'];

				$data = array(
					'PO'			=> $compra['Compra']['mv_orden'],
					'direccion'		=> str_replace('_',' ',Inflector::slug($direccion_texto)),
					'depto'		=> str_replace('_',' ',Inflector::slug($depto_texto)),
					'pais'			=> 'CHL',
					'comuna'		=> str_replace('_',' ',Inflector::slug($compra['Direccion']['otras_indicaciones'])),
					'nombre'		=> str_replace('_',' ',Inflector::slug($compra['Compra']['mv_cliente'])),
					'comuna2'		=> $codigo_comuna,
					'email'			=> $compra['UsuarioDespacho']['email'],
					'city'			=> $compra['Region']['codigo']
				);
				array_push($datos_despachos,$data);
			}

			// if (true)				$basedir = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'webroot'.DS.'archivos'.DS.'PROD'.DS;
				$basedir = DS.'var'.DS.'www'.DS.'archivos'.DS.'prod'.DS;
				$backupdir = DS.'var'.DS.'www'.DS.'archivos'.DS.'backup'.DS;
				//$basedir = 'D:\xampp\htdocs\skechers\archivos'.DS;
				//$backupdir = 'D:\xampp\htdocs\skechers\archivos'.DS.'backup'.DS;



			// else
				// $basedir = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'desarrollo'.DS.'webroot'.DS.'archivos'.DS.'DEV'.DS;

			if (! is_dir($basedir))
				@mkdir($basedir, 0755, true);
			if (! is_dir($backupdir))
				@mkdir($backupdir, 0755, true);

			//      GUARDAMOS EL ARCHIVO DE LOS PRODUCTOS
			$nombre = $store.'_'.$compra['Compra']['mv_orden'].'.csv';
			$fp = fopen($basedir.$nombre, 'w+');
			$fp2 = fopen($backupdir.$nombre, 'w+');
			$contador =1;
			$linea = '';
			foreach($datos_compras as $dato_compra)
			{
				$linea = $dato_compra['PO'].'|'.$dato_compra['ACC'].'|'.$dato_compra['tienda'].'|'.$dato_compra['PROMO_CODE'].'|'.$dato_compra['TERM_CODE'].'|'.$dato_compra['Shipper_code'].'|'.$dato_compra['Sales_Rep1'].'|'.$dato_compra['fecha'].'|'.$dato_compra['Special_Inst'].'|'.$dato_compra['SKK'].'|'.$dato_compra['DIV_CODE'].'|'.$dato_compra['estilo'].'|'.$dato_compra['color'].'|'.$dato_compra['precio_venta'].'|'.$dato_compra['talla'].'|'.$dato_compra['cantidad'].'|'.$dato_compra['fecha'].'||||||TK'.$dato_compra['despacho'].'||'.$dato_compra['precio_retail'].'|||';
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
			$nombre = $store.'_'.$dato_compra['PO'].'_address.csv';
			$fp = fopen($basedir.$nombre, 'w+');
			$fp2 = fopen($backupdir.$nombre, 'w+');
			$cabecera ='PO	|NOMBRE CLIENTE|	ADDRESS1|	ADDRESS2|	ADDRESS3|	STATE| CITY	|	COUNTRY|
			';
			$linea ='';
			foreach($datos_despachos as $datos_despacho)
			{
				$linea = $datos_despacho['PO'].'|'.trim(utf8_decode((($datos_despacho['nombre'])))).'|'.utf8_decode($datos_despacho['direccion']).'|'.utf8_decode($datos_despacho['depto']).'| |'.utf8_decode($datos_despacho['comuna']).'|'.utf8_decode($datos_despacho['comuna2']).'| |'.utf8_decode($datos_despacho['pais']).'';
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
