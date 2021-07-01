<?php
App::import('Core', array('Router','Controller'));

class  CorrectorShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main()
	{
		set_time_limit(0);
		error_reporting(8);
		App::import('Model','Producto');
		error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
		$ProductoOBJ = new Producto();

		//prx(compact('semana_pasada_inicio','semana_pasada_fin'));
	//	$ayer = strtotime(date('d-m-Y',$hoy)) - (60*60*24*1);
		$sql = "SELECT * FROM skechile_ecommerce.sitio_compras c, sitio_pagos p  where p.compra_id = c.id and c.estado =1 and p.respuesta != 0 and c.created > '2020-10-23 00:00:00' order by c.id desc";
		$respuesta = $ProductoOBJ->query($sql);
		//print_r($respuesta);
		//exit;
		$productos = array();
		foreach ($respuesta as $compra)
		{

			 	$sql = "update sitio_compras set estado = -1000 where id = ".$compra['c']['id'];
			 	echo $sql;
			 	App::import('Component', 'Email');
				$email =& new EmailComponent(null);
				$email->initialize($this->Controller);
				$email->smtpOptions = array(
					'transport' => 'Smtp',
					'port' => '465',
					'timeout' => '30',
					'auth' => true,
					'host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
					'username' => 'AKIAZK3GISZV5N5QNTQB',
					'password' => 'BP66zMPbbcyLkCTQq4DuptpKUmF0j/7dcIIFyCJXAbe/'
				);
				$mensaje = 'Ha habido un error en la compra '.$compra['c']['id'].' el cual ha sido corregido';
				$this->Controller->set('data', $mensaje);
				// DATOS DESTINATARIO (CLIENTE)
				$copias = array(
					'jwalton@skechers.com'
				);
				$email->to ='sebastian@sancirilo.cl';
				$email->bcc	= $copias;
				$email->subject = 'Problema en compra Compra #' .$compra['c']['id'] ;
				$email->from = 'Skechers <noreply@skechers-chile.cl>';
				$email->replyTo = 'noreply@skechers-chile.cl';
				$email->sendAs = 'html';
				$email->template	= 'mensaje';
				$email->delivery = 'smtp';
				if ($email->send())
				{

					echo 'ok';
				}else{
					print_r($email);
					die();
				}
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
