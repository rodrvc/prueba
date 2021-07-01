<?php
App::import('Core', array('Router','Controller'));

class  StockShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main()
		{
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

		error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
		$hoy = '2017-11-08 12:30:00';

		$ProductoOBJ = new Producto();

		//prx(compact('semana_pasada_inicio','semana_pasada_fin'));
	//	$ayer = strtotime(date('d-m-Y',$hoy)) - (60*60*24*1);
	$sql = "select count(*) as cantidad, producto_id, talla from sitio_compras c, sitio_productos_compras pc where pc.compra_id = c.id and c.estado = 1 and c.created > '".$hoy."' group by producto_id, talla HAVING COUNT(*) > 4";
	$respuesta = $ProductoOBJ->query($sql);
	print_r($respuesta);

	$productos = array();
	foreach ($respuesta as $stock)
	 {

		 	$sql = "update sitio_tallas set cantidad = 10 - ".$stock['0']['cantidad']." where producto_id = ".$stock['pc']['producto_id'].' and talla = '.$stock['pc']['talla'];
		 	$respuesta = $ProductoOBJ->query($sql);
			$sql = "update sitio_stocks set cantidad = 10 - ".$stock['0']['cantidad']." where producto_id = ".$stock['pc']['producto_id'].' and talla = '.$stock['pc']['talla'];
			$respuesta = $ProductoOBJ->query($sql);

		}

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
