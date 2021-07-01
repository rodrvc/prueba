<?php
Class MultivendeComponent extends Object
{
	var $base_url =  'https://app.multivende.com';
	var $usuario = 'sebastian@expbox.cl';
	var $clave = 'Sebaseba.00';
	var $token = null;
	var $tokenRipley = null;
	var $merchant	= null;
	//var $bodega ='4cd67fcb-565e-45ff-bd4e-efc443749563';
	var $market_place_connection ='bf556d59-2045-4fd4-94e4-b83ac2bb6d9a';
	//var $market_place_connection ='b5a99ffe-73c8-4587-9438-06982071937b';
	var $bodega ='77c4bd25-4065-4006-af87-19f9c5217a6f';
	var $stock = 3;
	var $delay_compras ='+1 hours';
	var $usuario_id = 999999999;
	var $apiKeyDafiti ='e84e3a6082afda6527e16b60a4822b652ef3b35b';
	var $apiDafiti  = 'https://sellercenter-api.dafiti.cl';
	var $apiFalabella ='https://dev-gwsellerlink.falabella.com/';
	var $falabella_id ='8B28811B6B13B5DA';
	var $falabella_secret ='A855A1FACA88ECAD87DBC37BE5AFCCFF217D5682B2E870FFA3E6B223DDE7A227';
	var $falabellaToken = null;
	var $apiKeyRipley ='eed1cc0c-076b-497e-8fbb-ded4a84dcbab';
	var $usuario_ripley ='prod_skechers';
	var $clave_ripley ='RCSEj4RnRrw4nHJD';
	var $apiRipley = 'https://sellercenter.ripleylabs.com';
	//var $datos => array('mercadolibre' => array('usuario_id' => ))
	function initialize(&$controller)
	{
		// CONTROLADOR DE DONDE ES LLAMADO
		$this->Controller	=& $controller;

		// INICIALIZA EL OBJECTO FACEBOOK (ACCESO API)
	}
	private function setToken($token)
	{
		$this->token = $token;
	}
	public function getToken()
	{
		if(isset($this->token) && $this->token != null)
			return $this->token;
		else
			return false;
	}
	private function setTokenRipley($token)
	{
		$this->tokenRipley = $token;
	}
	public function getTokenRipley()
	{
		if(isset($this->tokenRipley) && $this->tokenRipley != null)
			return $this->tokenRipley;
		else
			return false;
	}
	private function setMerchant($merchant)
	{
		$this->merchant = $merchant;
	}
	private function getMerchant()
	{
		if(isset($this->merchant) && $this->merchant != null)
			return $this->merchant;
		else
			return false;
	}
	private function getStock($sku =null)
	{
		if($sku == null)
		{
			return array('12345678','12345679','10');
		}
	}
	private function signatureDafiti($request)
	{
		return(hash_hmac( 'sha256', $request , $this->apiKeyDafiti));

	}
	private function extraRequestDafiti()
	{
		return '&Timestamp='.date('Y-m-d').'T'.date('H').'%3A48%3A34-04%3A00&UserID=sebastian%40sancirilo.cl&Version=1.0';
	}
	private function guardarStock($array)
	{
		$array_json = json_encode($array);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => $this->base_url."/api/product-stocks/stores-and-warehouses/".$this->bodega."/bulk-set",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_SSL_VERIFYHOST => 0,
		 CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS =>$array_json,
		  CURLOPT_HTTPHEADER => array(
		    "Content-Type: application/json",
		    "Authorization: Bearer ".$this->getToken()
		  ),
		));
		$result  = curl_exec($curl);
		
	if( $result === false)
		{
	    	echo 'Curl error: ' . curl_error($curl);

		}
		else
		{
			$stocks_procesados = json_decode($result ,true);
			$errores_stock = array();
			foreach ($stocks_procesados as $stock)
			{
				if($stock['success'] != 1){
					$errores_stock[]= array('sku' => $stock['code'],
										   'error' => $stock['error']
										   );
				}
			}
			return $errores_stock;

		}

	}
	public  function getVentas($desde,$hasta,$pagina=1)
	{
		$merchant = $this->getMerchant();
		$merchant_id = $merchant['_id'];
		$access_token = $this->getToken();
		$url = $this->base_url."/api/m/".$merchant_id."/checkouts/light/p/".$pagina."?_sold_at_from=".$desde."&_sold_at_to=".$hasta;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
	  	CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array(
			     "Content-Type: application/json",	
			     "Authorization: Bearer ".$this->getToken()
			  ),
			));
		$result = curl_exec($curl);
		if($result === false)
		{
	    	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode($result ,true);
			curl_close($curl);
			return $response;
		}

	}
	public function getVenta($id)
	{
			$curl = curl_init();

			curl_setopt_array($curl, array(
	  		CURLOPT_URL => $this->base_url."/api/checkouts/".$id,
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
	    		"Content-Type: application/x-www-form-urlencoded",
	    		"Authorization: Bearer ".$this->getToken()
	  ),
	));

		$response = json_decode(curl_exec($curl),true);
		curl_close($curl);
		return $response;

	}
	public function getVentaDebug($id)
	{
			$curl = curl_init();

			curl_setopt_array($curl, array(
	  		CURLOPT_URL => $this->base_url."/api/checkouts/".$id,
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
	    		"Content-Type: application/x-www-form-urlencoded",
	    		"Authorization: Bearer ".$this->getToken()
	  ),
	));

		$response = json_decode(curl_exec($curl),true);
		prx($response);
		curl_close($curl);
		return $response;

	}
	private function getCompras($completadas = true)
	{
		$compraModel = ClassRegistry::init('Compra');
		$compras = $compraModel->find('list', array('fields'=> array('id','token'),
													'conditions' => array('local' =>1,
																		  'despachado' => NULL,
																		  'estado' => 1)));
		//prx($compras);
	
		return ($compras);
	
	}
	function authenticate()
	{

		$curl = curl_init();
		$clave= $this->clave;
		$usuario = $this->usuario;
		//prx(compact ('clave','usuario'));
		$post = json_encode(array('email' => $this->usuario, 'password' => $this->clave));
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->base_url."/auth/local",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $post,
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "Content-Type: application/json"
		  ),
		));
		$result = curl_exec($curl);
		if($result  === false)
		{
	    	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode($result ,true);
			$this->setToken($response['token']);
			$usuario = $this->getData();
			//prx($usuario);
			if(isset($usuario['Merchants'][1]))
			{
				$this->setMerchant($usuario['Merchants'][1]);
				return true;
			}
			else
				return false;
			}
	}

	public function authenticate_ripley()
	{
		$curl = curl_init();
		$url = $this->apiRipley.'/api/current/auth/login/vendor';
		$headers = 'Authorization: Basic '.$this->usuario_ripley.':'.$this->clave_ripley;
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  //CURLOPT_USERPWD => 
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Basic '.base64_encode($this->usuario_ripley.':'.$this->clave_ripley)
		  ),
		));

		$result = curl_exec($curl);
		if($result   === false)
		{
	    	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode($result ,true);
		    $this->setTokenRipley($response['access_token']);
			return true;
		}

	}



	public function getConexiones()
	{
		$merchant = $this->getMerchant();
		$merchant_id = $merchant['_id'];
		$access_token = $this->getToken();
		$url = $this->base_url."/api/m/".$merchant_id."/mercadolibre-connections/p/1";

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Bearer ".$this->getToken()
		  )
		  ));
		$result  = curl_exec($curl);
		if($result   === false)
		{
	    	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode($result ,true);
			return $response;
		}



	}
	public function getStockPorSku()
	{
		$productoModel =ClassRegistry::init('Producto');
		$productos = $productoModel->find('list', array('fields' => array('id','id'),
														'conditions' => array('marketplace' => 1)));
		$stockModel = ClassRegistry::init('Stock');
		$skus = $stockModel->find('list', array('fields' => array('sku'),
												'conditions' => array('cantidad >' => 5, 'producto_id' => $productos )));
		return $skus;
	}

	private function getData()
	{

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->base_url."/api/users/me",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Bearer ".$this->getToken()
		  ),
		));
		if(curl_exec($curl) === false)
		{
	    	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode(curl_exec($curl),true);
			//prx($response);
			return $response;
		}
	}
	
	public function getProductos()
	{
		$merchant = $this->getMerchant();
		$merchant_id = $merchant['_id'];
		$access_token = $this->getToken();
		$url = $this->base_url."/api/m/".$merchant_id."/products/p/1";
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Bearer ".$this->getToken()
		  )
		  ));
		if(curl_exec($curl) === false)
		{
	    	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode(curl_exec($curl),true);
			//prx($response);
			return $response;
		}


	}
public function setStockMasivo( $skus = null, $cantidad = null)
	{
		if(is_null($skus))
		{
			$skus = $this->getStockPorSku();

		}
		$skus = $this->getSkus();
		$stocks = $this->getStockPorSku();

		Configure::write('debug', 2);
		$i=1;
		$errores_stock = array();
		foreach ($skus as $sku)
		{
			if(in_array($sku,$stocks))
			{
				$cantidad = 3;
			}
			else
				$cantidad =0;
			$stock[]=array('code' => $sku,'amount' => $cantidad);
			if($i++ %1000 ==0)
			{
				$respuesta = $this->guardarStock($stock);
				if($respuesta)
				{
					if(!empty($respuesta))
						$errores_stock = array_merge($errores_stock, $respuesta);
				}
				$stock = array();

			}
		}
		if(!empty($stock))
		{
			$respuesta = $this->guardarStock($stock);
			if(!empty($respuesta))
				$errores_stock = array_merge($errores_stock, $respuesta);
		}
		prx($errores_stock);

	}
	public function setStock($sku, $cantidad = null)
	{
		if(is_null($cantidad))
		{
			$cantidad = $this->stock;
		}

		$stock =array(array('code' => $sku,'amount' => $cantidad));

		if($respuesta = $this->guardarStock($stock))
		{
			if(!empty($respuesta))
			{
				$errores_stock = array_merge($errores_stock, $respuesta);
			}
		}
	
	}
	public function setStock0()
	{
		$productos = $this->getProductos();
		$skus = array();
		foreach ($productos['entries'] as $producto)
		{
			foreach ($producto['ProductVersions'] as $version) 
			{
				$skus []= $version['code'];
			}
		}
		$this->setStockMasivo($skus,0);
		
	}
	public function getSkus()
	{
		$merchant = $this->getMerchant();
		$merchant_id = $merchant['_id'];
		$access_token = $this->getToken();
		$url = $this->base_url."/api/m/".$merchant_id."/products/p/1";
		$curl = curl_init();
		$skus = array();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Bearer ".$this->getToken()
		  )
		  ));
		if(curl_exec($curl) === false)
		{
	    	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode(curl_exec($curl),true);
			foreach ($response['entries'] as $producto)
			{
				foreach ($producto['ProductVersions'] as $sku)
				{
					$skus[] = $sku['code'];
				}
			}
			if($response['pagination']['next_page'] != 0)
			{
				for($i=$response['pagination']['next_page'];$i <=  $response['pagination']['total_pages'];$i++)
				{
					$arr = $this->getSkus2($i);
					$skus = array_merge($arr,$skus);
					echo $i.'<br>';
				}
			}
			return ($skus);
		}
	}

	

	public function getSkus2($pagina)
	{
		$merchant = $this->getMerchant();
		$merchant_id = $merchant['_id'];
		$access_token = $this->getToken();
		$sku = array();
		$url = $this->base_url."/api/m/".$merchant_id."/products/p/".$pagina;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Bearer ".$this->getToken()
		  )
		  ));
		if(curl_exec($curl) === false)
		{
	    	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode(curl_exec($curl),true);
			foreach ($response['entries'] as $producto)
			{
				foreach ($producto['ProductVersions'] as $sku)
				{
					$skus[] = $sku['code'];
				}
			}
			return $skus;
		}


	}
	public function procesarVentasHistoricas()
	{
		
		for($i=2;$i<6;$i++)
		{
			$desde ='2020-06-'.($i-1);
			$hasta = '2020-06-'.$i;
			$this->procesarVentas($desde,$hasta);
		}
	}
	public function procesarVentaUnica($id)
	{
		$compraModel = ClassRegistry::init('Compra');
		$productoModel = ClassRegistry::init('Producto');
		$direccionModel = ClassRegistry::init('Direccion');
		$venta = ($this->getVenta($id));
		if($venta)
		{
			echo'aca';
			if($venta['paymentStatus'] == 'completed')
			{
						

				print_r($venta);
				$existe = $compraModel->find('first', array('conditions' => array('Compra.token' => $venta['_id'])));
				var_dump($existe);
				if($existe)
					return false;
				//prx($venta['DeliveryOrderInCheckouts'][0]['DeliveryOrder']['shippingLabelStatus']);
				$direccion = $venta['DeliveryOrderInCheckouts'][0]['DeliveryOrder']['ShippingAddress'];
				$task = '';
				if($venta['origin'] == 'mercadolibre')
				{
					
					$direccion_despacho = array('usuario_id' => $this->usuario_id,
										'nombre' => $venta['Client']['fullName'],
										'calle' => $direccion['street'],
										'numero' => $direccion['number'],
										'depto' => $direccion['indications'],
										'otras_indicaciones' => $direccion['city']
										);
					$task = $this->askCebra($venta['DeliveryOrderInCheckouts'][0]['DeliveryOrderId']);

				}else if ($venta['origin'] == 'dafiti')
				{
					$direccion_despacho = array('usuario_id' => $this->usuario_id,
										'nombre' => $venta['Client']['fullName'],
										'calle' => $direccion['address_1'],
										//'numero' => $direccion['number'],
										'depto' => trim(str_replace('Dpto/Casa/Of:','',$direccion['address_2'])),
										'otras_indicaciones' => $direccion['city']
										);
				}

				//prx($direccion_despacho);
				if($direccion['indications'] && $direccion['indications'] !='')
					$direccion_despacho['depto'] = $direccion['indications'];
				$direccionModel->create();
				$direccionModel->save($direccion_despacho);
				$direccionModel->Despacho->create();
				$direccionModel->Despacho->save(array('usuario_id' =>$this->usuario_id,
													  'direccion_id' => $direccionModel->id));
				$neto = (int)($venta['gross']/1.19);
				$arr = array('Compra' => array('subtotal' => $venta['gross'],
									   'total' => $venta['gross'],
									   'neto' => $neto,
									   'iva' => $venta['gross'] - $neto,
									   'ip' => $venta['origin'],
									   'local' => 1,
									   'valor_despacho' => 0,
									   'despachado' => NULL,
									   'estado' => 1,
									   'token' => $venta['_id'],
									   'mv_despacho' => $venta['DeliveryOrderInCheckouts'][0]['DeliveryOrderId'],
									   'mv_orden' => $venta['CheckoutLinks'][0]['externalOrderNumber'],
									   'despacho_id' => $direccionModel->Despacho->id,
									   'mv_cliente' => $venta['Client']['fullName'],
									   'cod_despacho' => $venta['DeliveryOrderInCheckouts'][0]['DeliveryOrder']['code'],
									   'mv_task' => $task['_id'],
									   'mv_numero' => $venta['Client']['phoneNumber']));
				$compraModel->create();
				$compraModel->save($arr);
				$compra_id = $compraModel->id;
				foreach ($venta['CheckoutItems'] as $producto)
				{
					$codigo = $producto['ProductVersion']['Product']['code'];
					$producto_id = $productoModel->find('first', array('conditions' => array('Producto.codigo_completo' => $codigo)));
					if($producto_id)
					{
								//prx($producto_id);
						$cantidad = $producto['count'];
						for($i=0;$i<$cantidad;$i++)
						{	
							$productosCompra= array('sku' => $producto['ProductVersion']['code'],
													 'talla' => $producto['ProductVersion']['Size']['name'],
													 'cantidad' => 1,
													 'valor' => $producto['gross'],
													 'compra_id' => $compra_id,	
													 'producto_id' =>$producto_id['Producto']['id'],
													 'estado'	=> 1  );
							$compraModel->ProductosCompra->create();
							$compraModel->ProductosCompra->save($productosCompra);
						}
					}
				}	

			
		
			}

		}
	}
	public function procesarVentas($desde = null,$hasta = null)
	{
		Configure::write('debug',2);
		$compraModel = ClassRegistry::init('Compra');
		$productoModel = ClassRegistry::init('Producto');
		$direccionModel = ClassRegistry::init('Direccion');
	
		if($hasta == null)
			$hasta = date('Y-m-d', strtotime('+2 days'));
		if($desde == null)
			$desde = date('Y-m-d',  strtotime('-0 days'));
		print_r(compact('hasta','desde'));
		//die();
		$ventas = array();
		$orders = $this->getVentas($desde,$hasta,1);
		$ventas = $orders['entries'];

		if($orders['pagination']['next_page'] != 0)
		{

			for($i=$orders['pagination']['next_page'];$i <= $orders['pagination']['total_pages'];$i++)
			{

				$orders = $this->getVentas($desde,$hasta,$i);
				$ventas = array_merge($ventas,$orders['entries']);
			}

		}
		foreach ($ventas as $venta)
		{
			if($venta['paymentStatus'] == 'completed')
			{
				
				$existe = $compraModel->find('first', array('conditions' => array('Compra.token' => $venta['_id'])));
				if($existe)
				{
					continue;
				}
				$venta = ($this->getVenta($venta['_id']));
			
				$direccion = $venta['DeliveryOrderInCheckouts'][0]['DeliveryOrder']['ShippingAddress'];
				$task = null;
				$verificado = 0;
				$orderId ='';
				$valor_despacho = 0;
				$original = $venta['CheckoutLinks'][0]['externalOrderNumber'];
				if($venta['origin'] == 'mercadolibre')
				{
					$direccion_despacho = array('usuario_id' => $this->usuario_id,
										'nombre' => $venta['Client']['fullName'],
										'calle' => $direccion['street'],
										'numero' => $direccion['number'],
										'depto' => $direccion['indications'],
										'otras_indicaciones' => $direccion['city']
										);
					$task = $this->askCebra($venta['DeliveryOrderInCheckouts'][0]['DeliveryOrderId']);
					if($venta['CheckoutLinks'][0]['externalOrderNumber'] > 20000000000)
					{

						$venta['CheckoutLinks'][0]['externalOrderNumber'] = substr($venta['CheckoutLinks'][0]['externalOrderNumber'],0,2).substr($venta['CheckoutLinks'][0]['externalOrderNumber'],7);
					}


				}else if ($venta['origin'] == 'dafiti')
				{
					$direccion_despacho = array('usuario_id' => $this->usuario_id,
										'nombre' => $venta['Client']['fullName'],
										'calle' => $direccion['address_1'],
										//'numero' => $direccion['number'],
										'depto' => trim(str_replace('Dpto/Casa/Of:','',$direccion['address_2'])),
										'otras_indicaciones' => $direccion['city']
										);
					$orderId = $venta['CheckoutLinks'][0]['externalContent']['OrderId'];
					$valor_despacho = $venta['DeliveryOrderInCheckouts'][0]['DeliveryOrder']['cost'];
					$this->setDespachoDafiti($orderId );
				}else if ($venta['origin'] == 'ripley')
				{
					$direccion_despacho = array('usuario_id' => $this->usuario_id,
										'nombre' => $venta['Client']['fullName'],
										'calle' => $direccion['address_1'],
										//'numero' => $direccion['number'],
										'depto' => trim(str_replace('Dpto/Casa/Of:','',$direccion['address_2'])),
										'otras_indicaciones' => $direccion['city']
										);
					$orderId = $venta['CheckoutLinks'][0]['externalContent']['OrderId'];
					$valor_despacho = $venta['DeliveryOrderInCheckouts'][0]['DeliveryOrder']['cost'];
					//$this->setDespachoDafiti($orderId );
				}
				//prx($direccion_despacho);
				if($direccion['indications'] && $direccion['indications'] !='')
					$direccion_despacho['depto'] = $direccion['indications'];
				$direccionModel->create();
				$direccionModel->save($direccion_despacho);
				$direccionModel->Despacho->create();
				$direccionModel->Despacho->save(array('usuario_id' =>$this->usuario_id,
													  'direccion_id' => $direccionModel->id));
						//$task = $this->askCebra($venta['DeliveryOrderInCheckouts'][0]['DeliveryOrderId']);
				$neto = (int)($venta['gross']/1.19);
				$arr = array('Compra' => array('subtotal' => $venta['gross'],
									   'total' => $venta['gross'],
									   'neto' => $neto,
									   'iva' => $venta['gross'] - $neto,
									   'ip' => $venta['origin'],
									   'local' => 1,
									   'valor_despacho' => 0,
									   'despachado' => NULL,
									   'verificado' => $verificado,
									   'estado' => 1,
									   'valor_despacho' => $valor_despacho,
									   'token' => $venta['_id'],
									   'mv_despacho' => $venta['DeliveryOrderInCheckouts'][0]['DeliveryOrderId'],
									   'mv_orden' => $venta['CheckoutLinks'][0]['externalOrderNumber'],
									   'mv_orden2' => $original,
									   'despacho_id' => $direccionModel->Despacho->id,
									   'mv_cliente' => $venta['Client']['fullName'],
									   'cod_despacho' => $venta['DeliveryOrderInCheckouts'][0]['DeliveryOrder']['code'],
									   'mv_task' => $task['_id'],
									   'mv_orderid' => $orderId,
									   'mv_numero' => $venta['Client']['phoneNumber']));
				$compraModel->create();
				$compraModel->save($arr);
				$compra_id = $compraModel->id;
				foreach ($venta['CheckoutItems'] as $producto)
				{
					$codigo = $producto['ProductVersion']['Product']['code'];
					$producto_id = $productoModel->find('first', array('conditions' => array('Producto.codigo_completo' => $codigo)));
					if($producto_id)
					{
								//prx($producto_id);
						$cantidad = $producto['count'];
						for($i=0;$i<$cantidad;$i++)
						{	
							$productosCompra= array('sku' => $producto['ProductVersion']['code'],
													 'talla' => $producto['ProductVersion']['Size']['name'],
													 'cantidad' => 1,
													 'valor' => $producto['gross'],
													 'compra_id' => $compra_id,	
													 'producto_id' =>$producto_id['Producto']['id'],
													 'estado'	=> 1  );
							$compraModel->ProductosCompra->create();
							$compraModel->ProductosCompra->save($productosCompra);
						}
					}
				}	

			
		
			}

		}
	}
	public function setShipped($order)
	{}
	public function actualizarVentas()
	{
		$compraModel = ClassRegistry::init('Compra');
		$compras = $this->getCompras(false);
		if($compras)
		{
			foreach ($compras as $compra_id => $token)
			{
				if($token)
				{
					$compra_info = $this->getVenta($token);
					if($compra_info)
					{
						if($compra_info['deliveryStatus'] =='completed')
						{
							$guardar = array('id' => $compra_id,
										'despachado' => 1
										);
							$compraModel->save($guardar);


						}
					}
				}
			}
		}
	}
	public function actualizarShipping($orden, $estado = '184e4ab4-2386-11e7-8642-2c56dc130c0d')
	{
		//_delivery_order_status_ready_to_ship_
		$curl = curl_init();
		$array_json = json_encode(array('DeliveryOrderId' => $orden,
										'date'	=> date('Y-m-d H:i:s'),
										'DeliveryOrderStatusId' => $estado));
		pr($array_json);
		$url = $this->base_url."/api/delivery-orders/".$orden."/change-delivery-status";
		pr($url);

		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "PUT",
		CURLOPT_POSTFIELDS =>$array_json,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_HTTPHEADER => array(
		    "Content-Type: application/json",
		    "Authorization: Bearer ".$this->getToken()
		  ),
		));
		$result = curl_exec($curl);
		if( $result === false)
		{
			die('error');
		   	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{

			$response = json_decode($result ,true);
			prx($response);
			return $response;
		}
	}

	public function getImpresion()
	{

	$curl = curl_init();
	$merchant = $this->getMerchant();
	$merchant_id = $merchant['_id'];
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $this->base_url."/api/m/".$merchant_id."/bulk-actions-task/mercadolibre-generate-delivery-order-ticket/p/1?_marketplaceConnectionId=e3bb952b-812e-43dc-8610-91c67a9e9524",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	    CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
		    "Authorization: Bearer ".$this->getToken()
	  ),
	));

	if(curl_exec($curl) === false)
		{
	    	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			prx(curl_exec($curl));
			$response = json_decode(curl_exec($curl),true);
			return $response;
		}

	}
	public function askCebra($ordenes)
	{
		$curl = curl_init();
		$merchant = $this->getMerchant();
		//$url = $this->base_url."/api/mercadolibre-connections/e3bb952b-812e-43dc-8610-91c67a9e9524/delivery-order-tickets/generation";
		//prx($url);
		$merchant_id = $merchant['_id'];
		$array_json = json_encode(array('orders' =>array($ordenes)));
		$url = $this->base_url."/api/mercadolibre-connections/".$this->market_place_connection."/delivery-order-tickets/generation";
		//pr($url);
		//pr($array_json);
		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_CUSTOMREQUEST => "PUT",
		CURLOPT_POSTFIELDS =>$array_json,
		  CURLOPT_HTTPHEADER => array(
		  	 "Content-Type: application/json",
			    "Authorization: Bearer ".$this->getToken()
		  ),
		));
		$result = curl_exec($curl);
		if( $result === false)
		{
			die('error');
		   	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{

			$response = json_decode($result ,true);
			return $response;
		}

	}
	public function getCebraMasivo()
	{
		$compraModel = ClassRegistry::init('Compra');
		$compras = $compraModel->find('all', array('conditions' => array('local' => 1, 'verificado' => 0, 'mv_orden  >' => 0, 'created >' => '2020-07-01 00:00:00', 'estado' => 1 )));
			foreach ($compras	as $compra) 
		{
			if($compra['Compra']['ip'] =='mercadolibre')
			{

				$archivo = $this->getCebra($compra['Compra']['mv_task']);
				if($archivo['processStatus'] != 'completed')
				{
					continue;
				}
				$this->procesarCebra($archivo['url'],$compra['Compra']['id']);
			}else if($compra['Compra']['ip'] =='dafiti')
			{
				$this->getDespachoDafiti($compra['Compra']['token']);
			}

				
		}
	}
	public function getCebraMasivoAtrasado()
	{

		$compraModel = ClassRegistry::init('Compra');
		$compras = $compraModel->find('all', array('conditions' => array('mv_task !=' => '', 'local' => 1, 'created >' => '2020-07-05')));
		foreach($compras as $compra)
		{
			$task = $this->askCebra($compra['Compra']['mv_despacho']);
			print_r($task);
			if($task['_id'] !='')
			{
				$save = array('id' => $compra['Compra']['id'],
							'mv_task' => $task['_id']);
				 $compraModel->save($save);
			}
		}
	}
	public function cambiarEstado($order_id, $estado)
	{
		$estado_ready_to_ship = '184e4ab4-2386-11e7-8642-2c56dc130c0d';
		$datos = array('DeliveryOrderId' => $order_id, 'DeliveryOrderStatusId' => $estado_ready_to_ship);

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->base_url.'/api/delivery-orders/'.$order_id.'/change-delivery-status',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'PUT',
		  CURLOPT_POSTFIELDS =>'{
		    "CustomAttributeValues": {
		        "24e6e6d4-5348-4735-beef-115b83ed142b": "613893ce-3169-4fce-9af1-83060c1c62b9"
		    }
		}',
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json',
		    'Authorization: Bearer {{access_token}}'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;


	}
	public function getCebra($task_id)
	{
		$curl = curl_init();
		$merchant = $this->getMerchant();
		$url = $this->base_url."/api/bulk-actions-task/".$task_id;
		curl_setopt_array($curl, array(
		  	CURLOPT_URL => $url,
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$this->getToken()
			  ),
		));
		$result = curl_exec($curl);

		if($result  === false)
		{
		   	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode($result ,true);
			return $response;
		}

	}
	public function getDespachoDafiti($order)
	{
		$compraModel = ClassRegistry::init('Compra');
		$compra = $compraModel->find('first', array('conditions' => array('token' => $order)));
		prx($compra);
		if($compra)
		{
			$orderItem = $this->getOrder($compra['Compra']['mv_orderid']);
			pr($OrderItem);
			$base64  = $this->getOrderItems($orderItem['orderItem']);
			pr($base64);
			$respuesta = $this->procesarBase64Dafiti($base64);
			prx($respuesta);
			if($respuesta['region'] && $respuesta['tipo'] && $orderItem['orderItem'])
			{
				 $guardar = array('id' => $compra['Compra']['id'],
				  							'mv_numero1' => $respuesta['tipo'].','.$respuesta['region'].','.$orderItem['package'] ,
				  							'verificado' => 1);
				 pr('guardado');
				 $compraModel->save($guardar);

			}
		}
	}
	public function setDespachoDafiti($order)
	{
		$compraModel = ClassRegistry::init('Compra');
		$orderItem = $this->getOrder($order);
		$this->actualizarEstadoDafiti($orderItem);

	}
	public function actualizarEstadoDafiti($orderItem)
	{
		
		$request = 'Action=SetStatusToReadyToShip&DeliveryType=pickup&Format=JSON&OrderItemIds=%5B'.$orderItem['orderItem'].'%5D'.$this->extraRequestDafiti();
		$signature = $this->signatureDafiti($request);
		$url = $this->apiDafiti.'/?'.$request. '&Signature='.$signature;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  	CURLOPT_URL => $url,
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$this->getToken()
			  ),
		));
		$result = curl_exec($curl);
		if( $result === false)
		{
			//die('error');
		   	echo 'Curl error: ' . curl_error($curl);
		   	die();
		}
		else
		{
			$response = json_decode($result ,true);
			return true ;
		}

	}
	private function procesarBase64Dafiti($base64)
	{
		$texto = base64_decode($base64);
		$tipo =null;
		$region = null;
		//pr($texto);

		if(strpos($texto,'(Tipo: P)')  !== false)
			$tipo = 'P  ';
		else if(strpos($texto,'(Tipo: MM)')  !== false)
			$tipo = 'MM ';
		else if(strpos($texto,'(Tipo: MRM)')  !== false)
			$tipo = 'MRM';
		else if(strpos($texto,'(Tipo: MR)')  !== false)
			$tipo = 'MR ';


		if(strpos($texto,'(I)')  !== false)
			$region = 'I   ';
		else if(strpos($texto,'(II)')  !== false)
			$region = 'II  ';
		else if(strpos($texto,'(III)')  !== false)
			$region = 'III ';
		else if(strpos($texto,'(IV)')  !== false)
			$region = 'IV  ';
		if(strpos($texto,'(V)')  !== false)
			$region = 'V   ';
		else if(strpos($texto,'(VI)')  !== false)
			$region = 'VI  ';
		else if(strpos($texto,'(VII)')  !== false)
			$region = 'VII ';
		else if(strpos($texto,'(VIII)')  !== false)
			$region = 'VIII';
		else if(strpos($texto,'(IX)')  !== false)
			$region = 'IX  ';
		else if(strpos($texto,'(X)')  !== false)
			$region = 'X   ';
		else if(strpos($texto,'(XI)')  !== false)
			$region = 'XI  ';
		else if(strpos($texto,'(XII)')  !== false)
			$region = 'XII ';
		else if(strpos($texto,'(RM)')  !== false)
			$region = 'RM  ';
		else if(strpos($texto,'(XIV)')  !== false)
			$region = 'XIV ';
		else if(strpos($texto,'(XV)')  !== false)
			$region = 'XV  ';
		else if(strpos($texto,'(XVI)')  !== false)
			$region = 'XVI ';
		else if(strpos($texto,'(XVII)')  !== false)
			$region = 'XVII';
		return array('tipo' => $tipo, 'region' => $region);
	}
	public function getOrder($orderItem)
	{
		$request = 'Action=GetOrderItems&Format=JSON&OrderId='.$orderItem.$this->extraRequestDafiti();
		$signature = $this->signatureDafiti($request);
		$url = $this->apiDafiti.'/?'.$request. '&Signature='.$signature;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  	CURLOPT_URL => $url,
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$this->getToken()
			  ),
		));
		$result = curl_exec($curl);
		prx($result);
		if( $result === false)
		{
			//die('error');
		   	echo 'Curl error: ' . curl_error($curl);
		   	die();
		}
		else
		{
			$response = json_decode($result ,true);
			if(isset($response['SuccessResponse']['Body']['OrderItems']['OrderItem'][0]))
			{
				$orderItems =array();
				foreach($response['SuccessResponse']['Body']['OrderItems']['OrderItem'] as $orderItem)
				{
					$orderItems []= $orderItem['OrderItemId'];

				}
				$orderItems_string = implode("%2C",$orderItems);
				return array('package' => $response['SuccessResponse']['Body']['OrderItems']['OrderItem'][0]['PackageId'],
						'orderItem' =>$orderItems_string);
			}else{
				return array('package' => $response['SuccessResponse']['Body']['OrderItems']['OrderItem']['PackageId'],
						'orderItem' =>$response['SuccessResponse']['Body']['OrderItems']['OrderItem']['OrderItemId']) ;
			}
		}
	}

	private function getOrderItems($orderItem)
	{
		$request = 'Action=GetDocument&DocumentType=shippingParcel&Format=JSON&OrderItemIds=%5B'.$orderItem.'%5D'.$this->extraRequestDafiti();
		$signature = $this->signatureDafiti($request);
		$url = $this->apiDafiti.'/?'.$request. '&Signature='.$signature;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  	CURLOPT_URL => $url,
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$this->getToken()
			  ),
		));
		$result = curl_exec($curl);
		if( $result === false)
		{
			//die('error');
		   	echo 'Curl error: ' . curl_error($curl);
		   	die();
		}
		else
		{

			$response = json_decode($result ,true);
			pr($response);
			return ($response['SuccessResponse']['Body']['Documents']['Document']['File']);
		}
	}
	public function procesarCebra($archivo, $orden =1 )
	{

		COnfigure::write('debug',2);
		$compraModel = ClassRegistry::init('Compra');
		$ruta ='/var/www/archivos/tmp/';
		$numeros = array();
		$zipFile ='/var/www/archivos/tmp/'.$orden.'.zip';
		$zip_resource = fopen($zipFile, "w");
		$ch_start = curl_init();
		curl_setopt($ch_start, CURLOPT_URL, $archivo);
		curl_setopt($ch_start, CURLOPT_FAILONERROR, true);
		curl_setopt($ch_start, CURLOPT_HEADER, 0);
		curl_setopt($ch_start, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch_start, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch_start, CURLOPT_BINARYTRANSFER,true);
		curl_setopt($ch_start, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch_start, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch_start, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($ch_start, CURLOPT_FILE, $zip_resource);
		$page = curl_exec($ch_start);
		if(!$page)
		{
		 echo "Error :- ".curl_error($ch_start);
		}
		curl_close($ch_start);

		$zip = new ZipArchive;
		$extractPath = $ruta.$orden."/";
		if($zip->open($zipFile) != "true")
		{
		 echo "Error :- Unable to open the Zip File";
		} 

		$zip->extractTo($extractPath);
		$zip->close();
		$zip = new ZipArchive;
		$extractPath = $ruta.$orden."/cebra/";
		$zipFile = $ruta.$orden."/delivery-order-tickets-zpl.zip";
		if($zip->open($zipFile) != "true")
		{
		 echo "Error :- Unable to open the Zip File";
		} 
		$zip->extractTo($extractPath);
		$zip->close();
		//$file = fopen('D:\xampp\htdocs\skechers\Etiqueta de envio.txt','r');
		$file = fopen($ruta.$orden."/cebra/Etiqueta de envio.txt", "r");
		if(!$file)
		{
			return false;
		}
		while ($linea = fgets($file)) 
		{
			
			if(substr($linea,0,6) =='^FO15,')
			{

				$arr = explode(',',$linea);
				$numeros[1] = substr($arr[count($arr)-1],4,22);
			}
				
			
		}
    	pr($numeros);
    	fclose($file);
		if(!empty($numeros) && $numeros[1] != '')
		{
			pr('entro');
			$save = array(	'id' => $orden,
							'mv_numero1' => $numeros[1],
							//'mv_numero2' => $numeros[2],
							'verificado' => 1,
							'mv_task'		=>'');
			$compraModel->save($save);

		}
	}

	public function get_orden_dafiti($ordenes)
	{
		$curl = curl_init();
	$merchant = $this->getMerchant();
	$merchant_id = $merchant['_id'];
		curl_setopt_array($curl, array(
  		CURLOPT_URL => $this->base_url."/api/m/".$merchant_id."/bulk-actions-task/mercadolibre-generate-delivery-order-ticket/p/1?_marketplaceConnectionId=".$this->dafiti_connection,
  		CURLOPT_RETURNTRANSFER => true,
  		CURLOPT_ENCODING => "",
  		CURLOPT_MAXREDIRS => 10,
  		CURLOPT_TIMEOUT => 0,
  		CURLOPT_FOLLOWLOCATION => true,
  		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  			    CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
  		CURLOPT_CUSTOMREQUEST => "GET",
  		CURLOPT_HTTPHEADER => array(
   				 "Authorization: Bearer ".$this->getToken()
  		),
		));
			$result = curl_exec($curl);
		if( $result === false)
		{
			//die('error');
		   	echo 'Curl error: ' . curl_error($curl);
		   	die();
		}
		else
		{

			$response = json_decode($result ,true);
			prx($response);
			return $response;
		}


	}

	public function ask_orden_dafiti($ordenes)
	{

		//prx($array_json);
		$array_json = json_encode(array('orders' =>array($ordenes)));

		$url = $this->base_url."/api/dafiti-connections/70448716-8e41-431b-b66f-7a32b22d19fc/delivery-order-tickets/generation";
		print_r($url);
		//echo $ordenes;
		$curl = curl_init();
		curl_setopt_array($curl, array(
	  	CURLOPT_URL => $url,
	  	CURLOPT_RETURNTRANSFER => true,
	  	CURLOPT_ENCODING => "",
	  	CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "PUT",
	  CURLOPT_SSL_VERIFYHOST => 0,
	CURLOPT_POSTFIELDS =>$array_json,
	  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_HTTPHEADER => array(
		  	 "Content-Type: application/json",
			    "Authorization: Bearer ".$this->getToken()
		  ),
	));
		$result = curl_exec($curl);
		if( $result === false)
		{
			die('aca');
		   	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			print_r($result);
			$response = json_decode($result ,true);
			
	
			return $response;
		}


	}

	//FALABELLA

	private function setFalabellaToken($token)
	{
		$this->falabellaToken = $token;		
	}
	private function getFalabellaToken()
	{
		if($this->falabellaToken)
			return $this->falabellaToken;
		else
			false;
	}
	public function falabella_getToken()
	{
		//$array_json = json_encode(array('client_id' => $this->falabella_id, 'client_secret' => $this->falabella_secret ));
		$parametros = "grant_type=client_credentials&client_id=".$this->falabella_id."&client_secret=".$this->falabella_secret;
		$url = $this->apiFalabella."auth/token";
		//echo $ordenes;
		$curl = curl_init();
		curl_setopt_array($curl, array(
	  	CURLOPT_URL => $url,
	  	CURLOPT_RETURNTRANSFER => true,
	  	CURLOPT_ENCODING => "",
	  	CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_POSTFIELDS =>$parametros,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_HTTPHEADER => array(
		//	"Content-Type: application/json",
		//"Authorization: Bearer ".$this->getToken()
		  ),
		));
		$result = curl_exec($curl);
		if( $result === false)
		{
			die('aca');
		   	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode($result ,true);
			if($response["access_token"] && $response["access_token"]!='')
			{
				$this->setFalabellaToken($response["access_token"]);
			}
			
	
			return $response;
		}
	}
	public function falabella_label($order_id)
	{
    	require_once 'vendor/autoload.php';

	//	use thiagoalessio\TesseractOCR\TesseractOCR;
		$token = $this->getFalabellaToken();
		if(!$token)
		{
			$this->falabella_getToken();
			$token = $this->getFalabellaToken();

		}
		$dir = 'D:\xampp\htdocs\pdf';
		$parametros = "order_id=".$order_id;
		$url = $this->apiFalabella."orders/".$order_id."/label";
		//prx($url);
		$curl = curl_init();
		curl_setopt_array($curl, array(
	  	CURLOPT_URL => $url,
	  	CURLOPT_RETURNTRANSFER => true,
	  	CURLOPT_ENCODING => "",
	  	CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_SSL_VERIFYHOST => 0,
		//CURLOPT_POSTFIELDS =>$parametros,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_HTTPHEADER => array(
				 "Content-Type: application/json",
			    "Authorization: Bearer ".$token
		  ),
		));
		$result = curl_exec($curl);
		if( $result === false)
		{
			die('aca');
		   	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			$response = json_decode($result ,true);
			//pr($response);
			if(isset($response['label']) && $response['label'] != '')
			{

				$respuesta = array(	'f12' => null, 
									'chilexpress' => null,
									'oc' =>null, 
									'suborden' => null, 
									'bultos' => null, 
									'destino' => null,
									'sku_fal' => null, 
									'codigo1' => null, 
									'codigo2' => null);

				App::import('Vendor', 'autoload', array('file' => 'tesseract_ocr'.DS.'TesseractOCR.php'));
				$pdf_decoded = base64_decode ($response['label']);
				$pdf = fopen ($dir.DS.$order_id.'.pdf','w');
				fwrite ($pdf,$pdf_decoded);
				fclose ($pdf);
				exec ('D:\gs\bin\gswin64c.exe -sDEVICE=pngalpha -sOutputFile='.$dir.'\\'.$order_id.'.png -r144 -dFirstPage=1 -dLastPage=1 -dNOPAUSE -dQUIET -dBATCH '.$dir.'\\'.$order_id.'.pdf');
				$texto = (new TesseractOCR($dir.'\\'.$order_id.'.png'))
				    ->run();

				$arr = explode('<br />',nl2br($texto));
				pr($arr);
				foreach ($arr as $texto)
				{
					$texto = trim($texto);
					if(strpos($texto,'F12:')  !== false && !$respuesta['f12'])
					{
						$arr2 = explode(" ", trim($texto));
						$respuesta['f12'] = $arr2[1];
					}
					else if(strpos($texto,'Bulto')  !== false)
					{
						$arr2 = explode(" ", $texto);
						$respuesta['bultos'] = $arr2[3];
					}
					else if(strpos($texto,'OC')  !== false)
					{
						$arr2 = explode(" ", $texto);
						$respuesta['oc'] = $arr2[1];
					}
					else if(strpos($texto,'Suborden')  !== false)
					{
						$arr2 = explode(" ", $texto);
						$respuesta['suborden'] = $arr2[2];
					}
					else if(strpos($texto,'CHILEXPRESS')  !== false)
					{
						$arr2 = explode(" ", $texto);
						$respuesta['chilexpress'] = $arr2[1].' '.$arr2[2].' '.$arr2[3].' '.$arr2[4].' '.$arr2[5];
					}
					else if(strpos($texto,'Destino')  !== false)
					{
						$arr2 = explode(" ", $texto);
						$respuesta['destino'] = $arr2[1];
					}
					else if(strpos($texto,'SKU Fal')  !== false)
					{
						$arr2 = explode(" ", $texto);
						$respuesta['sku_fal'] = $arr2[2];
					}
					else 
					{
						$arr2 = explode(" ", $texto);
						if(is_numeric($arr2[0]))
						{
							$respuesta['codigo1'] = $arr2[0];
							$respuesta['codigo2'] = substr($arr2[1],0,-1);
						}
					}
				}
				prx($respuesta);
		

			}
	
		}

	}

	public function getOrdenesRipley()
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->apiRipley.'/api/v6/logistics/order/list',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer '.$this->getTokenRipley()
		  ),
		));

		$result = curl_exec($curl);
		if( $result === false)
		{
		   	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			prx($result);
		}


	}
	public function generarEtiquetaRipley($orden)
	{
		$curl = curl_init();
		$data = array('label' => array('packages' => 1), 'order' => array('_id' => $orden));
		$url = $this->apiRipley.'/api/v6/label/label/generate';
		$data_json = json_encode($data);
		$header = 'Authorization: Bearer '.$this->getTokenRipley();
		pr(compact('header','url','data_json'));

		//pr($data_json);
		//pr($header);
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->apiRipley.'/api/v6/label/label/generate',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
  		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $data_json,
		 CURLOPT_HTTPHEADER => array(
		     "Content-Type: application/json",
			 "Authorization: Bearer ".$this->getTokenRipley()
		  ),
		));
		$result = curl_exec($curl);
		if( $result === false)
		{
		   	echo 'Curl error: ' . curl_error($curl);
		}
		else
		{
			prx($result);
		}

	}
	public function obtenerEtiquetaRipley($orden)
	{
		$curl = curl_init();
		$data = array('orders' => array(array('document_id' => $orden)));
		//$url = $this->apiRipley.'/api/v6/label/label/download';
		$data_json = json_encode($data);
		$header = 'Authorization: Bearer '.$this->getTokenRipley();
		pr(compact('header','url','data_json'));

		//pr($data_json);
		//pr($header);
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->apiRipley.'/api/v6/label/label/download',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
  		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $data_json,
		 CURLOPT_HTTPHEADER => array(
		     "Content-Type: application/json",
			 "Authorization: Bearer ".$this->getTokenRipley()
		  ),
		));
		$result = curl_exec($curl);
		pr($result);
		if( $result !== false)
		{
			$response = json_decode($result ,true);
			if($response['labels_generated'] !='')
			{
				$dir = 'D:\xampp\htdocs\pdf';
				$etiqueta = $response['labels_generated'];
				$pdf_decoded = base64_decode ($response['labels_generated']);
				$pdf = fopen ($dir.DS.$orden.'.pdf','w');
				fwrite ($pdf,$pdf_decoded);
				fclose ($pdf);
				$comando = 'd:/pdf/pdftotext.exe '.$dir.'\\'.$orden.'.pdf '.$dir.'\\'.$orden.'.txt' ;
				echo $comando;
				exec ($comando );
				exit;
				$arr = explode('<br />',nl2br($texto));
				pr($arr);
			}

		}
		else
		{
			
			
		}

	}

}









?>

