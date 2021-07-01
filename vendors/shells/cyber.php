<?php
App::import('Core', array('Router','Controller'));

class  CyberShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main()
	{
		App::import('Vendor', 'autoload', array('file' => 'google'.DS.'autoload.php'));
		//App::import('Vendor', 'google/lib/autoload');
		//prx(class_exists('Google_Auth_AssertionCredentials'));

		//$analytics = $this->analytics('2015-10-07', '2015-10-13')	;
		//prx($analytics);
		Configure::write('debug',2);
		$visitas_promedio = array(1 => 6294,
															2 =>  5852,
														3  =>	7271
);
		$transacciones_promedio = array(1 => 58,
															2 =>  60,
															3  =>	51);
		$ventas_promedio = array(1 => 2282214,
															2 =>  2472696,
															3  =>	1987854);


		/** /cake/1.3.11/cake/console/cake -app /home/skechile/public_html/desarrollo masivo > /home/skechile/public_html/cron.log */
		set_time_limit(0);

		$inicio			= microtime(true);
		$guardar = true;

		App::import('Model','Compra');
		App::import('Model','Usuario');

		error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);



		$CompraOBJ = new Compra();
		$UsuarioOBJ = new Usuario();

		$hoy = date('d-m-Y H:i:s');
		$semana_pasada_inicio = date("Y-m-d", strtotime("$hoy   -7 day"));
		$semana_pasada_fin = date("Y-m-d", strtotime("$hoy   -1 day"));

		$nombre_email = '[Skechers] Informe Cyberday '.$hoy ;
		//prx(compact('semana_pasada_inicio','semana_pasada_fin'));
	//	$ayer = strtotime(date('d-m-Y',$hoy)) - (60*60*24*1);
		$reporte = array();
		$options = array(
			'conditions' => array(
				'Compra.created BETWEEN ? AND ?' => array(date('Y-m-d'). ' 00:00:00' ,date('Y-m-d H:i:s')),
				'Compra.estado = 1'
			),
			'fields' => array(
				'Compra.id',
				'Compra.total',
				'Compra.usuario_id',
				'Compra.estado'
			)

		);

		$datos = array();
		$compras = $CompraOBJ->find('all',$options);
		$cantidad_compras = count($compras);
		$total_compras =0;
		foreach ($compras as $compra) {
			$total_compras += $compra['Compra']['total'];
		}
		$options = array(
			'conditions' => array(
				'Usuario.created BETWEEN ? AND ?' => array(date('Y-m-d'). ' 00:00:00' ,date('Y-m-d H:i:s')),
			),
			'fields' => array(
				'Usuario.id'
			)

		);
		$usuarios = $UsuarioOBJ->find('all',$options);
		$usuarios_nuevos = count($usuarios);
		$estadisticas = $this->analytics(date('Y-m-d') ,date('Y-m-d'));
	//	prx($estadisticas);
$dia_semana =date('N');




		$mensaje ='Ventas Acumuladas Evento Cybermonday Hoy  :<b>'.$total_compras.'</b><br>';
	$mensaje .='Ventas Acumuladas Promedio  :<b>'.$ventas_promedio[$dia_semana].'</b><br>';
	$mensaje .='Visitas Totales Acumuladas Actual  :<b>'.$estadisticas['usuarios'].'</b><br>';
	$mensaje .='Visitas Totales Acumuladas Promedio  :<b>'.$visitas_promedio[$dia_semana].'</b><br>';
	$mensaje .='Número de Transacciones Acumuladas Actual Hoy :<b>'.$cantidad_compras.'</b><br>';
	$mensaje .='Número de Transacciones Acumuladas Promedio  :<b>'.$transacciones_promedio[$dia_semana].'</b><br>';
	$mensaje .='Porcentaje de las ventas realizadas a través de dispositivos móviles – Actual :<b>'.$estadisticas['promedio_movil'].'%</b><br>';
	$mensaje .='Porcentaje de las ventas realizadas a través de dispositivos móviles Promedio :<b>33%</b><br>';
	$mensaje .='Nuevos Clientes Acumulados  :<b>'.$usuarios_nuevos.'</b><br>';


			// CONFIGURACION DEL EMAIL
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
		$email->to = 'cherrera@skechers.com';
		//$email->to = 'sdelvillar@andain.cl';
		//$email->cc	= array('cherrera@skechers.com','pyanez@skechers.com','ehenriquez@andain.cl');
		$email->bcc	= array('sdelvillar@andain.cl');

		$email->subject = $nombre_email ;
		$email->from = 'Skechers <noreply@skechers-chile.cl>';
		$email->replyTo = 'noreply@skechers-chile.cl';
		$email->sendAs = 'html';
		$email->template	= 'mensaje2';
		$email->delivery = 'smtp';
		if ( $email->send() )
		{
			prx('ok');
			//return true;
		}
		else
		{
			prx('error');
			return false;
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
		$private_key = file_get_contents('/var/www/html/vendors/shells/API Project-6818de595c4d.p12');
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
