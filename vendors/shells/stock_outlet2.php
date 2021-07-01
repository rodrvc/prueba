<?php
App::import('Core', array('Router','Controller'));

class  StockOutlet2Shell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main()
	{
			Configure::write('debug', 2);
	//	App::import('Vendor', 'autoload', array('file' => 'google'.DS.'autoload.php'));
		//App::import('Vendor', 'google/lib/autoload');
		//prx(class_exists('Google_Auth_AssertionCredentials'));

		//$analytics = $this->analytics('2015-10-07', '2015-10-13')	;
		//prx($analytics);

		/** /cake/1.3.11/cake/console/cake -app /home/skechile/public_html/desarrollo masivo > /home/skechile/public_html/cron.log */
		set_time_limit(0);
		error_reporting(8);


		//$fecha = date('2016-11-07 23:00:00');
		App::import('Model','Producto');
		$ProductoOBJ = new Producto();
		App::import('Model','Bloqueo');
		$BloqueoOBJ = new Bloqueo();
		App::import('Component', 'Multivende');
		$multivende =& new MultivendeComponent(null);
		$bloqueos = $BloqueoOBJ->find('all');
		$excluir = array();
		$skus = '';
		foreach ($bloqueos as $bloqueo)
		{
			$excluir [$bloqueo['Bloqueo']['producto_id'].$bloqueo['Bloqueo']['talla']]= array('producto_id' => $bloqueo['Bloqueo']['producto_id'],'cantidad' => $bloqueo['Bloqueo']['cantidad'],'talla' => $bloqueo['Bloqueo']['talla'], 'sku' => $bloqueo['Bloqueo']['sku'], 'created' => $bloqueo['Bloqueo']['created']);
			$skus []= $bloqueo['Bloqueo']['sku'];
		}
		print_r($excluir);

		$sql = "select count(*) as cantidad, producto_id, talla, pc.sku  from sitio_compras c, sitio_productos p, sitio_productos_compras pc where p.id = pc.producto_id and  pc.compra_id = c.id  and c.estado = 1 and c.created > '2021-04-26 00:00:00' and pc.producto_id  in (13327,13326)  group by producto_id, talla";
		$respuesta = $ProductoOBJ->query($sql);
		$block =array();
		$productos = array();
		foreach ($respuesta as $stock)
		{
			$indice = $stock['pc']['producto_id'].$stock['pc']['talla'];
			if(isset($excluir[$indice]) &&  $stock[0]['cantidad'] <= $excluir[$indice]['cantidad'])
			{
				echo 'paso';
				//print_r($stock);
				continue;
			}else{
				print_r($stock);
				$sql = "select * from sitio_stocks where  producto_id = ".$stock['pc']['producto_id']." and talla = ".$stock['pc']['talla'];
				$respuesta2 = $ProductoOBJ->query($sql);
				if(!empty($respuesta2))
				{
					print_r($respuesta2);
					$sku = ($respuesta2[0]['sitio_stocks']['sku']);
					print_r($sku);
					$sql = "Insert into sitio_quiebres (producto_id,talla,created) values(".$stock['pc']['producto_id'].",'".$stock['pc']['talla']."','".date('Y-m-d H:i:s')."')";
			 		$respuesta = $ProductoOBJ->query($sql);
			 		$sql = "Delete from sitio_tallas where producto_id = ".$stock['pc']['producto_id'].' and talla = '.$stock['pc']['talla'];
			 		$respuesta = $ProductoOBJ->query($sql);
					$sql = "Delete from sitio_stocks where producto_id = ".$stock['pc']['producto_id'].' and talla = '.$stock['pc']['talla'];
					$respuesta = $ProductoOBJ->query($sql);	
				}
			}
		}
					die('termino');

		

	}
	function estadisticas($compras,$fecha_inicio,$fecha_fin)
	{
		$reporte['total'] = 0;
		$reporte['productos'] =0;
		$reales = $detalle = array();
		foreach ($compras as $compra)
		{
			//prx($compra);
			$reporte['productos'] += sizeof($compra['Producto']);
			$reporte['total'] += $compra['Compra']['total'];
			$key = $compra['Compra']['usuario_id'].'-'.$compra['Compra']['total'];
			$estado = 0;
			foreach ($compra['Producto'] as $producto)
			{
				$categoria = $producto['ProductosCompra']['categoria'];
				$codigo = $producto['codigo'];
				if(!isset($reporte['Categoria'][$categoria]))
					$reporte['Categoria'][$categoria] = 1;
				else
					$reporte['Categoria'][$categoria]++;
				if(!isset($reporte['Estilos'][$codigo])){
					$reporte['Estilos'][$codigo]['cantidad'] = 1;
					$reporte['Estilos'][$codigo]['nombre'] = $producto['nombre'];
					$reporte['Estilos'][$codigo]['codigo'] = $producto['codigo'];
				}else{
					$reporte['Estilos'][$codigo]['cantidad']++;
				}
			}



		}

		App::import('Model','EmailBlast');
		$emailBlast = new EmailBlast();
		$emails = $emailBlast->find('all', array('conditions' => array(
				'EmailBlast.fecha BETWEEN ? AND ?' => array($fecha_inicio ,$fecha_fin))));
	//	prx(compact('emails','fecha_inicio','fecha_fin'));
		$reporte['Emails'] = array();
		foreach ($emails as  $email)
		{
			$reporte['Emails'][]= array('nombre' 	=> $email['EmailBlast']['nombre'],
										'monto'		=> $email['EmailBlast']['monto']);

		}

		uasort($reporte['Estilos'],'cmp');

		$reporte['Estilos'] = array_splice($reporte['Estilos'],0,20);
		$reporte['analytics'] = $this->analytics($fecha_inicio,$fecha_fin);
		return $reporte;
	}

	function analytics($desde, $hasta)
	{
		$client_email = '686220025223-s74jfboc6n6qeva5a872gcq7i7tnmvvg@developer.gserviceaccount.com';
		$private_key = file_get_contents('API Project-6818de595c4d.p12');
		$scopes = array('https://www.googleapis.com/auth/analytics.readonly');
		$credentials = new Google_Auth_AssertionCredentials
		(
		    $client_email,
		    $scopes,
		    $private_key
		);
		$client = new Google_Client();
		$client->setAssertionCredentials($credentials);
		if ($client->getAuth()->isAccessTokenExpired()) {
		  $client->getAuth()->refreshTokenWithAssertion();
		}
		 $service = new Google_Service_Analytics($client);
		 $man_accounts = $service->management_accounts->listManagementAccounts();
			$accounts = array();
			//prx($man_accounts);
		$vistas= 	$service->management_profiles->listManagementProfiles(33679066,'UA-33679066-1')->getItems();
		//prx($vistas[0]['id']);
		$results = $service->data_ga->get(
						'ga:'.$vistas[0]['id'],
						$desde,
						$hasta,
						'ga:users',
						array(   'dimensions' => 'ga:day'));
		$ventas = $service->data_ga->get(
							'ga:'.$vistas[0]['id'],
								$desde,
								$hasta,
								'ga:transactionRevenue',
								array(   'dimensions' => 'ga:deviceCategory'));
		$ventas_movil =0;
		$ventas_totales =0;
		foreach ($ventas['rows'] as $dispositivo)
		{
			if($dispositivo[0] == 'tablet' || $dispositivo[0] == 'mobile')
			{
				$ventas_movil += $dispositivo[1];

			}
			$ventas_totales += $dispositivo[1];

		}
		return array('usuarios' => $results['totalsForAllResults']['ga:users'],
								'promedio_movil' => (int)(($ventas_movil /$ventas_totales) *100)
					);

	}







}
function cmp($a, $b) {
    if ($a['cantidad'] == $b['cantidad']) {
        return 0;
    }
    return ($a['cantidad'] < $b['cantidad']) ? 1: -1;
}

function prx($data)
{

	print_r($data); exit;
}
