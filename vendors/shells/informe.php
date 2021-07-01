<?php
App::import('Core', array('Router','Controller'));
App::import('Vendor', 'google/lib/autoload');


class  InformeShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() 
	{
		//App::import('Vendor', 'autoload', array('file' => 'google'.DS.'autoload.php'));
	
		Configure::write('debug',2);

		/** /cake/1.3.11/cake/console/cake -app /home/skechile/public_html/desarrollo masivo > /home/skechile/public_html/cron.log */
		set_time_limit(0);

		$inicio			= microtime(true);
		$guardar = true;

		App::import('Model','Compra');
		App::import('Model','Estilo');

		error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
$factor = 0.00109;
		$CompraOBJ = new Compra();
		$EstilosOBJ = new Estilo();

		$estilos_email = $EstilosOBJ->find('all',array(
													'conditions' => array(
														'email' => 1),
													'contain' => 'Categoria'
													)
											);

	
		$hoy = date('d-m-Y H:i:s');
		$semana = date('W');
		$ano = date('Y'); 
		$dto = new DateTime();
  		$ano_pasado['week_start'] = $dto->setISODate($ano-1, $semana-1)->format('Y-m-d');
  		$ano_pasado['week_end'] = $dto->modify('+6 days')->format('Y-m-d');
		$dto2 = new DateTime();
  		$semana_pasada_inicio = $dto2->setISODate($ano, $semana-1)->format('Y-m-d');
  		$semana_pasada_fin = $dto2->modify('+6 days')->format('Y-m-d');
		$nombre_email = '[Skechers] Informe Semanal '.$semana_pasada_inicio. ' - '.$semana_pasada_fin ;
		$ayer = strtotime(date('d-m-Y',$hoy)) - (60*60*24*1);
		$reporte = array();

		foreach ($estilos_email as $key => $estilo)
		{
		
			$sql ="SELECT sum(pc.valor) as cantidad, count(*) as pares FROM sitio_compras c, sitio_productos_compras pc, sitio_productos p where c.id =pc.compra_id and pc.producto_id = p.id and c.estado = 1 and c.created > '".$semana_pasada_inicio." 00:00:00' and p.categoria_id = ".$estilo['Estilo']['categoria_id']." and c.created < '".$semana_pasada_fin." 23:59:59'  and p.grupo like '%[".$estilo['Estilo']['alias']."]%'";

			$resultado = $EstilosOBJ->query($sql);
			$estilos_email[$key]['Estilo']['vendido'] = $resultado[0][0]['cantidad'];
			$estilos_email[$key]['Estilo']['pares'] = $resultado[0][0]['pares'];
		
		}

		$options = array(
			'conditions' => array(
				'Compra.created BETWEEN ? AND ?' => array($semana_pasada_inicio. ' 00:00:00' ,$semana_pasada_fin. ' 23:59:59'),
				'Compra.estado = 1' ,
				'Compra.local' => 0
			),
			'fields' => array(
				'Compra.id',
				'Compra.total',
				'Compra.usuario_id',
				'Compra.estado'
			),
			'contain' => array(
				'Producto' => array(
					'fields' => array(
						'Producto.nombre',
						'Producto.id',
						'Producto.codigo_completo',
						'Producto.foto',
						'Producto.codigo',
						'Producto.division'
					)
				)
			),
			'order' => array(
				'Compra.id' => 'ASC'
			)
		);
		$datos = array();
		if ($compras = $CompraOBJ->find('all',$options))
		{

			$datos[0] = $this->estadisticas($compras,$semana_pasada_inicio,$semana_pasada_fin);
		}
		$dto3 = new DateTime();
  		$semana_pasada_inicio = $dto3->setISODate($ano, $semana-2)->format('Y-m-d');
  		$semana_pasada_fin = $dto3->modify('+6 days')->format('Y-m-d');
		$options = array(
			'conditions' => array(
				'Compra.created BETWEEN ? AND ?' => array($semana_pasada_inicio. ' 00:00:00' ,$semana_pasada_fin. ' 23:59:59'),
				'Compra.estado = 1' ,
				'Compra.local' => 0
			),
			'fields' => array(
				'Compra.id',
				'Compra.total',
				'Compra.usuario_id',
				'Compra.estado'
			),
			'contain' => array(
				'Producto' => array(
					'fields' => array(
						'Producto.nombre',
						'Producto.id',
						'Producto.codigo_completo',
						'Producto.foto',
						'Producto.codigo',
						'Producto.division'
					)
				)
			),
			'order' => array(
				'Compra.id' => 'ASC'
			)
		);
		if ($compras = $CompraOBJ->find('all',$options))
		{

			$datos[1] = $this->estadisticas($compras,$semana_pasada_inicio,$semana_pasada_fin);
		}

		$options = array(
			'conditions' => array(
				'Compra.created BETWEEN ? AND ?' => array($ano_pasado['week_start']. ' 00:00:00' ,$ano_pasado['week_end']. ' 23:59:59'),
				'Compra.estado = 1' ,
				'Compra.local' => 0
			),
			'fields' => array(
				'Compra.id',
				'Compra.total',
				'Compra.usuario_id',
				'Compra.estado'
			),
			'contain' => array(
				'Producto' => array(
					'fields' => array(
						'Producto.nombre',
						'Producto.id',
						'Producto.codigo_completo',
						'Producto.foto',
						'Producto.codigo',
						'Producto.division'
					)
				)
			),
			'order' => array(
				'Compra.id' => 'ASC'
			)
		);
		if ($compras = $CompraOBJ->find('all',$options))
		{

			$datos[2] = $this->estadisticas($compras,$semana_pasada_inicio,$semana_pasada_fin);
		}



	
		

			$mensaje ='<html>
    <head>
      <meta charset="utf-8">
      <style>
        table{border-collapse: collapse;}
        tr {border:1px solid;}
        td { border:1px solid}
      </style>
    </head>
  <table style="width:800px; border:solid 2px">
    <thead class="border">
      <tr class="border">
        <td>&nbsp;</td>
        

        <td  align="center">Curr. Week<br>
          (cw'.date("W", strtotime("$hoy   -3 day")).') 
        </td>
        <td  align="center">Prev.Week<br>
        (cw '.date("W", strtotime("$hoy   -10 day")).') 
        </td>
         <td  align="center">Prev YEAr<br>
        </td>

      </tr>
    </thead>
    <tr style="border:1px">
      <td align="right" style="border:1px">
        Sales Amount:<br>
        Average order value:<br>
      </td>
      <td align="center">
         USD '.number_format($factor * $datos[0]['total'], 0, ",", ".").' <br>
         USD '.number_format(($factor * $datos[0]['total'] / $datos[0]['productos']) , 0, ",", ".").'
      <td  align="center">
         USD '.number_format($factor * $datos[1]['total'], 0, ",", ".").' ('.number_format(((($datos[0]['total']/  $datos[1]['total'])-1)*100),2).'%)<br>
        USD '.number_format(($factor * $datos[1]['total'] / $datos[1]['productos']) , 0, ",", ".").' 
      </td>
         <td  align="center">
         USD '.number_format($factor * $datos[2]['total'], 0, ",", ".").' ('.number_format(((( $datos[0]['total']/  $datos[2]['total'])-1)*100),2).'%)<br>
        USD '.number_format(($factor * $datos[2]['total'] / $datos[1]['productos']) , 0, ",", ".").' 
      </td>

    </tr>
    <tr>
      <td align="right">Pairs sold:</td>
      <td align="center">'.number_format($datos[0]['productos'], 0, ",", ".").'</td>
      <td align="center">'.number_format($datos[1]['productos'], 0, ",", ".").'</td>
      <td align="center">'.number_format($datos[2]['productos'], 0, ",", ".").'</td>

    </tr>
    <tr>
      <td  align="right">
        <span style="float:left">Broken down by gender:</span><br>';

         foreach ($datos[0]['Categoria'] as $estilo => $cantidad) 
         	$mensaje .= $estilo.'<br>';
     
$mensaje .='</td>
      <td align="center">
          &nbsp;<br>';
          foreach ($datos[0]['Categoria'] as $estilo => $cantidad) 
         	$mensaje .= '<span style="float:left; padding-left:5em">'.$cantidad.'</span> <span style="float:right; padding-right:5em">'.number_format(($cantidad /$datos[0]['productos'])*100 , 2, ",", ".").'%</span><br>';
     $mensaje .='  </td>
      <td align="center"><br>';
        foreach ($datos[0]['Categoria'] as $estilo => $cantidad) 
         	$mensaje .= '<span style="float:left; padding-left:5em">'.$datos[1]['Categoria'][$estilo].'</span> <span style="float:right; padding-right:5em">'.number_format(($datos[1]['Categoria'][$estilo] /$datos[1]['productos'])*100 , 2, ",", ".").'%</span><br>';
       $mensaje .='</td>
       <td align="center"><br>';
        foreach ($datos[0]['Categoria'] as $estilo => $cantidad) 
         	$mensaje .= '<span style="float:left; padding-left:5em">'.$datos[2]['Categoria'][$estilo].'</span> <span style="float:right; padding-right:5em">'.number_format(($datos[2]['Categoria'][$estilo] /$datos[2]['productos'])*100 , 2, ",", ".").'%</span><br>';
       $mensaje .='</td>
 

    </tr>
 

  


  </table>
    <br><br>
  <table style="width:100%; border:solid 2px">
    <thead class="border">
      <tr class="border">
      <td>#</td>
        <td>StyleCode</td>
        <td  align="center">ProductName</td>
        <td  align="center">Usage</td>
        <td  align="center">TotalSales</td>

      </tr>
    </thead>';
    $i = 1;
    foreach($datos[0]['Estilos'] as $producto)
    {
 
 	$mensaje .=	'<tr>
      	<td>'.$i++.'</td>
      	<td  align="center">'.$producto['codigo'].' </td>
        <td>'.$producto['nombre'].'</td>
        <td  align="center">'.$producto['cantidad'].'</td>
        <td  align="center">USD '.number_format(($factor * $producto['total']) , 2, ",", ".").'</td>
      </tr>';
  }
  $mensaje .='
  </table>

    <table style="width:100%; border:solid 2px">
    <thead class="border">
      <tr class="border">
      <td>#</td>
        <td  align="center">Division</td>
        <td  align="center">Usage</td>

      </tr>
    </thead>';
    $i = 1;
    foreach($datos[0]['Division'] as $division =>$cantidad)
    {
 
 	$mensaje .=	'<tr>
      	<td>'.$i++.'</td>
      	<td  align="center">'.$division.' </td>
        <td>'.$cantidad.'</td>
      </tr>';
  }
  $mensaje .='
  </table>
    <br><br>
  <table style="width:100%; border:solid 2px">
    <thead class="border">
      <tr class="border">
        <td  align="center">Estilo</td>
        <td  align="center">Categoria</td>
        <td  align="center">Vendido</td>
        <td  align="center">pares</td>

      </tr>
    </thead>';
    foreach($estilos_email as $estilo)
    {
 
 	$mensaje .=	'<tr>
      	<td  align="center">'.$estilo['Estilo']['nombre'].' </td>
        <td  align="center">'.$estilo['Categoria']['nombre'].'</td>

        <td  align="center"> USD '.  number_format($factor * $estilo['Estilo']['vendido'], 0, ",", ".").'</td>
        <td  align="center"> '.  $estilo['Estilo']['pares'].'</td>

      </tr>';
  }
  $mensaje .='
  </table>
</html>';



			
	
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
		//$email->to = 'cherrera@skechers.cl';
		$email->to = 'sebastian@sancirilo.cl';
		$email->cc	= array('jwalton@skechers.com');
		//$email->bcc	= array('sdelvillar@andain.cl','ehenriquez@andain.cl','solanger@skechers.com');

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
			prx($email);
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
			
			$reporte['productos'] += sizeof($compra['Producto']);
			$reporte['total'] += $compra['Compra']['total'];
			$key = $compra['Compra']['usuario_id'].'-'.$compra['Compra']['total'];
			$estado = 0;
			foreach ($compra['Producto'] as $producto) 
			{
				$categoria = $producto['ProductosCompra']['categoria'];
				$division = $producto['division'];
				$codigo = $producto['codigo'];
				if(!isset($reporte['Categoria'][$categoria]))
					$reporte['Categoria'][$categoria] = 1;
				else
					$reporte['Categoria'][$categoria]++;
				if(!isset($reporte['Division'][$division]))
					$reporte['Division'][$division] = 1;
				else
					$reporte['Division'][$division]++;
				if(!isset($reporte['Estilos'][$codigo])){
					$reporte['Estilos'][$codigo]['cantidad'] = 1;
					$reporte['Estilos'][$codigo]['total'] = $producto['ProductosCompra']['valor'];
					$reporte['Estilos'][$codigo]['nombre'] = $producto['nombre'];
					$reporte['Estilos'][$codigo]['codigo'] = $producto['codigo'];
				}else{
					$reporte['Estilos'][$codigo]['cantidad']++;
					$reporte['Estilos'][$codigo]['total'] += $producto['ProductosCompra']['valor'];
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
		uasort($reporte['Showroom'],'cmp2');


		$reporte['Estilos'] = array_splice($reporte['Estilos'],0,25);
		//$reporte['analytics'] = $this->analytics($fecha_inicio,$fecha_fin);
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
						'ga:users, ga:pageviews, ga:sessions', 
						array(   'dimensions' => 'ga:week'));
		return array('usuarios' => $results['totalsForAllResults']['ga:users'],
					  'sesiones'	=>$results['totalsForAllResults']['ga:sessions'],
					  'pageviews'	=> $results['totalsForAllResults']['ga:pageviews'] );

	}

		
	


	

}
function cmp2($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? 1: -1;
}
function cmp($a, $b) {
    if ($a['total'] == $b['total']) {
        return 0;
    }
    return ($a['total'] < $b['total']) ? 1: -1;
}

function prx($data)
{

	print_r($data); exit;
}
