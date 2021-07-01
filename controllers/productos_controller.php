<?php
class ProductosController extends AppController
{
	var $name = 'Productos';

	var $productosPromo = array(4415,4416);
	var $helpers = array('Cache');
	var $cacheAction = array(	'inicio'	=> 60000,
								'view' => 300,
								'cybermonday2016' => 180,
								'nuevo' => 600,
								'especial' => 180);
	

function redirect_old($slug)
{
	$this->redirect(array('action' => 'view', $slug), 301);

}
function prueba()
{
	$datos_tbk = $this->tbkParams;
	require_once('../vendor/autoload.php');
	Transbank\Webpay\WebpayPlus::setCommerceCode('597055555532');
	Transbank\Webpay\WebpayPlus::setApiKey('579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C');
	Transbank\Webpay\WebpayPlus::setIntegrationType('TEST');
	$buyOrder = strval(rand(100000, 999999999));
	$returnUrl = "http://localhost/tecno/pago/cierre2.php";
	//$finalUrl = "https://callback/final/post/comprobante/webpay";
	$amount = 1000;
	$sessionId = uniqid();
	$response = Transbank\Webpay\WebpayPlus\Transaction::create($buyOrder, $sessionId, $amount, $returnUrl);
	//prx($response);
	$this->set(compact('response'));
	
/*	App::import('Vendor', 'autoload', array('file' => 'libwebpay'.DS.'webpay.php'));
	//$configuration = new Configuration();
	$webpay = new WebpayPlus($configuration);
	$transaccion = $webpay->create();
	$transaccion->create();
	$amount = 1000;
	// Identificador que será retornado en el callback de resultado:
	$sessionId = "mi-id-de-sesion";
	// Identificador único de orden de compra:
	$buyOrder = strval(rand(100000, 999999999));
	$returnUrl = "https://callback/resultado/de/transaccion";
	$finalUrl = "https://callback/final/post/comprobante/webpay";
	$initResult = $transaction->initTransaction(
    $amount, $buyOrder, $sessionId, $returnUrl, $finalUrl);

	$formAction = $initResult->url;
	$tokenWs = $initResult->token;
	//$configuration->setEnvironment($this->certificate['environment']);
	//$configuration->setCommerceCode($this->certificate['commerce_code']);
	//$configuration->setPrivateKey($this->certificate['private_key']);
	//$configuration->setPublicCert($this->certificate['public_cert']);
	//$configuration->setWebpayCert($this->certificate['webpay_cert']);
	$sessionId = uniqid();


			/** Creacion Objeto Webpay 
			$webpay = new Webpay($configuration);
			$result = $webpay->getNormalTransaction()->initTransaction($valores_compra['total'], $ordencompra, $sessionId, $datos_tbk['cierre'], $datos_tbk['exito']);*/

}
function test()
{	
		 $this->Multivende->authenticate_ripley();
		// $this->Multivende->getOrdenesRipley();
		// exit;
		$this->Multivende->obtenerEtiquetaRipley('605c9c328264eaaa227d00bf');
		exit;
		$this->Multivende->procesarVentas('2021-03-25','2021-03-26');
		exit;
		
		$this->Multivende->falabella_label(122500525125);
		exit();
	
		$this->Multivende->getVentaDebug('bff3a221-234b-425b-9bbe-6b6d72876085');
		prx($archivo);
		$this->Multivende->getCebraMasivo();
		prx('terminado');
		prx($productos);
	}
	function beforeRender()
	{
		parent::beforeRender();
		//LAYOUT EXCEL
		if ( $this->params['action'] == 'admin_excel_stock' )
			$this->layout = 'ajax';
	}
	//DESPACHOS
	function notificacion_mp()
	{
			$respuesta = json_decode(file_get_contents('php://input'), true);
			if(isset($respuesta['action']))
			{
				$pago_id = $respuesta["data"]["id"];
				$access_token = $this->tbkParams['mercado_pago'];
				$url = "https://api.mercadopago.com/v1/payments/".$pago_id."?access_token=".$access_token;
			    $curl = curl_init();
			    curl_setopt_array($curl, array(
			    CURLOPT_URL => $url,
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_ENCODING => "",
			    CURLOPT_MAXREDIRS => 10,
			    CURLOPT_TIMEOUT => 0,
			    CURLOPT_SSL_VERIFYHOST => 0,
			    CURLOPT_SSL_VERIFYPEER => 0,
			    CURLOPT_FOLLOWLOCATION => true,
			    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			    CURLOPT_CUSTOMREQUEST => "GET",
			    CURLOPT_HTTPHEADER => array(
			        "Content-Type: application/json",
			      ),
			    ));
			    $result  = curl_exec($curl);
			    
			  if( $result === false)
			  {
			        echo 'Curl error: ' . curl_error($curl);

			  }
			  else
			  {
			      $respuesta = json_decode($result ,true);
			      $id_compra = $respuesta['external_reference'];
			      $estado_pago = $respuesta['status'];
			      $estado_deposito = $respuesta['status_detail'];
			      $monto = $respuesta['transaction_amount'];
			      $compra = $this->Producto->Compra->find('first', array(
			      													'conditions' => array(
			      														'Compra.id' => $id_compra, 
			      														'Compra.estado <>' => 1
			      														),
			      													'contain' => 'Pago'
			      													)
			      											);
			      if(!$compra)
			      	die('error');
			      if($estado_deposito == 'accredited' && $estado_pago == 'approved' && $monto == $compra['Compra']['total'] )
			      {
			      	$guardar_compra = array('id' => $id_compra,
			      					'estado' => 1);
			      	$this->Producto->Compra->save($guardar_compra);

			      	$guardar_pago = array(		'id' =>$compra['Pago'][0]['id'],
			      								 'marca' => 'mercadopago',
			      						   		'numeroTarjeta' => $respuesta['card']['last_four_digits'],
			      						   		'expiracion' => $respuesta['card']['expiration_month'].$respuesta['card']['expiration_year'],
			      						   		'codAutorizacion' => $respuesta['order']['id'],
			      						   		'respuesta' => 0,
			      						   		'fechaContable' => date('dm'));
			      	if($this->Producto->Compra->Pago->save($guardar_pago))
			      		die('OK');
			      	else
			      		die('ERROR 1');
			      }else{
			      	die('ERROR 2');
			      }
			 	}
		}else
		{
			die('ERROR 3');
		}

	}
	function cierre_mp()
	{

	   	$orden =$_GET["external_reference"];
	   	$usuario_id = $this->Auth->user('id'); 
	   	$token = $_GET["preference_id"]; //
	   	$pago_id = $_GET["collection_id"];
	   	//prx(compact('orden','usuario_id','token'));
	   	$compra = $this->Producto->Compra->find('first', array('conditions' => array('Compra.token' => $token,
	   																				'Compra.usuario_id' => $usuario_id,
	   																				 'Compra.id' => $orden ),
	   															'contain' =>'Pago'));
	   	if($compra)
	   	{
	   		if($compra['Compra']['estado'] == 1 && $compra['Pago'][0]['respuesta'] == 0 )
	   		{
	   				$this->redirect(array('action' => 'felicidades','mercadopago'));
	   		}else{	
		   		$access_token = $this->tbkParams['mercado_pago'];
				$url = "https://api.mercadopago.com/v1/payments/".$pago_id."?access_token=".$access_token;
			    $curl = curl_init();
			    curl_setopt_array($curl, array(
			    CURLOPT_URL => $url,
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_ENCODING => "",
			    CURLOPT_MAXREDIRS => 10,
			    CURLOPT_TIMEOUT => 0,
			    CURLOPT_SSL_VERIFYHOST => 0,
			    CURLOPT_SSL_VERIFYPEER => 0,
			    CURLOPT_FOLLOWLOCATION => true,
			    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			    CURLOPT_CUSTOMREQUEST => "GET",
			    CURLOPT_HTTPHEADER => array(
			        "Content-Type: application/json",
			      ),
			    ));
			    $result  = curl_exec($curl);
			    
			  if( $result === false)
			    {
			        echo 'Curl error: ' . curl_error($curl);

			    }
			    else
			    {

			      $respuesta = json_decode($result ,true);
			      $id_compra = $respuesta['external_reference'];
			      $estado_pago = $respuesta['status'];
			      $estado_deposito = $respuesta['status_detail'];
			      $monto = $respuesta['transaction_amount'];
			      if($estado_deposito == 'accredited' && $estado_pago == 'approved' && $id_compra == $orden && $monto == $compra['Compra']['total'])
			      {
			      	$guardar_compra = array('id' => $orden,
			      					'estado' => 1);
			      	$this->Producto->Compra->save($guardar_compra);

			      	$guardar_pago = array('id' =>$compra['Pago'][0]['id'],
			      						   'numeroTarjeta' => $respuesta['card']['last_four_digits'],
			      						   'expiracion' => $respuesta['card']['expiration_month'].$respuesta['card']['expiration_year'],
			      						   'codAutorizacion' => $respuesta['authorization_code'],
			      						   'respuesta' => 0,
			      						   'marca' => 'mercadopago',
			      						   'fechaContable' => date('dm'));
			      	$this->Producto->Compra->Pago->save($guardar_pago);
					$this->redirect(array('action' => 'felicidades','mercadopago'));
				 }else{
				 	prx('error');
				 }

	      		}
	      }
	      	

	         	
		}else{
			prx('no existe la compra');
		}
	}
	function felicidades( $merchant = null)
	{
		$this->loadModel('Despacho');
		$options = array();
	if(is_null($merchant))
	{
			$options = array(
			'conditions' => array(
				'Compra.token' => $_POST['token_ws'],
			),
			'fields' => array(
				'Compra.id',
				'Compra.subtotal',
				'Compra.iva',
				'Compra.neto',
				'Compra.total',
				'Compra.token',
				'Compra.despacho_id',

				'Compra.valor_despacho',
				'Pago.id',
				'Pago.compra_id',
				'Pago.numeroTarjeta',
				'Pago.fecha',
				'Pago.tipoPago',
				'Usuario.nombre'
			),
			'recursive' => 1,
			'contain' => 'Usuario',
		
			'joins' => array(
				array(
					'table' => 'sitio_pagos',
					'alias' => 'Pago',
					'type' => 'INNER',
					'conditions' => array(
						'Pago.compra_id = Compra.id'
					)
				)
				)
		);

	}else{
		$options = array(
			'conditions' => array(
				'Compra.id' => $this->Session->read('Compra.id'),
				'Compra.usuario_id' => $this->Auth->user('id')
			),
			'fields' => array(
				'Compra.id',
				'Compra.subtotal',
				'Compra.iva',
				'Compra.neto',
				'Compra.total',
				'Compra.token',
				'Compra.despacho_id',

				'Compra.valor_despacho',
				'Pago.id',
				'Pago.compra_id',
				'Pago.numeroTarjeta',
				'Pago.fecha',
				'Pago.tipoPago',
				'Usuario.nombre'
			),
			'recursive' => 1,
			'contain' => 'Usuario',
			'joins' => array(
				array(
					'table' => 'sitio_pagos',
					'alias' => 'Pago',
					'type' => 'INNER',
					'conditions' => array(
						'Pago.compra_id = Compra.id'
					)
				)
			)
		);
	}


		if (! $compra = $this->Producto->Compra->find('first',$options))
		{
			$this->redirect(array('action' => 'inicio'));
		}

		$options = array(
			'conditions' => array(
				'Despacho.id' => $compra['Compra']['despacho_id']
			),
			'contain' => array(
				'Direccion' => array(
					'Comuna' => array(
						'Region'
					)
				)
			)
		);
		$this->loadModel('ProductosCompra');
		$despacho = $this->Despacho->find('first',$options);

		if ($compra['Pago']['tipoPago'] !='REGALO' && $merchant != 'mercadopago'  && (! isset($_POST['token_ws']) && $_POST['token_ws'] =='' ))
		{
			$this->redirect(array('action' => 'inicio'));
		}
		if ($compra['Pago']['tipoPago'] !='REGALO')
		{
			if(isset($_POST['token_ws']) && $_POST['token_ws'] != $compra['Compra']['token'])
			{
				$this->redirect(array('action' => 'inicio'));
			}	
		}
		$carrito = $this->Carro->productos();


		if ( ! $carrito )
		{
			$carrito=array();
			$sql = "SELECT s.id, count(*) as cantidad FROM sitio_productos_compras pc, sitio_stocks s where pc.compra_id = ". $compra['Compra']['id']." and s.sku = pc.sku group by s.sku";
		
		$respuesta = $this->Despacho->query($sql);

			foreach($respuesta as $producto)
			{
				if(!isset($carrito[$producto['s']['id']]))
					$carrito[$producto['s']['id']] = 1;
				else
					$carrito[$producto['s']['id']]++;			}
			
		}


		$productos = array();
		$cantprod = $total = $subtotal = 0;
		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Categoria.parent_id' => 58),
					array('Categoria.id' => 58)
				),
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		);
		$categorias_ropa = $this->Producto->Categoria->find('list',$options);

		foreach ( $carrito as $id => $cantidad )
		{
			$options = array(
				'conditions' => array('Stock.id' => $id),
				'contain'	=> array(
					'Producto' => array('Color','Categoria')
				)
			);
			$productos[$id] = $this->Producto->Stock->find('first',$options);

			if (in_array($productos[$id]['Producto']['categoria_id'],$categorias_ropa))
			{
				if ($productos[$id]['Stock']['talla'])
				{
					$talla_ropa = false;
					if ($productos[$id]['Stock']['talla'] == 1)
						$talla_ropa = 'XS';
					elseif ($productos[$id]['Stock']['talla'] == 2)
						$talla_ropa = 'S';
					elseif ($productos[$id]['Stock']['talla'] == 3)
						$talla_ropa = 'M';
					elseif ($productos[$id]['Stock']['talla'] == 4)
						$talla_ropa = 'L';
					elseif ($productos[$id]['Stock']['talla'] == 5)
						$talla_ropa = 'XL';

					if ($talla_ropa)
						$productos[$id]['Stock']['talla'] = $talla_ropa;
				}
			}

			$productos[$id]['cantidad']	= $cantidad;
			if ( $productos[$id]['Producto']['oferta'] )
				$total = $total + ((int)$productos[$id]['Producto']['precio_oferta'] * (int)$cantidad);
			else
				$total = $total + ((int)$productos[$id]['Producto']['precio'] * (int)$cantidad);
			$cantprod = (int)$cantprod + (int)$cantidad;
		}



		$this->data['Compra']['id'] = $compra['Compra']['id'];

		/** VERIFICACION DE COMPRA
		 *	si la ID de la compra no coincide con la ID que entrega webpay, no es una compra valida
		 *	si la compra no es un REGALO, no es valida
		 */
	/*	$verificacion_compra = 0;
		if ( isset($_POST['TBK_ORDEN_COMPRA'] ) && $_POST['TBK_ORDEN_COMPRA'] == $compra['Compra']['id'] )
			$verificacion_compra = 1;
		elseif ($compra['Pago']['tipoPago'] == 'REGALO' )
			$verificacion_compra = 1;
		// si la compra no es valida, redirecciona al inicio
		if ( $verificacion_compra == 0 )
			$this->redirect(array('action' => 'inicio'));
			*/
		$descuentos = $this->Session->read('Descuento') ;
		$this->data['Compra']['estado'] = 1;

		if ( $this->Producto->Compra->save($this->data) )
		{
			// DESCUENTO
			// aumenta el contador de descuentos
			$descuento = 0;
			$descontar =array();
			if ( $descuentos = $this->Session->read('Descuento') )
			{
				foreach ( $descuentos as $index => $desc )
				{
					if ( $desc['tipo'] == 'DIN' )
						$descuento = $descuento + ($desc['descuento'] * $desc['aplicar']) ;
					elseif ( $desc['tipo'] == 'POR' )
					{
						if ( $productos[$index]['Producto']['oferta'] == 1 )
							$descuento = $descuento + ((( $productos[$index]['Producto']['precio_oferta'] * $desc['descuento'] ) / 100 ) *$desc['aplicar']) ;
						else
							$descuento = $descuento + ((( $productos[$index]['Producto']['precio'] * $desc['descuento'] ) / 100 ) * $desc['aplicar']);
						if ( ($descuento % 10) > 0 )
							$descuento = (((int)($descuento/10))*10)+10;
						else
							$descuento = ((int)($descuento/10))*10;
					}
					$this->loadModel('Descuento');
					if(!isset($descontar[$desc['codigo']]))
						$descontar[$desc['codigo']] = $desc['contador'] + $desc['aplicar'];
					else
						$descontar[$desc['codigo']] += $desc['aplicar'];
					$this->Descuento->save(array('id' => $desc['id'],
												'contador' => $descontar[$desc['codigo']] ));

				}
			}
		}
		$nombre = $compra['Usuario']['nombre'];

		$total = $compra['Compra']['total'];
		$subtotal = $compra['Compra']['subtotal'];
		$neto = $compra['Compra']['neto'];
		$iva = $compra['Compra']['iva'];
		$despacho_val = $compra['Compra']['valor_despacho'];
		$usuario = array(
			'nombre' => $this->Auth->user('nombre'),
			'apellido_paterno' => $this->Auth->user('apellido_paterno'),
			'apellido_materno' => $this->Auth->user('apellido_materno'),
			'email' => $this->Auth->user('email'),
			'rut'	=> $this->Auth->user('rut')
		);
		$pago['Pago'] = $compra['Pago'];
		$this->limpiarCarro();


		$this->set(compact('productos', 'cantprod', 'subtotal', 'iva', 'neto', 'despacho_val', 'total', 'despacho', 'descuento' , 'descuentos', 'pago', 'usuario','nombre'));
		if ( $total <= 0 )
		{
			// guardar log
			$this->guardar_log($this->Auth->user('id'), 'productos', 'felicidades', 'compra realizada #'.$this->data['Compra']['id'].' (regalo)', $_SERVER['REMOTE_ADDR']);
		}
		//prx($productos);
		$this->guardar_log($this->Auth->user('id'), 'productos', 'felicidades', $compra['Compra']['id'], $_SERVER['REMOTE_ADDR']);
	}
	function cybermonday2016($categoria = null)
	{
		if($categoria)
		{
				$options = array(
					'conditions' => array(
						'Categoria.slug' => $categoria
					),
					'fields' => array(
						'Categoria.id',
						'Categoria.nombre',
						'Categoria.alias'
					)
				);
				$categoria = $this->Producto->Categoria->find('first',$options);
				$categoria =$categoria['Categoria']['id'];

			}
			else {
				$categoria = array(1,2,3,4,58,59,60);
			}
		$options = array(
			'conditions' => array( 
					'AND' => array(	
						'OR' => array(
							'Producto.cyber' 	=> 1,
							'Producto.outlet' => 1
							),
						 'NOT' => array(
								array('Producto.foto' => ''),
								array('Producto.foto' => null)
							)
						 )
				
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
					)
				),
			1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'	=> array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)
				)

			),
			'order'	=> array(
				'Producto.orden DESC',
				'RAND()'
			),
			'group' => array('Producto.id')
		);
		if ($categoria)
			$options['conditions']['Producto.categoria_id'] = $categoria;



		$productos = $this->Producto->find('all',$options);
	//	prx($productos);
		$cont = count($productos);

		$filtros = $otros = array();
		$canonical ='https://www.skechers.cl/sale';
		$this->set(compact('productos','cont','ordenar','filtros','titulo','canonical'));
	}
	function nuevo($categoria = null)
	{
		Configure::write('debug',2);
		if($categoria)
		{
				$options = array(
					'conditions' => array(
						'Categoria.slug' => $categoria
					),
					'fields' => array(
						'Categoria.id',
						'Categoria.nombre',
						'Categoria.alias'
					)
				);
				$categoria = $this->Producto->Categoria->find('first',$options);
				$categoria =$categoria['Categoria']['id'];

			}
			else {
				$categoria = array(1,2,3,4,58,59,60);
			}
		$options = array(
			'conditions' => array( 
					'AND' => array(	
						'OR' => array(
							'Producto.grupo like' 	=> '%[2020_2]%',
							),
						 'NOT' => array(
								array('Producto.foto' => ''),
								array('Producto.foto' => null)
							)
						 )
				
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
					)
				),
			1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'	=> array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)
				)

			),
			'order'	=> array(
				'Producto.orden DESC',
				'RAND()'
			),
			'group' => array('Producto.id')
		);
		if ($categoria)
			$options['conditions']['Producto.categoria_id'] = $categoria;



		$productos = $this->Producto->find('all',$options);
	//	prx($productos);
		$cont = count($productos);

		$filtros = $otros = array();



		// filtro tallas

		// filtro ordenar


		//$this->layout = 'default-cybermonday';
	$this->set(compact('productos','cont','ordenar','filtros','titulo'));
	}


		function especial()
	{
	
		$options = array(
			'conditions' => array( 
					'AND' => array(	
						'OR' => array(
							'Producto.dosporuno' 	=> 1
							),
						 'NOT' => array(
								array('Producto.foto' => ''),
								array('Producto.foto' => null)
							)
						 )
				
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
					)
				),
			1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'	=> array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)
				)

			),
			'order'	=> array(
				'Producto.orden DESC',
				'RAND()'
			),
			'group' => array('Producto.id')
		);
		;



		$productos = $this->Producto->find('all',$options);
	//	prx($productos);
		$cont = count($productos);

		$filtros = $otros = array();



		// filtro tallas

		// filtro ordenar


		//$this->layout = 'default-cybermonday';
		$this->set(compact('productos','cont','ordenar','filtros','titulo'));
	}



	function zapatillas()
	{
		$cortes = array('','full_','mini_','ith_');
		$zapatillas = $this->Producto->find('all', array(
																'fields'	=> array('Producto.id', 'Producto.nombre', 'Producto.foto'),
																'conditions' => array('Producto.cyber' => true, 'Producto.id >' => 5247),
																'contain'	=> array(
																'Galeria'	=> array(
																		'order' => array('Galeria.orden' => 'ASC'),
																		'fields'	=> array(
																			'Galeria.id',
																			'Galeria.imagen',
																			'Galeria.producto_id'
																		),
																		'limit' => 5
																	))));
		$basedir = 'F:/xampp/htdocs/skechers/fotos/';
		foreach ($zapatillas as $zapatilla)
		{
//			$resultado['bkp_name'] = "bkp_".strtotime(date('Y-m-d H:i:s')).'.csv';
	//		$resultado['log_name'] =  $basedir."bkp_".strtotime(date('Y-m-d H:i:s')).'.txt';
	//		$file = $basedir.$resultado['bkp_name'];
			$carpeta =$basedir.'Producto/'.$zapatilla['Producto']['id'];
			if (! is_dir($carpeta))
			{
				@mkdir($carpeta, 0777, true);
			}
			foreach ($zapatilla['Producto']['foto'] as $foto)
			{
				$link = 'http://www.skechers.cl/img/'.$foto;
				copy($link, $basedir.$foto);
			}
			foreach ($zapatilla['Galeria'] as $foto)
			{
				$carpeta =$basedir.'Galeria/'.$foto['id'];
				if (! is_dir($carpeta))
				{
					@mkdir($carpeta, 0777, true);
				}
				foreach($cortes as $corte)
				{

						$link = 'http://www.skechers.cl/img/Galeria/'.$foto['id'].'/'.$corte.$foto['imagen'];
						copy($link, $basedir.'Galeria/'.$foto['id'].'/'.$corte.$foto['imagen']);
				}

			}


		}
		prx(1);


	}

	function tienda()
	{
		$this->loadModel('Tienda');
		$tiendas = $this->Tienda->find('all');
		$this->set(compact('tiendas'));
	}

	function ajax_busqueda()
	{
		
		if (! isset($this->params['form']['busca']))
			die(false);
		if (! $this->params['form']['busca'])
			die(false);
		$options = array(
			'conditions' => array(
				'Producto.activo' => 1,
				'Producto.precio >' => 4000,
				'NOT' => array(
					array('Producto.foto' => null),
					array('Producto.foto' => '')
				),
				'OR'	=> array(
					array('Producto.nombre LIKE' => "%{$this->params['form']['busca']}%"),
					array('Producto.slug LIKE' => "%{$this->params['form']['busca']}%"),
					array('Producto.codigo LIKE' => "%{$this->params['form']['busca']}%"),
					array('Producto.codigo_completo LIKE' => "%{$this->params['form']['busca']}%"),
					array('Producto.grupo LIKE' => "%{$this->params['form']['busca']}%")
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.codigo',
				'Producto.codigo_completo',
				'Producto.foto',
				'Producto.categoria_id',
				'Producto.color_id',
				'Producto.slug',
			),
			'joins' => array(
				array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1
					)
				),
				array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'		=> array('Color.codigo'),
			'order'		=> array('Producto.nombre' => 'ASC'),
			'group' => array('Producto.id'),
			'limit'		=> 10,

		);
		if ($resultados = $this->Producto->find('all',$options))
		{
			foreach ($resultados as $index => $producto)
			{
				$resultados[$index]['Producto']['foto'] = $this->Carro->imagen($producto['Producto']['foto']['mini']);
			}
			die(json_encode($resultados));
		}
		die(false);
    }

	function ajax_productosxtienda()
	{
		$id = $this->params['form']['id'];
		$talla = $this->params['form']['talla'];
		$region = $this->params['form']['region'];
		$options = array(
			'conditions' => array(
				'Tienda.region_id' => $region,
				'Stock.producto_id' => $id,
				'Stock.talla' => $talla
			),
			'contain' => array('Tienda')
		);
		$resultados = $this->Producto->Stock->find('all',$options);
		$this->set(compact('resultados'));
    }

	function ajax_agregar_al_carro()
	{
		Configure::write('debug',1);
		if (! isset($this->params['form']['id']) || ! isset($this->params['form']['cantidad']))
			die(json_encode(array('codigo' => 'AGREGO_ERROR2','productos' => 0)));
		if (! $this->params['form']['id'] || ! $this->params['form']['cantidad'])
			die(json_encode(array('codigo' => 'AGREGO_ERROR2','productos' => 0)));

		$contador = 0;
		if ($this->Session->check('Carro') && $productos_carro = $this->Session->read('Carro'))
		{
			foreach ($productos_carro as $producto_carro)
			{
				if ($producto_carro['cantidad'])
					$contador+= (int)$producto_carro['cantidad'];
			}
		}
		$cantidad = $this->params['form']['cantidad'];

		if($contador + $cantidad > 10 )
			die(json_encode(array('codigo' => 'AGREGO_ERROR3','productos' => 0)));

		$options = array(
			'conditions' => array(
				'Stock.id' => $this->params['form']['id'],
				'Stock.cantidad >=' => $this->params['form']['cantidad']
			),
			'fields' => array(
				'Stock.id',
				'Stock.cantidad',
			)
		);
			
		if (! $stock = $this->Producto->Stock->find('first',$options))
			die(json_encode(array('codigo' => 'AGREGO_ERROR1','productos' => 0)));

		if (isset($this->productosPromo) && $this->productosPromo)
		{
			$options = array(
				'conditions' => array(
					'Stock.producto_id' => $this->productosPromo,
					'Stock.id' => $stock['Stock']['id']
				),
				'fields' => array(
					'Stock.id',
				),
			);
			if ($promo = $this->Producto->Stock->find('list',$options))
				die(json_encode(array('codigo' => 'AGREGO_ERROR1','productos' => 0)));
		}

		$cantidad = $this->params['form']['cantidad'];
		if ( $this->Session->check( "Carro.{$stock['Stock']['id']}.cantidad" ) )
		{
			$cantidad+= $this->Session->read( "Carro.{$stock['Stock']['id']}.cantidad" );
			$limite = $stock['Stock']['cantidad'] - $this->stock_seguridad;
			if ($cantidad > $limite)
			{
				die(json_encode(array('codigo' => 'AGREGO_ERROR1','productos' => 0, 'mensaje' => 'Ha excedido el stock de este producto.')));
			}
		}

		$this->Carro->agregar( $stock['Stock']['id'] );
		$this->Carro->actualiza( $stock['Stock']['id'], $cantidad );

		$contador = 0;
		if ($this->Session->check('Carro') && $productos_carro = $this->Session->read('Carro'))
		{
			foreach ($productos_carro as $producto_carro)
			{
				if ($producto_carro['cantidad'])
					$contador+= (int)$producto_carro['cantidad'];
			}
		}
		//Cache::set(array('duration' => '+10 minutes'));
		//Cache::config('carro', array('engine' => 'File', 'path' => CACHE.carro));
		if ($user = $this->Auth->user()){
			$carro = $this->datos_carro();
			Cache::write('carro_'.$user['Usuario']['id'],$carro,'carro');
			Cache::write('carro_id'.$user['Usuario']['id'],$this->Session->read('Carro'),'carro');
		}
		die(json_encode(array('codigo' => 'AGREGO_OK','productos' => $contador)));
	}

	function inicio()
	{
		if ($this->Auth->user())
		{
			$this->Session->write('LbPromo','lightboxblackfriday.jpg');
		}
		//BANNERS
		
		$this->loadModel('Banner');

		$options = array(
			'conditions' => array('Banner.tipo' => 0),
			'fields' => array(
				'Banner.id',
				'Banner.imagen',
				'Banner.link'
			),
			'order' => array('Banner.orden' => 'ASC')
		);
		$banners = $this->Banner->find('all',$options);
		$options = array(
			'conditions' => array('Banner.tipo' => 1,
								  'Banner.activo' => 1),
			'fields' => array(
				'Banner.id',
				'Banner.imagen',
				'Banner.link'
			)
		);
		$izquierda = $this->Banner->find('first',$options);
		$options = array(
			'conditions' => array(
				'Banner.tipo' => 2,
				'Banner.activo' => 1
			),
			'fields' => array(
				'Banner.id',
				'Banner.imagen',
				'Banner.link'
			)
		);
				$derecha = $this->Banner->find('first',$options);

		$options = array(
			'conditions' => array(
				'Producto.activo' => 1,
				'NOT' => array(
					array('Producto.foto' => ''),
					array('Producto.foto' => null),
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.slug',
				'Producto.stock_seguridad',
			),
			'order'	=> array(
				'Producto.id' =>'DESC'
			),
			'group' => array('Producto.id'),
			'limit' => 4
		);
		$productos_destacados = $this->Producto->find('all', $options);
		$options = array(
			'conditions' => array(
				'Producto.activo' => 1,
				'Producto.oferta' => 1,
				'NOT' => array(
					array('Producto.foto' => ''),
					array('Producto.foto' => null),
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.slug',
				'Producto.stock_seguridad',
			),
			'order'	=> array(
				'RAND()'
			),
			'group' => array('Producto.id'),
			'limit' => 4
		);
		$productos_oferta = $this->Producto->find('all', $options);
		$this->set(compact('banners','izquierda','derecha','productos_destacados','productos_oferta'));
	}

	// lista de productos normales (women, men, boys, girls)
	function catalogo( $slug = null) 
	{
		error_reporting(8);
		//prx($this->data);

		if ( ! empty($this->data) )
		{
			prx($this->data);
		}
		if (! $slug)
			$this->redirect(array('action' => 'inicio'));
		$inicio = true;
		$talla = false;
		$estilo = false;
		//CATEGORIA
		$options = array(
			'conditions' => array(
				'Categoria.slug' => $slug,
				'Categoria.publico' => 1,
			
			),
			'contain' => array('ChildCategoria','ParentCategoria'),
		
			'joins' => array(
				array(
					'table' => 'sitio_categorias',
					'alias' => 'CategoriaPadre',
					'type' => 'LEFT',
					'conditions' => array(
						'CategoriaPadre.id = Categoria.parent_id',
					)
				),
			
			),
			'recursive' => 1
		);
		if (! $categoria = $this->Producto->Categoria->find('first',$options))// EXISTE CATEGORIA ?
			$this->redirect(array('action' => 'inicio'));
		$categorias = array(0 => $categoria['Categoria']['id']);
		$categoria_filtros =$categoria['Categoria']['id'];
		if(isset($categoria['ChildCategoria']) && !empty($categoria['ChildCategoria']))
		{
			foreach($categoria['ChildCategoria'] as $categoria_hijo)
					$categorias[] = $categoria_hijo['id'];
		}
		if(isset($categoria['ParentCategoria']) && !empty($categoria['ParentCategoria']))
		{
			$categoria_filtros = ($categoria['ParentCategoria']['id']);
		}


		$options = array(
			'conditions' => array(
				'Producto.activo' => 1,
				'Producto.outlet' => 0,
				'Producto.precio >' => 4000,
				'NOT' => array(
					array('Producto.foto' => ''),
					array('Producto.foto' => null),
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
						'Categoria.id' => $categorias

					
					))
			/*	,
				1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					))*/
			),		
			'contain'	=> array(
				/*'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)

				)*/
				
			),
			'order'	=> array(
				'Producto.orden DESC',
				'Producto.coleccion_id DESC',
				'Producto.id DESC'
			),
			'group' => array('Producto.id')
		);
		$this->loadModel("Filtro");
		$buscador = $this->Filtro->find('all', array('condition' => array(
														'categoria_id' => $categoria_filtros
													),
													'contain' => 'Rango'
										));
		if (isset($this->params['url']['style']) && $this->params['url']['style'])
		{
			$inicio = false;
			$estilo = $this->params['url']['style'];
			$options['conditions']['Producto.grupo LIKE'] = '%['.$this->params['url']['style'].']%';
		}
		if (isset($this->params['url']['talla']) && $this->params['url']['talla'])
		{
			$inicio = false;
			if(!$estilo)
				$talla = $this->params['url']['talla'];
			$estilo = false;
			$options['joins'][1] = array(
				'table' => 'sitio_tallas',
				'alias' => 'FiltroTalla',
				'type' => 'INNER',
				'conditions' => array(
					'FiltroTalla.producto_id = Producto.id',
					'FiltroTalla.cantidad >=' => $this->stock_seguridad,
					'FiltroTalla.talla' => $this->params['url']['talla']
				)
			);
		}
		if (isset($this->params['url']['color']) && $this->params['url']['color'])
		{
			$inicio = false;
			$talla = false;
			$estilo = false;
			$primario = $this->Producto->Color->Primario->findBySlug($this->params['url']['color']);
			$options['joins'][2] = array(
				'table' => 'sitio_colores',
				'alias' => 'FiltroColor',
				'type' => 'INNER',
				'conditions' => array(
					'FiltroColor.id = Producto.color_id',
					'OR' => array(
						array('FiltroColor.primario_id' => $primario['Primario']['id']),
						array('FiltroColor.secundario_id' => $primario['Primario']['id'])
					)
				)
			);
		}
	

		if(isset($this->params['url']['orden']) && $o = $this->params['url']['orden'])
		{
			$inicio = false;
			$talla = false;
			$estilo = false;
			if ($o==1)
				$options['order'] = array('Producto.precio' => 'DESC');
			elseif ($o==2)
				$options['order'] = array('Producto.precio' => 'ASC');
			elseif ($o==3)
				$options['order'] = array('Producto.nombre' => 'ASC');
			elseif ($o==4)
				$options['order'] = array('Producto.codigo' => 'ASC');
		}
		if($inicio)
		{
			Cache::set(array('duration' => '+10 minutes'));
			$productos = Cache::read('productos_'.$slug);
			if(!($productos !== false))
			{
				$productos = $this->Producto->find('all',$options);
				Cache::set(array('duration' => '+10 minutes'));
				Cache::write('productos_'.$slug, $productos);
			}
		}else if($talla && !$estilo)
		{
			Cache::set(array('duration' => '+10 minutes'));
			$productos = Cache::read('productos_'.$slug.'-'.str_replace(".","-",$talla));
			if(!($productos !== false))
			{
				$productos = $this->Producto->find('all',$options);
				Cache::set(array('duration' => '+10 minutes'));
				Cache::write(('productos_'.$slug.'-'.str_replace(".","-",$talla)), $productos);
			}
		}else if($estilo && !$talla)
		{
			Cache::set(array('duration' => '+10 minutes'));
			$productos = Cache::read('productos_'.$slug.'-'.$estilo);
			if(!($productos !== false))
			{
				$productos = $this->Producto->find('all',$options);
				Cache::set(array('duration' => '+10 minutes'));
				Cache::write('productos_'.$slug.'-'.$estilo,$productos);
			}
		}

		else{
			$productos = $this->Producto->find('all',$options);
		}

		$otros_productos = null;
		if (isset($this->params['url']['otros']) && $this->params['url']['otros'])
		{
			$options_otros = $options;
			$options_otros['conditions']['Producto.grupo LIKE'] = '%['.$this->params['url']['otros'].']%';
			$options_otros['conditions']['Producto.grupo NOT LIKE'] = '%['.$this->params['url']['style'].']%';
			$otros_productos = $this->Producto->find('all',$options_otros);
		}


		$cont = count($productos);

		$filtros = $otros = array();
		Cache::set(array('duration' => '+10 minutes'));
		$filtros = Cache::read('filtros_'.$slug);
		/** PARA LAS CATEGORIAS CLASICAS (MUJER, HOMBRE, NIÑO Y NIÑA) */
		$titulo = $categoria['Categoria']['alias'];
		if (in_array($categoria['Categoria']['id'],array(3,4)))
			$titulo = Inflector::singularize($categoria['Categoria']['alias']);
		if (in_array($categoria['Categoria']['id'],array(1,2,3,4)))
		{
			if($filtros !== false)
			{
				

			}else
			{

			$this->loadModel('Estilo');
			$options = array(
				'conditions' => array(
					'OR' => array(
						array('Categoria.id' => $categoria['Categoria']['id'])
					),
					'Estilo.activo' => 1
				),
				'fields' => array(
					'Estilo.alias',
					'Estilo.nombre'
				),
				'joins' => array(
					array(
						'table' => 'sitio_categorias',
						'alias' => 'Categoria',
						'type' => 'INNER',
						'conditions' => array(
							'Categoria.id = Estilo.categoria_id'
						)
					)
				),
				'order' => array(
					'Estilo.nombre' => 'ASC'
				)
			);
			if ($estilos = $this->Estilo->find('list',$options))
			{
				// filtros
				$filtro = array(
					'name' => 'Estilos',
					'field' => 'style',
					'options' => array()
				);
				foreach ($estilos as $estiloAlias => $estiloName)
				{
					$options = array(
						'conditions' => array(
							'Producto.grupo LIKE' => '%['.$estiloAlias.']%',
							'Producto.activo' => 1,
							'OR' => array(
								array('Categoria.id' => $categoria['Categoria']['id']),
							//	array('Categoria.parent_id' => $categoria['Categoria']['id'])
							)
						),
						'fields' => array(
							'Producto.id'
						),
						'recursive' => -1,
						'joins' => array(
							array(
								'table' => 'sitio_stocks',
								'alias' => 'Stock',
								'type' => 'INNER',
								'conditions' => array(
									'Stock.producto_id = Producto.id'
								)
							),
							array(
								'table' => 'sitio_categorias',
								'alias' => 'Categoria',
								'type' => 'INNER',
								'conditions' => array(
									'Categoria.id = Producto.categoria_id'
								)
							)
						)
					);
					if ($verificacrEstilo = $this->Producto->find('first',$options))
					{
						$filtro['options'][] = array(
							'name' => $estiloName,
							'value' => $estiloAlias
						);
					}
				}
				$filtros[] = $filtro;
			}

			$options = array(
				'conditions' => array(
					'Talla.cantidad >=' => 1
				),
				'fields' => array(
					'Talla.talla',
					'Talla.talla'
				),
				'recursive' => -1,
				'joins' => array(
					0 => array(
						'table' => 'sitio_productos',
						'alias' => 'Producto',
						'type' => 'INNER',
						'conditions' => array(
							'Producto.id = Talla.producto_id',
							'Producto.activo' => 1,
							'Producto.outlet' => 0,
							'Producto.categoria_id' => $categoria['Categoria']['id'],
							'NOT' => array(
								array('Producto.foto' => ''),
								array('Producto.foto' => null),
							)
						)
					)
				),
				'order' => array(
					'Talla.talla' => 'ASC'
				)
			);
			$descripcion_color ='';
			if (isset($this->params['url']['color']) && $this->params['url']['color'])
			{
				$descripcion_color = $this->params['url']['color'];
				if ($primario = $this->Producto->Color->Primario->findBySlug($this->params['url']['color']))
				{
					$options['joins'][1] = array(
						'table' => 'sitio_colores',
						'alias' => 'Color',
						'type' => 'INNER',
						'conditions' => array(
							'Color.id = Producto.color_id',
							'OR' => array(
								array('Color.primario_id' => $primario['Primario']['id']),
								array('Color.secundario_id' => $primario['Primario']['id'])
							)
						)
					);
				}
			}
			if (isset($this->params['url']['style']) && $this->params['url']['style'])
			{
				$options['joins'][0]['conditions']['Producto.grupo LIKE'] = '%['.$this->params['url']['style'].']%';
				if (isset($this->params['url']['title']) && $this->params['url']['title'])
				{
					$titulo = urldecode($this->params['url']['title']);
				}
			}
			if ($tallas = $this->Producto->Talla->find('list',$options))
			{
				$filtro = array(
					'name' => 'Talla',
					'field' => 'talla',
					'options' => array()
				);
				foreach ($tallas as $talla)
				{
					$filtro['options'][] = array(
						'name' => $talla,
						'value' => $talla
					);
				}
				$filtros[] = $filtro;
			}

			$options = array(
				'fields' => array(
					'Primario.id',
					'Primario.nombre',
					'Primario.imagen',
					'Primario.slug'
				),
				'recursive' => -1,
				'joins' => array(
					0 => array(
						'table' => 'sitio_colores',
						'alias' => 'Color',
						'type' => 'INNER',
						'conditions' => array(
							'OR' => array(
								array('Color.primario_id = Primario.id'),
								array('Color.secundario_id = Primario.id')
							)
						)
					),
					1 => array(
						'table' => 'sitio_productos',
						'alias' => 'Producto',
						'type' => 'INNER',
						'conditions' => array(
							'Producto.color_id = Color.id',
							'Producto.activo' => 1,
							'Producto.outlet' => 0,
							'Producto.categoria_id' => $categoria['Categoria']['id'],
							'NOT' => array(
								array('Producto.foto' => ''),
								array('Producto.foto' => null),
							)
						)
					)
				),
				'order' => array('Primario.nombre' => 'ASC'),
				'group' => array('Primario.id')
			);
			if (isset($this->params['url']['talla']) && $this->params['url']['talla'])
			{
				$options['joins'][2] = array(
					'table' => 'sitio_tallas',
					'alias' => 'Talla',
					'type' => 'INNER',
					'conditions' => array(
						'Talla.producto_id = Producto.id',
						'Talla.talla' => $this->params['url']['talla']
					)
				);
			}
			if (isset($this->params['url']['style']) && $this->params['url']['style'])
			{
				$options['joins'][1]['conditions']['Producto.grupo LIKE'] = '%['.$this->params['url']['style'].']%';
			}
			if ($colores = $this->Producto->Color->Primario->find('all',$options))
			{
				$filtro = array(
					'name' => 'Color',
					'field' => 'color',
					'options' => array()
				);
				foreach ($colores as $color)
				{
					$filtro['options'][] = array(
						'name' => $color['Primario']['nombre'],
						'value' => $color['Primario']['slug'],
						'icon' => $color['Primario']['imagen']['cubo']
					);
				}
				$filtros[] = $filtro;
			}

			$filtro = array(
				'name' => 'Ordenar por Precio',
				'field' => 'orden',
				'options' => array(
					array(
						'name' => 'De mayor a menor',
						'value' => 1
					),
					array(
						'name' => 'De menor a mayor',
						'value' => 2
					)
				)
			);

            $filtros[] = $filtro;
			Cache::set(array('duration' => '+10 minutes'));
			Cache::write('filtros_'.$slug, $filtros);
			}

		}
		$descripcion_color ='';
		if (isset($this->params['url']['color']) && $this->params['url']['color'])
		{
			$descripcion_color = $this->params['url']['color'];
		}
		$descripcion = 'Compra la colección de Skechers '.$categoria['Categoria']['alias'].' y enfrenta este año junto a todo el estilo y comodidad que mereces. Click acá para llevarte el modelo que más te guste';
		if($descripcion_color !='')
			$descripcion = 'Renueva tus zapatillas Skechers '.$categoria['Categoria']['alias'].' y combina tu outfit con el '.$descripcion_color.' que tan bien te queda. Compra ahora y disfruta de todo el estilo y comodidad en cada paso';
		$canonical ='https://www.skechers.cl/catalogo/'.$slug;
		$this->set(compact('productos','otros','cont','filtros','titulo','titulo_otros','otros_productos','canonical','descripcion','categoria','buscador'));


	}



	function outlet()
	{
		$categoria=array();
		if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
		{
			$options = array(
				'conditions' => array(
					'Categoria.slug' => $this->params['url']['categoria']
				),
				'fields' => array(
					'Categoria.id',
					'Categoria.nombre',
					'Categoria.alias'
				)
			);
			$categoria = $this->Producto->Categoria->find('first',$options);
		}
		$options = array(
			'conditions' => array(
				'Producto.activo' 	=> 1,
				'Producto.outlet' 	=> 1,
				'NOT' => array(
					array('Producto.foto' => ''),
					array('Producto.foto' => null),
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
					)
				),
				1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'	=> array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)
				)
			),
			'order'	=> array(
				'Producto.orden DESC',
				'Producto.codigo ASC'
			),
			'group' => array('Producto.id')
		);

		if ($categoria)
			$options['conditions']['Producto.categoria_id'] = $categoria['Categoria']['id'];

		if (isset($this->params['url']['talla']) && $this->params['url']['talla'])
		{
			$options['joins'][1] = array(
				'table' => 'sitio_tallas',
				'alias' => 'FiltroTalla',
				'type' => 'INNER',
				'conditions' => array(
					'FiltroTalla.producto_id = Producto.id',
					'FiltroTalla.cantidad >=' => $this->stock_seguridad,
					'FiltroTalla.talla' => $this->params['url']['talla']
				)
			);
		}
		if (isset($this->params['url']['color']) && $this->params['url']['color'])
		{
			$primario = $this->Producto->Color->Primario->findBySlug($this->params['url']['color']);
			$options['joins'][2] = array(
				'table' => 'sitio_colores',
				'alias' => 'FiltroColor',
				'type' => 'INNER',
				'conditions' => array(
					'FiltroColor.id = Producto.color_id',
					'OR' => array(
						array('FiltroColor.primario_id' => $primario['Primario']['id']),
						array('FiltroColor.secundario_id' => $primario['Primario']['id'])
					)
				)
			);
		}
		if (isset($this->params['url']['style']) && $this->params['url']['style'])
		{
			$options['conditions']['Producto.grupo LIKE'] = '%['.$this->params['url']['style'].']%';
		}

		if(isset($this->params['url']['orden']) && $o = $this->params['url']['orden'])
		{
			if ($o==1)
				$options['order'] = array('Producto.precio' => 'DESC');
			elseif ($o==2)
				$options['order'] = array('Producto.precio' => 'ASC');
			elseif ($o==3)
				$options['order'] = array('Producto.nombre' => 'ASC');
			elseif ($o==4)
				$options['order'] = array('Producto.codigo' => 'ASC');
		}

		$productos = $this->Producto->find('all',$options);
		$cont = count($productos);

		$filtros = $otros = array();
		$titulo = 'Outlet';

		if ($categoria && in_array($categoria['Categoria']['id'],array(1,2,3,4)))
		{
			if (in_array($categoria['Categoria']['id'],array(1,2)))
				$titulo.= ' <small><i class="fa fa-angle-double-right"></i> '.$categoria['Categoria']['alias'];
			else
				$titulo.= ' <small><i class="fa fa-angle-double-right"></i> '.Inflector::singularize($categoria['Categoria']['alias']);
		}

		$options = array(
			'conditions' => array(
				'Categoria.id' => array(1,2,3,4)
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre',
				'Categoria.alias',
				'Categoria.slug'
			),
			'recursive' => -1
		);
		// filtro categorias
		if ($categorias = $this->Producto->Categoria->find('all',$options))
		{
			$filtro = array(
				'name' => 'Género',
				'field' => 'categoria',
				'options' => array()
			);
			foreach ($categorias as $_categoria)
			{
				$filtro['options'][] = array(
					'name' => (($_categoria['Categoria']['alias']) ? $_categoria['Categoria']['alias']:$_categoria['Categoria']['nombre']),
					'value' => $_categoria['Categoria']['slug']
				);
			}
			$filtros[] = $filtro;
		}
		// filtro tallas
		if ($categoria && in_array($categoria['Categoria']['id'],array(1,2,3,4)))
		{
			$options = array(
				'conditions' => array(
					'Talla.cantidad >=' => 1
				),
				'fields' => array(
					'Talla.talla',
					'Talla.talla'
				),
				'recursive' => -1,
				'joins' => array(
					0 => array(
						'table' => 'sitio_productos',
						'alias' => 'Producto',
						'type' => 'INNER',
						'conditions' => array(
							'Producto.id = Talla.producto_id',
							'Producto.activo' => 1,
							'Producto.outlet' => 1,
							'Producto.categoria_id' => $categoria['Categoria']['id'],
							'NOT' => array(
								array('Producto.foto' => ''),
								array('Producto.foto' => null),
							)
						)
					)
				),
				'order' => array(
					'Talla.talla' => 'ASC'
				)
			);
			if (isset($this->params['url']['color']) && $this->params['url']['color'])
			{
				if ($primario = $this->Producto->Color->Primario->findBySlug($this->params['url']['color']))
				{
					$options['joins'][1] = array(
						'table' => 'sitio_colores',
						'alias' => 'Color',
						'type' => 'INNER',
						'conditions' => array(
							'Color.id = Producto.color_id',
							'OR' => array(
								array('Color.primario_id' => $primario['Primario']['id']),
								array('Color.secundario_id' => $primario['Primario']['id'])
							)
						)
					);
				}
			}
			if ($tallas = $this->Producto->Talla->find('list',$options))
			{
				$filtro = array(
					'name' => 'Talla',
					'field' => 'talla',
					'options' => array()
				);
				foreach ($tallas as $talla)
				{
					$filtro['options'][] = array(
						'name' => $talla,
						'value' => $talla
					);
				}
				$filtros[] = $filtro;
			}

			$options = array(
				'fields' => array(
					'Primario.id',
					'Primario.nombre',
					'Primario.imagen',
					'Primario.slug'
				),
				'recursive' => -1,
				'joins' => array(
					0 => array(
						'table' => 'sitio_colores',
						'alias' => 'Color',
						'type' => 'INNER',
						'conditions' => array(
							'OR' => array(
								array('Color.primario_id = Primario.id'),
								array('Color.secundario_id = Primario.id')
							)
						)
					),
					1 => array(
						'table' => 'sitio_productos',
						'alias' => 'Producto',
						'type' => 'INNER',
						'conditions' => array(
							'Producto.color_id = Color.id',
							'Producto.activo' => 1,
							'Producto.outlet' => 1,
							'Producto.categoria_id' => $categoria['Categoria']['id'],
							'NOT' => array(
								array('Producto.foto' => ''),
								array('Producto.foto' => null),
							)
						)
					)
				),
				'order' => array('Primario.nombre' => 'ASC'),
				'group' => array('Primario.id')
			);
			if (isset($this->params['url']['talla']) && $this->params['url']['talla'])
			{
				$options['joins'][2] = array(
					'table' => 'sitio_tallas',
					'alias' => 'Talla',
					'type' => 'INNER',
					'conditions' => array(
						'Talla.producto_id = Producto.id',
						'Talla.talla' => $this->params['url']['talla']
					)
				);
			}
			if ($colores = $this->Producto->Color->Primario->find('all',$options))
			{
				$filtro = array(
					'name' => 'Color',
					'field' => 'color',
					'options' => array()
				);
				foreach ($colores as $color)
				{
					$filtro['options'][] = array(
						'name' => $color['Primario']['nombre'],
						'value' => $color['Primario']['slug'],
						'icon' => $color['Primario']['imagen']['cubo']
					);
				}
				$filtros[] = $filtro;
			}
		}
		// filtro ordenar
		$filtro = array(
			'name' => 'Ordenar por Precio',
			'field' => 'orden',
			'options' => array(
				array(
					'name' => 'De mayor a menor',
					'value' => 1
				),
				array(
					'name' => 'De menor a mayor',
					'value' => 2
				)
			)
		);
		$filtros[] = $filtro;

		$this->set(compact('productos','cont','ordenar','filtros','titulo'));
	}

	function escolar()
	{
		$categoria=array();
		if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
		{
			$options = array(
				'conditions' => array(
					'Categoria.slug' => $this->params['url']['categoria']
				),
				'fields' => array(
					'Categoria.id',
					'Categoria.nombre',
					'Categoria.alias'
				)
			);
			$categoria = $this->Producto->Categoria->find('first',$options);
		}
		$options = array(
			'conditions' => array(
				'Producto.activo'	=> 1,
				'Producto.escolar'	=> 1,
				'NOT' => array(
					array('Producto.foto' => ''),
					array('Producto.foto' => null),
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
					)
				),
				1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'	=> array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)
				)
			),
			'order'	=> array(
				'Producto.orden DESC',
				'Producto.codigo ASC'
			),
			'group' => array('Producto.id')
		);

		if ($categoria)
		{
			$options['conditions']['Producto.categoria_id'] = $categoria['Categoria']['id'];
		}

		if (isset($this->params['url']['talla']) && $this->params['url']['talla'])
		{
			$options['joins'][1] = array(
				'table' => 'sitio_tallas',
				'alias' => 'FiltroTalla',
				'type' => 'INNER',
				'conditions' => array(
					'FiltroTalla.producto_id = Producto.id',
					'FiltroTalla.cantidad >=' => $this->stock_seguridad,
					'FiltroTalla.talla' => $this->params['url']['talla']
				)
			);
		}
		if (isset($this->params['url']['color']) && $this->params['url']['color'])
		{
			$primario = $this->Producto->Color->Primario->findBySlug($this->params['url']['color']);
			$options['joins'][2] = array(
				'table' => 'sitio_colores',
				'alias' => 'FiltroColor',
				'type' => 'INNER',
				'conditions' => array(
					'FiltroColor.id = Producto.color_id',
					'OR' => array(
						array('FiltroColor.primario_id' => $primario['Primario']['id']),
						array('FiltroColor.secundario_id' => $primario['Primario']['id'])
					)
				)
			);
		}
		if (isset($this->params['url']['style']) && $this->params['url']['style'])
		{
			$options['conditions']['Producto.grupo LIKE'] = '%['.$this->params['url']['style'].']%';
		}

		if(isset($this->params['url']['orden']) && $o = $this->params['url']['orden'])
		{
			if ($o==1)
				$options['order'] = array('Producto.precio' => 'DESC');
			elseif ($o==2)
				$options['order'] = array('Producto.precio' => 'ASC');
			elseif ($o==3)
				$options['order'] = array('Producto.nombre' => 'ASC');
			elseif ($o==4)
				$options['order'] = array('Producto.codigo' => 'ASC');
		}

		if (isset($this->params['url']['gender']) && $this->params['url']['gender'])
		{
			if ($this->params['url']['gender'] == 'boys')
			{
				$options['conditions']['Categoria.id'] = array(2,3);
			}
			else
			{
				$options['conditions']['Categoria.id'] = array(1,4);
			}
		}

		$productos = $this->Producto->find('all',$options);
		$cont = count($productos);

		$filtros = $otros = array();
		$titulo = 'Escolares';

		if ($categoria && in_array($categoria['Categoria']['id'],array(1,2,3,4)))
		{
			if (in_array($categoria['Categoria']['id'],array(1,2)))
				$titulo.= ' <small><i class="fa fa-angle-double-right"></i> '.$categoria['Categoria']['alias'];
			else
				$titulo.= ' <small><i class="fa fa-angle-double-right"></i> '.Inflector::singularize($categoria['Categoria']['alias']);
		}
		elseif (isset($this->params['url']['gender']) && $this->params['url']['gender'])
		{
			$titulo.= ' <small><i class="fa fa-angle-double-right"></i> ';
			if ($this->params['url']['gender'] == 'boys')
			{
				$titulo.= "Boy's Shoes";
			}
			else
			{
				$titulo.= "Girl's Shoes";
			}
		}

		$options = array(
			'conditions' => array(
				'Categoria.id' => array(1,2,3,4)
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre',
				'Categoria.alias',
				'Categoria.slug'
			),
			'recursive' => -1
		);
		// filtro categorias
		if ($categorias = $this->Producto->Categoria->find('all',$options))
		{
			$filtro = array(
				'name' => 'Género',
				'field' => 'categoria',
				'options' => array()
			);
			foreach ($categorias as $_categoria)
			{
				$filtro['options'][] = array(
					'name' => (($_categoria['Categoria']['alias']) ? $_categoria['Categoria']['alias']:$_categoria['Categoria']['nombre']),
					'value' => $_categoria['Categoria']['slug']
				);
			}
			$filtros[] = $filtro;
		}

		$this->set(compact('productos','cont','ordenar','filtros','titulo'));
	}

	function grupos($slug = null, $imagen = null)
	{
		if (! $slug)
			$this->redirect(array('action' => 'inicio'));

		$slug = urldecode($slug);
		if (strrpos($slug,','))
			$slug = explode(',',$slug);
		

		$filtroEspecial = array(
			'activar' => false,
			'categorias' => array(),
			'filtro_categorias' => false,
			'otros' => false,
			'titulo' => (isset($this->params['pass'][1]) && $this->params['pass'][1] ? $this->params['pass'][1] : false),
			'titulo_otros' => false
		);
		$descripcion ='';

		if ( $slug == 'performance' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(1,2),
				'filtro_categorias' => true,
				'otros' => true,
				'titulo' => 'Skechers Performance',
				'titulo_otros' => 'Otros modelos Skechers Performance'
			));
		}
		elseif ( $slug == 'gowalk3' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(1,2),
				'filtro_categorias' => true,
				'otros' => true
			));
		}
		elseif ( $slug == 'coleccion_10' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(1,2,3,4),
				'filtro_categorias' => true,
				'otros' => true
			));
		}
		elseif ( $slug == 'winter_2018' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(1,2,3,4),
				'filtro_categorias' => true,
				'otros' => false
			));
		}
		elseif ( $slug == 'sptring_2018' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(1,2,3,4),
				'filtro_categorias' => true,
				'otros' => false
			));
		}
			elseif ( $slug == 'semanasanta' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(1,2,3,4),
				'filtro_categorias' => true,
				'otros' => false
			));
		}

		elseif ( $slug == 'kids_sport_memoryfoam' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(3,4),
				'filtro_categorias' => true,
				'otros' => true
			));
		}
		elseif ( $slug == 'kids_memory_foam' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(3,4),
				'filtro_categorias' => true,
				'otros' => true
			));
		}
		elseif ( $slug == 'sport' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(1,2),
				'filtro_categorias' => true,
			));
		}
		elseif ( $slug == 'summer_2017' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(1,2,3,4),
				'filtro_categorias' => true,
			));
		}
		elseif ( $slug == '2018_q1' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(1,2,3,4),
				'filtro_categorias' => true,
			));
		}
		elseif ( $slug == 'kids' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(3,4),
				'filtro_categorias' => true,
				'otros' => true
			));
		}	elseif ( $slug == 'kids' )
		{
			$filtroEspecial = array_merge($filtroEspecial, array(
				'activar' => true,
				'categorias' => array(3,4),
				'filtro_categorias' => true,
				'otros' => true
			));
		}else{
			$this->loadModel('Estilo');
			$estilos = $this->Estilo->find('first', array('conditions' => array('destacado' => 1, 'alias' => $slug)));
			foreach ($estilos as $estilo)
			{
				if($estilo['alias'] == $slug)
				{
					$imagen = $estilo['imagen']['path'];
					$descripcion ='Descubre Skechers '.$estilo['nombre'].' y sorpréndete con todos los modelos, diseño y comodidad que son tendencia para este año. ';
					$filtroEspecial = array_merge($filtroEspecial, array(
					'activar' => true,
					'categorias' => array(1,2,3,4),
					'filtro_categorias' => true,
					'filtro_tallas' => array(21,22,23,24,25,26),
					'imagen' => $estilo['imagen']['path']
					));
				}
				if($estilo['categorias']!='')
				{
					$categorias = explode(",",$estilo['categorias']);
					$filtroEspecial['categorias'] = $categorias;
				}



			}
		}

		$cache_name = $slug;
		if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
		{
			$cache_name = $cache_name.'_'.$this->params['url']['categoria'];
		}
	//	prx($this->params);
		$options_tallas = array();
		if (isset($this->params['url']['talla']) && $this->params['url']['talla'])
		{
			$inicio = false;
			if(!$estilo)
				$talla = $this->params['url']['talla'];
			$estilo = false;
			$options_tallas = array(
				'table' => 'sitio_tallas',
				'alias' => 'FiltroTalla',
				'type' => 'INNER',
				'conditions' => array(
					'FiltroTalla.producto_id = Producto.id',
					'FiltroTalla.cantidad >=' => $this->stock_seguridad,
					'FiltroTalla.talla' => $this->params['url']['talla']
				)
			);
			$cache_name = $cache_name.'_'.$this->params['url']['talla'];
		}
		/*if (isset($this->params['url']['color']) && $this->params['url']['color'])
		{
			$inicio = false;
			$talla = false;
			$estilo = false;
			$primario = $this->Producto->Color->Primario->findBySlug($this->params['url']['color']);
			$options['joins'][2] = array(
				'table' => 'sitio_colores',
				'alias' => 'FiltroColor',
				'type' => 'INNER',
				'conditions' => array(
					'FiltroColor.id = Producto.color_id',
					'OR' => array(
						array('FiltroColor.primario_id' => $primario['Primario']['id']),
						array('FiltroColor.secundario_id' => $primario['Primario']['id'])
					)
				)
			);
		}
		*/


		if(isset($this->params['url']['orden']) && $o = $this->params['url']['orden'])
		{
			$inicio = false;
			$talla = false;
			$estilo = false;
			if ($o==1)
				$options['order'] = array('Producto.precio' => 'DESC');
			elseif ($o==2)
				$options['order'] = array('Producto.precio' => 'ASC');
			elseif ($o==3)
				$options['order'] = array('Producto.nombre' => 'ASC');
			elseif ($o==4)
				$options['order'] = array('Producto.codigo' => 'ASC');
			$cache_name = $cache_name.'_'.$this->params['url']['orden'];
		}


		Cache::set(array('duration' => '+10 minutes'));
		$productos = Cache::read('productos_grupos_'.$cache_name);
		if($productos == false)
		{
			$options = array(
				'conditions' => array(
					'Producto.activo' => 1,
         			'Producto.precio >' 	=> 3000,
					'NOT' => array(
						array('Producto.foto' => ''),
						array('Producto.foto' => null),
					)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
					)
				),
				1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'	=> array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)
				)
			),
			'order'	=> array(
				'Producto.categoria_id ASC',
				'Producto.coleccion_id DESC',
				'Producto.orden DESC',
				'Producto.codigo ASC'
			),
			'group' => array('Producto.id')
		);
			if(!empty($options_tallas))
			{
				$options['joins'][1]= $options_tallas;
			}
		if (is_array($slug))
		{
			foreach ($slug as $grupo)
			{
				$options['conditions']['AND'][]['Producto.grupo LIKE'] = '%['.$grupo.']%';
			}
		}
		else
		{
			if ($slug == 'performance')
			{
				$options['conditions']['OR'] = array(
					array('Producto.grupo LIKE' => '%[running]%'),
					array('Producto.grupo LIKE' => '%[gowalk]%'),
					array('Producto.grupo LIKE' => '%[golf]%')
				);
			}
			elseif ($slug == 'kids')
			{
				$options['conditions']['Producto.categoria_id'] = array(3,4,16);
			}
			else
			{
				$options['conditions']['Producto.grupo LIKE'] = '%['.$slug.']%';
			}
		}

		if(isset($this->params['url']['orden']) && $o = $this->params['url']['orden'])
		{
			if ($o==1)
				$options['order'] = array('Producto.precio' => 'DESC');
			elseif ($o==2)
				$options['order'] = array('Producto.precio' => 'ASC');
			elseif ($o==3)
				$options['order'] = array('Producto.nombre' => 'ASC');
			elseif ($o==4)
				$options['order'] = array('Producto.codigo' => 'ASC');
		}

		if ( isset($filtroEspecial['activar']) && $filtroEspecial['activar'] )
		{
			if ( isset($filtroEspecial['categorias']) && $filtroEspecial['categorias'] )
			{
				$options['conditions']['Categoria.id'] = $filtroEspecial['categorias'];// limita llamado de productos
			}

			if ( isset($filtroEspecial['filtro_categorias']) && $filtroEspecial['filtro_categorias'] )
			{
				// filtro por categoria
				$_options = array(
					'conditions' => array(
						'Categoria.publico' => 1
					),
					'fields' => array(
						'Categoria.id',
						'Categoria.nombre',
						'Categoria.alias',
						'Categoria.slug'
					),
					'recursive' => -1
				);
				if ( isset($filtroEspecial['categorias']) && $filtroEspecial['categorias'] )
				{
					$_options['conditions']['Categoria.id'] = $filtroEspecial['categorias'];
				}

				if ($categorias = $this->Producto->Categoria->find('all',$_options))
				{
					$filtro = array(
						'name' => 'Género',
						'field' => 'categoria',
						'options' => array()
					);
					foreach ($categorias as $categoria)
					{
						$filtro['options'][] = array(
							'name' => (($categoria['Categoria']['alias']) ? $categoria['Categoria']['alias']:$categoria['Categoria']['nombre']),
							'value' => $categoria['Categoria']['slug']
						);
					}
					$filtros[] = $filtro;
				}
			}

			if ( isset($filtroEspecial['otros']) && $filtroEspecial['otros'] )
			{
				// filtrado por categoria
				if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
				{
					$_options = $options;
					$_options['conditions']['NOT'][]['Categoria.slug'] = $options['conditions']['Categoria.slug'] = $this->params['url']['categoria'];
					$otros = $this->Producto->find('all',$_options);
					$this->set(compact('otros'));
				}
			}

			if ( isset($filtroEspecial['titulo']) && $filtroEspecial['titulo'] )
			{
				$titulo = $filtroEspecial['titulo'];
				$this->set(compact('titulo'));
			}

			if ( isset($filtroEspecial['titulo_otros']) && $filtroEspecial['titulo_otros'] )
			{
				$titulo_otros = $filtroEspecial['titulo_otros'];
				$this->set(compact('titulo_otros'));
			}
		}

		if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
		{
			$options['conditions']['Categoria.slug'] = explode(',',$this->params['url']['categoria']);
		}

		$productos = $this->Producto->find('all',$options);
		Cache::write('productos_grupos_'.$cache_name,$productos);



	}
			$options = array(
				'conditions' => array(
					'Stock.cantidad >=' => 1
				),
				'fields' => array(
					'Stock.talla',
					'Stock.talla'
				),
				'recursive' => -1,
				'joins' => array(
					0 => array(
						'table' => 'sitio_productos',
						'alias' => 'Producto',
						'type' => 'INNER',
						'conditions' => array(
							'Producto.id = Stock.producto_id',
							'Producto.activo' => 1,
							'Producto.grupo like ' => '%['.$slug.']%',
							'NOT' => array(
								array('Producto.foto' => ''),
								array('Producto.foto' => null),
							)
						)
					)
				),
				'order' => array(
					'Stock.talla' => 'ASC'
				)
			);
		/*	if (isset($this->params['url']['color']) && $this->params['url']['color'])
			{
				if ($primario = $this->Producto->Color->Primario->findBySlug($this->params['url']['color']))
				{
					$options['joins'][1] = array(
						'table' => 'sitio_colores',
						'alias' => 'Color',
						'type' => 'INNER',
						'conditions' => array(
							'Color.id = Producto.color_id',
							'OR' => array(
								array('Color.primario_id' => $primario['Primario']['id']),
								array('Color.secundario_id' => $primario['Primario']['id'])
							)
						)
					);
				}
			}
			*/
			//($options);
		
			if ($tallas = $this->Producto->Stock->find('list',$options))
			{

				$filtro = array(
					'name' => 'Talla',
					'field' => 'talla',
					'options' => array()
				);
				foreach ($tallas as $talla)
				{
					$filtro['options'][] = array(
						'name' => $talla,
						'value' => $talla
					);
				}
				$filtros[] = $filtro;
			}

		$cont = count($productos);
			$filtro = array(
			'name' => 'Ordenar por Precio',
			'field' => 'orden',
			'options' => array(
				array(
					'name' => 'De mayor a menor',
					'value' => 1
				),
				array(
					'name' => 'De menor a mayor',
					'value' => 2
				)
			)
		);
					$filtros[] = $filtro;

		if($descripcion!='')
			$this->set(compact('descripcion'));

		$this->set(compact('productos','cont','filtros','titulo','imagen'));
		$this->render('catalogo');
	}

	function buscar()
	{
		if (! isset($this->data['Producto']['buscar']))
		{
			$this->Session->setFlash(__('<i class="fa fa-warning"></i> Busqueda invalida', true));
			$this->redirect(array('action' => 'inicio'));
		}
		if (! $this->data['Producto']['buscar'])
		{
			$this->Session->setFlash(__('<i class="fa fa-warning"></i> Busqueda invalida', true));
			$this->redirect(array('action' => 'inicio'));
		}

		$options = array(
			'conditions' => array(
				'Producto.activo' => 1,
				'NOT' => array(
					array('Producto.foto' => ''),
					array('Producto.foto' => null),
				),
				'OR'	=> array(
					array('Producto.nombre LIKE' => "%{$this->data['Producto']['buscar']}%"),
					array('Producto.slug LIKE' => "%{$this->data['Producto']['buscar']}%"),
					array('Producto.codigo LIKE' => "%{$this->data['Producto']['buscar']}%"),
					array('Producto.codigo_completo LIKE' => "%{$this->data['Producto']['buscar']}%"),
					array('Producto.grupo LIKE' => "%{$this->data['Producto']['buscar']}%")
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
					)
				),
				1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'	=> array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)
				)
			),
			'order'	=> array(
				'Producto.orden DESC',
				'Producto.codigo ASC'
			),
			'group' => array('Producto.id')
		);
		if (! $productos = $this->Producto->find('all', $options))
		{
			$this->Session->setFlash(__('<i class="fa fa-warning"></i> No hay resultados para esta busqueda.', true));
			$this->redirect(array('action' => 'inicio'));
		}
		$cont = count($productos);
		$this->set(compact('productos','cont'));
		$this->render('outlet');
	}

	function sale()
	{
		$filtroEspecial = array(
			'activar' => true,
			'categorias' => array(1,2,3,4),
			'filtro_categorias' => true,
			'otros' => false,
			'titulo' => 'Sale',
			'titulo_otros' => false
		);

		$options = array(
			'conditions' => array(
				'Producto.activo' => 1,
				'Producto.oferta' => 1,
				'Producto.precio > Producto.precio_oferta',
				'NOT' => array(
					array('Producto.foto' => ''),
					array('Producto.foto' => null),
					array('Producto.outlet' => 1),
					array('Producto.escolar' => 1),
				),
				'OR' => array(
					array('Categoria.id' => array(1,2,3,4)),
				//	array('Categoria.parent_id' => array(1,2,3,4)),
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores',

				'Categoria.id',
				'Categoria.parent_id'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
					)
				),
				1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'	=> array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)
				)
			),
			'order'	=> array(
				'Producto.categoria_id ASC',
				'Producto.orden DESC',
				'Producto.codigo ASC'
			),
			'group' => array('Producto.id')
		);

		if(isset($this->params['url']['orden']) && $o = $this->params['url']['orden'])
		{
			if ($o==1)
				$options['order'] = array('Producto.precio_oferta' => 'DESC');
			elseif ($o==2)
				$options['order'] = array('Producto.precio_oferta' => 'ASC');
			elseif ($o==3)
				$options['order'] = array('Producto.nombre' => 'ASC');
			elseif ($o==4)
				$options['order'] = array('Producto.codigo' => 'ASC');
		}

		if ( isset($filtroEspecial['activar']) && $filtroEspecial['activar'] )
		{
			if ( isset($filtroEspecial['categorias']) && $filtroEspecial['categorias'] )
			{
				$options['conditions']['Categoria.id'] = $filtroEspecial['categorias'];// limita llamado de productos
			}

			if ( isset($filtroEspecial['filtro_categorias']) && $filtroEspecial['filtro_categorias'] )
			{
				// filtro por categoria
				$_options = array(
					'conditions' => array(
						'Categoria.publico' => 1
					),
					'fields' => array(
						'Categoria.id',
						'Categoria.nombre',
						'Categoria.alias',
						'Categoria.slug'
					),
					'recursive' => -1
				);
				if ( isset($filtroEspecial['categorias']) && $filtroEspecial['categorias'] )
				{
					$_options['conditions']['Categoria.id'] = $filtroEspecial['categorias'];
				}

				if ($categorias = $this->Producto->Categoria->find('all',$_options))
				{
					$filtro = array(
						'name' => 'Género',
						'field' => 'categoria',
						'options' => array()
					);
					foreach ($categorias as $categoria)
					{
						$filtro['options'][] = array(
							'name' => (($categoria['Categoria']['alias']) ? $categoria['Categoria']['alias']:$categoria['Categoria']['nombre']),
							'value' => $categoria['Categoria']['slug']
						);
					}
					$filtros[] = $filtro;
				}
			}

			if ( isset($filtroEspecial['otros']) && $filtroEspecial['otros'] )
			{
				// filtrado por categoria
				if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
				{
					$_options = $options;
					$_options['conditions']['NOT'][]['Categoria.slug'] = $options['conditions']['Categoria.slug'] = $this->params['url']['categoria'];
					$otros = $this->Producto->find('all',$_options);
					$this->set(compact('otros'));
				}
			}

			if ( isset($filtroEspecial['titulo']) && $filtroEspecial['titulo'] )
			{
				$titulo = $filtroEspecial['titulo'];
				$this->set(compact('titulo'));
			}

			if ( isset($filtroEspecial['titulo_otros']) && $filtroEspecial['titulo_otros'] )
			{
				$titulo_otros = $filtroEspecial['titulo_otros'];
				$this->set(compact('titulo_otros'));
			}

			$filtro = array(
				'name' => 'Ordenar por Precio',
				'field' => 'orden',
				'options' => array(
					array(
						'name' => 'De mayor a menor',
						'value' => 1
					),
					array(
						'name' => 'De menor a mayor',
						'value' => 2
					)
				)
			);
			$filtros[] = $filtro;
		}

		if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
		{
			$options['conditions']['Categoria.slug'] = explode(',',$this->params['url']['categoria']);
		}

		$productos = $this->Producto->find('all',$options);


		if (! isset($this->params['url']['orden']) && ! isset($this->params['url']['categoria']) )
		{
			$ordenados = array();
			$orden = array(1,2,4,3);
			foreach ( $orden as $categoriaId )
			{
				foreach ($productos as $index => $producto)
				{
					if ( $producto['Categoria']['id'] == $categoriaId ||  $producto['Categoria']['parent_id'] == $categoriaId )
					{
						array_push($ordenados, $producto);
						unset($productos[$index]);
					}
				}
			}
			$productos = $ordenados;
		}
		$cont = count($productos);
		$canonical ='https://www.skechers.cl/sale';
		$this->set(compact('productos','cont','filtros','titulo','canonical'));
		$this->render('catalogo');
	}

	function notfound() { }

	function view($slug = null)
	{
		if ( ! $slug )
			$this->redirect(array('action' => 'inicio'));
		$options = array(
			'conditions' => array(
				'Producto.activo' => 1,
				'Producto.slug' => $slug,
				'NOT' => array(
					array('Producto.foto' => null),
					array('Producto.foto' => '')
				),
			),
			'fields' => array(
				'Producto.id',
				'Producto.sku',
				'Producto.nombre',
				'Producto.categoria_id',
				'Producto.foto',
				'Producto.imagen_campana',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.codigo_completo',
				'Producto.precio',
				'Producto.oferta',
				'Producto.precio_oferta',
				'Producto.slug',
				'Producto.new',
				'Producto.descripcion',
				'Producto.ficha',
				'Producto.escolar',
				'Producto.outlet',
				'Producto.stock_seguridad',
				'Producto.tipo',
				'Producto.excluir_descuento',
				'Categoria.id',
				'Categoria.parent_id',
				'Categoria.nombre',
				'Categoria.desde',
				'Categoria.hasta',
				'Categoria.medios',
				'Categoria.sexo_id',
				'Categoria.publico',
				'Categoria.slug',
				'Categoria.alias',
				'Marca.nombre',

				'CategoriaPadre.id',
				'CategoriaPadre.nombre',
				'CategoriaPadre.desde',
				'CategoriaPadre.hasta',
				'CategoriaPadre.medios',
				'CategoriaPadre.slug',
				'CategoriaPadre.alias',
			),
			'contain'	=> array(
				'Color'	=> array(
					'fields'	=> array(
						'Color.id',
						'Color.nombre',
						'Color.codigo',
						'Color.primario_id'
					)
				),
				'Galeria'	=> array(
					'order' => array('Galeria.orden' => 'ASC'),
					'fields'	=> array(
						'Galeria.id',
						'Galeria.imagen',
						'Galeria.producto_id'
					),
					'limit' => 5
				),
				'Marca',
				'Stock' => array(
					'conditions' => array('Stock.cantidad >=' => $this->stock_seguridad),
					'fields' => array(
						'Stock.id',
						'Stock.talla',
						'Stock.cantidad',
						'Stock.sku'
					),
					'order' => array('Stock.talla' => 'ASC')
				)
			),
			'joins' => array(
				array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1
					)
				),
				array(
					'table' => 'sitio_categorias',
					'alias' => 'CategoriaPadre',
					'type' => 'LEFT',
					'conditions' => array(
						'CategoriaPadre.id = Categoria.parent_id',
					)
				)
			)
		);
		if ( ! $producto = $this->Producto->find('first',$options) )
			$this->redirect(array('action' => 'inicio'));

		// verifica datos de subcategoria
		if (! $producto['Categoria']['desde'] && $producto['CategoriaPadre']['desde'])
			$producto['Categoria']['desde'] = $producto['CategoriaPadre']['desde'];
		if (! $producto['Categoria']['hasta'] && $producto['CategoriaPadre']['hasta'])
			$producto['Categoria']['hasta'] = $producto['CategoriaPadre']['hasta'];
		if (! $producto['Categoria']['medios'] && $producto['CategoriaPadre']['medios'])
			$producto['Categoria']['medios'] = $producto['CategoriaPadre']['medios'];

		// asociacion manual de productos por color
		$listas_especiales = array(
			1 => array(715, 716, 717, 725, 726, 728),
			2 => array(710, 711, 729, 730),
			3 => array(491, 492, 493, 494, 731, 732, 735, 736)
		);

		$colores = array();
		if ($listas_especiales)
		{
			foreach ($listas_especiales as $especiales)
			{
				if (in_array($producto['Producto']['id'], $especiales))
				{
					$options = array(
						'conditions' => array(
							'Producto.id' => $lista_especial,
							'Producto.activo' => 1,
							'NOT' => array(
								array('Producto.id' => $producto['Producto']['id']),
								array('Producto.foto' => null),
								array('Producto.foto' => '')
							),
						),
						'fields' => array(
							'Producto.id',
							'Producto.nombre',
							'Producto.categoria_id',
							'Producto.foto',
							'Producto.color_id',
							'Producto.codigo',
							'Producto.slug'
						),
						'contain' => array(
							'Color'	=> array(
								'fields'	=> array(
									'Color.id',
									'Color.nombre',
									'Color.codigo'
								)
							)
						),
						'joins' => array(
							array(
								'table' => 'sitio_categorias',
								'alias' => 'Categoria',
								'type' => 'INNER',
								'conditions' => array(
									'Categoria.id = Producto.categoria_id',
									'Categoria.publico' => 1
								)
							),
							array(
								'table' => 'sitio_stocks',
								'alias' => 'Stock',
								'type' => 'INNER',
								'conditions' => array(
									'Stock.producto_id = Producto.id',
									'Stock.cantidad >=' => $this->stock_seguridad
								)
							)
						),
						'group' => array(
							'Producto.id'
						)
					);
					$colores = $this->Producto->find('all',$options);
					break;
				}
			}
		}

		if (! $colores)
		{
			$options = array(
				'conditions' => array(
					'Producto.codigo'	=> $producto['Producto']['codigo'],
					'Producto.outlet' => $producto['Producto']['outlet'],
					'Producto.activo' => 1,
					'NOT' => array(
						array('Producto.id'	=> $producto['Producto']['id']),
						array('Producto.foto' => null),
						array('Producto.foto' => '')
					)
				),
				'fields' => array(
					'Producto.id',
					'Producto.nombre',
					'Producto.categoria_id',
					'Producto.foto',
					'Producto.color_id',
					'Producto.codigo',
					'Producto.slug'
				),
				'contain' => array(
					'Color'	=> array(
						'fields' => array(
							'Color.id',
							'Color.nombre',
							'Color.codigo'
						)
					)
				),
				'joins' => array(
					array(
						'table' => 'sitio_categorias',
						'alias' => 'Categoria',
						'type' => 'INNER',
						'conditions' => array(
							'Categoria.id = Producto.categoria_id',
							'Categoria.publico' => 1
						)
					),
					array(
						'table' => 'sitio_stocks',
						'alias' => 'Stock',
						'type' => 'INNER',
						'conditions' => array(
							'Stock.producto_id = Producto.id',
							'Stock.cantidad >=' => $this->stock_seguridad
						)
					)
				),
				'group' => array(
					'Producto.id'
				)
			);
			$colores = $this->Producto->find('all',$options);
		}

		$options = array(
			'conditions' => array(
				'OR' => array(
				//	array('Categoria.parent_id' => 58),
					array('Categoria.id' => array(59,60))
				),
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		);
		$categorias_ropa = $this->Producto->Categoria->find('list',$options);
		if (in_array($producto['Producto']['categoria_id'],$categorias_ropa))
		{
			$tallas = array(
				 'XS' => 0,
				'S' => 0,
				'M' => 0,
				'L' => 0,
				'XL' => 0,
				'XXL' => 0
			);
			if (isset($producto['Stock']) && $producto['Stock'])
			{
				foreach ($producto['Stock'] as $stock)
				{
					$talla = 0;
					 if ($stock['talla'] == 1)
					 	$talla='XS';
					 elseif ($stock['talla'] == 2)
						$talla='S';
					elseif ($stock['talla'] == 3)
						$talla='M';
					elseif ($stock['talla'] == 4)
						$talla='L';
					elseif ($stock['talla'] == 5)
						$talla='XL';
					elseif ($stock['talla'] == 6)
						$talla='XXL';
					if (! $talla)
						continue;

					$tallas[$talla] = array(
						'Talla' => array(
							'id' => $stock['id'],
							'talla' => $stock['talla'],
							'cantidad' => $stock['cantidad'],
							'sku'	=> $stock['sku']
						)
					);
				}
			}
		}
		else
		{
			/**
			 * SE GENERA ARREGLO VACIO CON EL RANGO DE TALLAS DISPONIBLES PARA LA CATEGORIA
			 * para productos tipo cribs el rando va desde 15 a 18
			 * para productos con codigo N el rango va desde 20 a 25
			 * para productos con codigo L el rango va desde 26 a 38
			 */
			$startTalla = $producto['Categoria']['desde'];
			$endTalla = $producto['Categoria']['hasta'];
			$categoriasKids = $this->Producto->Categoria->find('list',array(
				'conditions' => array(
					'Categoria.publico' => 1,
					'OR' => array(
						array('Categoria.id' => 16),
				//		array('Categoria.parent_id' => 16),
					)
				),
				'fields' => array(
					'Categoria.id',
					'Categoria.id'
				)
			));
			if (in_array($producto['Producto']['categoria_id'],$categoriasKids))
			{
				if (isset($producto['Producto']['tipo']) && in_array($producto['Producto']['tipo'],array('CRIBS')))
				{
					$startTalla = 15;
					$endTalla = 18;
				}
				elseif (substr($producto['Producto']['codigo'],-1,1) == 'N')
				{
					$startTalla = 20;
					$endTalla = 25;
				}
				elseif (substr($producto['Producto']['codigo'],-1,1) == 'L')
				{
					$startTalla = 26;
					$endTalla = 38;
				}
			}
			for ($talla=$startTalla;$talla <= $endTalla;$talla++)
			{
				$tallas[$talla] = array();
				if ($producto['Categoria']['medios']==1 && $talla < $producto['Categoria']['hasta'] )
					$tallas[$talla . '.5'] = array();
			}

			// LISTA DE TALLAS DISPONIBLES POR ZAPATILLA
			// REORDENA EL STOCK POR TALLA, PARA NO REPETIR STOCK DE DISTINTAS TIENDAS Y SACA ZAPATILLAS CON MENOS DE 3 UNIDADES DE STOCK
			$options = array(
				'conditions' => array(
					'OR' => array(
						array('Categoria.id' => 2),
				//		array('Categoria.parent_id' => 2)
					)
				),
				'fields' => array(
					'Categoria.id',
					'Categoria.id'
				)
			);
			$categoriasHombre = $this->Producto->Categoria->find('list',$options);
			foreach( $producto['Stock'] as $stock )
			{
				$talla = array();
				if (in_array($producto['Categoria']['id'],$categoriasHombre))
				{
					if (in_array($stock['talla'], array(45,46)))
					{
						$talla = array(
							'Talla' => array(
								'id' => $stock['id'],
								'talla' => $stock['talla'],
								'cantidad' => $stock['cantidad']
							)
						);
					}
					elseif (isset($tallas[$stock['talla']]) && $stock['cantidad'] >= $producto['Producto']['stock_seguridad'])
					{
						$talla = array(
							'Talla' => array(
								'id' => $stock['id'],
								'talla' => $stock['talla'],
								'cantidad' => $stock['cantidad'],
								'sku'	=> $stock['sku']
							)
						);
					}
				}
				else
				{
					if (isset($tallas[$stock['talla']]))
					{
						if ($stock['cantidad'] >= $producto['Producto']['stock_seguridad'])
						{
							$talla = array(
								'Talla' => array(
									'id' => $stock['id'],
									'talla' => $stock['talla'],
									'cantidad' => $stock['cantidad'],
									'sku'	=> $stock['sku']
								)
							);
						}
					}
				}

				// if (isset($tallas[$stock['talla']]))
				// {
				// 	if ($stock['cantidad'] >= $producto['Producto']['stock_seguridad'])
				// 	{
				// 		$talla = array(
				// 			'Talla' => array(
				// 				'id' => $stock['id'],
				// 				'talla' => $stock['talla'],
				// 				'cantidad' => $stock['cantidad'],
				// 				'sku'	=> $stock['sku']
				// 			)
				// 		);
				// 	}
				// 	elseif (in_array($producto['Categoria']['sexo_id'], array(1)) && in_array($stock['talla'], array(45,46)) && $stock['cantidad'] >= $producto['Producto']['stock_seguridad'])
				// 	{
				// 		$talla = array(
				// 			'Talla' => array(
				// 				'id' => $stock['id'],
				// 				'talla' => $stock['talla'],
				// 				'cantidad' => $stock['cantidad']
				// 			)
				// 		);
				// 	}
				// 	$tallas[$stock['talla']] = $talla;
				// }
				if ($talla)
					$tallas[$stock['talla']] = $talla;
			}
		}

		$activarVenta = false;
		// verifica disponibilidad de tallas
		foreach ($tallas as $talla)
		{
			if ($talla)
			{
				$activarVenta = true;
				break;
			}
		}
		// activar venta de producto
		if ($activarVenta)
		{
			if ($producto['Producto']['precio'] <= 5000)
				$activarVenta = false;
			elseif (in_array($producto['Producto']['categoria_id'],$categorias_ropa))
				$activarVenta = false;
			elseif (! $producto['Categoria']['publico'])
				$activarVenta = false;
		}
		//$activarVenta = false;

		$compartir = array('imagen' => 'http://'.$_SERVER['SERVER_NAME'].$this->webroot.'img/'.$producto['Producto']['foto']['path'],
						   'url' => 'http://'.$_SERVER['SERVER_NAME'].$this->webroot.'detalle/'.$producto['Producto']['slug']);
		$descripcion ='Llévate tus '.$producto['Producto']['nombre'].'! y disfruta de toda la comodidad y estilo de Skechers en cada paso.';


		$this->set(compact('producto','colores','tallas','compartir','activarVenta','descripcion'));
	}

	function carro()
	{

		$user =$this->Auth->user();
		if ($user){
			if(Cache::read('carro_id'.$user['Usuario']['id'],'carro')){
				$this->Session->write('Carro', Cache::read('carro_id'.$user['Usuario']['id'], 'carro'));
			}else{
				Cache::write('carro_'.$user['Usuario']['id'],$this->Session->read('Carro'),'carro');
				Cache::write('carro_id'.$user['Usuario']['id'],$this->Session->read('Carro'),'carro');
			}

		}

		//$this->Session->delete('Descuento');
		$descuentos = array();
		if($this->Session->read('Descuento'))
		{
			$descuentos = $this->Session->read('Descuento');
		}
		$this->Session->delete('Despacho');
		$this->Session->delete('IntentosDescuento');
		if (! empty($this->data)) // solo permite agregar productos promo
		{
			$options = array(
				'conditions' => array(
					'Stock.id' => $this->data['Producto']['id']
				),
				'fields' => array(
					'Stock.id',
					'Stock.producto_id',
					'Stock.talla',
					'Stock.cantidad'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'sitio_productos',
						'alias' => 'Producto',
						'type' => 'INNER',
						'conditions' => array(
							'Producto.id = Stock.producto_id',
							'Producto.activo' => 1
						)
					),
					array(
						'table' => 'sitio_categorias',
						'alias' => 'Categoria',
						'type' => 'INNER',
						'conditions' => array(
							'Categoria.id = Producto.categoria_id',
							'Categoria.publico' => 1
						)
					)
				)
			);

			if ($stock = $this->Producto->Stock->find('first',$options))
			{
				if (isset($this->productosPromo) && $this->productosPromo)
				{
					$options = array(
						'conditions' => array(
							'Stock.producto_id' => $this->productosPromo,
							'Stock.id' => $stock['Stock']['id']
						),
						'fields' => array(
							'Stock.id',
						),
					);
					if ($promo = $this->Producto->Stock->find('list',$options))
					{
						$this->Carro->agregar($stock['Stock']['id']);
						$actualiza = $this->Carro->actualiza($stock['Stock']['id'], 1);
					}
				}
				else
				{
					$this->Carro->agregar($stock['Stock']['id']);
					$actualiza = $this->Carro->actualiza($stock['Stock']['id'], 1);
				}
			}
		}

		$datos_compra = $this->datos_carro(null);

		if (! $datos_compra)
		{
			$this->Session->setFlash('Tu carro de compras está vacío. Selecciona almenos un producto de nuestro catalogo e intentalo nuevamente.', 'default', array('class' => 'alerta-carro'));
			//if ($this->referer() == '/productos/carro')
				$this->redirect(array('action' => 'inicio'));
			//else
			//	$this->redirect($this->referer());
		}
		$dosporuno = $this->Session->read('2x1');
		if($dosporuno)
			$this->set(compact('dosporuno')); 

		$productos = $datos_compra['ListadoProductos'];
		$aplicar_descuento = $datos_compra['AplicarDescuento'];
		$valores_compra = $datos_compra['DetalleCompra'];

		if (isset($this->productosPromo) && $this->productosPromo)
		{
			$activaPromo = true;
			foreach ($productos as $producto)
			{
				if (in_array($producto['Producto']['id'],$this->productosPromo))
					$activaPromo = false;
			}
			if ($activaPromo)
			{
				$options = array(
					'conditions' => array(
						'Producto.id' => $this->productosPromo,
						'Producto.activo' => 1,
					),
					'fields' => array(
						'Producto.id',
						'Producto.nombre',
						'Producto.categoria_id',
						'Producto.foto',
						'Producto.codigo',
						'Producto.codigo_completo',
						'Producto.precio',
						'Producto.precio_oferta',
						'Producto.oferta',
						'Producto.slug',


						'Stock.id',
						'Stock.talla'
					),
					'joins' => array(
						array(
							'table' => 'sitio_categorias',
							'alias' => 'Categoria',
							'type' => 'INNER',
							'conditions' => array(
								'Categoria.id = Producto.categoria_id',
								'Categoria.publico' => 1
							)
						),
						array(
							'table' => 'sitio_stocks',
							'alias' => 'Stock',
							'type' => 'INNER',
							'conditions' => array(
								'Stock.producto_id = Producto.id',
							)
						),
					),
					'group' => array('Producto.id')
				);
				$productosPromo = $this->Producto->find('all',$options);
			}
		}
		$this->set(compact('productos', 'aplicar_descuento', 'valores_compra','productosPromo','descuentos'));
	}


	function ajax_descuento()
	{
		//VERIFICACION DE SESION DE USUARIO
		//Configure::write('debug',2);
		if (! $this->Session->check('Auth.Usuario'))
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Para ingresar tu codigo primero debes iniciar sesión con tu cuenta.'
			);
			die(json_encode($respuesta));
		}
		if (! $this->params['form']['id'])
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Código Inválido</b> revisa si está bien escrito1.'// id del producto invalido
			);
			die(json_encode($respuesta));
		}
		if (! $this->params['form']['codigo'])
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Código Inválido</b> revisa si está bien escrito2.'// codigo invalido
			);
			die(json_encode($respuesta));
		}


		$cantidad = $this->params['form']['cantidad'];
		$id = $this->params['form']['id'];
		$codigo = $this->params['form']['codigo'];
		//VERIFICA EXISTENCIA DE PRODUCTO EN EL CARRO
		if (! $this->Session->check('Carro.'.$id) )
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Este producto no se encuentra en el carro.'
			);
			die(json_encode($respuesta));
		}

		/** INTENTOS SEGURIDAD
		 * limita el ingreso de descuentos sobre un producto a 5 intentos
		 * despues dee los 5 intentos por producto no permite ingresar nuevamente
		 */
		if ( $this->Session->check('IntentosDescuento.'.$id) && $intentos = $this->Session->read('IntentosDescuento.'.$id) )
		{
			if ($intentos >= 20)
			{
				$respuesta = array(
					'estado' => 'ERROR',
					'mensaje' => '<b>Lo sentimos</b> Tu codigo no es valido y haz intentado muchas veces.'
				);
				die(json_encode($respuesta));
			}
			$intentos++;
			$this->Session->write('IntentosDescuento.'.$id,$intentos);
		}
		else
		{
			$this->Session->write('IntentosDescuento.'.$id,1);
		}

		/** DESCUENTO */
		$options = array(
			'conditions' => array(
				'Descuento.codigo' => $codigo,
				'Descuento.fecha_caducidad >=' => date('Y-m-d'),
				'Descuento.web_tienda' => array(0,2),
				'OR' => array(
					array('Descuento.tipo' => 'POR'),
					array('Descuento.tipo' => 'DIN')
				)
			),
			'fields' => array(
				'Descuento.id',
				'Descuento.codigo',
				'Descuento.tipo',
				'Descuento.descuento',
				'Descuento.contador',
				'Descuento.cantidad',
				'Descuento.maximo',
				'Descuento.escolar',
				'Descuento.email',
				'Descuento.minimo',
				'Descuento.showroom',
				'Descuento.super',
				'Descuento.ciclico'
			),
			'recursive'	=> -1
		);


		if (! $descuento = $this->Producto->Categoria->Descuento->find('first',$options)) // verifica si existe descuento
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Código Inválido</b> revisa si está bien escrito<br />EJ: SKX-XXXX-18722'
			);
			die(json_encode($respuesta));
		}
		if ( isset($descuento['Descuento']['email']) && $descuento['Descuento']['email'] !='' && strtolower(trim($descuento['Descuento']['email'])) != strtolower($this->Auth->user('email')))
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Este codigo esta asociado a otro correo.'
			);
			die(json_encode($respuesta));
		}
		$datos = array(
			'conditions' => array(
				'Compra.estado' => 1,
				'MONTH(Compra.created) =' => date('m')
				//'MONTH(Compra.created) =' => date('m',strtotime('-2 month'))
			),
			'joins' => array(
				array(
					'table' => 'sitio_productos_compras',
					'alias' => 'ProductosCompra',
					'type' => 'INNER',
					'conditions' => array(
						'ProductosCompra.compra_id = Compra.id',
						'ProductosCompra.descuento_id' => $descuento['Descuento']['id']
					)
				)
			),
			'recursive' => -1
		);
		$usuario_mes = array(
			'conditions' => array(
				'Compra.estado' => 1,
				'MONTH(Compra.created) =' => date('m'),
				//'MONTH(Compra.created) =' => date('m',strtotime('-2 month')),
				'Compra.usuario_id' => $this->Auth->user('id')
			),
			'joins' => array(
				array(
					'table' => 'sitio_productos_compras',
					'alias' => 'ProductosCompra',
					'type' => 'INNER',
					'conditions' => array(
						'ProductosCompra.compra_id = Compra.id',
						'ProductosCompra.descuento_id' => $descuento['Descuento']['id']
					)
				)
			),
			'recursive' => -1
		);
		$count_usuario_mes = $this->Producto->Compra->find('count',$usuario_mes);
		$count_mes = $this->Producto->Compra->find('count',$datos);

		/*	$respuesta = array(
			'estado' => 'ERROR',
			'mensaje' => $count_usuario_mes
		);

		die(json_encode($respuesta));*/


		/** VERIFICACION DE CANTIDAD
		*	permite utilizar hasta 3 veces el mismo descuento en una compra
		*	verifica la cantidad de veces que se a utilizado el descuento, restringiendo segun la cantidad disponible y los utilizados
		*	limita la utilizacion del descuento, segun el maximo asociado al descuento
		*/
		$repetido = 0;
		//VERIFICA SI SE HAN INGRESADO MAS DESCUENTOS EN LA MISMA COMPRA
		if ($this->Session->check('Descuento') && $descuentos_ingresados = $this->Session->read('Descuento'))
		{
			//prx($descuentos_ingresados);
			//NO PERMITE INGRESAR MAS DESCUENTOS A UN PRODUCTO
			if (isset($descuentos[$id]))
			{
				$respuesta = array(
					'estado' => 'ERROR',
					'mensaje' => '<b>Lo sentimos</b> No puedes aplicar un descuento sobre un producto con descuento.'
				);
				die(json_encode($respuesta));
			}
			foreach ($descuentos_ingresados as $descuento_ingresado)
			{
				if ($descuento_ingresado['id'] == $descuento['Descuento']['id'])
					$repetido ++;
			}
		}

		if ($repetido > 10)// 10 descuentos por compra
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Puedes utilizar hasta 10 veces el mismo descuento en una compra.'
			);
			die(json_encode($respuesta));
		}

		//primera validacion
		if($descuento['Descuento']['ciclico'] == 1){
			if (($count_mes + $repetido) >= $descuento['Descuento']['cantidad'])// cantidad disponible
			{
				$respuesta = array(
					'estado' => 'ERROR',
					'mensaje' => '<b>Lo sentimos</b> Tu codigo ya ha sido utilizado.'
				);
				die(json_encode($respuesta));
			}
		}else{
			if (($descuento['Descuento']['contador'] + $repetido) >= $descuento['Descuento']['cantidad'])// cantidad disponible
			{
				$respuesta = array(
					'estado' => 'ERROR',
					'mensaje' => '<b>Lo sentimos</b> Tu codigo ya ha sido utilizado.'
				);
				die(json_encode($respuesta));
			}

		}
		//fin primera validacion
		/*if (($descuento['Descuento']['contador'] + $repetido) >= $descuento['Descuento']['cantidad'])// cantidad disponible
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Tu codigo ya ha sido utilizado.'
			);
			die(json_encode($respuesta));
		}*/
		if ( $repetido >= $descuento['Descuento']['maximo'] )// limite maximo
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> No puedes seguir utilizando este codigo.'
			);
			die(json_encode($respuesta));
		}

		// segunda validacion
		if($descuento['Descuento']['ciclico'] == 1){
			if (($count_mes + $repetido + $cantidad) > $descuento['Descuento']['cantidad'])// cantidad disponible
				{
					$respuesta = array(
						'estado' => 'ERROR',
						'mensaje' => '<b>Lo sentimos</b> Lo sentimos, no se puede aplicar este descuento, por que el codigo utilizado, no posee la cantidad necesaria para estos artículos'
					);
					die(json_encode($respuesta));
				}
		}else {
			if (($descuento['Descuento']['contador'] + $repetido + $cantidad) > $descuento['Descuento']['cantidad'])// cantidad disponible
				{
					$respuesta = array(
						'estado' => 'ERROR',
						'mensaje' => '<b>Lo sentimos</b> Lo sentimos, no se puede aplicar este descuento, por que el codigo utilizado, no posee la cantidad necesaria para estos artículos'
					);
					die(json_encode($respuesta));
				}
		}
		//fin segunda validacion modificada

		/**
		 * VERIFICAR SI EL USUARIO UTILIZO EL DESCUENTO ANTERIORMENTE
		 *	restringe reutilizacion del codigo de descuento por el mismo usuario
		 *	valor limitado por campo [maximo]
		*/
		$options = array(
			'conditions' => array(
				'Compra.estado' => 1,
				'Compra.usuario_id' => $this->Auth->user('id')
			),
			'joins' => array(
				array(
					'table' => 'sitio_productos_compras',
					'alias' => 'ProductosCompra',
					'type' => 'INNER',
					'conditions' => array(
						'ProductosCompra.compra_id = Compra.id',
						'ProductosCompra.descuento_id' => $descuento['Descuento']['id']
					)
				)
			),
			'recursive' => -1
		);

		//validacion de usuario 1
		if($descuento['Descuento']['ciclico'] == 1){
			if (($count_usuario_mes + $repetido) >= $descuento['Descuento']['maximo'])
			{
				$respuesta = array(
					'estado' => 'ERROR',
					'mensaje' => '<b>Lo sentimos</b> No puedes seguir utilizando este codigo 1.'
				);
				die(json_encode($respuesta));
			}
		}else {
			if ($utilizados = $this->Producto->Compra->find('count',$options))
			{
				if (($utilizados + $repetido) >= $descuento['Descuento']['maximo'])
				{
					$respuesta = array(
						'estado' => 'ERROR',
						'mensaje' => '<b>Lo sentimos</b> No puedes seguir utilizando este codigo.'
					);
					die(json_encode($respuesta));
				}
			}
		}


		//fin validacion de usuario 1
		if($descuento['Descuento']['ciclico'] == 1){
			if (($count_usuario_mes + $repetido + $cantidad) > $descuento['Descuento']['maximo'])
			{
				$respuesta = array(
					'estado' => 'ERROR',
					'mensaje' => '<b>Lo sentimos</b> No puedes seguir utilizando este codigo.'
				);
				die(json_encode($respuesta));
			}
		}else {
			if ($utilizados = $this->Producto->Compra->find('count',$options))
			{
				if (($utilizados + $repetido + $cantidad) > $descuento['Descuento']['maximo'])
				{
					$respuesta = array(
						'estado' => 'ERROR',
						'mensaje' => '<b>Lo sentimos</b> Lo sentimos, no se puede aplicar este descuento, por que el codigo utilizado, no posee la cantidad necesaria para estos artículos'
					);
					die(json_encode($respuesta));
				}
			}
		}

		//$disponibles = $descuento['Descuento']['maximo'] - ($utilizados + $repetido);
		// fin de descuentos a validar

		/** PRODUCTO */
		$options = array(
			'conditions' => array(
				'Producto.activo' => 1
			),
			'fields' => array(
				'Producto.id',
				'Producto.escolar',
				'Producto.excluir_descuento',
				'Producto.precio',
				'Producto.oferta',
				'Producto.outlet',
				'Producto.showroom',

				'Categoria.id',
				'Categoria.parent_id'
			),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'sitio_stocks',
					'alias' => 'Stock',
					'type' => 'INNER',
					'conditions' => array(
						'Stock.id' => $id,
						'Stock.producto_id = Producto.id',
						'Stock.cantidad >=' => 1
					)
				),
				array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id'
					)
				),
			)
		);
		if (! $producto = $this->Producto->find('first',$options))
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Producto Inválido</b> el producto no ha sido encontrado.'
			);
			die(json_encode($respuesta));
		}
		if ($descuento['Descuento']['showroom'])
		{
			if (! $producto['Producto']['showroom'])
			{
				$respuesta = array(
					'estado' => 'ERROR',
					'mensaje' => '<b>Producto Inválido</b> el descuento no es aplicable sobre este producto...'
				);
				die(json_encode($respuesta));
			}
			$showrooms = explode(',',$descuento['Descuento']['showroom']);
			if (in_array('MEN', $showrooms))
				$showrooms[] ='CC MEN';
			if (in_array('WOMEN', $showrooms))
				$showrooms[] ='CC WOMEN';

			if (! in_array($producto['Producto']['showroom'],$showrooms))
			{
				$respuesta = array(
					'estado' => 'ERROR',
					'mensaje' => '<b>Producto Inválido</b> el descuento no es aplicable sobre este producto.....'
				);
				die(json_encode($respuesta));
			}
		}

		// excluir productos de descuento
		if ($descuento['Descuento']['escolar'] && ! $producto['Producto']['escolar'])
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Producto no escolar excluido de descuentos de escolares.'
			);
			die(json_encode($respuesta));
		}
		if ($descuento['Descuento']['super'] !=1 && $producto['Producto']['excluir_descuento'] != 0)

		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Este producto esta excluido de descuentos.'
			);
			die(json_encode($respuesta));
		}
		/*if ($producto['Producto']['outlet'] == 1)
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Este producto esta excluido de descuentos.'
			);
			die(json_encode($respuesta));
		}*/
		/*if ($producto['Producto']['oferta'])
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Este producto en oferta esta excluido de descuentos.'
			);
			die(json_encode($respuesta));
		}*/
		if (isset($descuento['Descuento']['minimo']) && $descuento['Descuento']['minimo'] && $producto['Producto']['precio'] <= $descuento['Descuento']['minimo'])
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Descuento aplicable en productos con valor sobre $'.$descuento['Descuento']['minimo'].'.'
			);
			die(json_encode($respuesta));
		}

		$options = array(
			'conditions' => array(
				'CategoriasDescuento.descuento_id' => $descuento['Descuento']['id']
			),
			'fields' => array(
				'CategoriasDescuento.categoria_id',
				'CategoriasDescuento.categoria_id'
			)
		);
		if (! $listado_categorias = $this->Producto->Categoria->CategoriasDescuento->find('list',$options))
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Descuento no aplicable sobre la categoria del producto.'
			);
			die(json_encode($respuesta));
		}
	/*	if($datos_compra['DetalleCompra']['total'] < 15000)
		{
				$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>El total de tu compra debe ser mayor a $15.000.'
				);
			die(json_encode($respuesta));

		}*/
		$descuento['Descuento']['aplicar'] = $cantidad;

		if (in_array($producto['Categoria']['id'], $listado_categorias) || in_array($producto['Categoria']['parent_id'], $listado_categorias))
		{
			// guardar log
			$this->guardar_log($this->Auth->user('id'), 'productos', 'ajax_descuento', 'desc#'.$descuento['Descuento']['id'].'//prod#'.$id, $_SERVER['REMOTE_ADDR']);
			$this->Session->write('Descuento.' . $id, $descuento['Descuento']);
			$datos_compra = $this->datos_carro();
			if($datos_compra['DetalleCompra']['total'] > 0 && $datos_compra['DetalleCompra']['total'] < 14990)
			{
				$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>El total de tu compra debe ser mayor a $15.000.'
				);
				$this->Session->delete('Descuento.' . $id, $descuento['Descuento']);
				$datos_compra = $this->datos_carro();
				die(json_encode($respuesta));
			}


			$respuesta = array(
				'estado' => 'DESCUENTO_OK',
				'mensaje' => $descuento['Descuento']['descuento'],
			);
			if ($descuento['Descuento']['tipo']=='POR')
				$respuesta['mensaje'].='%';
			else
				$respuesta['mensaje']= '$'.$respuesta['mensaje'];

			$datos_compra = $this->datos_carro();
			$respuesta['total'] = $datos_compra['DetalleCompra'];
			//$respuesta['mensaje'] = 'Descuento Funcionarios';
			
	

			die(json_encode($respuesta));
		}
		else
		{
			$respuesta = array(
				'estado' => 'ERROR',
				'mensaje' => '<b>Lo sentimos</b> Descuento no aplicable sobre la categoria del producto.'
			);
			die(json_encode($respuesta));
		}
	}

	function ajax_actualizar_producto_descuento()
	{
		if (! isset($this->params['form']['id']))
			die(false);
		if (! $this->params['form']['id'])
			die(false);

		$datos_compra = $this->datos_carro();

		if (! isset($datos_compra['ListadoProductos'][$this->params['form']['id']]['Descuento']))
			die(false);
		if (! $producto = $datos_compra['ListadoProductos'][$this->params['form']['id']])
			die(false);

		$respuesta = array(
			'producto' => '',
			'total' => ''
		);

		//DATOS DESCUENTO EN DETALLE DEL PRODUCTO
		if (! isset($producto['Descuento']['tipo']))
			die(false);
		elseif (! in_array($producto['Descuento']['tipo'],array('POR','DIN')))
			die(false);

		$respuesta['producto']['codigo'] = $producto['Descuento']['codigo'];
		$respuesta['producto']['descuento'] = $producto['Descuento']['descuento'];
		if ($producto['Descuento']['tipo'] == 'POR')
			$respuesta['producto']['descuento'].='%';
		else
			$respuesta['producto']['descuento']='$'.$respuesta['producto']['descuento'];

		//DETALLE DE LA COMPRA
		$respuesta['total']['subtotal'] = $datos_compra['DetalleCompra']['subtotal'];
		$respuesta['total']['despacho'] = 0;
		$respuesta['total']['descuento'] = $datos_compra['DetalleCompra']['descuento'];
		$respuesta['total']['total'] = $datos_compra['DetalleCompra']['total'];
		die(json_encode($respuesta));
	}

	function despacho()
	{
		if(! $this->Auth->user() )
		{
			$this->Session->write('Login.redirect', array('controller' => 'productos','action' => 'despacho'));
			$this->redirect(array('controller' => 'usuarios', 'action' => 'add'));
		}

		if (! $this->Session->check('Carro'))
			$this->redirect(array('action' => 'inicio'));

		$this->Session->delete('Despacho');
		$options = array(
			'fields' => array(
				'Region.id',
				'Region.nombre'
			),
			'order' => array(
				'Region.id' => 'ASC'
			)
		);
		$this->loadModel('Direccion');
		$regiones = $this->Direccion->Comuna->Region->find('list',$options);
		$options = array(
			'fields' => array(
				'Region.id',
				'Region.nombre'
			),
			'order' => array(
				'Region.id' => 'ASC'
			),
			'joins' => array(
				array(
					'table' => 'sitio_retiros',
					'alias' => 'Retiro',
					'type' => 'INNER',
					'conditions' => array(
						'Region.id = Retiro.region_id',
						'Retiro.tipo_id = 3',
						'Retiro.activo = 1'
					)
				)
			)
		);

		$regiones_tiendas = $this->Direccion->Comuna->Region->find('list',$options);
		$options = array(
			'fields' => array(
				'Comuna.id',
				'Comuna.nombre'
			),
			'conditions' => array(
				'Comuna.activo' => 1
			),
			'order' => array(
				'Comuna.nombre' => 'ASC'
			)
		);

		$comunas = $this->Direccion->Comuna->find('list',$options);
		$options = array(
			'conditions' => array(
				'Direccion.usuario_id' => $this->Auth->user('id')
			),
			'fields' => array(
				'Direccion.id',
				'Direccion.nombre'
			),
			'order' => array(
				'Direccion.id' => 'ASC'
			)
		);
		$this->loadModel('Retiro');
		$tiendas = $this->Retiro->find('all', array('conditions' => array('tipo_id' => 3)));
		$direcciones = $this->Direccion->find('list',$options);

		$this->set(compact('regiones', 'comunas', 'direcciones','tiendas','regiones_tiendas'));
	}

	function confirmar()
	{
		configure::write('debug',2 );
		$this->loadModel('Direccion');
		$tipo_retiro = 0;
		//prx($this->data);
		if(isset($this->data['Producto']['retiro_id']) && $this->data['Producto']['retiro_id'])
		{
			$retiro_id = $this->data['Producto']['retiro_id'];
			$anterior = $this->Direccion->find('first', array('conditions' => array(
																	'Direccion.retiro_id' => $retiro_id,
																	'Direccion.usuario_id' => $this->Auth->user('id')
																			),
															'contain' => 'Retiro'));
			$entrega = null;
			if(isset($this->data['Producto']['nombre']) && $this->data['Producto']['nombre'] !='')
			{
				$entrega  = str_replace("|","",$this->data['Producto']['nombre'])." ".str_replace("|","",$this->data['Producto']['apellido']);
			}

			if(isset($anterior) && $anterior)
			{
				if(isset($anterior['Retiro']['tipo_id']) &&  $anterior['Retiro']['tipo_id'] == 3)
					$tipo_retiro = 1;
				$this->data['Producto']['direccion_id'] =$anterior['Direccion']['id'];
			}else{
				$this->loadModel('Retiro');
				$retiro = $this->Retiro->find('first', array('conditions' => array('Retiro.id' => $retiro_id),
															'contain' => 'Tipo'));
				if($retiro['Retiro']['tipo_id'] == 3)
				{
					
					$nombre_retiro = 'Skechers - '.$retiro['Retiro']['nombre'];
					$tipo_retiro = 1;

				}else{
					$nombre_retiro = 'Chilexpress - '.$retiro['Retiro']['nombre'];
				}
				if($retiro['Retiro']['numero'] =='')
						$retiro['Retiro']['numero'] ='s/n';	
				if(!$retiro)
					$this->redirect(array('action' => 'inicio'));
				$direccion = array(	'usuario_id' => $this->Auth->user('id'),
									'calle'		=>	$retiro['Retiro']['calle'],
									'depto'		=> $retiro['Retiro']['extra'],
									'numero'	=>	$retiro['Retiro']['numero'],
									//'numero'	=>	2,
									'telefono'	=>	((isset($this->data['Producto']['telefono']) && $this->data['Producto']['telefono']) ) ? $this->data['Producto']['telefono']: "",
									'celular'	=>	((isset($this->data['Producto']['telefono']) && $this->data['Producto']['telefono']) ) ? $this->data['Producto']['telefono']: "",
									'comuna_id'	=> $retiro['Retiro']['comuna_id'],
									'region_id'	=> $retiro['Retiro']['region_id'],
									'nombre'	=> $nombre_retiro, //retiro['Tipo']['nombre'].' '.$retiro['Retiro']['nombre'],
									'retiro_id'	=> $retiro_id );
			


				//prx($direccion);	
				$this->Direccion->create();
				if(!$this->Direccion->save($direccion))
				{
					prx($this->Direccion->validationErrors);
					$this->redirect(array('action' => 'inicio'));
				}
				$this->data['Producto']['direccion_id'] = $this->Direccion->id;
			}
		}
		if(! $this->Auth->user())
			$this->redirect(array('action' => 'inicio'));
		if (! isset($this->data['Producto']['direccion_id']))
			$this->redirect(array('action' => 'despacho'));
		elseif (! $this->data['Producto']['direccion_id'])
			$this->redirect(array('action' => 'despacho'));
		$entrega = null;
		if(isset($this->data['Producto']['nombre']) && $this->data['Producto']['nombre'] !='')
		{
			$entrega  = $entrega  = str_replace("|","",$this->data['Producto']['nombre'])." ".str_replace("|","",$this->data['Producto']['apellido']);

		}
		if(isset($this->data['Producto']['rut']))
			$this->Session->write('Despacho.rut',$this->data['Producto']['rut']);
		$this->Session->write('Despacho.tipo_retiro',$tipo_retiro);
		$this->Session->write('Despacho.entrega',$entrega);
		$this->Session->write('Despacho.direccion_id',$this->data['Producto']['direccion_id']);
		$carrito = $this->Carro->productos();
		if ( ! $carrito )
			$this->redirect(array('action' => 'inicio'));
		$options = array(
			'conditions' => array(
				'Direccion.id' => $this->data['Producto']['direccion_id']
			),
			'fields' => array(
				'Direccion.comuna_id',
				'Direccion.retiro_id'
			),
			'recursive' => -1,
		);
		if(! $direccion2 = $this->Direccion->find('first',$options))
			$this->redirect(array('action' => 'inicio'));
		
		$comuna = $direccion2['Direccion']['comuna_id'];
		$datos_compra = $this->datos_carro($comuna,$tipo_retiro);
		if (! $datos_compra)
			$this->redirect(array('action' => 'inicio'));
		$mercadoPago = true;

		$productos = $datos_compra['ListadoProductos'];
		$aplicar_descuento = $datos_compra['AplicarDescuento'];
		$valores_compra = $datos_compra['DetalleCompra'];
		if($valores_compra['descuento'] > 0 && $valores_compra['descuento'] == $valores_compra['subtotal'] && $valores_compra['total'] == 0 )
			$mercadoPago = false;
		$options = array(
			'conditions' => array(
				'Direccion.id' => $this->data['Producto']['direccion_id']
			),
			'fields' => array(
				'Direccion.id',
				'Direccion.calle',
				'Direccion.numero',
				'Direccion.depto',
				'Direccion.codigo_postal',
				'Direccion.telefono',
				'Direccion.celular',
				'Direccion.comuna_id',

				'Comuna.id',
				'Comuna.nombre',
				'Comuna.region_id',

				'Region.id',
				'Region.nombre'
			),
			'recursive' => -1,
			'joins' => array(
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
				)
			)
		);
		if(! $despacho = $this->Direccion->find('first',$options))
			$this->redirect(array('action' => 'inicio'));
		$despacho['Direccion']['entrega'] = $entrega;
		$this->set(compact('productos', 'despacho', 'valores_compra', 'entrega','mercadoPago'));
	}
	function ip()
	{
		Configure::write('debug',2);
		prx($_SERVER);
	}
	function pago($mediopago ='webpay')
	{
		$carrito	= $this->Session->read('Carro');
		$tipo_retiro = 		$this->Session->read('Despacho.tipo_retiro');
		if ( ! $carrito || empty($carrito) )
			$this->redirect('/');
		$this->loadModel('Direccion');
		$options = array(
			'conditions' => array(
				'Direccion.id' => $this->Session->read('Despacho.direccion_id')
			),
			'fields' => array(
				'Direccion.comuna_id',
				'Direccion.retiro_id'
			),
			'recursive' => -1,
		);
		if(! $direccion2 = $this->Direccion->find('first',$options))
			$this->redirect(array('action' => 'inicio'));

		$comuna = $direccion2['Direccion']['comuna_id'];

		$datos_compra = $this->datos_carro($comuna, $tipo_retiro);
		if (! $datos_compra)
			$this->redirect(array('action' => 'inicio'));
		$dosporuno = $this->Session->read('2x1');
		$productos = $datos_compra['ListadoProductos'];
		$valores_compra = $datos_compra['DetalleCompra'];

		if (! $productos)
			$this->redirect(array('action' => 'inicio'));

		$this->Session->write('totalcompra', $valores_compra['total']);

		$this->loadModel('Despacho');
		$new_despacho = array(
			'Despacho' => array(
				'direccion_id'	=> $this->Session->read('Despacho.direccion_id'),
				'entrega'		=> $this->Session->read('Despacho.entrega'),
				'rut'			=> $this->Session->read('Despacho.rut'),
				'usuario_id'		=> $this->Auth->user('id')
			)
		);
		$this->Despacho->create();
		$this->Despacho->save($new_despacho);
		$despacho_id = $this->Despacho->id;
		$this->Session->write('Despacho.id', $despacho_id);

		$new_productos = $aplicar_descuento = array();
		$options = array(
			'conditions' => array(
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre'
			)
		);
		$categorias_publicas = $this->Producto->Categoria->find('list',$options);
		$cont_2x1=0;

		foreach ($productos as $key => $producto)
		{
			if ($producto['Producto']['id'])
			{
				$valor_producto = $producto['Producto']['precio'];
				if (isset($producto['Producto']['oferta']) && $producto['Producto']['oferta'] == 1)
					$valor_producto = $producto['Producto']['precio_oferta'];

				for ( $cantidad = 1; $cantidad <= $producto['cantidad']; $cantidad++ )
				{
					$categoria = '';
					if ($producto['Producto']['outlet'])
						$categoria.='Outlet ';
					if (isset($categorias_publicas[$producto['Producto']['categoria_id']]))
						$categoria.=$categorias_publicas[$producto['Producto']['categoria_id']];

					$new_producto = array(
						'producto_id'	=> $producto['Producto']['id'],
						'talla'		=> $producto['Stock']['talla'],
						'categoria' 	=> $categoria,
						'valor' 		=> $valor_producto
					);
					if (isset($producto['Stock']['sku']) && $producto['Stock']['sku'])
						$new_producto['sku'] = $producto['Stock']['sku'];

					if (isset($producto['Descuento']) && $producto['Descuento'])
					{
						if (! in_array($producto['Stock']['id'], $aplicar_descuento))
						{
							$new_producto['descuento_id'] = $producto['Descuento']['id'];
							$new_producto['valor'] = $producto['Producto']['precio'];
							$producto['Descuento']['aplicar']--;
							if($producto['Descuento']['aplicar'] == 0)
								$aplicar_descuento[$producto['Stock']['id']] = $producto['Stock']['id'];
						}
					}else if(isset($dosporuno) && is_array($dosporuno) && in_array($key, $dosporuno))
					{
						
						$new_producto['descuento_id'] = 9999999;
						$cont_2x1++;
						if($cont_2x1 >= count($dosporuno))
							unset($dosporuno);
						
					}

					$new_productos[] = $new_producto;
				}
			}
		}

		if (! $new_productos)
			$this->redirect(array('action' => 'inicio'));

		$ip = 0;
		if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'])
			$ip = $_SERVER['REMOTE_ADDR'];

		$save = array(
			'Compra'		=> array(
				'despacho_id' 		=> $despacho_id,
				'usuario_id' 		=> $this->Auth->user('id') ,
				'despacho'			=> $valores_compra['despacho'],
				'subtotal' 			=> $valores_compra['subtotal'],
				'descuento' 		=> $valores_compra['descuento'],
				'iva'		  		=> $valores_compra['iva'],
				'total'				=> $valores_compra['total'],
				'neto'		 		=> $valores_compra['neto'],
				'valor_despacho'	=> $valores_compra['despacho'],
				'ip'				=> $ip
			),
			'Producto'	=> $new_productos
		);

		$this->Producto->Compra->create();
		$this->Producto->Compra->saveAll($save);
		$ordencompra = $this->Producto->Compra->id;
		$this->Session->write('Compra.id', $ordencompra);
		if ( $valores_compra['subtotal'] == $valores_compra['descuento'] && $valores_compra['total'] <= 0)
		{
			$new_pago = array(
				'Pago' => array(
					'compra_id' => $ordencompra,
					'numeroOrden' => $ordencompra,
					'usuario_id' => $this->Auth->user('id'),
					'monto' => $valores_compra['total'],
					'tipoPago' => 'REGALO',
					'numeroTarjeta' => '0000',
					'fecha' => date('Y-m-d', strtotime(date('Y-m-d')))
				)
			);

			if ($this->Session->check('Descuento'))
			{
				$descuento = reset($this->Session->read('Descuento'));
				$identificarCambio = substr($descuento['codigo'],0,4);
				if ($identificarCambio==='CMB-')
				{
					$new_pago['tipoPago'] = 'CAMBIO';
				}
			}
		}
		else
		{
			$new_pago = array(
				'Pago' => array(
					'compra_id' => $ordencompra,
					'numeroOrden' => $ordencompra,
					'usuario_id' => $this->Auth->user('id'),
					'monto' => $valores_compra['total'],
					'marca' => $mediopago,

					'tipoPago' => 'NO'
				)
			);
		}

		$this->Producto->Compra->Pago->create();
		$this->Producto->Compra->Pago->save($new_pago);
		if (isset($new_pago['Pago']['tipoPago']) && $new_pago['Pago']['tipoPago'] == 'REGALO')
		{
			$this->redirect(array('action' => 'felicidades', 'giftcard'));
		}
		elseif (isset($new_pago['Pago']['tipoPago']) && $new_pago['Pago']['tipoPago'] == 'CAMBIO')
		{
			// GENERAR REPORTE !
			$url = array(
				'controller' => 'compras',
				'action' => 'generar_reporte'
			);
			//$this->requestAction($url,array('pass' => array($ordencompra)));
			$this->redirect(array('action' => 'felicidades'));
		}

		$desarrollo = false;
		if ($desarrollo)
		{
			/**
			 **** USUARIOS DESARROLLO CON PERMISO COMPRA
			 * 1	ehenriquez@andain.cl
			 * 2	jwalton@leytonmedia.com
			 * 10	cherrera@skechers.cl
			 * 13	pablo_y_c@hotmail.com
			 * 14	espermatoedu@hotmail.com
			 * 16	viejon@gmail.com
			 */
			$usuariosDesarrollo = array(1);
			if (in_array($this->Auth->user('id'), $usuariosDesarrollo))
			{
				if ($valores_compra['total'] >= 150000)
					$this->redirect(array('action' => 'fallo'));

				// simulacion tbk - desarrollo: validacion por cierre
				$this->redirect(array('action' => 'simulaTbk',$ordencompra));
			}
			else
			{
				$this->redirect(array('action' => 'fallo'));
			}
		}

		//TBK OLD
		if($mediopago =='webpay')
		{
			$datos_tbk = $this->tbkParams;
			require_once('../vendor/autoload.php');
			Transbank\Webpay\WebpayPlus::setCommerceCode('597055555532');
			Transbank\Webpay\WebpayPlus::setApiKey('579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C');
			Transbank\Webpay\WebpayPlus::setIntegrationType('TEST');
			$buyOrder = $ordencompra;
			$returnUrl =$datos_tbk['cierre'];
			$amount = $valores_compra['total'];
			$sessionId = uniqid();
			$result = Transbank\Webpay\WebpayPlus\Transaction::create($buyOrder, $sessionId, $amount, $returnUrl);
			//$this->redirect(array('action' => 'fallo'));// comentar cuando este en produccion
		}else if($mediopago =='mercadopago')
		{
			$this->redirect('../pago/mercadopago.php?orden='.$ordencompra);
		}
		$this->set(compact('result'));
	}

	function simulaTbk($id = null)
	{
		/**
		**** USUARIOS DESARROLLO CON PERMISO COMPRA
		* 1	ehenriquez@andain.cl
		* 2	jwalton@leytonmedia.com
		* 10	cherrera@skechers.cl
		* 13	pablo_y_c@hotmail.com
		* 14	espermatoedu@hotmail.com
		* 16	viejon@gmail.com
		*/

		$this->layout = 'ajax';
		$usuariosDesarrollo = array(1);
		if (! in_array($this->Auth->user('id'), $usuariosDesarrollo))
			$this->redirect(array('action' => 'fallo'));
		$options = array(
			'conditions' => array(
				'Compra.id' => $id
			),
			'fields' => array(
				'Compra.id',
				'Compra.total',
				'Compra.created'
			)
		);
		if (! $compra = $this->Producto->Compra->find('first',$options))
			$this->redirect(array('action' => 'fallo'));
		$this->set(compact('compra'));
	}

	function cierre()
	{
		Configure::write('debug',0);
		// if (! $_POST)
		// 	die("RECHAZADO");

		// if (isset($_POST['TBK_ORDEN_COMPRA']) && $_POST['TBK_ORDEN_COMPRA'])
			$trs_orden_compra = $_POST['TBK_ORDEN_COMPRA'];
		// else
		// 	die("RECHAZADO");

		$trs_transaccion = $_POST['TBK_TIPO_TRANSACCION'];
		$trs_respuesta = $_POST['TBK_RESPUESTA'];
		$trs_id_session = $_POST['TBK_ID_SESION'];
		$trs_cod_autorizacion = $_POST['TBK_CODIGO_AUTORIZACION'];
		$trs_monto = substr($_POST['TBK_MONTO'],0,-2).".00";
		$trs_nro_final_tarjeta = $_POST['TBK_FINAL_NUMERO_TARJETA'];
		$trs_fecha_expiracion = $_POST['TBK_FECHA_EXPIRACION'];
		$trs_fecha_contable = $_POST['TBK_FECHA_CONTABLE'];
		$trs_fecha_transaccion = $_POST['TBK_FECHA_TRANSACCION'];
		$trs_hora_transaccion = $_POST['TBK_HORA_TRANSACCION'];
		$trs_id_transaccion = $_POST['TBK_ID_TRANSACCION'];
		$trs_tipo_pago = $_POST['TBK_TIPO_PAGO'];
		$trs_nro_cuotas = $_POST['TBK_NUMERO_CUOTAS'];
		$trs_mac = $_POST['TBK_MAC'];
		$trs_tasa_interes_max = $_POST['TBK_TASA_INTERES_MAX'];
		$trs_fecha_transaccion = ($trs_fecha_transaccion=='') ? strftime('%Y-%m-%d') : strftime('%Y').'-'.substr($trs_fecha_transaccion,0,2).'-'.substr($trs_fecha_transaccion,2,2);
		$trs_fecha_expiracion = ($trs_fecha_expiracion=='') ? strftime('%Y-%m-%d') : strftime('%Y').'-'.substr($trs_fecha_expiracion,0,2).'-'.substr($trs_fecha_expiracion,2,2);
		$trs_fecha_contable = ($trs_fecha_contable=='') ? strftime('%Y-%m-%d') : strftime('%Y').'-'.substr($trs_fecha_contable,0,2).'-'.substr($trs_fecha_contable,2,2);
		$resto = substr($trs_monto, 0, -2);
		/**** inicio de pagina de cierre xt_compra.php***/
		if( $trs_respuesta != 0 )
			die("ACEPTADO");

		// log TBK params
		$filename = "/home/skechile/public_html/store/cgi-bin/log/log".$trs_orden_compra."--".$trs_id_transaccion.".txt";
		$fp = fopen($filename,"w");
		reset($_POST);
		while ( list($key,$val) = each($_POST) )
		{
			fwrite($fp,"$key=$val&");
		}
		fclose($fp);
		$cmdline = "/cgi-bin/tbk_check_mac.cgi $filename";
		exec($cmdline,$result,$retint);

		// if ($result[0] != "CORRECTO")
		// {
		// 	die( "RECHAZADO");
		// }

		// verificamos pago
		$options =array(
			'conditions' => array(
				'Pago.numeroOrden' => $trs_orden_compra,
				'tipoPago' => 'NO'
			)
		);
		if (! $linea = $this->Producto->Compra->Pago->find('first',$options))
			die("RECHAZADO");

		// verificamos montos
		if (substr($_POST['TBK_MONTO'],0,-2) != $linea['Pago']['monto'])
			die("RECHAZADO");

		// guardamos estado y validamos el pago
		$save = array(
			'Pago'	=>	array(
				'id' 				=> $linea['Pago']['id'],
				'codigo' 			=> $trs_id_transaccion,
				'numeroTarjeta' 	=> $trs_nro_final_tarjeta,
				'fecha' 			=> $trs_fecha_transaccion,
				'hora' 				=> $trs_hora_transaccion,
				'tipoPago'			=> $trs_tipo_pago,
				'mac'				=> $trs_mac,
				'codAutorizacion'	=> $trs_cod_autorizacion,
				'cuotas'			=> $trs_nro_cuotas,
				'tipoPago2'			=> $trs_tipo_pago,
				'expiracion'		=> $trs_fecha_expiracion,
				'fechaContable'		=> $trs_fecha_contable,
				'estado'	=> $trs_tipo_pago,
				'respuesta' => $trs_respuesta
			)
		);

		if (! $this->Producto->Compra->Pago->save($save))
			die("RECHAZADO");

		// verificamos compra
		$options = array(
			'conditions' => array(
				'Compra.id' => $trs_orden_compra
			),
			'contain' => array(
				'Pago',
				'Despacho' => array(
					'Direccion' => array(
						'Comuna'
					)
				)
			)
		);

		if (! $compra = $this->Producto->Compra->find('first',$options))
			die("RECHAZADO");

		// verificamos usuario
		$options = array(
			'conditions' => array(
				'Usuario.id' => $compra['Compra']['usuario_id']
			)
		);
		if (! $usuario = $this->Producto->Compra->Usuario->find('first',$options))
			die("RECHAZADO");

		// verificamos productos
		$options = array(
			'conditions' => array(
				'ProductosCompra.compra_id' => $compra['Compra']['id']
			),
			'order' => array(
				'ProductosCompra.producto_id' => 'ASC'
			)
		);
		if (! $lista_productos = $this->Producto->ProductosCompra->find('all',$options))
			die("RECHAZADO");

		// validamos compra
		$save = array(
			'Compra' => array(
				'id' => $trs_orden_compra,
				'estado' => 1
			)
		);
		if (! $this->Producto->Compra->save($save))
			die("RECHAZADO");

		// guardamos log
		$this->guardar_log($compra['Compra']['usuario_id'], 'productos', 'cierre', 'compra realizada #'.$compra['Compra']['id'], $_SERVER['REMOTE_ADDR']);
		//enviamos email

		// GENERAR REPORTE !
		$url = array(
			'controller' => 'compras',
			'action' => 'generar_reporte'
		);
		$this->requestAction($url,array('pass' => array($compra['Compra']['id'])));
		die("ACEPTADO");
	}

	function fallo()
	{
		$carrito = $this->Carro->productos();

		if ( ! $carrito )
			$this->redirect(array('action' => 'inicio'));

		$productos = array();
		$total = $subtotal = 0;
		$options = array(
			'conditions' => array(
				'OR' => array(
				//	array('Categoria.parent_id' => 58),
					array('Categoria.id' => 58)
				),
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		);
		$categorias_ropa = $this->Producto->Categoria->find('list',$options);

		foreach ( $carrito as $id => $cantidad )
		{
			$options = array(
				'conditions' => array('Stock.id' => $id),
				'contain'	=> array(
					'Producto' => array('Color')
				)
			);
			$productos[$id] = $this->Producto->Stock->find('first',$options);

			if (in_array($productos[$id]['Producto']['categoria_id'],$categorias_ropa))
			{
				if ($productos[$id]['Stock']['talla'])
				{
					$talla_ropa = false;
					if ($productos[$id]['Stock']['talla'] == 1)
						$talla_ropa = 'XS';
					elseif ($productos[$id]['Stock']['talla'] == 2)
						$talla_ropa = 'S';
					elseif ($productos[$id]['Stock']['talla'] == 3)
						$talla_ropa = 'M';
					elseif ($productos[$id]['Stock']['talla'] == 4)
						$talla_ropa = 'L';
					elseif ($productos[$id]['Stock']['talla'] == 5)
						$talla_ropa = 'XL';
					elseif ($productos[$id]['Stock']['talla'] == 6)
						$talla_ropa = 'XXL';

					if ($talla_ropa)
						$productos[$id]['Stock']['talla'] = $talla_ropa;
				}
			}

			$productos[$id]['cantidad']	= $cantidad;
			if ( $productos[$id]['Producto']['oferta'] )
				$total = $total + ((int)$productos[$id]['Producto']['precio_oferta'] * (int)$cantidad);
			else
				$total = $total + ((int)$productos[$id]['Producto']['precio'] * (int)$cantidad);
		}

		$options = array(
			'conditions' => array(
				'Compra.id' => $this->Session->read('Compra.id'),
				'Compra.usuario_id' => $this->Auth->user('id')
			),
			'fields' => array(
				'Compra.id',
				'Compra.subtotal',
				'Compra.iva',
				'Compra.neto',
				'Compra.total',
				'Compra.valor_despacho',
				'Pago.id',
				'Pago.compra_id',
				'Pago.numeroTarjeta',
				'Pago.fecha',
				'Pago.tipoPago'
			),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'sitio_pagos',
					'alias' => 'Pago',
					'type' => 'INNER',
					'conditions' => array(
						'Pago.compra_id = Compra.id'
					)
				)
			)
		);

		if (! $compra = $this->Producto->Compra->find('first',$options))
			$this->redirect(array('action' => 'inicio'));

		// DESCUENTO
		// aumenta el contador de descuentos
		$descuento = 0;
		if ( $descuentos = $this->Session->read('Descuento') )
		{
			foreach ( $descuentos as $index => $desc )
			{
				if ( $desc['tipo'] == 'DIN' )
					$descuento = $descuento + $desc['descuento'];
				elseif ( $desc['tipo'] == 'POR' )
				{
					if ( $productos[$index]['Producto']['oferta'] == 1 )
						$descuento = $descuento + (( $productos[$index]['Producto']['precio_oferta'] * $desc['descuento'] ) / 100 );
					else
						$descuento = $descuento + (( $productos[$index]['Producto']['precio'] * $desc['descuento'] ) / 100 );
					if ( ($descuento % 10) > 0 )
						$descuento = (((int)($descuento/10))*10)+10;
					else
						$descuento = ((int)($descuento/10))*10;
				}
				$this->loadModel('Descuento');
			}
		}

		$total = $compra['Compra']['total'];
		$subtotal = $compra['Compra']['subtotal'];
		$neto = $compra['Compra']['neto'];
		$iva = $compra['Compra']['iva'];
		$despacho_val = $compra['Compra']['valor_despacho'];
		$this->set(compact('productos', 'subtotal', 'iva', 'neto', 'despacho_val', 'total', 'descuento' , 'descuentos', 'usuario'));
		$this->limpiarCarro();
	}

		function pendiente()
	{
		$carrito = $this->Carro->productos();

		if ( ! $carrito )
			$this->redirect(array('action' => 'inicio'));

		$productos = array();
		$total = $subtotal = 0;
		$options = array(
			'conditions' => array(
				'OR' => array(
				//	array('Categoria.parent_id' => 58),
					array('Categoria.id' => 58)
				),
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		);
		$categorias_ropa = $this->Producto->Categoria->find('list',$options);

		foreach ( $carrito as $id => $cantidad )
		{
			$options = array(
				'conditions' => array('Stock.id' => $id),
				'contain'	=> array(
					'Producto' => array('Color')
				)
			);
			$productos[$id] = $this->Producto->Stock->find('first',$options);

			if (in_array($productos[$id]['Producto']['categoria_id'],$categorias_ropa))
			{
				if ($productos[$id]['Stock']['talla'])
				{
					$talla_ropa = false;
					if ($productos[$id]['Stock']['talla'] == 1)
						$talla_ropa = 'XS';
					elseif ($productos[$id]['Stock']['talla'] == 2)
						$talla_ropa = 'S';
					elseif ($productos[$id]['Stock']['talla'] == 3)
						$talla_ropa = 'M';
					elseif ($productos[$id]['Stock']['talla'] == 4)
						$talla_ropa = 'L';
					elseif ($productos[$id]['Stock']['talla'] == 5)
						$talla_ropa = 'XL';
					elseif ($productos[$id]['Stock']['talla'] == 6)
						$talla_ropa = 'XXL';

					if ($talla_ropa)
						$productos[$id]['Stock']['talla'] = $talla_ropa;
				}
			}

			$productos[$id]['cantidad']	= $cantidad;
			if ( $productos[$id]['Producto']['oferta'] )
				$total = $total + ((int)$productos[$id]['Producto']['precio_oferta'] * (int)$cantidad);
			else
				$total = $total + ((int)$productos[$id]['Producto']['precio'] * (int)$cantidad);
		}

		$options = array(
			'conditions' => array(
				'Compra.id' => $this->Session->read('Compra.id'),
				'Compra.usuario_id' => $this->Auth->user('id')
			),
			'fields' => array(
				'Compra.id',
				'Compra.subtotal',
				'Compra.iva',
				'Compra.neto',
				'Compra.total',
				'Compra.valor_despacho',
				'Pago.id',
				'Pago.compra_id',
				'Pago.numeroTarjeta',
				'Pago.fecha',
				'Pago.tipoPago'
			),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'sitio_pagos',
					'alias' => 'Pago',
					'type' => 'INNER',
					'conditions' => array(
						'Pago.compra_id = Compra.id'
					)
				)
			)
		);

		if (! $compra = $this->Producto->Compra->find('first',$options))
			$this->redirect(array('action' => 'inicio'));

		// DESCUENTO
		// aumenta el contador de descuentos
		$descuento = 0;
		if ( $descuentos = $this->Session->read('Descuento') )
		{
			foreach ( $descuentos as $index => $desc )
			{
				if ( $desc['tipo'] == 'DIN' )
					$descuento = $descuento + $desc['descuento'];
				elseif ( $desc['tipo'] == 'POR' )
				{
					if ( $productos[$index]['Producto']['oferta'] == 1 )
						$descuento = $descuento + (( $productos[$index]['Producto']['precio_oferta'] * $desc['descuento'] ) / 100 );
					else
						$descuento = $descuento + (( $productos[$index]['Producto']['precio'] * $desc['descuento'] ) / 100 );
					if ( ($descuento % 10) > 0 )
						$descuento = (((int)($descuento/10))*10)+10;
					else
						$descuento = ((int)($descuento/10))*10;
				}
				$this->loadModel('Descuento');
			}
		}

		$total = $compra['Compra']['total'];
		$subtotal = $compra['Compra']['subtotal'];
		$neto = $compra['Compra']['neto'];
		$iva = $compra['Compra']['iva'];
		$despacho_val = $compra['Compra']['valor_despacho'];
		$this->limpiarCarro();
		$this->set(compact('productos', 'subtotal', 'iva', 'neto', 'despacho_val', 'total', 'descuento' , 'descuentos', 'usuario'));
	}

	

	private function limpiarCarro()
	{
		$this->Carro->vaciar();
		$this->Session->delete('Despacho');
		$this->Session->delete('Compra');
		$this->Session->delete('totalcompra');
		$this->Session->delete('Descuento');
		$this->Session->delete('NoFinalizado');
		$this->Session->delete('2x1');
	}

	function comprar($slug = null)
	{
		if ( ! $slug )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'inicio'));
		}
		$options = array(
			'conditions' => array(
				'Producto.slug' => $slug
			),
			'fields' => array(
				'Producto.id'
			)
		);
		if (! $producto = $this->Producto->find('first',$options))
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'inicio'));
		}
		// $this->Carro->agregar($producto['Producto']['id']);
		$this->redirect(array('action' => 'carro'));
	}

	function eliminar($id = null)
	{
		if ( ! $id )
			$this->redirect($this->referer());
		if ($this->Session->check('Descuento.'.$id) )
			$this->Session->delete('Descuento.' . $id);
		$this->Carro->eliminar($id);

		$user =$this->Auth->user();
		if ($user){
			Cache::write('carro_id'.$user['Usuario']['id'],$this->Session->read('Carro'),'carro');
			Cache::write('carro_'.$user['Usuario']['id'],$this->datos_carro(),'carro');
		}

		if (isset($this->productosPromo) && $this->productosPromo)
		{
			$options = array(
				'conditions' => array(
					'Stock.producto_id' => $this->productosPromo
				),
				'fields' => array(
					'Stock.id',
					'Stock.id',
				),
			);
			if ($listPromo = $this->Producto->Stock->find('list',$options))
			{
				if ($carro = $this->Carro->productos())
				{
					foreach ($carro as $stock_id => $cantidad)
					{
						if (in_array($stock_id,$listPromo))
							unset($carro[$stock_id]);
					}

					if (! $carro)
					{
						$this->Carro->vaciar();
						$this->Session->setFlash(__('Su carro se encuentra vacio.', true));
						$this->redirect(array('action' => 'inicio'));
					}
				}
			}
		}
		$this->redirect($this->referer());
	}

	function carroConfirmar() {
		$carrito	= $this->Carro->productos();

		if ( ! $carrito )
			$this->redirect(array('action' => 'inicio'));
		$productos	= array();
		$total = $cantprod = 0;
		foreach ( $carrito as $id => $cantidad )
		{
			$options = array(
				'conditions' => array(
					'Stock.id' => $id
				),
				'contain'	=> array(
					'Producto' => array(
						'Color'
					)
				)
			);
			if (! $producto = $this->Producto->Stock->find('first',$options))
				continue;

			$producto['cantidad'] = $cantidad;
			$precio = $producto['Producto']['precio'];
			if ($producto['Producto']['oferta'])
				$precio = $producto['Producto']['precio_oferta'];
			$total+=($precio * $cantidad);
			$cantprod+=$cantidad;

			$productos[$id] = $producto;
		}
		$this->set(compact('productos', 'total', 'cantprod'));
	}

	private function datos_carro($comuna_id = null,$tipo_retiro = null)
	{
		/* LLAMAR CARRO */
	if ( ! $carro	= $this->Carro->productos() )
			return false;

		/* INICIALIZACION DE VALORES */
		$this->loadModel('Comuna');
		$despacho =0;
		$total = $subtotal = $neto = $iva = $despacho = $descuento = 0;
		$lista_carrito = $productos = $descuentos = array();
		$aplicar_descuento = true;

		foreach ($carro as $producto_id => $cantidad)
			$lista_carrito[] = $producto_id;

		/* PRODUCTOS EN EL CARRO */
		$options = array(
			'conditions' => array(
				'Stock.id' => $lista_carrito
			),
			'fields' => array(
				'Stock.id',
				'Stock.producto_id',
				'Stock.talla',
				'Stock.sku'
			),
			'contain' => array(
				'Producto' => array(
					'fields' => array(
						'Producto.id',
						'Producto.nombre',
						'Producto.categoria_id',
						'Producto.foto',
						'Producto.color_id',
						'Producto.codigo',
						'Producto.oferta',
						'Producto.precio',
						'Producto.precio_oferta',
						'Producto.oferta',
						'Producto.excluir_descuento',
						'Producto.escolar',
						'Producto.outlet',
						'Producto.dosporuno',
						'Producto.slug',
					),
					'Color'	=> array(
						'fields' => array(
							'Color.id',
							'Color.nombre',
							'Color.codigo'
						)
					),
					'Categoria.nombre'
				)
			),
			'order' => array('Producto.precio' => 'ASC')
		);
		if (! $productos_carro = $this->Producto->Stock->find('all',$options))
			return false;

		if ($this->Session->check('Descuento'))
			$descuentos = $this->Session->read('Descuento');

		/* CATEGORIAS DE ROPA */
		$options = array(
			'conditions' => array(
				'OR' => array(
				//	array('Categoria.parent_id' => 58),
					array('Categoria.id' => 58)
				),
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		);
		$categorias_ropa = $this->Producto->Categoria->find('list',$options);
		$conversion = array(1 => 'XS','S','M','L','XL','XXL'); // CONVERSION A TALLA DE ROPA

		foreach ( $productos_carro as $producto )
		{
			/* CONVERSION A TALLA DE ROPA */
			if (in_array($producto['Producto']['categoria_id'],$categorias_ropa))
			{
				if ($producto['Stock']['talla'])
				{
					$talla_ropa = false;
					if (isset($conversion[$producto['Stock']['talla']]))
						$talla_ropa = $conversion[$producto['Stock']['talla']];
					if ($talla_ropa)
						$producto['Stock']['talla'] = $talla_ropa;
				}
			}

			$producto['cantidad'] = $carro[$producto['Stock']['id']];
			/* suma $$ de productos */
			$valor = $producto['Producto']['precio'];
			if ($producto['Producto']['oferta']==1)
			{
				if ($producto['Producto']['precio_oferta'] < $valor)
					$valor = $producto['Producto']['precio_oferta'];
			}
			$subtotal+= ($valor * $carro[$producto['Stock']['id']]);

			/* descuento??? */
			$productos[$producto['Stock']['id']] = $producto;
			if (! isset($descuentos[$producto['Stock']['id']]['tipo']))
				continue;
			if (! in_array($descuentos[$producto['Stock']['id']]['tipo'],array("DIN","POR")))
				continue;

			/* calcular descuento */
			$datos_descuento = $descuentos[$producto['Stock']['id']];
			if ($datos_descuento['tipo']=='DIN')	// descuento en dinero
			{
				$producto['Descuento'] = $datos_descuento;
				$descuento+=$datos_descuento['descuento'];
				$aplicar_descuento = false;
			}
			else	// descuento en porcentaje
			{
				//prx($datos_descuento);
				$producto['Descuento'] = $datos_descuento;
				$valor = $producto['Producto']['precio'];
				if ($producto['Producto']['oferta']==1)
				{
					if ($producto['Producto']['precio_oferta'] < $valor)
						$subtotal += ($producto['Producto']['precio'] - $producto['Producto']['precio_oferta']) * $datos_descuento['aplicar'];
				}
				$descuento+= ((($valor * $datos_descuento['descuento']) / 100)* $datos_descuento['aplicar']);

				/* REDONDEA */
				$valor = intval($descuento/10);
				$valor = $valor*10;
				if ( ($descuento % 10) > 0 )
					$valor+=10;
				$descuento = $valor;
				// cambia estado a aplicacion de descuento
				$aplicar_descuento = false;
			}
			$productos[$producto['Stock']['id']] = $producto;
		}


		if ($this->Session->check('Despacho.direccion_id'))
		{
			$options = array(
				'conditions' => array(
					'Direccion.id' => $this->Session->read('Despacho.direccion_id')
				),
				'fields' => array(
					'Direccion.id',
					'Direccion.comuna_id',
					'Comuna.limite',
					'Comuna.despacho1',
					'Comuna.despacho2',
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'sitio_comunas',
						'alias' => 'Comuna',
						'type' => 'INNER',
						'conditions' => array(
							'Comuna.id = Direccion.comuna_id'
						)
					)
				)
			);
			if ($direccion = $this->Producto->Compra->Despacho->Direccion->find('first',$options))
			{
				$despacho = $direccion['Comuna']['despacho1'];
				if (($subtotal-$descuento) >= $direccion['Comuna']['limite'])
					$despacho = $direccion['Comuna']['despacho2'];
			}
		}
		$despacho =0;	
		if ( $dosporuno = $this->descuento_dosporuno() )
		{
			$descuento+=$dosporuno;
		}
		if($comuna_id && !$tipo_retiro)
		{
			$despacho = $this->Comuna->calcularDespacho($comuna_id, $subtotal);
		}


		//$despacho = 0;
		$subtotal = (intval($subtotal/10))*10;
		$total = ($subtotal + $despacho) - $descuento;
		$neto = (intval(($total / 1.19)/10))*10;
		$iva = $total - $neto;
		$datos_compra = array(
			'ListadoProductos'	=> $productos,
			'DetalleCompra'		=> array(
				'subtotal'		=> $subtotal,
				'neto'			=> $neto,
				'iva'			=> $iva,
				'despacho'		=> $despacho,
				'descuento'		=> $descuento,
				'total'			=> $total
			),
			'AplicarDescuento'	=> $aplicar_descuento
		);
		return $datos_compra;
	}

	private function descuento_dosporuno()
	{
		$porcentaje_descuento = 50;
		// validar carro
		if (! $this->Session->check('Carro'))
			return false;

		if (! $carro = $this->Session->read('Carro') )
			return false;
		// leer carro
		$list = array();
		foreach ($carro as $stockId => $cantidad)
		{
			array_push($list, $stockId);
		}
		//prx($carro);


		// consultar productos 2x1
		$options = array(
			'conditions' => array(
				'Stock.id' => $list,
				'Producto.dosporuno' => 1,
				'Producto.outlet' => 0
			),
			'fields' => array(
				'Producto.id',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Stock.id'
			),
			'joins' => array(
				array(
					'table' => 'sitio_stocks',
					'alias' => 'Stock',
					'type' => 'INNER',
					'conditions' => array(
						'Stock.producto_id = Producto.id'
					)
				)
			),
			'order' => array('Producto.precio' => 'DESC'),
			'recursive' => -1
		);
		if (! $productos = $this->Producto->find('all', $options) )
			return false;

		// verificar productos 2x1
		$cantidad_2x1 =0;
		foreach ( $productos as $producto )
		{
			// validar cantidad
			if (! isset($carro[ $producto['Stock']['id'] ]['cantidad']) )
				continue;
			$cantidad = $carro[ $producto['Stock']['id'] ]['cantidad'];
			if (! $cantidad )
				continue;
			$cantidad_2x1 += $cantidad;
		}

		$listado_2x1 = array();
		$codigos = array();
		$i=0;
		$limit = (int)($cantidad_2x1 / 2);
		if(($cantidad_2x1 % 2) )
			$limit++;
	
		foreach ( $productos as $producto )
		{
			// validar cantidad
			if (! isset($carro[ $producto['Stock']['id'] ]['cantidad']) )
				continue;
			$cantidad = $carro[ $producto['Stock']['id'] ]['cantidad'];
			if (! $cantidad )
				continue;
		

			// recorre cantidades de productos para individualizar precios
			for ( $x=1; $x <= $cantidad; $x++ )
			{
				$precio = $producto['Producto']['precio'];
				if ( $producto['Producto']['oferta'] && $producto['Producto']['precio_oferta'] <= $producto['Producto']['precio'] )
					$precio = $producto['Producto']['precio_oferta'];
				{
					array_push($listado_2x1, $precio);
					//pr(compact('limit','i'));
					if($i++ >= $limit)
					{
						array_push($codigos,  $producto['Stock']['id']);
					}
				}
			}
		}

		// veficar cantidad de productos
		if (! $cont = count($listado_2x1) )
		{
			$this->Session->delete('2x1');
			return false;
		}
		if ( $cont <= 1 )
		{
			$this->Session->delete('2x1');
			return false;
		}
		// ordenar precios de mayor a menor
		if (! rsort($listado_2x1) )
			prx($listado_2x1);
		// determinar
		$limit = (int)($cont / 2);
		$descuento = 0;
		// sacar la mitad de precios de menor valor
/*
		foreach ( $listado_2x1 as $index => $precio )
		{
		
			if (! is_numeric($porcentaje_descuento))
				continue;
			if ($porcentaje_descuento <= 0 && $porcentaje_descuento > 100)
				continue;
			if ($porcentaje_descuento < 100)
			{
				$precio = (int)($precio * ($porcentaje_descuento / 100));
				if ( ($precio % 10) > 0 )// redondea descuento
					$precio = (((int)($precio/10))*10)+10;
				else
					$precio = ((int)($precio/10))*10;
			}
			$descuento+=$precio;
		}
		*/
		for($i=1;$i<=$limit;$i++)
		{
			$precio = $listado_2x1[count($listado_2x1)-$i];
			if (! is_numeric($porcentaje_descuento))
				continue;
			if ($porcentaje_descuento <= 0 && $porcentaje_descuento > 100)
				continue;
			if ($porcentaje_descuento < 100)
			{
				$precio = (int)($precio * ($porcentaje_descuento / 100));
				if ( ($precio % 10) > 0 )// redondea descuento
					$precio = (((int)($precio/10))*10)+10;
				else
					$precio = ((int)($precio/10))*10;
			}
			$descuento+=$precio;
		}


		$this->Session->write('2x1', $codigos);

		return $descuento;
	}


	function elim_prod()
	{
		// VERIFICA SI VIENE ID
		if(isset($this->params['form']['id']) && $this->params['form']['id'])
		{
			$options = array(
				'conditions' => array(
					'Compra.usuario_id' => $this->Auth->user('id')
				),
				'fields' => array(
					'Compra.id'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'sitio_productos_compras',
						'alias' => 'ProductosCompra',
						'type' => 'INNER',
						'conditions' => array(
							'ProductosCompra.compra_id = Compra.id',
							'ProductosCompra.compra_id' => $this->params['form']['id']
						)
					)
				)
			);
			if (! $compra = $this->Producto->Compra->find('first',$options))
				die('ELIMINO_FAIL');

			//SE ELIMINA EL PRODUCTO DE LA ORDEN DE COMPRA
			if( $this->Producto->ProductosCompra->delete( $this->params['form']['id'] ) )
			{
				// VERIFICA SI LA ORDEN TIENE PRODUCTOS, SI NO TIENE ELIMINA LA ORDEN
				$productos = $this->Producto->ProductosCompra->find('count', array('conditions' => array('ProductosCompra.compra_id' => $compra['Compra']['id'])));
				if( $productos < 1 )
				{
					if($this->Producto->Compra->delete($compra['Compra']['id']))
						die('DESTRUCCION_OK');
				}
				die('ELIMINO_OK');
			}
		}
		die('ELIMINO_FAIL');
	}

	function ropa()
	{
		$categoria=array();
		if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
		{
			$options = array(
				'conditions' => array(
					'Categoria.slug' => $this->params['url']['categoria']
				),
				'fields' => array(
					'Categoria.id',
					'Categoria.nombre',
					'Categoria.alias'
				)
			);
			$categoria = $this->Producto->Categoria->find('first',$options);
		}
		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Categoria.id' => array(58,59,60))
				),
				'Producto.activo' => 1,
				'Producto.outlet' => 0,
				'NOT' => array(
					array('Producto.foto' => ''),
					array('Producto.foto' => null),
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
					)
				),
				1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'	=> array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)
				)
			),
			'order'	=> array(
				'Producto.orden DESC',
				'Producto.codigo ASC'
			),
			'group' => array('Producto.id')
		);

		if ($categoria)
			$options['conditions']['Producto.categoria_id'] = $categoria['Categoria']['id'];

		if (isset($this->params['url']['style']) && $this->params['url']['style'])
		{
			$options['conditions']['Producto.grupo LIKE'] = '%['.$this->params['url']['style'].']%';
		}

		if (isset($this->params['url']['talla']) && $this->params['url']['talla'])
		{
			$talla = 0;
			if ($this->params['url']['talla']=='xs')
				$talla=1;
			elseif ($this->params['url']['talla']=='s')
				$talla=2;
			elseif ($this->params['url']['talla']=='m')
				$talla=3;
			elseif ($this->params['url']['talla']=='l')
				$talla=4;
			elseif ($this->params['url']['talla']=='xl')
				$talla=5;
			elseif ($this->params['url']['talla']=='xxl')
				$talla=6;

			$options['joins'][1] = array(
				'table' => 'sitio_tallas',
				'alias' => 'FiltroTalla',
				'type' => 'INNER',
				'conditions' => array(
					'FiltroTalla.producto_id = Producto.id',
					'FiltroTalla.cantidad >=' => $this->stock_seguridad,
					'FiltroTalla.talla' => $talla
				)
			);
		}

		if(isset($this->params['url']['orden']) && $o = $this->params['url']['orden'])
		{
			if ($o==1)
				$options['order'] = array('Producto.precio' => 'DESC');
			elseif ($o==2)
				$options['order'] = array('Producto.precio' => 'ASC');
			elseif ($o==3)
				$options['order'] = array('Producto.nombre' => 'ASC');
			elseif ($o==4)
				$options['order'] = array('Producto.codigo' => 'ASC');
		}

		$productos = $this->Producto->find('all',$options);
		$cont = count($productos);

		$filtros = $otros = array();
		$titulo = 'ROPA';

		if ($categoria)
		{
			$titulo.= ' <small><i class="fa fa-angle-double-right"></i> '.$categoria['Categoria']['alias'];
		}

		$options = array(
			'conditions' => array(
				'Categoria.parent_id' => 58
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre',
				'Categoria.alias',
				'Categoria.slug'
			),
			'recursive' => -1
		);
		// filtro categorias
		if ($categorias = $this->Producto->Categoria->find('all',$options))
		{
			$filtro = array(
				'name' => 'Género',
				'field' => 'categoria',
				'options' => array()
			);
			foreach ($categorias as $_categoria)
			{
				$filtro['options'][] = array(
					'name' => (($_categoria['Categoria']['alias']) ? $_categoria['Categoria']['alias']:$_categoria['Categoria']['nombre']),
					'value' => $_categoria['Categoria']['slug']
				);
			}
			$filtros[] = $filtro;
		}
		$this->loadModel('Estilo');
		$options = array(
			'conditions' => array(
				'Estilo.activo' => 1,
				'Categoria.publico' => 1,
				'OR' => array(
						array('Categoria.id' => array(58,59,60))
				)
			),
			'fields' => array(
				'Estilo.alias',
				'Estilo.nombre',
			),
			'joins' => array(
				array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Estilo.categoria_id'
					)
				)
			),
			'order' => array(
				'Estilo.nombre' => 'ASC'
			)
		);
		if ($estilos = $this->Estilo->find('list',$options))
		{
			// filtros
			$filtro = array(
				'name' => 'Estilos',
				'field' => 'style',
				'options' => array()
			);
			foreach ($estilos as $estiloAlias => $estiloName)
			{
				$filtro['options'][] = array(
					'name' => $estiloName,
					'value' => $estiloAlias
				);
			}
			$filtros[] = $filtro;
		}

		$filtro = array(
			'name' => 'Tallas',
			'field' => 'talla',
			'options' => array(
				 array(
				 	'name' => 'XS',
				 	'value' => 'xs'
				 ),
				array(
					'name' => 'S',
					'value' => 's'
				),
				array(
					'name' => 'M',
					'value' => 'm'
				),
				array(
					'name' => 'L',
					'value' => 'l'
				),
				array(
					'name' => 'XL',
					'value' => 'xl'
				),
					array(
					'name' => 'XXL',
					'value' => 'xxl'
				),
			)
		);
		$filtros[] = $filtro;
		// filtro ordenar
		$filtro = array(
			'name' => 'Ordenar por Precio',
			'field' => 'orden',
			'options' => array(
				array(
					'name' => 'De mayor a menor',
					'value' => 1
				),
				array(
					'name' => 'De menor a mayor',
					'value' => 2
				)
			)
		);
		$filtros[] = $filtro;
		$canonical ='https://www.skechers.cl/ropa';
		$descripcion = 'Renueva tu outfit y combina tus calzados junto a esa polera, chaqueta, calza o cortaviento Skechers. ¡No esperes más!';
		$this->set(compact('productos','cont','ordenar','filtros','titulo','canonical','descripcion'));
	}
	function reloj()
	{
		$categoria=array();
		if (isset($this->params['url']['categoria']) && $this->params['url']['categoria'])
		{
			$options = array(
				'conditions' => array(
					'Categoria.slug' => $this->params['url']['categoria']
				),
				'fields' => array(
					'Categoria.id',
					'Categoria.nombre',
					'Categoria.alias'
				)
			);
			$categoria = $this->Producto->Categoria->find('first',$options);
		}
		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Categoria.id' => array(65,66,64))
				),
				'Producto.activo' => 1,
				'Producto.outlet' => 0,
				'NOT' => array(
					array('Producto.foto' => ''),
					array('Producto.foto' => null),
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto',
				'Producto.foto_categoria',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.escolar',
				'Producto.new',
				'Producto.slug',
				'Producto.stock_seguridad',
				'Producto.colores'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1,
					)
				),
				1 => array(
					'table' => 'sitio_tallas',
					'alias' => 'FiltroTalla',
					'type' => 'INNER',
					'conditions' => array(
						'FiltroTalla.producto_id = Producto.id',
					)
				)
			),
			'contain'	=> array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'conditions' => array(
						'Talla.cantidad >=' => $this->stock_seguridad
					),
					'fields' => array(
						'Talla.id',
						'Talla.talla',
						'Talla.producto_id',
						'Talla.cantidad'
					)
				)
			),
			'order'	=> array(
				'Producto.orden DESC'
			),
			'group' => array('Producto.id')
		);

		if ($categoria)
			$options['conditions']['Producto.categoria_id'] = $categoria['Categoria']['id'];


		if(isset($this->params['url']['orden']) && $o = $this->params['url']['orden'])
		{
			if ($o==1)
				$options['order'] = array('Producto.precio' => 'DESC');
			elseif ($o==2)
				$options['order'] = array('Producto.precio' => 'ASC');
			elseif ($o==3)
				$options['order'] = array('Producto.nombre' => 'ASC');
			elseif ($o==4)
				$options['order'] = array('Producto.codigo' => 'ASC');
		}

		$productos = $this->Producto->find('all',$options);
		$cont = count($productos);

		$filtros = $otros = array();
		$titulo = 'Relojes';

		if ($categoria)
		{
			$titulo.= ' <small><i class="fa fa-angle-double-right"></i> '.$categoria['Categoria']['alias'];
		}

		$options = array(
			'conditions' => array(
				'Categoria.parent_id' => 58
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre',
				'Categoria.alias',
				'Categoria.slug'
			),
			'recursive' => -1
		);
		// filtro categorias
		if ($categorias = $this->Producto->Categoria->find('all',$options))
		{
			$filtro = array(
				'name' => 'Género',
				'field' => 'categoria',
				'options' => array()
			);
			foreach ($categorias as $_categoria)
			{
				$filtro['options'][] = array(
					'name' => (($_categoria['Categoria']['alias']) ? $_categoria['Categoria']['alias']:$_categoria['Categoria']['nombre']),
					'value' => $_categoria['Categoria']['slug']
				);
			}
			$filtros[] = $filtro;
		}

	
		$filtro = array(
			'name' => 'Precio',
			'field' => 'orden',
			'options' => array(
				array(
					'name' => 'De mayor a menor',
					'value' => 1
				),
				array(
					'name' => 'De menor a mayor',
					'value' => 2
				)
			)
		);
		$filtros[] = $filtro;
		$canonical ='https://www.skechers.cl/reloj';
		$descripcion = 'Si el tiempo es todo para ti, entonces llévalo con estilo y junto a los mejores diseños. Descubre tu reloj junto a Skechers.';
		$this->set(compact('productos','cont','ordenar','filtros','titulo','canonical','descripcion'));
	}

	function admin_no_publicados()
	{
		$this->Producto->recursive = 0;
		$options = array(
			'conditions' => array(
				
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		);
		$lista_categorias = $this->Producto->Categoria->find('list',$options);
		$this->paginate = array(
			'conditions' => array(
				'Producto.categoria_id' => $lista_categorias
			),
			'conditions' => array(
				'Producto.activo' => 1,
				'OR' => array(
					array('Producto.foto' => NULL),
					array('Producto.foto' => ''),
					array('Producto.precio <' => 4000),
					array('Producto.categoria_id' => 0),
					array('Producto.categoria_id' => 63)
				)
			),
			'order' => array(
				'Producto.categoria_id' => 'DESC',
				'Producto.nombre' => 'ASC',
				'Producto.codigo' => 'ASC'
			)
		);
		$this->set('productos', $this->paginate());
	}
	

	function admin_index()
	{
		$this->Producto->recursive = 0;
		$options = array(
			'conditions' => array(
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		);
		$lista_categorias = $this->Producto->Categoria->find('list',$options);
		$this->paginate = array(
			'conditions' => array(
				'Producto.categoria_id' => $lista_categorias
			),
			'order' => array(
				'Producto.categoria_id' => 'DESC',
				'Producto.nombre' => 'ASC',
				'Producto.codigo' => 'ASC'
			)
		);
		$this->set('productos', $this->paginate());
	}

	function admin_nuevos()
	{
		$this->Producto->recursive = 0;
		$this->paginate = array(
			'conditions' => array(
				'Producto.categoria_id' => array(12,13,14,15,26)
			),
			'order' => array(
				'Producto.categoria_id' => 'DESC',
				'Producto.nombre' => 'ASC',
				'Producto.codigo' => 'ASC'
			)
		);
		$this->set('productos', $this->paginate());
		$this->render('admin_index');
	}
	function admin_nuevos_arreglar()
	{
		$this->Producto->recursive = 0;
		$this->paginate = array(
			'conditions' => array(
				'Producto.categoria_id' => array(12,13,14,15,26),
				'OR' => array(
					array('Producto.foto' => NULL),
					array('Producto.foto' => '')
				),
				'Producto.new' => 1,
				'Producto.id >' => 1102
			),
			'order' => array(
				'Producto.categoria_id' => 'DESC',
				'Producto.nombre' => 'ASC',
				'Producto.codigo' => 'ASC'
			)
		);
		$this->set('productos', $this->paginate());
		$this->render('admin_index');
	}

	function admin_antiguos()
	{
		$this->Producto->recursive = 0;
		$this->paginate = array(
			'conditions' => array(
				'Producto.categoria_id' => array(50,51,52,53,54,55,56,57,58,59)
			),
			'order' => array(
				'Producto.categoria_id' => 'DESC',
				'Producto.nombre' => 'ASC',
				'Producto.codigo' => 'ASC')
		);
		$this->set('productos', $this->paginate());
		$this->render('admin_index');
	}

	function admin_todos($buscar = null)
	{
		$this->Producto->recursive = 0;
		if ($buscar)
		{
			$condicion = array(
				'conditions' => array(
					'OR' => array(
						array('Producto.codigo_completo LIKE' => '%' . $buscar . '%'),
						array('Producto.nombre LIKE' => '%' . $buscar . '%')
					)
				),
				'order' => array(
					'Producto.categoria_id' => 'DESC',
					'Producto.nombre' => 'ASC',
					'Producto.codigo' => 'ASC'
				)
			);
			$this->data['Producto']['buscar'] = $buscar;
		}
		else
		{
			$condicion = array(
				'order' => array(
					'Producto.categoria_id' => 'DESC',
					'Producto.nombre' => 'ASC',
					'Producto.codigo' => 'ASC'
				)
			);
		}
		$this->paginate = $condicion;
		$this->set('productos', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$options = array(
			'conditions' => array(
				'Producto.id' => $id
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.categoria_id',
				'Producto.coleccion_id',
				'Producto.color_id',
				'Producto.foto',
				'Producto.codigo',
				'Producto.precio',
				'Producto.oferta',
				'Producto.precio_oferta',
				'Producto.descripcion',
				'Producto.ficha',
				'Producto.new',
				'Producto.escolar',
				'Producto.division',
				'Producto.tipo',
				'Producto.grupo',
				'Producto.slug'
			),
			'contain' => array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.nombre',
						'Color.codigo'
					)
				),
				'Categoria' => array(
					'fields' => array(
						'Categoria.id',
						'Categoria.nombre'
					)
				),
				'Coleccion' => array(
					'fields' => array(
						'Coleccion.id',
						'Coleccion.nombre'
					)
				)
			)
		);
		$producto = $this->Producto->find('first',$options);
		$this->set(compact('producto'));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			if (isset($this->data['Producto']['grupo']))
			{
				if ($this->data['Producto']['grupo'])
				{
					$grupos = '['.implode('][',$this->data['Producto']['grupo']).']';
					$this->data['Producto']['grupo'] = $grupos;
				}
				else
				{
					unset($this->data['Producto']['grupo']);
				}
			}
			//verifica ingreso de imagen a la galeria
			if( isset($this->data['Galeria']) && $this->data['Galeria'] )
			{
				$cont = 1;
				foreach( $this->data['Galeria'] as $index => $galeria )
				{
					if (! $galeria['imagen']['name'])
					{
						unset($this->data['Galeria'][$index]);
					}
					else
					{
						$this->data['Galeria'][$index]['orden'] = $cont;
						$cont++;
					}
				}

				if (! $this->data['Galeria'] )
					unset($this->data['Galeria']);
			}

			$options = array(
				'conditions' 	=> array(
					'Producto.codigo' => $this->data['Producto']['codigo'],
					'Producto.color_id' => $this->data['Producto']['color_id']
				),
				'fields'		=> array(
					'Producto.id',
					'Producto.codigo',
					'Producto.color_id'
				),
				'contain' 		=> array(
					'Color' => array(
						'fields' => array(
							'Color.id',
							'Color.codigo'
						)
					)
				)
			);
			if ( $repetido = $this->Producto->find('first',$options) )
			{
				$this->Session->setFlash(__('El producto con codigo: ' . $repetido['Producto']['codigo'] . $repetido['Color']['codigo'] . ' ya existe', true));
			}
			else
			{
				if ($this->data['Producto']['codigo'] && $this->data['Producto']['color_id'])
				{
					$options = array(
						'conditions' => array(
							'Color.id' => $this->data['Producto']['color_id']
						),
						'fields' => array(
							'Color.id',
							'Color.codigo'
						),
						'recursive' => -1
					);
					if ($color = $this->Producto->Color->find('first',$options))
					{
						$this->data['Producto']['codigo_completo'] = $this->data['Producto']['codigo'].$color['Color']['codigo'];
					}
				}
				$this->Producto->create();
				if ( $this->Producto->saveAll($this->data) )
				{
					$this->Session->setFlash(__('Registro guardado correctamente', true));
					$this->redirect(array('action' => 'edit', $this->Producto->id));
				}
				else
					$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$this->Producto->recursive = 2;
		$categorias = array();
		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Categoria.parent_id' => null),
					array('Categoria.parent_id' => 0)
				)
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre'
			),
			'contain' => array(
				'ChildCategoria' => array(
					'fields' => array(
						'ChildCategoria.id',
						'ChildCategoria.nombre',
						'ChildCategoria.parent_id'
					),
				   'order' => array(
						'ChildCategoria.nombre' => 'ASC'
					)
				)
			),
			'order' => array(
				'Categoria.nombre' => 'ASC'
			)
		);
		$lista_categorias = $this->Producto->Categoria->find('all',$options);
		if ($lista_categorias)
		{
			foreach ($lista_categorias as $categoria)
			{
				if ($categoria['ChildCategoria'])
				{
					$subcategorias = array();
					foreach ($categoria['ChildCategoria'] as $subcategoria)
					{
						$subcategorias[$subcategoria['id']] = $subcategoria['nombre'];
					}
					$categorias[$categoria['Categoria']['nombre']] = $subcategorias;
				}
				else
				{
					$categorias[$categoria['Categoria']['id']] = $categoria['Categoria']['nombre'];
				}
			}
		}
		if ( ! $categorias)
		{
			$this->Session->setFlash(__('Debes crear al menos una categoría', true));
			$this->redirect(array('controller' => 'categorias', 'action' => 'add'));
		}
		$options = array(
			'fields' => array(
				'Color.codigo'
			),
			'order' => array(
				'Color.codigo'
			)
		);
		if ( ! $colores = $this->Producto->Color->find('list',$options) )
		{
			$this->Session->setFlash(__('Debes tener al menos un color', true));
			$this->redirect(array('controller' => 'colores', 'action' => 'add'));
		}
		unset($this->data['Producto']['grupo']);
		$colecciones = $this->Producto->Coleccion->find('list', array('order' => array('Coleccion.id' => 'DESC')));
		$this->set(compact('categorias', 'colores', 'colecciones'));
	}

	function admin_edit($id = null)
	{
		if ( ! $id && empty($this->data) )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		//Check anteriores
		$checks = $this->Producto->ProductosTecnologia->find('all', array(
			'conditions' => array(
				'ProductosTecnologia.producto_id' => $id
			),
			'fields' => array(
				'ProductosTecnologia.tecnologia_id'
			)
		));
		// genera list para marcar los check anteriores en la vista
		foreach( $checks as $check )
		{
			$tecno_check[$check['ProductosTecnologia']['tecnologia_id']] = $check['ProductosTecnologia']['tecnologia_id'];
		}
		if ( ! empty($this->data) )
		{
			//verifica ingreso de imagen a la galeria
			if( isset($this->data['Galeria']) && $this->data['Galeria'] )
			{
				$cont = 1;
				foreach( $this->data['Galeria'] as $index => $galeria )
				{
					if (! $galeria['imagen']['name'])
					{
						unset($this->data['Galeria'][$index]);
					}
					else
					{
						$this->data['Galeria'][$index]['orden'] = $cont;
						$cont++;
					}
				}

				if (! $this->data['Galeria'] )
					unset($this->data['Galeria']);
			}

			//verifica cambio de foto de producto
			if ( ! $this->data['Producto']['foto']['name'] )
				unset($this->data['Producto']['foto']);

			if ($this->data['Producto']['codigo'] && $this->data['Producto']['color_id'])
			{
				$options = array(
					'conditions' => array(
						'Color.id' => $this->data['Producto']['color_id']
					),
					'fields' => array(
						'Color.id',
						'Color.codigo'
					),
					'recursive' => -1
				);
				if ($color = $this->Producto->Color->find('first',$options))
				{
					$this->data['Producto']['codigo_completo'] = $this->data['Producto']['codigo'].$color['Color']['codigo'];
				}
			}

			if ( $this->Producto->saveAll($this->data) )
			{
				// guardar log
				$this->guardar_log($this->Auth->user('id'), 'productos', 'admin_edit', 'editar producto '.$id, $_SERVER['REMOTE_ADDR']);

				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'edit', $this->data['Producto']['id']));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
				$this->redirect(array('action' => 'edit', $this->data['Producto']['id']));
			}
		}
		if ( empty($this->data) )
		{
			$this->data = $this->Producto->find('first', array(
				'conditions' => array(
					'Producto.id' => $id
				),
				'contain' => array(
					'Galeria' => array(
						'order' => array('Galeria.orden' => 'ASC')
					),
					'Minisitio',
					'Tecnologia'
				)
			));
		}
		$categorias = array();
		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Categoria.parent_id' => null),
					array('Categoria.parent_id' => 0)
				)
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre'
			),
			'contain' => array(
				'ChildCategoria' => array(
					'fields' => array(
						'ChildCategoria.id',
						'ChildCategoria.nombre',
						'ChildCategoria.parent_id'
					),
					'order' => array('ChildCategoria.nombre' => 'ASC')
				)
			),
			'order' => array('Categoria.nombre' => 'ASC')
		);
		if ($lista_categorias = $this->Producto->Categoria->find('all',$options))
		{
			foreach ($lista_categorias as $categoria)
			{
				if ($categoria['ChildCategoria'])
				{
					$subcategorias = array();
					foreach ($categoria['ChildCategoria'] as $subcategoria)
					{
						$subcategorias[$subcategoria['id']] = $subcategoria['nombre'];
					}
					$categorias[$categoria['Categoria']['nombre']] = $subcategorias;
				}
				else
				{
					$categorias[$categoria['Categoria']['id']] = $categoria['Categoria']['nombre'];
				}
			}
		}
		if ( ! $categorias)
		{
			$this->Session->setFlash(__('Debes crear al menos una categoría', true));
			$this->redirect(array('controller' => 'categorias', 'action' => 'add'));
		}

		$options = array(
			'fields' => array('Color.codigo'),
			'order' => array('Color.codigo')
		);
		if (! $colores = $this->Producto->Color->find('list',$options))
		{
			$this->Session->setFlash(__('Debes tener al menos un color', true));
			$this->redirect(array('controller' => 'colores', 'action' => 'add'));
		}
		$colecciones = $this->Producto->Coleccion->find('list', array(
			'order' => array('Coleccion.id' => 'DESC')
		));
		$this->set(compact('categorias', 'colores', 'colecciones'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Producto->save(array('id' => $id, 'categoria_id' => 50)) )
		{
			// guardar log
			$this->guardar_log($this->Auth->user('id'), 'productos', 'admin_delete', 'eliminar producto '.$id, $_SERVER['REMOTE_ADDR']);

			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}

	function admin_buscar($buscar = null)
	{
		if ($this->data['Producto']['buscar'])
		{
			$this->redirect(array('action' => 'buscar', $this->data['Producto']['buscar']));
		}

		if ($buscar)
		{
			$this->data['Producto']['buscar'] = $buscar;
		}
		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Producto.codigo_completo LIKE' => '%'.$buscar.'%'),
					array('Producto.nombre LIKE' => '%'.$buscar.'%')
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.codigo',
				'Producto.precio',
				'Producto.oferta',
				'Producto.precio_oferta',
				'Producto.foto',
				'Producto.color_id',
				'Producto.categoria_id'
			),
			'contain' => array(
				'Categoria' => array(
					'fields' => array(
						'Categoria.id',
						'Categoria.nombre'
					)
				),
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				)
			)
		);
		$this->paginate = $options;
		$productos = $this->paginate();
		$this->set(compact('productos'));
	}

	function admin_buscar_OLD()
	{
		$this->redirect(array('controller' => 'productos', 'action' => 'index'));
		if (! $this->data['Producto']['buscar'] )
			$this->redirect(array('action' => 'index'));
		$options = array(
			'conditions' => array(
				'Producto.codigo LIKE' => "%{$this->data['Producto']['buscar']}%"
			),
			'contain' => array(
				'Stock',
				'Color'
			)
		);
		if ( ! $productos = $this->Producto->find('all',$options) )
		{
			$this->Session->setFlash(__('No se encontraron productos', true));
			$this->redirect(array('action' => 'index'));
		}

		$lista = $this->Producto->find('list', array(
			'conditions' => array(
				'Producto.codigo LIKE' => "%{$this->data['Producto']['buscar']}%"
			),
			'fields' => array('Producto.id')
		));

		$tallas_aux = $this->Producto->Stock->find('all', array(
			'conditions' => array(
				'Stock.producto_id' => $lista
			),
			'group' => array('Stock.talla')
		));

		$tiendas = $this->Producto->Stock->find('all', array(
			'conditions' => array(
				'Stock.producto_id' => $lista
			),
			'contain' => array('Tienda'),
			'group' => array('Stock.tienda_id')
		));

		$tallas = array(0 => '-- Todas las Tallas');
		foreach( $tallas_aux as $talla )
		{
			$tallas[str_replace(".","",$talla['Stock']['talla'])] = $talla['Stock']['talla'];
		}
		$lista_tiendas = array(0 => '-- Todas las Tiendas');
		foreach( $tiendas as $index => $tienda )
		{
			$lista_tiendas[$tienda['Tienda']['id']] = $tienda['Tienda']['nombre'];
		}
		foreach ($productos as $index => $producto)
		{
			foreach( $producto['Stock'] as $index2 => $stock )
			{
				if (! isset($productos[$index]['Producto']['Tienda'][$stock['tienda_id']]) )
				{
					$productos[$index]['Producto']['Tienda'][$stock['tienda_id']] = array(
						'id' => $stock['tienda_id'],
						'nombre' => $lista_tiendas[$stock['tienda_id']]
					);
				}
				$productos[$index]['Producto']['Tienda'][$stock['tienda_id']]['Tallas'][str_replace(".","",$stock['talla'])] = $stock;
			}
			if (! $producto['Stock'] )
				unset($productos[$index]);
			unset($productos[$index]['Stock']);
		}
		$this->set(compact('tallas', 'tiendas', 'productos', 'lista_tiendas'));
	}

	function admin_emailcompra( $id_compra = null )
	{
		if (! $id_compra )
		{
			$this->Session->setFlash(__('Registro Invalido', true));
			$this->redirect(array('action' => 'index'));
		}

		$options = array(
			'conditions' => array(
				'Compra.id' => $id_compra
			),
			'contain' => array(
				'Pago',
				'Despacho' => array(
					'Direccion' => array(
						'Comuna'
					)
				)
			)
		);
		if ($compra = $this->Producto->Compra->find('first',$options))
		{
			// CARGAR DATOS DE TB
			//$compra['Pago'][0]['numeroTarjeta'] = 1111;
			$usuario = $this->Producto->Compra->Usuario->find('first', array(
				'conditions' => array(
					'Usuario.id' => $compra['Compra']['usuario_id']
				)
			));

			$productos = array();
			$options = array(
				'conditions' => array(
					'ProductosCompra.compra_id' => $compra['Compra']['id']
				),
				'order' => array(
					'ProductosCompra.producto_id' => 'ASC'
				)
			);
			$lista_productos = $this->Producto->ProductosCompra->find('all',$options);
			foreach ($lista_productos as $listaproducto)
			{
				$producto = $this->Producto->find('first', array(
					'conditions' => array(
						'Producto.id' => $listaproducto['ProductosCompra']['producto_id']
					),
					'contain' => array('Color')
				));
				$producto['Producto']['precio'] = $producto['Producto']['precio_oferta'] = $listaproducto['ProductosCompra']['valor'];
				$producto['Producto']['Color'] = $producto['Color'];
				$productos[] = array(
					'Producto' => $producto['Producto'],
					'ProductosCompra' => $listaproducto['ProductosCompra']
				);
			}
			$this->set(compact('productos', 'compra', 'usuario'));
			//EMAIL
			$this->Email->smtpOptions = array(
				'port' => '25',
				'timeout' => '30',
				'auth' => true,
				'host' => $this->datos_email['host'],
				'usern' => $this->datos_email['user'],
				'password' => $this->datos_email['pass']
			);
			// ========== EMAIL USUARIO
			// DATOS DESTINATARIO
			$this->Email->to = $usuario['Usuario']['email'];
			$this->Email->bcc	= array('ventas@skechers-chile.cl', 'pyanez@skechers.com', 'store383@skechers.com', 'cherrera@skechers.cl', 'ehenriquez@andain.cl', 'sdelvillar@andain.cl','solanger@skechers.com');
			$this->Email->subject = '[Skechers - Tienda en linea] Compra #' . $compra['Compra']['id'];
			$this->Email->from = 'Skechers <'.$this->email_skechers[2].'>';
			$this->Email->replyTo = $this->email_skechers[2];
			$this->Email->sendAs = 'html';
			$this->Email->template	= 'compra2';
			$this->Email->delivery = 'smtp';
			if ( $this->Email->send() )
			{
				$this->Session->setFlash(__('Correo enviado con exito', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('Se produjo un error al enviar el correo. Porfavor intentelo nuevamente', true));
				$this->redirect(array('action' => 'index'));
			}
		}
		else
		{
			$this->Session->setFlash(__('Registro Invalido', true));
			$this->redirect(array('action' => 'index'));
		}
	}

	function admin_excel_stock()
	{
		$categorias_publicas = $this->Producto->Categoria->find('list', array(
			'conditions' => array(
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		));
		$options = array(
			'conditions' => array(
				'Producto.categoria_id' => $categorias_publicas
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.codigo',
				'Producto.codigo_completo',
				'Producto.categoria_id',
				'Producto.color_id'
			),
			'contain' => array(
				'Categoria' => array(
					'fields' => array(
						'Categoria.id',
						'Categoria.nombre'
					)
				),
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
				'Talla' => array(
					'fields' => array(
						'Talla.id',
						'Talla.producto_id',
						'Talla.talla',
						'Talla.cantidad'
					)
				)
			)
		);
		$productos = $this->Producto->find('all',$options);
		$this->set(compact('productos'));
	}

	function admin_excel_productos()
	{
		$options = array(
			
			'fields' => array(
				'Producto.id',
				'Producto.categoria_id',
				'Producto.coleccion_id',
				'Producto.nombre',
				'Producto.codigo_completo',
				'Producto.codigo',
				'Producto.precio',
				'Producto.precio_oferta',
				'Producto.oferta',
				'Producto.activo',
				'Categoria.id',
				'Categoria.nombre',
				'Stock.id'
			),
			'joins' => array(
				array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id'
					)
				),
				array(
					'table' => 'sitio_stocks',
					'alias' => 'Stock',
					'type' => 'LEFT',
					'conditions' => array(
						'Stock.producto_id = Producto.id'
					)
				)
			),
			'contain' => array(
				'Coleccion.nombre',
				'Color.codigo'
			),
			'group' => array(
				'Producto.id'
			)
		);
		if ($productos = $this->Producto->find('all',$options))
		{
			$excel = '<table><tr><th>ID</th><th>codigo</th><th>style</th><th>color</th><th>categoria</th><th>coleccion</th><th>activo</th><th>stock</th><th>Precio</th><th>Precio Oferta</th><th>Oferta</th></tr>';
			foreach ($productos as $producto)
			{
				$excel.='<tr><td>'.$producto['Producto']['id'].'</td><td>'.$producto['Producto']['codigo_completo'].'</td><td>'.$producto['Producto']['codigo'].'</td><td>'. ( (isset($producto['Color']['codigo']) && $producto['Color']['codigo']) ? $producto['Color']['codigo'] : '' ) .'</td><td>'. ( (isset($producto['Categoria']['nombre']) && $producto['Categoria']['nombre']) ? $producto['Categoria']['nombre'] : '' ).'</td><td>'. ( (isset($producto['Coleccion']['nombre']) && $producto['Coleccion']['nombre']) ? $producto['Coleccion']['nombre'] : '' ).'</td><td>' . ( (isset($producto['Producto']['activo']) && $producto['Producto']['activo']) ? 'si' : 'no' ) . '</td><td>' . ( (isset($producto['Stock']['id']) && $producto['Stock']['id']) ? 'si' : 'no' ) . '</td><td>'.$producto['Producto']['precio'].'</td><td>'.$producto['Producto']['precio_oferta'].'</td><td>' . ( (isset($producto['Stock']['oferta']) && $producto['Stock']['oferta']) ? 'si' : 'no' ) . '</td></tr>';
			}
			$excel.= '</table>';

			$fileName = 'productos_activos_'.date('d-m-Y').'_'.date('H.i.s').'.xls';
			header('Content-Type: application/force-download');
			header('Content-Disposition: attachment; filename="'.$fileName.'"');
			header('Content-Transfer-Encoding: binary');
			$excel.='</table>';
			die(utf8_decode($excel));
		}
		die('No se encontraron productos...');
	}


	function admin_carga_productos()
	{
		//Configure::write('debug',1);
		set_time_limit(0);

		if (! empty($this->data))
		{
			//prx($this->data);
			$tipos = array(
				'calzado',
				'ropa',
				//'outlet'
			);
			// inicializar respuesta
			$respuesta = array(
				'estado' => true,
				'mensaje' => ''
			);
			// validar
			if (! isset($this->data['Producto']['tipo']))
			{
				$respuesta = array(
					'estado' => false,
					'mensaje' => 'Tipo de carga inválido.'
				);
			}
			if (! in_array($this->data['Producto']['tipo'], $tipos))
			{
				$respuesta = array(
					'estado' => false,
					'mensaje' => 'Tipo de carga inválido.'
				);
			}
			if (! isset($this->data['Producto']['archivo']['name']))
			{
				$respuesta = array(
					'estado' => false,
					'mensaje' => 'Archivo inválido.'
				);
			}
			if (! isset($this->data['Producto']['coleccion_id']))
			{
				$respuesta = array(
					'estado' => false,
					'mensaje' => 'Colección inválida.'
				);
			}
			$options = array(
				'conditions' => array(
					'Coleccion.id' => $this->data['Producto']['coleccion_id']
				),
				'fields' => array(
					'Coleccion.id'
				)
			);
			if (! $coleccion = $this->Producto->Coleccion->find('first', $options))
			{
				$respuesta = array(
					'estado' => false,
					'mensaje' => 'Colección inválida.'
				);
			}
			// si el pasa correctamente la validacion se intenta cargar el archivo
			if ($respuesta['estado'])
			{
				$resultado = $this->cargarProductosCsv__($this->data['Producto']['archivo'], $this->data['Producto']['tipo'], $this->data['Producto']['coleccion_id']);
				$this->set(compact('resultado'));
			}
			else
			{
				$this->Session->setFlash(__($respuesta['mensaje'], true));
			}
		}
		$tipos_carga = array(
			'calzado' => 'Shoes',
			'ropa' => 'Apparel',
			'outlet' => 'Outlet',
		);
		$colecciones = $this->Producto->Coleccion->find('list', array(
			'fields' => array(
				'Coleccion.id',
				'Coleccion.nombre'
			),
			'order' => array(
				'Coleccion.id' => 'DESC'
			)
		));
		$this->set(compact('tipos_carga', 'colecciones'));
	}

	private function cargarProductosCsv__($archivo = null, $tipo = 'calzado', $coleccion_id = null)
	{
		$resultado = array(
			'estado' => true,
			'mensaje' => null,
			'bkp' => false,
			'bkp_name' => null,
			'log_name' => null
		);
		if (! file_exists($archivo['tmp_name']))
		{
			$resultado = array_merge($resultado, array(
				'estado' => false,
				'mensaje' => 'No ha sido posible encontrar el archivo.'
			));
			return $resultado;
		}

		$basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'img'.DS.'cargas_andain'.DS.'productos'.DS;
		$resultado['bkp_name'] = "bkp_".strtotime(date('Y-m-d H:i:s')).'.csv';
		$resultado['log_name'] =  $basedir."bkp_".strtotime(date('Y-m-d H:i:s')).'.txt';
		$file = $basedir.$resultado['bkp_name'];

		if (! is_dir($basedir))
		{
			@mkdir($basedir, 0777, true);
		}

		function logCarga__($texto = '', $hora = true)
		{
			//pr($texto);
			$texto = ( $hora ? '('.date('H:i:s').') ' : '' ).$texto.PHP_EOL;
			return $texto;
		}

		function crearLog__($fileName = null, $texto = null)
		{
			$fp = fopen($fileName,"c");
			fwrite($fp,$texto.PHP_EOL);
			fclose($fp);
		}

		$log = '=== INICIO DE CARGA: ('.date('Y-m-d H:i:s').') ==='.PHP_EOL;

		if (! copy($archivo['tmp_name'],$file))
		{
			$log.=logCarga__('error al crear copia del archivo CSV');
			crearLog__($resultado['log_name'], $log);
			$resultado = array_merge($resultado, array(
				'estado' => false,
				'mensaje' => 'Error al generar respaldo.'
			));
			return $resultado;
		}

		$resultado['bkp'] = true;
		$log.=logCarga__('copia del archivo CSV creada con exito');

		$separador=';';

		$mapeo = array(
			0 => 'codigo_empresa',	//codigo completo
			1 => 'codigo',
			2 => 'nombre',
			3 => 'color',
			4 => 'categoria',
			5 => 'precio',
			6 => 'descripcion',
			);
	
		$registros = array();
		$log.=logCarga__('inicio de lectura del archivo CSV');
		// iniciar lectura del archivo
		if (( $handle = fopen($file, 'r') ) !== FALSE )
		{
			$linea = 0;
			while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
			{
				$linea++;
				if ($linea <= 1)
					continue;


				$registro = array();
				foreach ( $datos as $index => $valor )
				{
					if ( ! isset($mapeo[$index]) )
						continue;
					if ( $mapeo[$index] == false )
						continue;
					$registro[$mapeo[$index]] = trim($valor);
				}
				if (count($registro) < 5)
				{
					continue;
				}
				array_push($registros, $registro);
			}
		}

		$log.=logCarga__('fin de lectura del archivo CSV');
		$log.=logCarga__(count($registros).' lineas de registros');

		$stats = array(
			'total' => count($registros),
			'procesados' => 0,
			'nuevos' => 0,
			'actualizados' => 0,
			'invalido' => array(
				'color' => array(
					'list' => array(),
					'count' => 0
				),
				'categoria' => array(
					'list' => array(),
					'count' => 0
				)
			),
			'error' => array(
				'producto' => array(
					'list' => array(),
					'count' => 0
				)
			)
		);

		$log.=logCarga__('preparando procesamiento de datos');

		if ($registros)
		{
			$log.=logCarga__('carga de variables de verificacion');
			$colores = $this->Producto->Color->find('list', array(
				'fields' => array(
					'Color.codigo_empresa',
					'Color.id'
				)
			));
			if ($colores)
			{
				$log.=logCarga__('- colores OK');
			}
			else
			{
				$log.=logCarga__('- colores ERROR');
			}
			$categorias = $this->Producto->Categoria->find('list', array(
				'fields' => array(
					'Categoria.codigo_empresa',
					'Categoria.id',
				)
			));
			
			$codigos = $this->Producto->find('list', array(
				'fields' => array(
					'Producto.codigo_completo',
					'Producto.id',
				)
			));
			if ($codigos)
			{
				$log.=logCarga__('- codigos OK');
			}
			else
			{
				$log.=logCarga__('- codigos ERROR');
			}

			$i = 0;
			$log.=logCarga__('inicio de procesamiento de datos');

			foreach ($registros as $registro)
			{
				$i++;
				if (! $registro['codigo'])
				{
					$log.=logCarga__('- registro omitido por codigo'.$i);
					continue;
				}
				if (! $registro['color'])
				{
					$log.=logCarga__('- registro omitido por color'.$i);
					continue;
				}
				if (! isset($colores[$registro['color']]))
				{
					$log.=logCarga__('- color invalido en registro '.$i);
					if (! in_array($registro['color'], $stats['invalido']['color']['list']) )
					{
						array_push($stats['invalido']['color']['list'], $registro['color']);
					}
					$stats['invalido']['color']['count']++;
					continue;
				}
			//	prx($registro['categoria']);
				if (! isset($categorias[($registro['categoria'])]))
				{
					$log.=logCarga__('- genero invalido en registro '.$i);
					if (! in_array($registro['genero'], $stats['invalido']['categoria']['list']) )
					{
						array_push($stats['invalido']['categoria']['list'], $registro['categoria']);
					}
					$stats['invalido']['categoria']['count']++;
					continue;
				}
				$stats['procesados']++;
				// limpiar precio
				$precio = 0;
				if ($registro['precio'])
				{
					for ($x = 0; $x < strlen($registro['precio']); $x++)
					{
						if (is_numeric($registro['precio'][$x]))
						{
							if ($precio)
								$precio.= $registro['precio'][$x];
							else
								$precio = $registro['precio'][$x];
						}
					}
				}

				$activo = 1;
				if (isset($registro['fecha_activo']) && $registro['fecha_activo'])
				{
					$activo = 0;
				}
				$save = array(
					'codigo_completo' => $registro['codigo'].$registro['color'],
					'codigo' => $registro['codigo'],
					'nombre' => $registro['nombre'],
					'precio' => $precio,
					'oferta' => 0,
					'precio_oferta' => 0,
					'coleccion_id' => $coleccion_id,
					'outlet' => 0,
					'escolar' => 0,
					'categoria_id' => $categorias[($registro['categoria'])],
					'color_id' => $colores[$registro['color']],
					'excluir_descuento' => 0,
					'division' => 'division',
					'showroom' => 'showroom',
					'grupo' => '',
					'activo' => $activo
				);
				
				
				if (isset($codigos[$save['codigo_completo']]))
				{
					$save['id'] = $codigos[$save['codigo_completo']];
					if ( $this->Producto->save($save) )
					{
						$log.=logCarga__('+ registro actualizado '.$i.' - id:'.$save['id'].' codigo:'.$save['codigo_completo']);
						$stats['actualizados']++;
					}
					else
					{
						prx( $this->Producto->validationErrors);
						$log.=logCarga__('- error al actializar registro '.$i.' - id:'.$save['id'].' codigo:'.$save['codigo_completo']);
						$stats['error']['producto']['count']++;
						array_push($stats['error']['producto']['list'], $save['codigo_completo']);
					}
				}
				else
				{
					$this->Producto->create();
					if ( $this->Producto->save($save) )
					{
						$log.=logCarga__('+ registro creado '.$i.' - id:'.$this->Producto->id.' codigo:'.$save['codigo_completo']);
						$stats['nuevos']++;
					}
					else
					{
						$log.=logCarga__('- error al actializar registro '.$i.' - codigo:'.$save['codigo_completo']);
						$stats['error']['producto']['count']++;
						array_push($stats['error']['producto']['list'], $save['codigo_completo']);
					}
				}
			}
		}
		$log.=logCarga__('fin de procesamiendo de datos');
		$log.=logCarga__('========================================', false);
		$log.=logCarga__('== RESUMEN DE CARGA ==', false);
		$log.=logCarga__('estado de carga: '.( $resultado['estado'] ? 'finalizada':'con errores' ), false);
		$log.=logCarga__('archivo de respaldo: '.$resultado['bkp_name'], false);

		foreach ($stats as $dato => $valor)
		{
			if (is_array($valor))
			{
				foreach ($valor as $sub_dato => $sub_valor)
				{
					$log.=logCarga__($dato.' por '.$sub_dato.': '.$sub_valor['count'].' ('.implode(', ', $sub_valor['list']).')', false);
				}
			}
			else
			{
				$log.=logCarga__($dato.': '.$valor, false);
			}
		}
		crearLog__($resultado['log_name'], $log);
		$resultado['resumen'] = $stats;
		$resultado['mensaje'] = 'proceso de carga finalizado';
		return $resultado;
	}

	function generar_descuento()
	{
		$options = array(
			'conditions' => array(
				'Producto.outlet' => 0,
				'Producto.escolar' => 0,
				'Producto.activo' => 1,
				'OR' => array(
					array('Categoria.id' => array(1,2,3,4)),
					array('Categoria.parent_id' => array(1,2,3,4))
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.codigo_completo',
				'Producto.categoria_id',
				'Producto.coleccion_id',
				'Producto.precio',
				'Producto.oferta',
				'Producto.precio_oferta'
			),
			'joins' => array(
				array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id'
					)
				)
			)
		);
		$productos = $this->Producto->find('all', $options);
		foreach ($productos as $producto)
		{
			$precio = ($producto['Producto']['precio']*70)/1000;
			$precio = ((int)$precio)*10;
			$save = array(
				'id' => $producto['Producto']['id'],
				'oferta' => 1,
				'precio_oferta' => $precio
			);
			$this->Producto->save($save);
		}
		prx(count($productos));
	}
}
?>
