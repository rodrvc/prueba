<?php
App::import('Core', array('Router','Controller'));

class MasivoShell extends Shell {
	var $Controller = null;

	function initialize() {
		$this->Controller =& new Controller();
	}

	function main() {
		/** /cake/1.3.11/cake/console/cake -app /home/skechile/public_html/desarrollo masivo > /home/skechile/public_html/cron.log */
						$this->borrarBloqueos();

		Configure::write('debug',1);

		set_time_limit(0);

		$inicio			= microtime(true);
		$guardar = true;

		$mapeo = array(0 => '1', '2', 'categoria', 'codigo', 'color',  'nombre', '7','precio',  'sku', 'talla', 'stock');

		/**
		 * FICHERO LOCAL O PRODUCCION
		 * ruta de ficheros:
		 * * BASEDIR	[desde el cual se leeran los archivos]
		 * * BASECOPY	[el cual se utilizara como repositorio de respaldos de archivos]
		 */
		$now = strtotime(date('d-m-Y H:i:s'));
		//prx(date('mdY', $now));
		/**
		 * ARCHIVO DE LECTURA
		 */

# Cambie estos datos por los de su Servidor FTP
		define("SERVER","andain.cl"); //IP o Nombre del Servidor
		define("PORT",21); //Puerto
		define("USER","amazon@skechers-chile.cl"); //Nombre de Usuario
		define("PASSWORD","Andain5546."); //Contraseña de acceso
		define("PASV",true); //Activa modo pasivo

		# FUNCIONES

		//Permite conectarse al Servidor FTP
		$id_ftp=ftp_connect(SERVER,PORT); //Obtiene un manejador del Servidor FTP
		ftp_login($id_ftp,USER,PASSWORD);
		ftp_pasv($id_ftp, true);
		$directorio=ftp_pwd($id_ftp);
		//print_r($directorio);
		$programacion = $now -(3600 *12);
		$name = 'products_'.date('mdY', $programacion).'.csv';
		//$name= 'products_10052018.csv';
		$basedir = DS.'var'.DS.'www'.DS.'html'.DS.'archivos'.DS;

		ftp_get($id_ftp, $basedir.$name, $name, FTP_BINARY );
		$namecopy = md5($name).'.csv';
		//die('si');

		$basedir = DS.'var'.DS.'www'.DS.'html'.DS.'archivos'.DS;
		$basecopy =DS.'var'.DS.'www'.DS.'html'.DS.'webroot'.DS.'archivos'.DS.'archivos_usa'.DS.date('Y-m-d',$programacion).DS;
		/* CREACION DE CARPETA BASECOPY */
		@mkdir($basecopy, 0755, true);
		$filename_log = $basecopy."log.txt";
		$fp = fopen($filename_log,"w");

		$archivo = $basedir.$name;

		$texto =
		'===================================================================='.PHP_EOL.
		'=== time: '.date('d-m-Y H:i:s').PHP_EOL.
		'=== archivo: '.$name.PHP_EOL.
		'=== fecha stock: '.date('d-m-Y',$programacion).PHP_EOL.
		'=== guardar: ';
		if ($guardar)
			$texto.='si';
		else
			$texto.='no';
		$texto.=PHP_EOL.
		'====================================================================';
		fwrite($fp,$texto.PHP_EOL);

		if ( ! $archivo )
		{

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
                $this->Controller->set('mensaje', 'Ha fallado la carga del archivo, favor correr nuevamente el proceso');
                // DATOS DESTINATARIO (CLIENTE)
                $email->to = 'jwalton@skechers.com';
                $email->bcc     = array(
                        'sebastian@sancirilo.cl',
                        //'solanger@skechers.com',
                        //'xeniac@skechers.com'
                );
                //$email->to = 'sdelvillar@andain.cl';

                $email->subject = '[Skechers] ERROR CARGA ARCHIVO';
                $email->from = 'Skechers <noreply@skechers-chile.cl>';
                $email->replyTo = 'noreply@skechers-chile.cl';
                $email->sendAs = 'html';
                $email->template        = 'mensaje';
                $email->delivery = 'smtp';
                if ( $email->send() )
                {
                        return true;
                }
                else
                {
                        return false;
                }
			$texto =
			'===================================================================='.PHP_EOL.
			'=== ARCHIVO INVALIDO'.PHP_EOL.
			'=== FIN ['.date('H:i:s').']'.PHP_EOL.
			'====================================================================';
			fwrite($fp,$texto.PHP_EOL);
			fclose($fp);
			die(false);
		}
		if (! file_exists($archivo))
		{
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
                $this->Controller->set('mensaje', 'Ha fallado la carga del archivo, favor correr nuevamente el proceso');
                // DATOS DESTINATARIO (CLIENTE)
                $email->to = 'jwalton@skechers.com';
                $email->bcc     = array(
                        'sebastian@sancirilo.cl',
                        //'solanger@skechers.com',
                        //'xeniac@skechers.com'
                );
                //$email->to = 'sdelvillar@andain.cl';

                $email->subject = '[Skechers] ERROR CARGA ARCHIVO';
                $email->from = 'Skechers <noreply@skechers-chile.cl>';
                $email->replyTo = 'noreply@skechers-chile.cl';
                $email->sendAs = 'html';
                $email->template        = 'mensaje';
                $email->delivery = 'smtp';
                if ( $email->send() )
                {
                        return true;
                }
                else
                {
                        return false;
                }

			$texto =
			'===================================================================='.PHP_EOL.
			'=== NO EXISTE ARCHIVO'.PHP_EOL.
			'=== FIN ['.date('H:i:s').']'.PHP_EOL.
			'====================================================================';
			fwrite($fp,$texto.PHP_EOL);
			fclose($fp);
			die(false);
		}

		fwrite($fp,'* lectura OK ['.date('H:i:s').'] '.PHP_EOL);
		/**
		 *	GUARDAR BKP DE ARCHIVO
		 */
		App::import('Model','ArchivosCarga');
		$ArchivosCargaOBJ = new ArchivosCarga();
		if (copy($archivo,$basecopy.$namecopy))
		{
			$texto =
			'*	respaldo archivo: OK ['.date('H:i:s').'] ';
			fwrite($fp,$texto.PHP_EOL);
			$options = array(
				'conditions' => array(
					'nombre' => $name,
					'archivo' => $namecopy
					),
				'fields' => array('id')
				);
			$archivo_carga = $ArchivosCargaOBJ->find('first',$options);
			if (! $archivo_carga)
			{
				$copyfile = array('nombre' => $name,
								  'archivo' => $namecopy,
								  'fecha_stock' => date('Y-m-d',$programacion),
								  'cargado' => 0,
								  'fecha_subido' => date('Y-m-d H:i:s',filectime($archivo))
								  );
				$ArchivosCargaOBJ->create();
				if ($ArchivosCargaOBJ->save($copyfile))
				{
					$texto =
					'*	guardar datos archivo: OK ['.date('H:i:s').'] ';
					fwrite($fp,$texto.PHP_EOL);
					if ($archivo_carga = $ArchivosCargaOBJ->find('first',$options))
						$copyfile['id'] = $archivo_carga['ArchivosCarga']['id'];
				}
				else
				{
					$texto =
					'*	guardar datos archivo: ERROR'.PHP_EOL.
					'===================================================================='.PHP_EOL.
					'=== FIN ['.date('H:i:s').']'.PHP_EOL.
					'====================================================================';
					fwrite($fp,$texto.PHP_EOL);
					fclose($fp);
					die(false);
				}
			}
			else
			{
				$texto =
				'-- el archivo ya existia en la base de datos! ['.date('H:i:s').'] ';
				fwrite($fp,$texto.PHP_EOL);
				$copyfile['id'] = $archivo_carga['ArchivosCarga']['id'];
			}
		}
		else
		{
			$texto =
			'===================================================================='.PHP_EOL.
			'=== SE PRODUJO UN PROBLEMA AL INTENTAR RESPALDAR EL ARCHIVO'.PHP_EOL.
			'=== FIN ['.date('H:i:s').']'.PHP_EOL.
			'====================================================================';
			fwrite($fp,$texto.PHP_EOL);
			fclose($fp);
			die(false);
		}
		if (! isset($copyfile['id']))
		{
			$texto =
			'===================================================================='.PHP_EOL.
			'=== ERROR'.PHP_EOL.
			'=== FIN ['.date('H:i:s').']'.PHP_EOL.
			'====================================================================';
			fwrite($fp,$texto.PHP_EOL);
			fclose($fp);
			die(false);
		}
		elseif (! $copyfile['id'])
		{
			$texto =
			'===================================================================='.PHP_EOL.
			'=== ERROR'.PHP_EOL.
			'=== FIN ['.date('H:i:s').']'.PHP_EOL.
			'====================================================================';
			fwrite($fp,$texto.PHP_EOL);
			fclose($fp);
			die(false);
		}

		// RECOLECCION DE ESTADISTICAS
		$stats 			= array('lineas' => 0,
								'nuevos' => 0,
								'list_productos' => array(),
								'omitido_producto' => 0,
								'list_omitido_producto' => array(),
								'omitido_stock' => 0,
								'errores' => 0,
								'guardo' => 'no',
								'memoria' => 0,
								'tiempo' => 0);

		App::import('Model','Stock');
		$StockOBJ = new Stock();
		$productos = $this->lista_productos__();
		//$options = array(
		//	'conditions' => array(
		//		'Producto.oferta' => 1
		//	),
		//	'fields' => array(
		//		'Producto.id',
		//		'Producto.id'
		//	)
		//);
		//$ofertas = $StockOBJ->Producto->find('list',$options);
		$options = array(
			'conditions' => array(
				'Producto.outlet' => 1
			),
			'fields' => array(
				'Producto.id',
				'Producto.id'
			)
		);
		$outletList = $StockOBJ->Producto->find('list',$options);
		//QUITAR OFERTAS
		$query = 'UPDATE sitio_productos SET oferta = 0 WHERE outlet = 0;';
		// $StockOBJ->Producto->query($query);
		$options = array(
			'conditions' => array(
				'Producto.coleccion_id' => 7
			),
			'fields' => array(
				'Producto.id',
				'Producto.id'
			)
		);
		$excluir = $StockOBJ->Producto->find('list',$options);

		$options = array('conditions' => array('Tienda.region_id' => 13,
											   'Tienda.id NOT' => array(20,32)),
						 'fields' => array('Tienda.id', 'Tienda.id'));
		$tienda = $StockOBJ->Tienda->find('first',$options);

		// INICIALIZA STOCK
		$new_stock = $new_precios = array();
		$texto =
		'===================================================================='.PHP_EOL.
		'=== INICIO DE LECTURA DEL ARCHIVO ['.date('H:i:s').']'.PHP_EOL.
		'====================================================================';
		fwrite($fp,$texto.PHP_EOL);

		$omisiones = '';
		$productosActivos = array();
		$separador=',';
		if ( ( $handle = fopen($archivo, 'r') ) !== FALSE )
		{
			while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
			{
				$stats['lineas']++;
				$registro = array();
				foreach ( $datos as $index => $valor )
				{
					if ( ! isset($mapeo[$index]) )
						continue;
	
					$registro[$mapeo[$index]]	= $valor;
				}

				if (isset($registro['codigo']) && isset($registro['color']) && isset($registro['talla']))
				{
					$codigo = trim($registro['codigo']).trim($registro['color']);

					$talla = trim($registro['talla']);
					/*  TALLAS ROPA */
					if (! is_numeric($talla))
					{
						$size = trim(strtoupper(preg_replace('/[^a-z0-9\s]/i', '', iconv('UTF-8', 'ASCII//IGNORE//TRANSLIT', $talla))));
						$sizes = array(
							'XS' => 1,
							'S' => 2,
							'XS/S' => 2,
							'M' => 3,
							'L' => 4,
							'L/XL' => 4,
							'XL' => 5,
							'XXL' => 6,
							'ONE' => 100,
							'ONE*' => 100



						);
						$talla = 0;
						if (isset($sizes[$size]))
						{
							$talla = $sizes[$size];
						}
					}
					/* --- */
					$cantidad = 0;
		
					if (isset($productos[$codigo]))
					{
						if (isset($registro['stock']) && in_array(trim($registro['stock']), array('y','Y')))
							$cantidad = 10;
						if ($productos[$codigo] == 798 && $talla == 40)	// excepcion de producto !!!
						{
							$cantidad = 0;
						}
						if ($cantidad)
						{
							if(!isset($productosActivos [$productos[$codigo]]))
								$productosActivos [$productos[$codigo]]= array('id' =>$productos[$codigo],'activo' => 1 );
							$stats['nuevos']++;
							$stats['list_productos'][$productos[$codigo]] = true;
							$stock = array('tienda_id' => $tienda['Tienda']['id'],
										   'producto_id' => $productos[$codigo],
										   'talla' => $talla,
										   'cantidad' => $cantidad
										   );
							if (isset($registro['sku']) && $registro['sku'])
							{
								$stock['sku'] = $registro['sku'];
							}
							$new_stock[] = $stock;
						}
						else
						{
							$stats['omitido_stock']++;
						}
						// actualizar precios
						$precio = 0;

						if ($registro['precio'])
							$precio = (int)(trim($registro['precio']));
						/**
						*	SI 
						*		el producto es outlet y viene en la carga diaria lo activa
						*		no actualiza el precio !!!
						*	SINO
						*		reemplaza precio y precio oferta por el precio del archivo
						*/

						if (in_array($productos[$codigo],$outletList))
						{
							$new_precio = array(
								'id' => $productos[$codigo],
								'activo' => 1
							);
							$new_precios[$productos[$codigo]] = $new_precio;
						}
						else if(!in_array($productos[$codigo],$excluir))
						{
							if ($precio)
							{
								$new_precio = array(
									'id' => $productos[$codigo],
									// 'precio_oferta' => $precio,
									// 'precio' => $precio
								);
								$new_precios[$productos[$codigo]] = $new_precio;
							}
						}else{
							// echo ' Excluido :'.$productos[$codigo]. '<br>';
						}
					}
					else
					{
						$stats['omitido_producto']++;
						$stats['list_omitido_producto'][$codigo] = true;
					}
				}
			}
			fclose($handle);
		}

		$texto =
		//'===================================================================='.PHP_EOL.
		'=== LECTURA FINALIZADA ['.date('H:i:s').']'.PHP_EOL.
		'====================================================================';
		fwrite($fp,$texto.PHP_EOL);

		/********** GUARDAR EN BASE DE DATOS **********/
		if ($guardar && $new_stock )
		{
			$texto =
			//'===================================================================='.PHP_EOL.
			'=== INICIO SAVE ['.date('H:i:s').']'.PHP_EOL.
			'====================================================================';
			fwrite($fp,$texto.PHP_EOL);
			$StockOBJ->query('TRUNCATE TABLE sitio_stocks;');

			if ( $StockOBJ->saveAll($new_stock, array('validate' => false)) )
			{
				// stock de productos promo
				$query = "
				INSERT INTO sitio_stocks (
					id,
					tienda_id,
					producto_id,
					talla,
					cantidad,
					sku,
					created,
					modified
				)
				VALUES (
					NULL , '1', '4415', '3', '10', NULL , NULL , NULL
				);";
				$StockOBJ->query($query);
				$query = "
				INSERT INTO sitio_stocks (
					id,
					tienda_id,
					producto_id,
					talla,
					cantidad,
					sku,
					created,
					modified
				)
				VALUES (
					NULL , '1', '4416', '3', '10', NULL , NULL , NULL
				);";
				$StockOBJ->query($query);
				// ==========
				$stats['guardo']='si';

				// CARGA DE STOCK TOTAL DE PRODUCTOS POR TALLA
				if ($new_stock)
				{
					foreach ($new_stock as $index => $stock)
					{
						unset($new_stock[$index]['tienda_id']);
					}

					App::import('Model','Talla');
					$TallaOBJ = new Talla();
					$TallaOBJ->query('TRUNCATE TABLE sitio_tallas;');
					$TallaOBJ->saveAll($new_stock, array('validate' => false));
					$ArchivosCargaOBJ->save(array('id' => $copyfile['id'],
												  'fecha_carga' => date('Y-m-d H:i:s'),
												  'cargado' => 1));
				}
				$this->borrarBloqueos();
				$this->cargar_menu();
				$this->colores_disponibles();
				 $this->limpiarStock(false);
				$this->verificacionOfertas();
				//if ($estilos = $this->refrezcar_estilos())
				//{
				//	fwrite($fp,$estilos.PHP_EOL);
				//}
			}
			$StockOBJ->Producto->saveAll($productosActivos);

			$query ='update sitio_stocks set id = 2000000000 + id;';
			$StockOBJ->query($query);
			$query = "update sitio_stocks set id = concat(rpad(replace(talla,'.',''),4,0),lpad(producto_id,5,0));";
			$StockOBJ->query($query);
			$query ='update sitio_tallas set id = 2000000000 + id;';
			$StockOBJ->query($query);
			$query = "update sitio_tallas set id = concat(rpad(replace(talla,'.',''),4,0),lpad(producto_id,5,0));";
			$StockOBJ->query($query);
			$query = "delete  from sitio_stocks where producto_id in (select id from sitio_productos where activo =0);";
			$StockOBJ->query($query);
			$query = "delete  from sitio_tallas where producto_id in (select id from sitio_productos where activo =0);";
			$StockOBJ->query($query);
			$texto =
			//'===================================================================='.PHP_EOL.
			'=== FINALIZACION SAVE ['.date('H:i:s').']'.PHP_EOL.
			'====================================================================';
			fwrite($fp,$texto.PHP_EOL);

			if ($new_precios)
			{
				$texto =
				//'===================================================================='.PHP_EOL.
				'=== INICIO ACTUALIZACION DE PRECIOS ['.date('H:i:s').']'.PHP_EOL.
				'====================================================================';
				fwrite($fp,$texto.PHP_EOL);
	
				$texto = '*	actualizacion de precios: ';
				if ( $StockOBJ->Producto->saveAll($new_precios, array('validate' => false)) )
					$texto.='si';
				else
					$texto.='no';
				fwrite($fp,$texto.PHP_EOL);
				$this->productosLiquidacion();
				$texto =
				//'===================================================================='.PHP_EOL.
				'=== FINALIZACION ACTUALIZACION DE PRECIOS ['.date('H:i:s').']'.PHP_EOL.
				'====================================================================';
				fwrite($fp,$texto.PHP_EOL);
			}
		}

		$stats['memoria']		= (memory_get_peak_usage(true) / 1024 / 1024) . ' MB';
		$stats['tiempo']		= round(microtime(true) - $inicio, 3);

		$texto =
		PHP_EOL.
		'===================================================================='.PHP_EOL.
		'#'.PHP_EOL.
		'#				REPORTE'.PHP_EOL.
		'#				-------'.PHP_EOL.
		'#'.PHP_EOL.
		'#	*	lineas leidas.......................'.$stats['lineas'].PHP_EOL.
		'#	*	stock cargados (tallas x producto)..'.$stats['nuevos'].PHP_EOL.
		'#	*	productos actualizados..............'.count($stats['list_productos']).PHP_EOL.
		'#	*	talla x producto omitidos...........'.$stats['omitido_producto'].PHP_EOL.
		'#	*	productos no encontrados............'.count($stats['list_omitido_producto']).PHP_EOL.
		'#	*	omitido por stock...................'.$stats['omitido_stock'].PHP_EOL.
		'#	*	precios actualizados................'.count($new_precios).PHP_EOL.
		'#	*	actualizacion BBDD..................'.$stats['guardo'].PHP_EOL.
		'#	*	memoria consumida...................'.$stats['memoria'].PHP_EOL.
		'#	*	tiempo..............................'.(int)$stats['tiempo'].' seg.'.PHP_EOL.
		'#'.PHP_EOL.
		'===================================================================='.PHP_EOL.
		PHP_EOL.
		'=== FIN ['.date('H:i:s').']'.PHP_EOL.
		'====================================================================';
		fwrite($fp,$texto.PHP_EOL);
		$texto = 'informe : no';
		if ($envio = $this->enviar_notificacion_email($stats['nuevos']))
			$texto = 'informe : si';
		fwrite($fp,$texto.PHP_EOL);
		fclose($fp);
		
		die('fin');
	}

	function borrarBloqueos()
	{
		App::import('Model','Bloqueo');
		$bloqueoOBJ = new Bloqueo();		
		$bloqueos = $bloqueoOBJ->find('all',array('fields' => array('producto_id', 'talla')));
		foreach ($bloqueos as $bloqueo)
		{
				$producto = $bloqueoOBJ->Producto->Stock->find('first', array('conditions' => array ('producto_id' => $bloqueo['Bloqueo']['producto_id'], 'talla' => $bloqueo['Bloqueo']['talla'] )));
				if($producto)
					$bloqueoOBJ->Producto->Stock->delete($producto['Stock']['id']);
				$producto = $bloqueoOBJ->Producto->Talla->find('first', array('conditions' => array ('producto_id' => $bloqueo['Bloqueo']['producto_id'], 'talla' => $bloqueo['Bloqueo']['talla'] )));
				if($producto)
					$bloqueoOBJ->Producto->Talla->delete($producto['Talla']['id']);
		}


	}
	
	function lista_productos__()
	{
		App::import('Model','Stock');
		$StockOBJ = new Stock();
		$consulta = 'SELECT
						Producto.id,
						Producto.codigo,
						Producto.categoria_id,
						Color.codigo
					FROM
						sitio_productos AS Producto
						LEFT JOIN sitio_colores AS Color ON (Color.id = Producto.color_id)';
		$productos = $StockOBJ->Producto->query($consulta);
		$lista_productos = array();
		if ($productos)
		{
			foreach ($productos as $producto)
			{
				if (isset($producto['Producto']['id']) && $producto['Producto']['id'] && isset($producto['Producto']['codigo']) && $producto['Producto']['codigo'] && isset($producto['Color']['codigo']) && $producto['Color']['codigo'])
				{
					$codigo = $producto['Producto']['codigo'].$producto['Color']['codigo'];
					$lista_productos[$codigo] = $producto['Producto']['id'];
				}
			}
		}
		return $lista_productos;
	}

	private function leerArchivo($archivo = null, $mapeo = null, $separador = null)
	{
		set_time_limit(0);
		if ( ! $archivo || empty($mapeo) || ! $separador)
			return false;

		$registros	= array();
		$fila		= 0;

		if ($separador)
		{
			if ( ( $handle = fopen($archivo, 'r') ) !== FALSE )
			{
				while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
				{
					if ( ++$fila == 1 )
						continue;
	
					$periodo_registro = 0;
					foreach ( $datos as $index => $valor )
					{
						if ( ! isset($mapeo[$index]) )
							continue;
	
						$registro[$mapeo[$index]]	= $valor;
					}
					array_push($registros, $registro);
				}
				fclose($handle);
			}
		}
		return $registros;
	}

	private function cargar_menu()
	{
		/** TIPO:
		 * 1 = POR TALLA
		 * 2 = POR COLOR
		 */
		$stock_seguridad = 4;
		App::import('Model','Stock');
		$StockOBJ = new Stock();

		$StockOBJ->Producto->Color->Primario->Menu->query('TRUNCATE TABLE sitio_menus;');

		// LISTA DE CATEGORIAS, CON RANGO DE TALLAS
		for ($padre = 1; $padre <= 4; $padre++)
		{
			$options =  array('conditions' => array('Categoria.id' => $padre),
							  'fields' => array('Categoria.id',
												'Categoria.desde',
												'Categoria.hasta',
												'Categoria.medios'));
			$categoria = $StockOBJ->Producto->Categoria->find('first',$options);
			$options = array('conditions' => array('Categoria.parent_id' => $padre),
							 'fields' => array('Categoria.id','Categoria.id'));
			$categorias_asociadas = $StockOBJ->Producto->Categoria->find('list', $options);
			if ($categorias_asociadas)
				$categorias_asociadas[$padre] = $padre;
			else
				$categorias_asociadas = array($padre => $padre);

			 //SE GENERA ARREGLO VACIO CON EL RANGO DE TALLAS DISPONIBLES PARA LA CATEGORIA
			for ($talla = $categoria['Categoria']['desde']; $talla <= $categoria['Categoria']['hasta'];$talla++)
			{
				$cont = array();
				$options = array('conditions' => array('Stock.cantidad >' => 0,
													   'Stock.talla' => $talla,
													   'Producto.categoria_id' => $categorias_asociadas),
								 'fields' => array('Stock.talla',
												   'Stock.cantidad'
												   ),
								 'contain' => array('Producto'));
				$productos = $StockOBJ->find('all', $options);
				// SUMAR CANTIDADES
				$estado = 0;
				foreach ($productos as $producto)
				{
					if (! $estado)
					{
						if (isset($cont[$producto['Producto']['id']]))
							$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'] + $cont[$producto['Producto']['id']];
						else
							$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'];
						if ($cont[$producto['Producto']['id']] >= $stock_seguridad)
							$estado = 1;
					}
				}

				$new_menu = array('Menu' => array('categoria_id' => $padre,
												  'talla' => $talla,
												  'estado' => $estado,
												  'tipo' => 1));
				$StockOBJ->Producto->Color->Primario->Menu->create();
				$StockOBJ->Producto->Color->Primario->Menu->save($new_menu);
				// TALLAS MEDIAS !!!
				if ( $categoria['Categoria']['medios'] == 1 && $talla < $categoria['Categoria']['hasta'] )
				{
					$cont = array();
					$options = array('conditions' => array('Stock.cantidad >' => 0,
														   'Stock.talla' => $talla . '.5',
														   'Producto.categoria_id' => $categorias_asociadas),
									 'fields' => array('Stock.talla',
													   'Stock.cantidad',
													   'Producto.id'
													   ),
									 'contain' => array('Producto'));
					$productos = $StockOBJ->find('all', $options);
					// SUMAR CANTIDADES
					$estado = 0;
					foreach ($productos as $producto)
					{
						if (! $estado)
						{
							if (isset($cont[$producto['Producto']['id']]))
								$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'] + $cont[$producto['Producto']['id']];
							else
								$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'];
							if ($cont[$producto['Producto']['id']] >= $stock_seguridad)
								$estado = 1;
						}
					}
			
					$new_menu = array('Menu' => array('categoria_id' => $padre,
													  'talla' => $talla . '.5',
													  'estado' => $estado,
													  'tipo' => 1));
					$StockOBJ->Producto->Color->Primario->Menu->create();
					$StockOBJ->Producto->Color->Primario->Menu->save($new_menu);
				}
			}
			// TALLAS 45 Y 46 PARA HOMBRES
			if ($padre == 2)
			{
				for ($talla = 45; $talla <= 46; $talla++)
				{
					$cont = array();
					$options = array('conditions' => array('Stock.cantidad >' => 0,
														   'Stock.talla' => $talla,
														   'Producto.categoria_id' => $categorias_asociadas),
									 'fields' => array('Stock.talla',
													   'Stock.cantidad'),
									 'contain' => array('Producto'));
					$productos = $StockOBJ->find('all', $options);
					// SUMAR CANTIDADES
					$estado = 0;
					foreach ($productos as $producto)
					{
						if (! $estado)
						{
							if (isset($cont[$producto['Producto']['id']]))
								$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'] + $cont[$producto['Producto']['id']];
							else
								$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'];
							if ($cont[$producto['Producto']['id']] >= $stock_seguridad)
							{
								$estado = 1;
								$new_menu = array('Menu' => array('categoria_id' => $padre,
																  'talla' => $talla,
																  'estado' => $estado,
																  'tipo' => 1));
								$StockOBJ->Producto->Color->Primario->Menu->create();
								$StockOBJ->Producto->Color->Primario->Menu->save($new_menu);
							}
						}
					}
				}
			}

			// COLORES MENU
			$colores_primarios = $StockOBJ->Producto->Color->Primario->find('all', array('fields' => array('Primario.id')));
			foreach ($colores_primarios as $color_primario)
			{
				$options = array('conditions' => array('OR' => array('Color.primario_id' => $color_primario['Primario']['id'],
																	 'Color.secundario_id' => $color_primario['Primario']['id']
																	 )),
								 'fields' => array('Color.id'));
				$colores_asociados = $StockOBJ->Producto->Color->find('list', $options);
				$estado = 0;
				$options = array('conditions' => array('Producto.categoria_id' => $categorias_asociadas,
													   'Producto.color_id' => $colores_asociados),
								 'fields' => array('Producto.id'),
								 'contain' => array('Stock' => array('fields' => array('Stock.cantidad'))));
				$productos = $StockOBJ->Producto->find('all', $options);
				if ($productos)
				{
					foreach ($productos as $producto)
					{
						if (! $estado)
						{
							$cantidad = 0;
							foreach ($producto['Stock'] as $stock)
							{
								$cantidad = $cantidad + $stock['cantidad'];
							}
							if ($cantidad >= $stock_seguridad)
								$estado = 1;
						}
					}
				}
				if ($estado)
				{
					$StockOBJ->Producto->Color->Primario->Menu->create();
					$new_menu = array('Menu' => array('categoria_id' => $padre,
													  'primario_id' => $color_primario['Primario']['id'],
													  'tipo' => 2));
					$StockOBJ->Producto->Color->Primario->Menu->save($new_menu);
				}
			}
		}
		return true;
	}

	private function colores_disponibles()
	{
		App::import('Model','Producto');
		$ProductoOBJ = new Producto();

		$options = array(
			'conditions' => array(
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		);
		$categorias = $ProductoOBJ->Categoria->find('list',$options);
		
		$options = array(
			'conditions' => array(
				'Producto.activo' => 1,
				'Producto.foto <> ' => ''

			),
			'fields' => array(
				'Producto.codigo',
				'COUNT(codigo) as count'
			),
			'group' => array(
				'Producto.codigo'
			)
		);
		$list = array();
		if ($counts = $ProductoOBJ->find('all',$options))
		{
			foreach ($counts as $count)
			{
				if ($count['Producto']['codigo'] && $count[0]['count'])
					$list[$count['Producto']['codigo']] = $count[0]['count'];
			}
		}
		$options = array(
			'conditions' => array(
				'Producto.categoria_id' => $categorias
			),
			'fields' => array(
				'Producto.id',
				'Producto.codigo'
			)
		);

		if(! $productos = $ProductoOBJ->find('list',$options))
			return false;
		$update = array();
		foreach ($productos as $id => $codigo)
		{
			$count = 0;
			if (isset($list[$codigo]) && $list[$codigo])
				$count = $list[$codigo];
			$update[$id] = array('id' => $id,
								 'colores' => $count);
		}
		if ($update)
			$ProductoOBJ->saveAll($update);
		return true;
	}

	private function enviar_notificacion_email($cargados = null)
	{
		App::import('Model','Compra');
		$CompraOBJ = new Compra();
		$hoy = strtotime(date('d-m-Y H:i:s'));
		$ayer = strtotime(date('d-m-Y',$hoy)) - (60*60*24*1);
		$reporte = array(
			'Ventas' => array(
				'total' => 0,
				'exito' => 0,
				'fracaso' => 0
			),
			'Real' => array(
				'total' => 0,
				'finalizadas' => 0
			),
			'Monto' => array(
				'total' => 0,
				'finalizadas' => 0
			),
			'Producto' => array(
				'total' => 0,
				'detalle' => array()
			)
		);
		$options = array(
			'conditions' => array(
				'Compra.created BETWEEN ? AND ?' => array(date('Y-m-d',$ayer).' :00:00:00',date('Y-m-d',$ayer).' 23:59:59')
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
						'Producto.id',
						'Producto.codigo_completo',
						'Producto.foto',
					)
				)
			),
			'order' => array(
				'Compra.id' => 'ASC'
			)
		);
		if ($compras = $CompraOBJ->find('all',$options))
		{
			$reales = $detalle = array();
			foreach ($compras as $compra)
			{
				$key = $compra['Compra']['usuario_id'].'-'.$compra['Compra']['total'];
				$estado = 0;
				if ($compra['Compra']['estado']==1)
					$estado = 1;

				$reporte['Ventas']['total']++;

				if (! isset($reales[$key]))
				{
					$reales[$key] = array('cont' => 1,'estado' => 0);
					$reporte['Monto']['total']+=$compra['Compra']['total'];
					$reporte['Real']['total']++;
				}

				if ($estado)
				{
					if (isset($reales[$key]))
					{
						if ($reales[$key]['estado'])
						{
							$reales[$key]['cont']++;
							$reporte['Monto']['total']+=$compra['Compra']['total'];
							$reporte['Real']['total']++;
						}
						else
						{
							$reales[$key]['estado'] = 1;
						}
					}
					$reporte['Ventas']['exito']++;
					$reporte['Real']['finalizadas']++;
					$reporte['Monto']['finalizadas']+=$compra['Compra']['total'];
					if ($compra['Producto'])
					{
						foreach ($compra['Producto'] as $producto)
						{
							$reporte['Producto']['total']++;
							if (isset($detalle[$producto['id']]))
							{
								$detalle[$producto['id']]['seleccionado']++;
								if (isset($detalle[$producto['id']]['Talla'][$producto['ProductosCompra']['talla']]))
								{
									$detalle[$producto['id']]['Talla'][$producto['ProductosCompra']['talla']]++;
								}
								else
								{
									$detalle[$producto['id']]['Talla'][$producto['ProductosCompra']['talla']] = 1;
								}
							}
							else
							{
								$detalle[$producto['id']] = array(
									'id' => $producto['id'],
									'codigo' => $producto['codigo_completo'],
									'foto' => $producto['foto'],
									'seleccionado' => 1,
									'Talla' => array(
										$producto['ProductosCompra']['talla'] => 1
									)
								);
							}
						}
					}
				}
			}
			$reporte['Ventas']['fracaso'] = $reporte['Ventas']['total']-$reporte['Ventas']['exito'];
		}
		// ordenar productos
		if ($detalle)
		{
			$list = array();
			foreach ($detalle as $producto)
			{
				$list[$producto['id']] = $producto['seleccionado'];
			}
			arsort($list);
			$limit = 0;
			$cont = 0;
			foreach ($list as $id => $valor)
			{
				if ($limit && $cont >= $limit)
					break;
				if (! isset($detalle[$id]))
					continue;
				array_push($reporte['Producto']['detalle'],$detalle[$id]);
				$cont++;
			}
		}

		$mensaje = '';
		if ($reporte)
		{
			$mensaje.='<div style="width:500px;margin:auto;"><h2>Carga Stock: '.$cargados.'</h2></div>';
			$mensaje.='<div style="width:500px;margin:auto;"><h1>Ventas diarias</h1>';
			$mensaje.='<table width="100%">';
			foreach ($reporte as $item => $datos)
			{
				if ($item == 'Producto')
					continue;
				$mensaje.='<tr><td colspan="2" style="padding-top:10px;text-align:left;"><b style="text-transform:uppercase;color:#000;">'.$item.'</b></td></tr>';
				foreach ($datos as $dato => $valor)
				{
					$mensaje.='<tr><td style="border-bottom:1px solid #777;color:#000;text-align:left;">'.$dato.'</td><td style="text-align:right;border-bottom:1px solid #777;color:#000;">'.number_format($valor,0,',','.').'</td></tr>';
				}
			}
			$mensaje.='</table>';
			if ($reporte['Producto'])
			{
				$mensaje.='<table width="100%">';
				$mensaje.='<tr><td colspan="3" style="padding-top:10px;"><b style="text-transform:uppercase;color:#000;">'.((isset($limit) && $limit)?$limit.' Productos mas vendidos':'Productos').'</b></td></tr>';
				$mensaje.='<tr><td colspan="2" style="text-align:left;"><b style="color:#000;">Productos vendidos</b></td><td style="text-align:right;color:#000;text-align:right;"><b>'.$reporte['Producto']['total'].'</b></td></tr>';
				foreach ($reporte['Producto']['detalle'] as $producto)
				{
					$mensaje.='<tr><td rowspan="'.(count($producto['Talla'])+2).'" style="border-top: 1px solid #777;text-align:left;"><img src="https://s3.amazonaws.com/andain-sckechers/img/Producto/'.$producto['id'].'/mini_'.$producto['foto'].'" /></td><td style="border-top: 1px solid #777;color:#000;text-align:left;">'.$producto['codigo'].'</td><td style="border-top: 1px solid #777;text-align:right;color:#000;">'.$producto['seleccionado'].'</td></tr>';
					$mensaje.='<tr><td style="color:#4285f4;font-weight:bold;text-align:left;">Tallas</td><td style="text-align:right;color:#4285f4;">&nbsp;</td></tr>';
					if ($producto['Talla'])
					{
						foreach ($producto['Talla'] as $talla => $valor)
						{
							$mensaje.='<tr><td style="color:#4285f4;text-align:left;border-top: 1px solid #777;">'.$talla.'</td><td style="text-align:right;color:#4285f4;border-top: 1px solid #777;">'.number_format($valor,0,',','.').'</td></tr>';
						}
					}
				}
				$mensaje.='</table>';
			}
			$mensaje.='</div>';
		}
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
		$email->to = 'jwalton@skechers.com';
		$email->bcc	= array(
			'sebastian@sancirilo.cl',
			//'solanger@skechers.com',
			//'xeniac@skechers.com'
		); 
		//$email->to = 'sdelvillar@andain.cl';

		$email->subject = '[Skechers] Informe de ventas diaria '.date('d-m-Y',$ayer);
		$email->from = 'Skechers <noreply@skechers-chile.cl>';
		$email->replyTo = 'noreply@skechers-chile.cl';
		$email->sendAs = 'html';
		$email->template	= 'mensaje';
		$email->delivery = 'smtp';
		if ( $email->send() )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	private function limpiarStock($sacar = true)
	{
		App::import('Model','Producto');
		$ProductoOBJ = new Producto();

		if (true)
		{
			// consultar productos sin estado coming soon que no tienen stock (que no estan en listado de productos con stock)
			$options = array(
				'conditions' => array(
					'Producto.coming_soon' => 0
				),
				'fields' => array(
					'Producto.id',
					'Producto.id'
				),
				'recursive' => -1,
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
							'Stock.cantidad >=' => 1
						)
					)
				),
			);
			if ($productosConStock = $ProductoOBJ->find('list',$options))
			{
				$options = array(
					'conditions' => array(
						'Producto.coming_soon' => 0,
						'NOT' => array(
							array('Producto.id' => $productosConStock)
						)
					),
					'fields' => array(
						'Producto.id',
						'Producto.categoria_id'
					),
					'recursive' => -1,
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
					),
				);
				if ($productosSinStock = $ProductoOBJ->find('all',$options))
				{
					foreach ($productosSinStock as $producto)
					{
						/** CATEGORIAS:
						 *** PUBLICAS
						 * 1	Mujer
						 * 2	Hombre
						 * 3	Niño
						 * 4	Niña
						 * 5	Performance
						 * 6	Performance-Mujer
						 * 9	Performance-Hombre
						 * 16	Kids
	
						 * FUERA DE TEMPORADA
						 * 50	FueraTemporada
						 * 51	FueraTemporada(Mujer)
						 * 52	FueraTemporada(hombre)
						 * 53	FueraTemporada(niño)
						 * 54	FueraTemporada(niña)
						 * 56	FueraTemporada(performance-mujer)
						 * 57	FueraTemporada(performance-hombre)
						 */
						//$categoriaId = 50;
						//if ($producto['Producto']['categoria_id'] == 1)	// Mujer
						//	$categoriaId = 51;
						//elseif ($producto['Producto']['categoria_id'] == 2)	// Hombre
						//	$categoriaId = 52;
						//elseif ($producto['Producto']['categoria_id'] == 3)	// Niño
						//	$categoriaId = 53;
						//elseif ($producto['Producto']['categoria_id'] == 4)	// Niña
						//	$categoriaId = 54;
						//elseif ($producto['Producto']['categoria_id'] == 6)	// Performance-mujer
						//	$categoriaId = 56;
						//elseif ($producto['Producto']['categoria_id'] == 9)	// Performance-hombre
						//	$categoriaId = 57;
						//$producto['Producto']['categoria_id'] = $categoriaId;
						$producto['Producto']['activo'] = 0;
						$ProductoOBJ->save($producto['Producto']);
					}
				}
			}
		}
		return true;
	}
	
	private function verificacionOfertas()
	{
		App::import('Model','Producto');
		$ProductoOBJ = new Producto();
		$options = array(
			'conditions' => array(
				'Producto.oferta' => 1,
				'Producto.precio_oferta >= Producto.precio'
			),
			'fields' => array(
				'Producto.id',
				'Producto.id',
			),
			'recursive' => -1,
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
			),
		);
		if ($productos = $ProductoOBJ->find('list',$options))
		{
			$ProductoOBJ->updateAll(array('Producto.oferta' => 0),array('Producto.id' => $productos));
		}
		return true;
	}

	private function productosLiquidacion()
	{
		//$basedir = 'C:'.DS.'xampp'.DS.'public_html'.DS.'andain'.DS.'skechers'.DS.'catalogo'.DS.'sitio2'.DS.'webroot'.DS.'img'.DS.'cargas_andain'.DS;
		$basedir = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'store'.DS.'webroot'.DS.'img'.DS.'cargas_andain'.DS;

		$name = 'ofertas.csv';
		$archivo = $basedir.$name;
		if ( ! $archivo )
			return false;

		if (! file_exists($archivo))
			return false;
		// echo '...<br>';
		$mapeo = array('CODIGO','PRECIO', 'OFERTA_PORCENTAJE','OFERTA_VALOR');
		$separador = ',';
		App::import('Model','Producto');
		$ProductoOBJ = new Producto();
		if ( ( $handle = fopen($archivo, 'r') ) !== FALSE )
		{
			while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
			{
				$registro = array();
				foreach ( $datos as $index => $valor )
				{
					if ( ! isset($mapeo[$index]) )
						continue;
					$registro[$mapeo[$index]]	= trim($valor);
				}
				if (! $registro )
					continue;

				if (! $codigo = $registro['CODIGO'] )
					continue;
				// echo $codigo.'<br>';
				$options = array(
					'conditions' => array(
						'Producto.codigo_completo' => $codigo
					),
					'fields' => array(
						'Producto.id',
						//'Producto.codigo_completo',
						'Producto.precio',
						'Producto.oferta',
						'Producto.precio_oferta'
					),
				);

				if (! $producto = $ProductoOBJ->find('first',$options) )
					continue;

				if ( isset($registro['OFERTA_PORCENTAJE']) && $registro['OFERTA_PORCENTAJE'] && $registro['OFERTA_PORCENTAJE'] >= 1 && $registro['OFERTA_PORCENTAJE'] <= 100 )
				{
					$descontar = ($producto['Producto']['precio']*($registro['OFERTA_PORCENTAJE']))/1000;
					$descontar = ((int)$descontar)*10;
					$oferta = $producto['Producto']['precio']-$descontar;

					if (! ( $oferta % 10 ) )
						$oferta = $oferta-10;

					$producto['Producto'] = array_merge($producto['Producto'], array(
						'oferta' => 1,
						'precio_oferta' => $oferta

					));
				}
				elseif (isset($registro['OFERTA_VALOR']) && $registro['OFERTA_VALOR'])
				{
					$producto['Producto'] = array_merge($producto['Producto'], array(
						'oferta' => 1,
						'precio_oferta' => $registro['OFERTA_VALOR']
					));
				}

				if ($producto['Producto']['precio'] > $producto['Producto']['precio_oferta'])
				{
					// echo implode(' - ',$producto['Producto']).'<br>';
					$ProductoOBJ->save($producto);
				}
			}
		}
		return true;
	}
	
	private function refrezcar_estilos()
	{
		App::import('Model','Producto');
		$ProductoOBJ = new Producto();
		$estilos = $ProductoOBJ->query(
			'SELECT
				Estilo.id,
				Estilo.nombre,
				Estilo.alias,
				Estilo.categoria_id
			FROM sitio_estilos AS Estilo;'
		);

		if (! $estilos)
			return false;

		$ProductoOBJ->query(
			'UPDATE sitio_estilos SET activo = 0;'
		);
		$reporte = 'reiniciado de estilos...'.PHP_EOL;
		foreach ($estilos as $estilo)
		{
			$options = array(
				'conditions' => array(
					'Producto.grupo LIKE' => '%['.$estilo['Estilo']['alias'].']%',
					'Producto.activo' => 1,
					'Producto.outlet' => 0,
					'Producto.categoria_id' => $estilo['Estilo']['categoria_id'],
					'Categoria.publico' => 1,
					'OR' => array(
						array('Categoria.id' => array(1,2,3,4)),
						array('Categoria.parent_id' => array(1,2,3,4))
					),
					'NOT' => array(
						array('Producto.foto' => null),
						array('Producto.foto' => '')
					)
				),
				'fields' => array(
					'Producto.id',
					'Producto.codigo_completo'
				),
				'recursive' => -1,
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
						'type' => 'INNER',
						'conditions' => array(
							'Stock.producto_id = Producto.id'
						)
					)
				),
				'group' => array(
					'Producto.id'
				)
			);
			if ($productos = $ProductoOBJ->find('list',$options))
			{
				$ProductoOBJ->query(
					'UPDATE sitio_estilos SET activo = 1 WHERE id = '.$estilo['Estilo']['id'].';'
				);
				$reporte.='ESTILO: '.$estilo['Estilo']['alias'].' #'.$estilo['Estilo']['categoria_id'].' - activado ['.count($productos).']'.PHP_EOL;
			}
			else
			{
				$reporte.='ESTILO: '.$estilo['Estilo']['alias'].' - desactivado'.PHP_EOL;
			}
		}
		$reporte.= 'actualizacion de estilos finalizada '.date('H:i:s').PHP_EOL;
		return $reporte;
	}
}
function prx($data)
{
	pr($data); exit;
}
