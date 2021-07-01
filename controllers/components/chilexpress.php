<?php
Class ChilexpressComponent extends Object
{
	var $apiKey = 'cfddd9daf2284e9fa36b7de46d6cdf8f';


	function initialize(&$controller)
	{
		// CONTROLADOR DE DONDE ES LLAMADO
		$this->Controller =& $controller;

		// INICIALIZA EL OBJECTO FACEBOOK (ACCESO API)
	}

	private function setToken($token)
	{
	}

	public function getRegiones()
	{

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://testservices.wschilexpress.com/georeference/api/v1.0/regions",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			//CURLOPT_POSTFIELDS =>$array_json,
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Ocp-Apim-Subscription-Key: 4653bbc4bdc146b1bb9c1796fdaca7af"
			),
		));
		if (curl_exec($curl) === false) {
			echo 'Curl error: ' . curl_error($curl);
		} else {
			$respuesta = json_decode(curl_exec($curl), true);
			$regiones = array();
			foreach ($respuesta['regions'] as $region) {
				$regiones[] = array('nombre' => $region['regionName'],
					'codigo' => $region['ineRegionCode']);
			}
			return $regiones;
		}
	}

	function eliminar_acentos($cadena)
	{

		//Reemplazamos la A y a
		$cadena = str_replace(
			array('á'),
			array('a'),
			$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
			array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
			array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
			$cadena);

		//Reemplazamos la I y i
		$cadena = str_replace(
			array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
			array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
			$cadena);

		//Reemplazamos la O y o
		$cadena = str_replace(
			array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
			array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
			$cadena);

		//Reemplazamos la U y u
		$cadena = str_replace(
			array('ú'),
			array('u',),
			$cadena);

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
			array('Ñ', 'ñ', 'Ç', 'ç'),
			array('N', 'n', 'C', 'c'),
			$cadena
		);

		return utf8_encode($cadena);
	}

	public function getOficinas($regionId)
	{
		$curl = curl_init();
		$comunasModel = ClassRegistry::init('Comuna');
		$regionesModel = ClassRegistry::init('Region');
		$retirosModel = ClassRegistry::init('Retiro');
		$oficinas_old = $retirosModel->find('list', array('fields' => array('codigo', 'id'), 'conditions' => array('tipo_id' => 2), 'order' => array('id' => 'asc')));
		$region = $regionesModel->find('first', array('conditions' => array('codigo2' => 'R' . $regionId)));
		$comunas_tmp = $comunasModel->find('list', array('fields' => array('Comuna.nombre', 'id'),
			'conditions' => array('region_id' => $region['Region']['id'])));
		$comunas = array();
		foreach ($comunas_tmp as $key => $value) 
		{
			$nombre_comuna = str_replace(array('á', 'é', 'í', 'ó', 'ú', 'ñ','Ñ'), array('a', 'e', 'i', 'o', 'u', 'n','n'), utf8_decode(utf8_encode(($key))));
			$comunas[strtolower($nombre_comuna)] = strtolower($value);
		}
		if ($regionId == 13)
			$regionId = 'M';
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://testservices.wschilexpress.com/georeference/api/v1.0/offices?Type=1&RegionCode=R" . $regionId,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			//CURLOPT_POSTFIELDS =>$array_json,
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Ocp-Apim-Subscription-Key: 4653bbc4bdc146b1bb9c1796fdaca7af"
			),
		));
		if (curl_exec($curl) === false) {
			echo 'Curl error: ' . curl_error($curl);
		} else {
			$oficinas = array();
			$respuesta = json_decode(curl_exec($curl), true);
			//prx($respuesta);

			foreach ($respuesta['offices'] as $oficina) 
			{
				
				$comuna = (strtolower($oficina['countyName']));
				if (!isset($comunas[$comuna])) {
					pr($comuna);
					continue;
				}
				$comuna_id = $comunas[$comuna];
				$oficina_tmp = array('nombre' => $oficina['officeName'],
						'codigo' => $oficina['officeCode'],
						'region_id' => $region['Region']['id'],
						'numero' => $oficina['streetNumber'],
						'extra' => $oficina['complement'],
						'tipo_id' => 2,
						'calle' => $oficina['streetName'],
						'comuna_id' => $comuna_id);
				if(isset($oficinas_old[$oficina['officeCode']]))
				{
					$oficina_tmp['id'] = $oficinas_old[$oficina['officeCode']];
				}
				if ($oficina['officeType'] == 3) 
				{
					$oficina_tmp['activo'] = 1;
				}else{
					$oficina_tmp['activo'] = 0;

				}
				$oficinas[]= $oficina_tmp;
			}

		}
		return $oficinas;


	}

	public function actualizarOficinas()
	{

		
		$oficinas = array();
		$regiones = $this->getRegiones();
		//prx($regiones);
		$retirosModel = ClassRegistry::init('Retiro');

		foreach ($regiones as $region) {
			$oficinas = array_merge($oficinas, $this->getOficinas($region['codigo']));
		}
		//pr($oficinas);
		prx($retirosModel->saveAll($oficinas));
	}

	public function despacho($data)
	{

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://services.wschilexpress.com/transport-orders/api/v1.0/tracking",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Ocp-Apim-Subscription-Key: 4653bbc4bdc146b1bb9c1796fdaca7af"
			),
		));
		if (curl_exec($curl) === false) {
			echo 'Curl error: ' . curl_error($curl);
		} else {
			$respuesta = json_decode(curl_exec($curl), true);
			//var_dump($respuesta);
			//	prx($respuesta);
			if ($respuesta['statusCode'] == 0) {
				$despacho = array('direccion' => $respuesta['data']['addressData']['address'],
					'comuna' => $respuesta['data']['addressData']['destinationCoverageCode'],
					'origen' => $respuesta['data']['addressData']['originCoverageCode'],
					'peso' => $respuesta['data']['transportOrderData']['weight']);
				$recepcion = array('nombre' => $respuesta['data']['deliveryData']['receptorName'],
					'rut' => $respuesta['data']['deliveryData']['receptorRut'],
					'fecha' => $respuesta['data']['deliveryData']['deliveryDate'],
					'hora' => $respuesta['data']['deliveryData']['deliveryHour']);
				$trackeo = array();
				$trackings = $respuesta['data']['trackingEvents'];
				for ($i = 0; $i < count($trackings); $i++) //for ($i=(count($trackings)-1); $i >= 0 ; $i--)
				{
					$fecha = $trackings[$i]['eventDate'] . ' ' . $trackings[$i]['eventHour'];
					$date = date_create($fecha);
					$trackeo [] = array('fecha' => date_format($date, "d-m-Y"),
						'hora' => date_format($date, "H:i"),
						'estado' => $trackings[$i]['description']);
				}
				//aqui debo retornar despacho recepcion y trackeo
				$datos = array(
					'despacho' => $despacho,
					'recepcion' =>$recepcion,
					'trackeo' => $trackeo
				);
				return($datos);
			}
			//return($respuesta);
			return false;
		}
	}
	//recibe id de compra
	public function detalle($token){

		//$hex = hex2bin("353430313032202f73696e646172656c4073696e646172656c2e636c");
		$q = $this->obtenerToken($token);
		if($q) {
			list($id_compra, $correo) = split('/', $q);
		}else{
			return false;
		}
		 $datos = ClassRegistry::init('Compra');

		 $usuario = $datos->Usuario->find('first', array('fields' =>array('id','nombre', 'apellido_paterno'),
			'conditions' => array('email' => $correo)));

		 if($usuario) {
			 $compra = $datos->find('first', array('fields' => array(
				 'id',
				 'created',
				 'cod_despacho'),
				 'conditions' => array(
					 'usuario_id' => $usuario['Usuario']['id'],
					 'cod_despacho' => $id_compra,
					 'estado' => 1),
				 'order' => array('id' => 'desc')));

			 if($compra) {
				 if ($compra['Compra']['cod_despacho'] != '') {

				 	$busqueda = array("reference" => $compra['Compra']['id'],
						 "transportOrderNumber" => $compra['Compra']['cod_despacho'],
						 "rut" => 76047265,
						 "showTrackingEvents" => 1);

					 $array_json = json_encode($busqueda);

					 $date=date_create($compra['Compra']['created']);
					 $busqueda['created'] = date_format($date,"d-m-Y");
					 $busqueda = array(
							'busqueda' => $busqueda
					 );

					 $datos = $this->despacho($array_json);
					 $datos = array_merge($datos,$busqueda);

					 return($datos);
					 }
				 	return false;
				 }
				 return false;
			 }
			return false;
	}
	function generarToken( $q ) {
		try {
			$qEncoded = bin2hex($q);
			return( $qEncoded );
		}catch(Exception $e){
			return $e->getMessage();
		}
	}

	function obtenerToken( $q ) {

			if(ctype_xdigit($q) && strlen($q) % 2 == 0){
				$qDecoded = hex2bin($q);
				return( $qDecoded );
			}else{
				return false;
			}

	}
}
?>

