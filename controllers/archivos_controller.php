<?php
class ArchivosController extends AppController
{
	var $name = 'Archivos';
	var $components = array('Masivo');
	var $tiendas = array("276","282","278","275","280","283","290","336","334","390","385");
	var $mapeo = array(0 => 'division', 'divisionname', 'codigo', 'tienda', 'one',  '3', '3H','4',  '5', '5H', '6', '6H', '7', '7H', '8', '8H', '9', '9H', '10', '10H', '11', '11H', '12', '13', '13H', '14', '1', '1H', '2');
	//var $tallas_ninos = array(3,4,16);
	//var $tallas_hombre = array(2,9,10,11);
	//var $tallas_mujer = array(1,5,6,7,8,19);
	//var $guardar = true;
	/**
	 ************** TIPOS DE ARCHIVOS **************
	 ***********************************************
	 * 0 = stock
	 * 1 = productos
	 * 2 = descuentos
	 * 3 = precios
	 * 4 = ropa
	 ***********************************************
	 ***********************************************
	 */
	public function test()
	{
		$this->Archivo->revisarFTP();
	}
	private function verificarUsaFile_($time = null)
	{
		if (! $time)
			return false;
		$basedir = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'webroot'.DS.'archivos'.DS;
		$basedir = 'D:'.DS.'Webserver'.DS.'public_html'.DS.'leyton'.DS.'catalogo-skechers'.DS.'sitio'.DS.'webroot'.DS.'img'.DS.'cargas_andain'.DS;
		$file = $basedir.'products_'.date('mdY',$time).'.csv';
		if (! file_exists($file))
			return false;
		$archivo = array('nombre' => 'products_'.date('mdY',$time).'.csv',
						 'url' => $file,
						 'peso' => round(filesize($file) / 1024 / 1024, 2).' MB',
						 'fecha' => date('d-m-Y',$time),
						 'modified' => date('d-m-Y h:i',filectime($file))
						 );
		return $archivo;
	}
	private function usaFiles__()
	{
		$cont = $x = 0;
		$limit = 20;
		$time = strtotime(date('d-m-Y'));
		$usaFile = $this->verificarUsaFile_($time);
		$usaFiles = array();
		if ($usaFile)
		{
			$usaFiles[] = $usaFile;
			$cont++;
		}
		while ($cont <= $limit)
		{
			$time-=(60*60*24);
			$usaFile = $this->verificarUsaFile_($time);
			if ($usaFile)
			{
				$usaFiles[] = $usaFile;
				$cont++;
				$x = 0;
			}
			else
				$x++;
			if ($x>=15)
				$cont = 100;
		}
		return $usaFiles;
	}
	function admin_index()
	{
		$this->paginate = array('conditions' => array('Archivo.tipo' => 0),
								'fields' => array('Archivo.id',
												  'Archivo.nombre',
												  'Archivo.tipo',
												  'Archivo.flag',
												  'Archivo.administrador_id',
												  'Archivo.created'),
								'contain' => array('Administrador' => array('fields' => array('Administrador.id',
																							  'Administrador.nombre'))),
								'order' => array('Archivo.created' => 'DESC'));
		$archivos = $this->paginate();
		if ($archivos)
			$archivos = $this->calcular_peso($archivos);
		
		$this->loadModel('ArchivosCarga');
		$options = array(
			'fields' => array(
				'ArchivosCarga.id',
				'ArchivosCarga.nombre',
				'ArchivosCarga.archivo',
				'ArchivosCarga.fecha_stock',
				'ArchivosCarga.cargado',
				'ArchivosCarga.fecha_subido',
				'ArchivosCarga.fecha_carga'
				),
			'order' => array('ArchivosCarga.fecha_stock' => 'DESC'),
			'limit' => 20
			);
		$usaFiles = $this->ArchivosCarga->find('all',$options);
		if ($usaFiles)
		{
			foreach ($usaFiles as $index => $usaFile)
			{
				$size = 0;
				if (file_exists("img/archivos_usa/".$usaFile['ArchivosCarga']['fecha_stock'].'/'.$usaFile['ArchivosCarga']['archivo']))
					$size = round(filesize("img/archivos_usa/".$usaFile['ArchivosCarga']['fecha_stock'].'/'.$usaFile['ArchivosCarga']['archivo']) / 1024 / 1024, 2);
				$usaFiles[$index]['ArchivosCarga']['size'] = $size;
			}
		}
		$this->set(compact('archivos', 'usaFiles'));

		if ($this->Auth->user('perfil') == 3)
		{
			$archivos_productos = $this->Archivo->find('all', array('conditions' => array('Archivo.tipo' => 1),
																	'fields' => array('Archivo.id',
																					  'Archivo.nombre',
																					  'Archivo.tipo',
																					  'Archivo.flag',
																					  'Archivo.administrador_id',
																					  'Archivo.created'),
																	'contain' => array('Administrador' => array('fields' => array('Administrador.id',
																																  'Administrador.nombre'))),
																	'order' => array('Archivo.created' => 'DESC'),
																	'limit' => 10));
			if ($archivos_productos)
				$archivos_productos = $this->calcular_peso($archivos_productos);
			$archivos_descuentos = $this->Archivo->find('all', array('conditions' => array('Archivo.tipo' => 2),
																	 'fields' => array('Archivo.id',
																					   'Archivo.nombre',
																					   'Archivo.tipo',
																					   'Archivo.flag',
																					   'Archivo.administrador_id',
																					   'Archivo.created'),
																	 'contain' => array('Administrador' => array('fields' => array('Administrador.id',
																																   'Administrador.nombre'))),
																	 'order' => array('Archivo.created' => 'DESC'),
																	 'limit' => 10));
			if ($archivos_descuentos)
				$archivos_descuentos = $this->calcular_peso($archivos_descuentos);
			$archivos_precios = $this->Archivo->find('all', array('conditions' => array('Archivo.tipo' => 3),
																  'fields' => array('Archivo.id',
																					'Archivo.nombre',
																					'Archivo.tipo',
																					'Archivo.flag',
																					'Archivo.administrador_id',
																					'Archivo.created'),
																  'contain' => array('Administrador' => array('fields' => array('Administrador.id',
																																'Administrador.nombre'))),
																  'order' => array('Archivo.created' => 'DESC'),
																  'limit' => 10));
			if ($archivos_precios)
				$archivos_precios = $this->calcular_peso($archivos_precios);
			$this->loadModel('Categoria');
			$categorias = $this->Categoria->find('list', array('conditions' => array('Categoria.id' => array(1,2,3,4,6,7,8,9,10,11)),
															   'fields' => array('Categoria.id', 'Categoria.nombre'),
															   'order' => array('Categoria.id' => 'ASC')));
			
			$archivos_ropa = $this->Archivo->find('all', array('conditions' => array('Archivo.tipo' => 4),
															   'fields' => array('Archivo.id',
																				 'Archivo.nombre',
																				 'Archivo.tipo',
																				 'Archivo.flag',
																				 'Archivo.administrador_id',
																				 'Archivo.created'),
															   'contain' => array('Administrador' => array('fields' => array('Administrador.id',
																															 'Administrador.nombre'))),
															   'order' => array('Archivo.created' => 'DESC'),
															   'limit' => 10));
			if ($archivos_ropa)
				$archivos_ropa = $this->calcular_peso($archivos_ropa);
			$this->set(compact('archivos_productos','archivos_descuentos', 'archivos_precios', 'categorias','archivos_ropa'));
		}
	}
	
	function calcular_peso($archivos = array())
	{
		if ($archivos)
		{
			foreach ($archivos as $index => $archivo)
			{
				$size = 0;
				if (file_exists("img/{$archivo['Archivo']['nombre']['path']}"))
					$size = round(filesize("img/{$archivo['Archivo']['nombre']['path']}") / 1024 / 1024, 2);
				$archivos[$index]['Archivo']['size'] = $size;
			}
		}
		return $archivos;
	}

	private function tallas($tallas_mujer, $tallas_nino, $talla,$categoria, $infant = false, $gateadora = false)
	{
		
		$numero = (int)$talla;
		if ( in_array($categoria, $tallas_nino) )
		{
			if ($infant)
			{
				if ($numero)
				{
					if ($gateadora)
					{
						if ($numero <= 4)
						{
							$numero +=  14;
						}
						else
						{
							$numero +=  15;
						}
					}
					else
					{
						if ($numero > 4 && $numero <= 10 )
						{
							$numero +=  15;
						}
					}
				}
				else
				{
					return 0;
				}
			}
			else
			{
				if ( $talla == 10 && trim( substr( $talla,-1 )) == 'H' )
				{
					$numero =  26;
				}
				elseif ( $numero > 10 && $numero < 14 )
				{
					$numero +=  16;
				}
				elseif ( trim( $talla ) == '1' )
				{
					$numero = 30; 
				}
				elseif ( trim( $talla ) == '1H' )
				{
					$numero = 31; 
				}
				elseif ( trim( $talla ) == '2' )
				{
					$numero = 32; 
				}
				elseif ( trim( $talla )== '3' )
				{
					$numero = 33; 
				}
				elseif ( trim( $talla )== '3H' )
				{
					$numero = 34; 
				}
				else if ( trim( $talla ) == '4' )
				{
					$numero = 35; 
				}else if ( trim( $talla ) == '5' )
				{
					$numero = 36; 
				}else if ( trim( $talla ) == '5H' )
				{
					$numero = 37; 
				}else if ( trim( $talla ) == '6' )
				{
					$numero = 38; 
				}
			}
		}
		else
		{
			if ( in_array($categoria, $tallas_mujer) )
				$numero += 30;
			else
				$numero += 32;
			if ( trim( substr( $talla, -1 )) == 'H' )
				$numero .= ".5";
			if ( $numero < 33 )
			  return 0;
		}
		return $numero;
	}
	
	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$mensaje = '';

		$archivo = $this->Archivo->find('first', array('conditions' => array('Archivo.id' => $id,
																			 'Archivo.administrador_id' => $this->Auth->user('id')),
													   'fields' => array('Archivo.id',
																		 'Archivo.nombre',
																		 'Archivo.tipo'),
													   'recursive' => -1));

		if (isset($archivo['Archivo']['tipo']) && $archivo['Archivo']['tipo'] == 2)
		{
			// MAPEO DE CAMPOS
			$mapeo	 		= array(0 => 'codigo');
			$registros		= $this->Masivo->procesarArchivo('img/' . $archivo['Archivo']['nombre']['path'], $mapeo);

			if ($registros)
			{
				$lista_descuentos = array();
				foreach($registros as $registro)
				{
					if ( trim($registro['codigo']) )
					{
						$lista_descuentos[] = trim($registro['codigo']);
					}
				}
				$this->loadModel('Descuento');
				$options = array('conditions' => array('Descuento.codigo' => $lista_descuentos,
													   'Descuento.contador <=' => 0),
								 'fields' => array('Descuento.id',
												   'Descuento.id'),
								 'recursive' => -1);
				$descuentos = $this->Descuento->find('list', $options);
				if ($descuentos)
				{
					$this->Descuento->deleteAll(array('Descuento.id' => $descuentos));
					$mensaje = '. Se eliminaron '.count($descuentos).' descuentos asociados a este archivo.';
				}
			}
		}
		if ( $this->Archivo	->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado'.$mensaje, true));
			$this->redirect($this->referer());
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}

	function admin_cargar_excel()
	{
		if ( ! empty($this->data) )
		{
			$this->data['Archivo']['administrador_id']		= $this->Auth->user('id');
			$this->data['Archivo']['tipo'] = 0;
			$this->Archivo->create();
			if ( $this->Archivo->save($this->data) )
			{
				if ( isset($this->data['Archivo']['tipo']) && $this->data['Archivo']['tipo'] == 1)
				{
					$this->Session->setFlash(__('Registro guardado correctamente', true));
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$archivo = $this->Archivo->find('first', array('conditions' => array('Archivo.id' => $this->Archivo->id)));
					// validar archivo
					$valido = $this->valido($archivo['Archivo']['nombre']['path']);
					if (! $valido = $this->valido($archivo['Archivo']['nombre']['path']) )
					{
						$this->Session->setFlash(__('ERROR_ARCHIVO-0001', true));
						$this->Archivo->save(array('Archivo' => array('id' => $archivo['Archivo']['id'],
																	  'flag' => 2)));
					}
					else
					{
						$this->Session->setFlash(__('El archivo fue guardado correctamente', true));
					}
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
	}

	function admin_cargar_productos()
	{
		if ( ! empty($this->data) )
		{
			$this->data['Archivo']['administrador_id']		= $this->Auth->user('id');
			$this->data['Archivo']['tipo'] = 1;
			$this->Archivo->create();
			if ( $this->Archivo->save($this->data) )
			{
				if ( isset($this->data['Archivo']['tipo']) && $this->data['Archivo']['tipo'] == 1)
				{
					$this->Session->setFlash(__('Registro guardado correctamente', true));
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$archivo = $this->Archivo->find('first', array('conditions' => array('Archivo.id' => $this->Archivo->id)));
					// validar archivo
					$valido = $this->valido($archivo['Archivo']['nombre']['path']);
					if (! $valido = $this->valido($archivo['Archivo']['nombre']['path']) )
					{
						$this->Session->setFlash(__('ERROR_ARCHIVO-0001', true));
						$this->Archivo->save(array('Archivo' => array('id' => $archivo['Archivo']['id'],
																	  'flag' => 2)));
					}
					else
					{
						$this->Session->setFlash(__('El archivo fue guardado correctamente', true));
					}
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$this->render('admin_cargar_excel');
	}

	function admin_cargar_ropa()
	{
		if ( ! empty($this->data) )
		{
			$this->data['Archivo']['administrador_id']		= $this->Auth->user('id');
			$this->data['Archivo']['tipo'] = 4;
			$this->Archivo->create();
			if ( $this->Archivo->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$this->render('admin_cargar_excel');
	}

	function admin_cargar($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}

		if ( ! $archivo = $this->Archivo->find('first', array('conditions'	=> array('Archivo.id' => $id),
															  'fields'		=> array('Archivo.id', 'Archivo.nombre'))) )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}

		if ( ! $resultado = $this->procesar($archivo['Archivo']['nombre']['path']) )
		{
			$this->Session->setFlash(__('Error al procesar el archivo. Intenta nuevamente', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->Archivo->query('UPDATE sitio_archivos SET flag = 0 WHERE tipo = 0 AND flag = 1');
		$this->Archivo->id = $id;
		$this->Archivo->saveField('flag', 1);
		
		$this->set(compact('resultado'));
	}
	
	private function valido($archivo = null)
	{
		// MAPEO DE CAMPOS
		$mapeo	 		= $this->mapeo;
		$falta = null;
		$orden = array(0 => null,
					   1 => null);

		$handle = fopen('img/'. $archivo, 'r');
		$datos[1] = fgetcsv($handle, 0, ';');
		$datos[] = fgetcsv($handle, 0, ';');
		fclose($handle);

		for ($index = 5; $index <= 28; $index++)
		{
			if ($orden[0])
				$orden[0] = $orden[0] . ', ' . $mapeo[$index];
			else
				$orden[0] = $mapeo[$index];

			for ($x=1; $x<=2; $x++)
			{
				if (isset($datos[$x][$index]) && $datos[$x][$index])
				{
					if (isset($orden[$x]) && $orden[$x])
						$orden[$x] = $orden[$x] . ', ' . trim($datos[$x][$index]);
					else
						$orden[$x] = $datos[$x][$index];
				}
			}
		}
		if ($orden[0] == $orden[1] || $orden[0] == $orden[2])
			return true;
		else
			return false;
	}

	private function procesar($archivo = null)
	{
		// BENCHMARKING
		set_time_limit(0);
		$inicio			= microtime(true);
		$guardar = true;
		$verificar_producto_id = false;
		$verificar_talla = false;

		if ( ! $archivo )
			return false;

		// MAPEO DE CAMPOS
		$mapeo	 		= $this->mapeo;
		$registros		= $this->Masivo->procesarArchivo("img/{$archivo}", $mapeo);
		
		if ( ! $registros )
			return false;

		// RECOLECCION DE ESTADISTICAS
		$stats 			= array('nuevos' => 0, 'errores' => 0, 'error_numero' => 0, 'rango_cantidad' => 0, 'no_stgo' => 0, 'memoria' => 0, 'tiempo' => 0);

		// CARGA EL MODELO
		$this->loadModel('Stock');

		$tienda = $this->Stock->Tienda->find('all');
		// ELIMINA DEL STOCK LOS REGISTROS CON TIENDAS INVALIDAS, FUERA DE SANTIAGO O DISTINTAS DE LA TIENDA "Mall Easton Center" (id: 20, codigo: 321)
		$tiendas_stgo = $this->Stock->Tienda->find('list', array('conditions' => array('Tienda.region_id' => 13,
																					   'Tienda.id NOT' => array(20,32)),
																 'fields' => array('Tienda.id', 'Tienda.id')));

		$tiendas;
		$cod_ant = 0;
		foreach ( $tienda as $tien )
		{
			$tiendas [] = $tien["Tienda"]["codigo"];
		}
		foreach ( $tienda as $tien )
		{
			$tiendas_aux[$tien["Tienda"]["codigo"]] = $tien["Tienda"]["id"];
		}
		
		// COLORES
		$color_aux = array();
		$color_aux['L'] = $this->Stock->Producto->Color->find('list', array('conditions' => array('Color.codigo LIKE' => 'L%'),
																			'fields' => array('Color.id', 'Color.codigo')));
		$color_aux['N'] = $this->Stock->Producto->Color->find('list', array('conditions' => array('Color.codigo LIKE' => 'N%'),
																			'fields' => array('Color.id', 'Color.codigo')));

		$cont =0;
		$infant;
		
		$this->loadModel('Categoria');
		$tallas_nino = $this->Categoria->find('list', array('conditions' => array('Categoria.publico' => 1,
																				  'Categoria.hasta <=' => 38),
															'fields' => array('Categoria.id',
																			  'Categoria.id')));
		$tallas_mujer = $this->Categoria->find('list', array('conditions' => array('Categoria.publico' => 1,
																				   'Categoria.hasta <=' => 40,
																				   'Categoria.sexo_id' => 2,
																				   'Categoria.id NOT' => $tallas_nino),
															 'fields' => array('Categoria.id',
																			   'Categoria.id')));
		$gateadoras = array(3771,3772,3773,3774,3811);
		// INICIALIZA STOCK
		$new_stock = array();
		$productos_tallas = array();
		
		//for( $i = 2220; $i < 2250; $i++ )
		for( $i = 1; $i < count($registros); $i++ )
		{
			$codigo = $registros[$i]['codigo'];
			$cod_tienda = $registros[$i]['tienda'];

			if (! in_array(trim($cod_tienda),$tiendas) )
				continue;

			// VERIFICA SI LA ZAPATILLA (por el codigo) LA MISMA QUE LA ANTERIOR
			if ( $codigo != $cod_ant )
			{
				$infant = $nino = $gateadora = false;
				$cod_prod = (int)$codigo;

				/**
				 * VERIFICA SI EL 6º CARACTER DEL CODIGO ES NUMERICO
				 * si ES numerico, comprueba si este caracter es "L" o "N"
				 * si se cumple la condicion, comprueba que no sea un codigo de color
				 * guarda el stock del producto segun corresponda (para niño o infante)
				 *
				 * si NO ES numerico, guarda el stock del producto segun corresponda (para niño o infante)
				 */
				$letra_codigo = (int)(substr($codigo,5,1));
				if ($letra_codigo)
				{
					$codigo_aux = str_replace($cod_prod,"",$codigo);
					if (substr($codigo_aux,0,1) == "L")
					{
						$codigo_aux = str_replace($color_aux['L'],"",$codigo_aux);
						if (substr($codigo_aux,0,1) == "L")
						{
							$cod_prod .="L";
							$nino =true;
						}
					}
					elseif (substr($codigo_aux,0,1) == "N")
					{
						$codigo_aux = str_replace($color_aux['N'],"",$codigo_aux);
						if (substr($codigo_aux,0,1) == "N")
						{
							$cod_prod .="N";
							$nino =true;
							$infant = true;
						}
					}
					elseif (substr($codigo_aux,0,2) == "CL")
					{
						$cod_prod .="CL";
					}
				}
				else
				{
					$codigo_aux = str_replace($cod_prod,"",$codigo);
					if (substr($codigo_aux,0,2) == "CL")
					{
						$cod_prod .="CL";
					}
					elseif( substr($codigo_aux,0,1) == "L" )
					{
						$codigo_aux = str_replace($color_aux['L'],"",$codigo_aux);
						if (substr($codigo_aux,0,1) == "L")
						{
							$cod_prod .="L";
							$nino =true;
						}
					}
					elseif( substr($codigo,5,1) == "N" )
					{
						$codigo_aux = str_replace($color_aux['N'],"",$codigo_aux);
						if (substr($codigo_aux,0,1) == "N")
						{
							$cod_prod .="N";
							$nino =true;
							$infant = true;
						}
					}
				}
				// Extrae el codigo del producto y deja el codigo del color
				$cod_color = str_replace($cod_prod,"",$codigo);

				// Busca el color de la zapatilla
				$color = $this->Stock->Producto->Color->find('first', array('conditions' => array('Color.codigo' => $cod_color)));
				// Buscar id zapato
				$zapato = $this->Stock->Producto->find('first', array('conditions' => array('Producto.codigo' => $cod_prod,
																							'Producto.color_id' => $color['Color']['id'])));
			}
			$cod_ant = $codigo;

			if(! $zapato )
			{
				continue;
			}
			$categoria = $zapato['Producto']['categoria_id'];
			foreach ( $registros[$i] as $talla => $cantidad )
			{
				if (! in_array($talla, array('division', 'divisionname', 'codigo', 'tienda')) )
				{
					// salta campos vacios en excel
					if (! trim( $cantidad ) )
					{
						continue;
					}

					if ( (int)$cantidad < 200 && (int)$cantidad > 0 )
					{
						$gateadora = false;
						if (in_array($zapato['Producto']['id'], $gateadoras))
						{
							$gateadora = true;
						}
						$numero = $this->tallas( $tallas_mujer, $tallas_nino, $talla, $categoria, $infant, $gateadora );

						//guardar dato, idzapato,idtienda,talla,cantidad
						if (isset($numero) && $numero)
						{
							// FILTRA PRODUCTOS DE SANTIAGO
							if (in_array($tiendas_aux[$cod_tienda], $tiendas_stgo))
							{
								// NUEVA CARGA
								$new_stock[] = array('tienda_id' => $tiendas_aux[$cod_tienda],
													 'producto_id' => $zapato['Producto']['id'],
													 'talla' => $numero,
													 'cantidad' => $cantidad
													 );
								if (isset($productos_tallas[$zapato['Producto']['id']][$numero]))
								{
									$productos_tallas[$zapato['Producto']['id']][$numero] = $productos_tallas[$zapato['Producto']['id']][$numero] + $cantidad;
								}
								else
								{
									$productos_tallas[$zapato['Producto']['id']][$numero] = $cantidad;
								}

							}
							else
							{
								$stats['no_stgo'] = $stats['no_stgo'] + 1;
								$stats['errores'] = $stats['errores'] + 1;
							}
						}
						else
						{
							$stats['errores'] = $stats['errores'] + 1;
							$stats['error_numero'] = $stats['error_numero'] + 1;
						}
					}
					else
					{
						$stats['rango_cantidad'] = $stats['rango_cantidad'] + 1;
						$stats['errores'] = $stats['errores'] + 1;
					}
				}
			}
		}

		/********** GUARDAR EN BASE DE DATOS **********/
		$new_tallas = array();
		if ($guardar && $new_stock )
		{
			$this->Stock->query('TRUNCATE TABLE sitio_stocks;');
			
			if ( $this->Stock->saveAll($new_stock, array('validate' => false)) )
			{
				$stats['nuevos'] = count($new_stock);
				// BORRA PRODUCTOS INGRESADOS QUE NO SON DE SANTIAGO
				$conditions = array('Stock.tienda_id NOT' => $tiendas_stgo);
				$cont_no_stgo = $this->Stock->find('count', array('conditions' => $conditions));
				$this->Stock->deleteAll($conditions);
				if ($cont_no_stgo)
				{
					$stats['errores'] = $stats['errores'] + $cont_no_stgo;
					$stats['no_stgo'] = $stats['no_stgo'] + $cont_no_stgo;
					$stats['nuevos'] = $stats['nuevos'] - $cont_no_stgo;
				}
				// CARGA DE STOCK TOTAL DE PRODUCTOS POR TALLA
				if ($productos_tallas)
				{
					foreach ($productos_tallas as $producto_id => $tallas)
					{
						if ($tallas)
						{
							foreach ($tallas as $talla => $cantidad)
							{
								$new_tallas[] = array('producto_id' => $producto_id,
													  'talla' => $talla,
													  'cantidad' => $cantidad);
							}
						}
					}

					if ($new_tallas)
					{
						$this->loadModel('Talla');
						$this->Talla->query('TRUNCATE TABLE sitio_tallas;');
						$this->Talla->saveAll($new_tallas, array('validate' => false));
					}
				}
				$this->cargar_menu();
			}
		}

		//$stats['nuevos']		= $cont;
		$stats['memoria']		= (memory_get_peak_usage(true) / 1024 / 1024) . ' MB';
		$stats['tiempo']		= round(microtime(true) - $inicio, 3);
		return $stats;
	}
	
	private function stock_general($id = null, $numero = null, $cantidad = null)
	{
		$this->loadModel('Talla');
		$this->Talla->query('TRUNCATE TABLE sitio_tallas;');
		if ($id && $numero && $cantidad)
		{
			$talla = $this->Talla->find('first', array('conditions' => array('Talla.producto_id' => $id,
																			 'Talla.talla' => $numero),
													   'fields' => array('Talla.id',
																		 'Talla.producto_id',
																		 'Talla.talla',
																		 'Talla.cantidad')));

			if ($talla)
			{
				$update_talla = array('Talla' => array('id' => $talla['Talla']['id'],
													   'cantidad' => $talla['Talla']['cantidad'] + $cantidad));
				$this->Talla->save($update_talla);
			}
			else
			{
				$new_talla = array('Talla' => array('producto_id' => $id,
													'talla' => $numero,
													'cantidad' => $cantidad));
				$this->Talla->create();
				$this->Talla->save($new_talla);
			}
		}
		return true;
	}

	private function cargar_menu()
	{
		/** TIPO:
		 * 1 = POR TALLA
		 * 2 = POR COLOR
		 */
		$stock_seguridad = $this->stock_seguridad;
		$this->loadModel("Stock");
		$this->loadModel("Primario");
		$this->loadModel("Menu");

		$this->Menu->query('TRUNCATE TABLE sitio_menus;');

		// LISTA DE CATEGORIAS, CON RANGO DE TALLAS
		for ($padre = 1; $padre <= 4; $padre++)
		{
			$categoria = $this->Stock->Producto->Categoria->find('first', array('conditions' => array('Categoria.id' => $padre),
																				'fields' => array('Categoria.id',
																								  'Categoria.desde',
																								  'Categoria.hasta',
																								  'Categoria.medios')));
			$categorias_asociadas = $this->Stock->Producto->Categoria->find('list', array('conditions' => array('Categoria.parent_id' => $padre),
																						  'fields' => array('Categoria.id','Categoria.id')));
			if ($categorias_asociadas)
				$categorias_asociadas[$padre] = $padre;
			else
				$categorias_asociadas = array($padre => $padre);

			 //SE GENERA ARREGLO VACIO CON EL RANGO DE TALLAS DISPONIBLES PARA LA CATEGORIA
			for ($talla = $categoria['Categoria']['desde']; $talla <= $categoria['Categoria']['hasta'];$talla++)
			{
				$cont = array();
				$productos = $this->Stock->find('all', array('conditions' => array('Stock.cantidad >' => 0,
																				   'Stock.talla' => $talla,
																				   'Producto.categoria_id' => $categorias_asociadas),
															 'fields' => array('Stock.talla',
																			   'Stock.cantidad',
																			   /*'Producto.categoria_id',
																			   'Producto.slug'*/),
															 'contain' => array('Producto')));
				// SUMAR CANTIDADES
				$estado = 0;
				foreach ($productos as $producto)
				{
					if (! $estado)
					{
						if (isset($cont[$producto['Producto']['id']]))
						{
							$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'] + $cont[$producto['Producto']['id']];
						}
						else
						{
							$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'];
						}
						if ($cont[$producto['Producto']['id']] >= $stock_seguridad)
						{
							$estado = 1;
						}
					}
				}

				$new_menu = array('Menu' => array('categoria_id' => $padre,
												  'talla' => $talla,
												  'estado' => $estado,
												  'tipo' => 1));
				$this->Menu->create();
				$this->Menu->save($new_menu);
				// TALLAS MEDIAS !!!
				if ( $categoria['Categoria']['medios'] == 1 && $talla < $categoria['Categoria']['hasta'] )
				{
					$cont = array();
					$productos = $this->Stock->find('all', array('conditions' => array('Stock.cantidad >' => 0,
																					   'Stock.talla' => $talla . '.5',
																					   'Producto.categoria_id' => $categorias_asociadas),
																 'fields' => array('Stock.talla',
																				   'Stock.cantidad',
																				   'Producto.id'
																				   /*'Producto.categoria_id',
																				   'Producto.slug'*/),
																 'contain' => array('Producto')));
					// SUMAR CANTIDADES
					$estado = 0;
					foreach ($productos as $producto)
					{
						if (! $estado)
						{
							if (isset($cont[$producto['Producto']['id']]))
							{
								$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'] + $cont[$producto['Producto']['id']];
							}
							else
							{
								$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'];
							}
							if ($cont[$producto['Producto']['id']] >= $stock_seguridad)
							{
								$estado = 1;
							}
						}
					}
			
					$new_menu = array('Menu' => array('categoria_id' => $padre,
													  'talla' => $talla . '.5',
													  'estado' => $estado,
													  'tipo' => 1));
					$this->Menu->create();
					$this->Menu->save($new_menu);
				}
			}
			// TALLAS 45 Y 46 PARA HOMBRES
			if ($padre == 2)
			{
				for ($talla = 45; $talla <= 46; $talla++)
				{
					$cont = array();
					$productos = $this->Stock->find('all', array('conditions' => array('Stock.cantidad >' => 0,
																					   'Stock.talla' => $talla,
																					   'Producto.categoria_id' => $categorias_asociadas),
																 'fields' => array('Stock.talla',
																				   'Stock.cantidad',
																				   /*'Producto.categoria_id',
																				   'Producto.slug'*/),
																 'contain' => array('Producto')));
					// SUMAR CANTIDADES
					$estado = 0;
					foreach ($productos as $producto)
					{
						if (! $estado)
						{
							if (isset($cont[$producto['Producto']['id']]))
							{
								$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'] + $cont[$producto['Producto']['id']];
							}
							else
							{
								$cont[$producto['Producto']['id']] = $producto['Stock']['cantidad'];
							}
							if ($cont[$producto['Producto']['id']] >= $stock_seguridad)
							{
								$estado = 1;
								$new_menu = array('Menu' => array('categoria_id' => $padre,
																  'talla' => $talla,
																  'estado' => $estado,
																  'tipo' => 1));
								$this->Menu->create();
								$this->Menu->save($new_menu);
							}
						}
					}
				}
			}

			// COLORES MENU
			$colores_primarios = $this->Primario->find('all', array('fields' => array('Primario.id')));
			foreach ($colores_primarios as $color_primario)
			{
				$colores_asociados = $this->Primario->Color->find('list', array('conditions' => array('OR' => array('Color.primario_id' => $color_primario['Primario']['id'],
																												   'Color.secundario_id' => $color_primario['Primario']['id']
																												   )),
																			   'fields' => array('Color.id')));
				$estado = 0;
				$productos = $this->Stock->Producto->find('all', array('conditions' => array('Producto.categoria_id' => $categorias_asociadas,
																							 'Producto.color_id' => $colores_asociados),
																	   'fields' => array('Producto.id'),
																	   'contain' => array('Stock' => array('fields' => array('Stock.cantidad')))));
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
							{
								$estado = 1;
							}
						}
					}
				}
				if ($estado)
				{
					$this->Menu->create();
					$new_menu = array('Menu' => array('categoria_id' => $padre,
													  'primario_id' => $color_primario['Primario']['id'],
													  'tipo' => 2));
					$this->Menu->save($new_menu);
				}
			}
		}
		return true;
	}

	function admin_cargar2($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}

		$guardar = true;
		$generarLog = true;

		$options = array(
			'conditions' => array(
				'Archivo.id' => $id,
				'Archivo.tipo' => 1
			),
			'fields' => array(
				'Archivo.id',
				'Archivo.nombre'
			)
		);

		if (! $archivo = $this->Archivo->find('first', $options))
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (! file_exists('img/'.$archivo['Archivo']['nombre']['path']))
		{
			$this->Session->setFlash(__('Archivo inválido', true));
			$this->redirect(array('action' => 'index'));
		}

		//============================== PROCESAR ========================
		/** categorias nuevas
		 *	12	Women
		 *	13	Men
		 *	14	Girl
		 *	15	Boy
		 */
		// BENCHMARKING
		set_time_limit(0);
		$inicio			= microtime(true);

		// MAPEO DE CAMPOS
		$mapeo = array(0 => 'Codigo','Color','Nombre','Categoria','Precio','Escolar','division','tipo','tag1','tag2','tag3','tag4','tag5','fecha','lanzamiento');
		$registros		= $this->Masivo->procesarArchivo("img/{$archivo['Archivo']['nombre']['path']}", $mapeo);

		if ( ! $registros )
		{
			$this->Session->setFlash(__('Error al procesar el archivo. Intenta nuevamente', true));
			$this->redirect(array('action' => 'index'));
		}

		if ($generarLog)
		{
			$basecopy = 'img/Archivo/'.$archivo['Archivo']['id'].'/';
			$filename_log = $basecopy."log.txt";
			$fp = fopen($filename_log,"a");
			fwrite($fp,'=== INICIO ['.date('d-m-Y H:i:s').'] ==='.PHP_EOL);
		}

		// RECOLECCION DE ESTADISTICAS4
		$linea = 0;
		$colores_invalidos = array();
		$resultado = array(
			'nuevos' => 0,
			'actualizados' => 0,
			'errores' => 0,
			'omitidos' => 0,
			'reactivados' => 0
		);
		$this->loadModel('Producto');

		$options = array(
			'conditions' => array(
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.id'
			)
		);
		$categorias_publicas = $this->Producto->Categoria->find('list',$options);

		$options = array(
			'fields' => array(
				'Coleccion.id'
			),
			'order' => array('Coleccion.id' => 'DESC'),
			'recursive' => -1
		);
		$coleccion = $this->Producto->Coleccion->find('first',$options);

		foreach($registros as $index => $registro)
		{
			if (++$linea == 1)
				continue;
			$porcentaje = ($index*100)/count($registros);

			if (! isset($registro['Color']))
			{
				$resultado['omitidos']++;
				if ($generarLog)
					fwrite($fp,'- color vacio ('.$linea.')'.PHP_EOL);
				continue;
			}
			if (! $registro['Color'])
			{
				$resultado['omitidos']++;
				if ($generarLog)
					fwrite($fp,'- color vacio ('.$linea.')'.PHP_EOL);
				continue;
			}
			if (! isset($registro['Codigo']))
			{
				$resultado['omitidos']++;
				if ($generarLog)
					fwrite($fp,'- codigo vacio ('.$linea.')'.PHP_EOL);
				continue;
			}
			if (! $registro['Codigo'])
			{
				$resultado['omitidos']++;
				if ($generarLog)
					fwrite($fp,'- codigo vacio ('.$linea.')'.PHP_EOL);
				continue;
			}

			$options = array(
				'conditions' => array(
					'Color.codigo' => $registro['Color']
				),
				'fields' => array(
					'Color.id',
					'Color.codigo'
				)
			);

			if (! $color = $this->Producto->Color->find('first',$options))
			{
				$resultado['omitidos']++;
				$colores_invalidos[trim($registro['Color'])] = trim($registro['Color']);
				if ($generarLog)
					fwrite($fp,'- color invalido '.$registro['Color'].' ('.$linea.')'.PHP_EOL);
				continue;
			}

			if (! isset($registro['Categoria']))
			{
				$resultado['omitidos']++;
				if ($generarLog)
					fwrite($fp,'- categoria vacio ('.$linea.')'.PHP_EOL);
				continue;
			}
			if (! $registro['Categoria'])
			{
				$resultado['omitidos']++;
				if ($generarLog)
					fwrite($fp,'- categoria vacio ('.$linea.')'.PHP_EOL);
				continue;
			}
			$categoriaName = str_replace(array(' ', '-'), '', strtolower(trim($registro['Categoria'])));
			$categoriaName = utf8_encode($categoriaName);

			$categoriaId = 0;
			if ( in_array($categoriaName, array('men', 'hombres', 'hombre', 'newmen', 'newhombre', 'newhombres', 'permen')) )
				$categoriaId = 2;
			elseif ( in_array($categoriaName, array('girl', 'girls', 'niña', 'niñas', 'newgirl', 'newgirls', 'newniña', 'newniñas', 'infants','kidsgirlsinf','kidsgirls', 'girlsinf', 'girlsinfants')) )
				$categoriaId = 4;
			elseif ( in_array($categoriaName, array('boy', 'boys', 'niño', 'niños', 'newboy', 'newboys', 'newniño', 'newniños','kidsboys','kidsboysinf','boysinf', 'boysinfants')) )
				$categoriaId = 3;
			elseif ( in_array($categoriaName, array('women', 'mujeres', 'mujer', 'newwomen', 'newmujer', 'newmujeres', 'perwomen')) )
				$categoriaId = 1;
			elseif ( in_array($categoriaName, array('ropawomen')) )
				$categoriaId = 60;
			elseif ( in_array($categoriaName, array('ropamen')) )
				$categoriaId = 59;

			if (! $categoriaId)
			{
				$resultado['omitidos']++;
				if ($generarLog)
					fwrite($fp,'- categoria invalido '.$registro['Categoria'].' ('.$linea.')'.PHP_EOL);
				continue;
			}

			$options = array(
				'conditions' => array(
					'Producto.codigo' => trim($registro['Codigo']),
					'Producto.color_id' => $color['Color']['id']
				),
				'fields' => array(
					'Producto.id',
					'Producto.codigo',
					'Producto.foto',
					'Producto.color_id',
					'Producto.categoria_id',
					'Producto.grupo',
					'Producto.activo'
				),
				'recursive' => -1
			);

			$save = array(
				'nombre' => trim($registro['Nombre']),
				'codigo' => trim($registro['Codigo']),
				'color_id' => $color['Color']['id'],
				'categoria_id' => $categoriaId,
				'coleccion_id' => $coleccion['Coleccion']['id'],
				'codigo_completo' => trim($registro['Codigo']).trim($registro['Color']),
				'activo' => 0
			);

			if (trim($registro['division']))
				$save['division'] = strtoupper(trim($registro['division']));

			if (is_numeric(trim($registro['Precio'])) && trim($registro['Precio']) > 0)
			{
				$save['precio'] = trim($registro['Precio']);
				$save['oferta'] = 0;
				$save['precio_oferta'] = trim($registro['Precio']);
			}

			// escolar
			if (isset($registro['Escolar']) && trim($registro['Escolar']))
			{
				$save['escolar'] = 0;
				if (in_array(strtoupper(trim($registro['Escolar'])),array('S','SI','Y','YES')))
					$save['escolar'] = 1;
			}
			//tags
			$tags = array();
			for ($x=1;$x<=5;$x++)
			{
				if (isset($registro['tag'.$x]) && trim($registro['tag'.$x]))
				{
					$tag = Inflector::slug(strtolower(trim($registro['tag'.$x])));
					$tags[$tag] = '['.$tag.']';
				}
			}

			if (in_array($categoriaName, array('perwomen'))) 
			{
				$tags['performance'] = '[performance]';
				$save['showroom'] = 'PERFORMANCE';
			}
			elseif (in_array($categoriaName, array('permen')))
			{
				$tags['performance'] = '[performance]';
				$save['showroom'] = 'PERFORMANCE';
			}


			if (isset($registro['tipo']) && trim($registro['tipo']))
			{
				$tag = Inflector::slug(strtolower(trim(utf8_encode($registro['tipo']))));
				$tags[$tag] = '['.$tag.']';
			}

			$productoId = 0;
			if ($productoOld = $this->Producto->find('first',$options)) // actualizar
			{
				if ($productoOld['Producto']['grupo'])
				{
					if ($tagsOld = explode(',',$productoOld['Producto']['grupo']))
					{
						foreach ($tagsOld as $tagOld)
						{
							if ($tagOld)
							{
								$tagNew = Inflector::slug(strtolower($tagOld));
								$tagNew = str_replace(array('_2015i','_2014ii','2015'),'',$tagNew);
								$tags[$tagNew] = '['.$tagNew.']';
							}
						}
					}
				}

				$save['id'] = $productoOld['Producto']['id'];
				$save['grupo'] = implode($tags);
				if ($productoOld['Producto']['activo'])
				{
					$save['activo'] = 1;
				}
				elseif ($productoOld['Producto']['foto'])
				{
					$save['activo'] = 1;
				}

				if (! in_array($productoOld['Producto']['categoria_id'],$categorias_publicas))
				{
					if ($guardar)
					{
						if ($this->Producto->save($save))
						{
							$resultado['reactivados']++;
							if ($generarLog)
								fwrite($fp,'+ reactivado '.$save['codigo_completo'].' ('.$linea.')'.PHP_EOL);
						}
						else
						{
							$resultado['errores']++;
							if ($generarLog)
								fwrite($fp,'- error save '.$save['codigo_completo'].' ('.$linea.')'.PHP_EOL);
						}
					}
					else
					{
						$resultado['reactivados']++;
					}
				}
				else
				{
					if ($guardar)
					{
						if ($this->Producto->save($save))
						{
							$resultado['actualizados']++;
							if ($generarLog)
								fwrite($fp,'+ actualizado '.$save['codigo_completo'].' ('.$linea.')'.PHP_EOL);
						}
						else
						{
							$resultado['errores']++;
							if ($generarLog)
								fwrite($fp,'- error save '.$save['codigo_completo'].' ('.$linea.')'.PHP_EOL);
						}
					}
					else
					{
						$resultado['actualizados']++;
					}
				}
			}
			else // nuevo
			{
				$save['new'] = 1;
				if ($tags)
				{
					$save['grupo'] = implode($tags);
				}

				if ($guardar)
				{
					$this->Producto->create();
					if ($this->Producto->saveAll($save, array('validate' => false)))
					{
						$resultado['nuevos']++;
						if ($generarLog)
							fwrite($fp,'+ nuevo '.$save['codigo_completo'].' ('.$linea.')'.PHP_EOL);
					}
					else
					{
						$resultado['errores']++;
						if ($generarLog)
							fwrite($fp,'- error save '.$save['codigo_completo'].' ('.$linea.')'.PHP_EOL);
					}
				}
				else
				{
					$resultado['nuevos']++;
				}
			}
		}

		$resultado['memoria']		= (memory_get_peak_usage(true) / 1024 / 1024) . ' MB';
		$resultado['tiempo']		= round(microtime(true) - $inicio, 3);
		$resultado['colores_invalidos'] = implode(', ',$colores_invalidos);

		//============================== FIN PROCESAR ========================

		$this->Archivo->id = $id;
		$this->Archivo->saveField('flag', 1);

		if ($generarLog)
		{
			$texto = '=== FIN ['.date('d-m-Y H:i:s').'] ===';
			fwrite($fp,$texto.PHP_EOL.PHP_EOL.PHP_EOL);
			fclose($fp);
		}

		$this->set(compact('resultado', 'archivo'));
	}

	private function procesar2($archivo = null)
	{
		/** categorias nuevas
		 *	12	Women
		 *	13	Men
		 *	14	Girl
		 *	15	Boy
		 */
		// BENCHMARKING
		set_time_limit(0);
		$inicio			= microtime(true);

		if ( ! $archivo )
			return false;

		// MAPEO DE CAMPOS
		$mapeo	 		= array(0 => 'Codigo', 'Color', 'Nombre', 'Categoria', 'Precio', 'Escolar', 'division', 'tipo', 'tag1', 'tag2', 'tag3', 'tag4', 'tag5');
		$registros		= $this->Masivo->procesarArchivo("img/{$archivo}", $mapeo);
		
		if ( ! $registros )
			return false;
		
		// RECOLECCION DE ESTADISTICAS4
		$cont = $cont2 = 0;
		$stats 			= array('nuevos' => 0, 'errores' => 0, 'omitidos' => 0, 'memoria' => 0, 'tiempo' => 0, 'detalle_omitido' => '', 'detalle_error' => '', 'reactivados' => 0, 'detalle_reactivados' => '');
		$this->loadModel('Producto');
		$categorias_publicas = $this->Producto->Categoria->find('list', array('conditions' => array('Categoria.publico' => 1),
																			  'fields' => array('Categoria.id','Categoria.id')));
		foreach($registros as $registro)
		{
			if (isset($registro['Color']) && $registro['Color'] && isset($registro['Codigo']) && $registro['Codigo'])
			{
				$color = $this->Producto->Color->find('first', array('conditions' => array('Color.codigo' => $registro['Color']),
																	 'fields' => array('Color.id',
																					   'Color.codigo')));
				if ($color)
				{
					$verificar = $this->Producto->find('first', array('conditions' => array('Producto.codigo' => $registro['Codigo'],
																							'Producto.color_id' => $color['Color']['id']),
																	  'fields' => array('Producto.id', 'Producto.codigo', 'Producto.color_id', 'Producto.categoria_id'),
																	  'contain' => array('Color' => array('fields' => array('Color.id', 'Color.codigo')))));
	
					if ( $verificar )
					{
						// reactiva productos de temporadas pasadas, que se encuentren inactivos
						if (! in_array($verificar['Producto']['categoria_id'], $categorias_publicas))
						{
							if ( isset($registro['Categoria']) && $registro['Categoria'] )
							{
								$validar_categoria = trim($registro['Categoria']);
								$validar_categoria = str_replace(array(' ', '-'), '', $validar_categoria);
								$validar_categoria = strtolower($validar_categoria);
								$validar_categoria = utf8_encode($validar_categoria);
		
								if ( in_array($validar_categoria, array('men', 'hombres', 'hombre', 'newmen', 'newhombre', 'newhombres')) )
								{
									$categoria = 13;
								}
								elseif ( in_array($validar_categoria, array('girl', 'girls', 'niña', 'niñas', 'newgirl', 'newgirls', 'newniña', 'newniñas', 'infants','kidsgirls','kidsgirl','kidgirl','kidgirls','kidsgirlsinf')) )
								{
									$categoria = 14;
								}
								elseif ( in_array($validar_categoria, array('boy', 'boys', 'niño', 'niños', 'newboy', 'newboys', 'newniño', 'newniños','kidsboys','kidsboy','kidboy','kidboys','kidsboysinf')) )
								{
									$categoria = 15;
								}
								elseif ( in_array($validar_categoria, array('performance', 'perwomen', 'permen')) )
								{
									$categoria = 26;
									if ( in_array($validar_categoria, array('permen')) )
									{
										$categoria = 27;
									}
								}
								else
								{
									$categoria = 12;
								}
							}
							else
							{
								$categoria = 12;
							}

							$precio = 0;
							if (isset($registro['Precio']) && $registro['Precio'])
							{
								$precio = str_replace(array('.', '$'),'',$registro['Precio']);
							}

							$edit_producto = array('Producto' => array('id' => $verificar['Producto']['id'],
																	   'categoria_id' => $categoria,
																	   'coleccion_id' => 0,
																	   'precio'	=> $precio,
																	   'new' => 0));
							if (isset($registro['division']) && trim($registro['division']))
								$edit_producto['Producto']['division'] = strtoupper(trim($registro['division']));
							if (isset($registro['tipo']) && trim($registro['tipo']))
								$edit_producto['Producto']['tipo'] = strtoupper(trim($registro['tipo']));
							if ( (isset($registro['tag1']) && trim($registro['tag1'])) || (isset($registro['tag2']) && trim($registro['tag2'])) || (isset($registro['tag3']) && trim($registro['tag3'])) || (isset($registro['tag4']) && trim($registro['tag4'])) || (isset($registro['tag5']) && trim($registro['tag5'])) )
							{
								$tags = '';
								for ($x = 1; $x <= 5; $x++)
								{
									if (trim($registro['tag'.$x]))
									{
										$tags = $tags.trim($registro['tag'.$x]).',';
									}
								}
								if ($tags)
								{
									$edit_producto['Producto']['grupo'] = ','.$tags;
								}
							}

							if ( $this->Producto->save($edit_producto) )
							{
								$stats['reactivados']++;
								if ($stats['detalle_reactivados'])
								{
									$stats['detalle_reactivados'] = $stats['detalle_reactivados'] . ', ';
								}
								$stats['detalle_reactivados'] = $stats['detalle_reactivados'] . $verificar['Producto']['codigo'] . '-' . $verificar['Color']['codigo'];
							}
						}
						else
						{
							// omitidos
							$cont2++;
							if ($stats['detalle_omitido'])
							{
								$stats['detalle_omitido'] = $stats['detalle_omitido'] . ', ';
							}
							$stats['detalle_omitido'] = $stats['detalle_omitido'] . $verificar['Producto']['codigo'] . '-' . $verificar['Color']['codigo'];
						}
					}
					else
					{
						if ( isset($registro['Categoria']) && $registro['Categoria'] )
						{
							$validar_categoria = trim($registro['Categoria']);
							$validar_categoria = str_replace(array(' ', '-'), '', $validar_categoria);
							$validar_categoria = strtolower($validar_categoria);
							//$validar_categoria = utf8_encode($validar_categoria);
							if ( in_array($validar_categoria, array('men', 'hombres', 'hombre', 'newmen', 'newhombre', 'newhombres')) )
							{
								$categoria = 13;
							}
							elseif ( in_array($validar_categoria, array('girl', 'girls', 'niña', 'niñas', 'newgirl', 'newgirls', 'newniña', 'newniñas', 'infants')) )
							{
								$categoria = 14;
							}
							elseif ( in_array($validar_categoria, array('boy','kids', 'boys', 'niño', 'niños', 'newboy', 'newboys', 'newniño', 'newniños')) )
							{
								$categoria = 15;
							}
							elseif ( in_array($validar_categoria, array('performance', 'perwomen', 'permen')) )
							{
								$categoria = 26;
								if ( in_array($validar_categoria, array('permen')) )
								{
									$categoria = 27;
								}
							}
							else
							{
								$categoria = 12;
							}
						}
						else
						{
							$categoria = 12;
						}

						$precio = 0;
						if (isset($registro['Precio']) && $registro['Precio'])
						{
							$precio = str_replace(array('.', '$'),'',$registro['Precio']);
						}

						$producto = array('Producto' => array('nombre' 			=> $registro['Nombre'],
															  'categoria_id'	=> $categoria,
															  'coleccion_id'	=> 0,
															  'color_id'		=> $color['Color']['id'],
															  'codigo'			=> $registro['Codigo'],
															  'codigo_completo' => $registro['Codigo'].$color['Color']['codigo'],
															  'precio'			=> $precio,
															  'oferta' 			=> 0,
															  'descripcion_id' 	=> 0,
															  //'foto' =>'nada.jpg',
															  'new' => 1,
															  'excluir_descuento' => 0));
						if (isset($registro['Escolar']) && $registro['Escolar'] == 'si')
						{
							$producto['Producto']['escolar'] = 1;
						}

						if (isset($registro['division']) && trim($registro['division']))
							$producto['Producto']['division'] = strtoupper(trim($registro['division']));
						if (isset($registro['tipo']) && trim($registro['tipo']))
							$producto['Producto']['tipo'] = strtoupper(trim($registro['tipo']));
						if ( (isset($registro['tag1']) && trim($registro['tag1'])) || (isset($registro['tag2']) && trim($registro['tag2'])) || (isset($registro['tag3']) && trim($registro['tag3'])) || (isset($registro['tag4']) && trim($registro['tag4'])) || (isset($registro['tag5']) && trim($registro['tag5'])) )
						{
							$tags = '';
							for ($x = 1; $x <= 5; $x++)
							{
								if (trim($registro['tag'.$x]))
								{
									$tags = $tags.trim($registro['tag'.$x]).',';
								}
							}
							if ($tags)
							{
								$producto['Producto']['grupo'] = strtoupper(','.$tags);
							}
						}

						$this->Producto->create();
						if ( $this->Producto->saveAll($producto, array('validate' => false)) )
						{
							$localhost = true;
							//$this->generar_foto($this->Producto->id, $registro['Codigo'].'_'.$color['Color']['codigo'], $localhost);
							$cont++;
							echo($registro['Codigo'].'_'.$color['Color']['codigo']);
						}
					}
				}
				else
				{
					$stats['errores'] = $stats['errores'] + 1;
					if ($stats['detalle_error'])
					{
						$stats['detalle_error'] = $stats['detalle_error'] . ', ';
					}
					$stats['detalle_error'] = $stats['detalle_error'] . $registro['Codigo'] . '-' . $registro['Color'];
				}
			}
		}

		$stats['nuevos']		= $cont;
		$stats['omitidos'] 		= $cont2;
		$stats['memoria']		= (memory_get_peak_usage(true) / 1024 / 1024) . ' MB';
		$stats['tiempo']		= round(microtime(true) - $inicio, 3);

		return $stats;
	}
	
	function cargar_todas__($clave = null)
	{
		Configure::write('debug',0);
		set_time_limit(0);
		if (! $clave == 'EDUARDITO-SKECHERS')
			die (false);
		//echo ('<script src="'.$this->webroot.'js/www.andain.cl-jquery-1.6.2.min.js" type="text/javascript"></script>');
		//echo ('<div id="porcentajeCarga">0%</div>');
		//$n = 0;
		//$this->loadModel('Producto');
		//
		//$productos = $this->Producto->find('all', array('conditions' => array('Producto.foto' => '',
		//																	  'Producto.coleccion_id' => 0),
		//												'fields' => array('Producto.id',
		//																  'Producto.codigo',
		//																  'Producto.codigo_completo',
		//																  'Producto.color_id'),
		//												'contain' => array('Color' => array('fields' => array('Color.id',
		//																									  'Color.codigo')),
		//																   'Galeria' => array('fields' => array('Galeria.id',
		//																										'Galeria.imagen'))
		//																   ),
		//												'limit' => 10
		//												));
		//if ($productos)
		//{
		//	foreach ($productos as $index => $producto)
		//	{
		//		$productos[$index]['Producto']['codigo_imagen'] = $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'];
		//	}
		//}
		//$cont = 1;
		//foreach ($productos as $producto)
		//{
		//	// carpeta de origen de las imagenes
		//	$folder_ini = 'http://cdn4.skechers-usa.com/img/productimages/';
		//	// carpeta de destino de las imagenes
		//	$folder_producto = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Producto' . DS;
		//	$folder_galeria = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Galeria' . DS;
		//
		//	// verificar imagen
		//	$foto = null;
		//	if(fopen($folder_ini . 'large/' . $producto['Producto']['codigo_imagen'].'.JPG', 'r'))
		//		$foto = $producto['Producto']['codigo_imagen'].'.JPG';
		//	elseif(fopen($folder_ini . 'large/' . $producto['Producto']['codigo_imagen'].'.jpg', 'r'))
		//		$foto = $producto['Producto']['codigo_imagen'].'.jpg';
		//
		//	if ($foto)
		//	{
		//		/**
		//		 * verifica si existe la carpeta y la limpia
		//		 * si no existe la crea
		//		 */
		//		if (! $this->limpiar_carpeta($folder_producto.$producto['Producto']['id']))
		//		{
		//			$folder = &new Folder($folder_producto.$producto['Producto']['id'], $create = true, $mode = 0777);
		//		}
		//		copy($folder_ini.'large/'.$foto, $folder_producto.$producto['Producto']['id'].'/'.$foto);
		//		copy($folder_ini.'large/'.$foto, $folder_producto.$producto['Producto']['id'].'/full_'.$foto);
		//		copy($folder_ini.'medium/'.$foto, $folder_producto.$producto['Producto']['id'].'/ith_'.$foto);
		//		copy($folder_ini.'small/'.$foto, $folder_producto.$producto['Producto']['id'].'/mini_'.$foto);
		//	}
		//	else
		//	{
		//		$localhost = true;
		//		$this->generar_foto($producto['Producto']['id'], $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'], $localhost);
		//	}
		//	//galeria = path, full
		//	
		//	$n++;
		//	//if (! (int)($n % (count($registros)/((int)(count($registros)/2)))) )
		//	//{
		//		$porcentaje = ($n*100)/count($productos);
		//		echo('<script>$("#porcentajeCarga").html("'.(int)($porcentaje).'%")</script>');
		//	//}
		//}
		die('<br /><hr />LISTO');
	}

	function fotos_multivende()
	{
		Configure::write('debug',2);
		set_time_limit(0);
		$this->loadModel('Producto');


		//$list = array();
		$list = $this->Producto->find('list', array('fields' => array('id','codigo_completo'),'conditions' => array('coleccion_id' => 21)));
		//$list = array('996479LWMLT');
		$this->Session->write('Archivo.progress',0);
		$basecopy = 'img/Archivo/';
		$filename_log = $basecopy."log.txt";
		$fp = fopen($filename_log,"w");
		fwrite($fp,'=== inicio ' .date('d-m-Y H:i:s').PHP_EOL);
		$localhost = true;
		$options = array(
			'conditions' => array(
				'Producto.codigo_completo' => $list
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.categoria_id',
				'Producto.codigo',
				'Producto.color_id',
				'Producto.foto'
			),
			'contain' => array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
			),
			'limit' => 1500
		);

		if (! $productos = $this->Producto->find('all',$options))
		{
			fwrite($fp,'=== fin ' .date('d-m-Y H:i:s').PHP_EOL);
			fclose($fp);
			die('OK');
		}

		$corte['width'] = 1104;
		$corte['height'] = 1600;
		$corte['crop'] = false;
		$corte['allow_enlarge'] = false;
		$corte['aspect'] = false;

		$folder_local ='D:\xampp\htdocs\skechers\ecomm\webroot\img\Producto_MV/Dafiti/';


		$cont = 0;
		foreach ($productos as $producto)
		{
			pr($producto);

			$cont++;
			$progress = ($cont/count($productos))*100;
			$producto['Producto']['codigo_imagen'] = $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'];
			// carpeta de origen de las imagenes
			//$folder_ini = 'https://www.skechers.com/dw/image/v2/BDCN_PRD/on/demandware.static/-/Sites-skechers-master/default/dw4d0f4ccc/images/large/';
			$folder_ini = 'D:/xampp/htdocs/skechers/fotos/';
			// carpeta de destino de las imagenes
			$folder_producto = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Producto_MV' . DS;
			if (! is_dir($folder_producto))
			{
				prx('aca');
				fwrite($fp,'--- repositorio invalido '.$folder_producto.PHP_EOL);
				break;
			}



			$foto = null;
			$foto_tmp= $folder_ini  . $producto['Producto']['codigo_imagen'];
			if(fopen($foto_tmp.'.jpg', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.jpg';
			elseif(fopen($foto_tmp.'.JPG', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.JPG';
			/*elseif(fopen($foto_tmp.'.gif', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.gif';
			elseif(fopen($foto_tmp.'.GIF', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.GIF';*/
			else
			{
				$codigo_infant = explode('_',$producto['Producto']['codigo_imagen']);
				if (in_array(substr($codigo_infant[0], ((int)strlen($codigo_infant[0])-1), 1), array('L','N')))
				{
					$producto['Producto']['codigo_imagen'] = substr($codigo_infant[0], 0, -1).'_'.$codigo_infant[1];
				}
				if ($producto['Producto']['codigo_imagen'])
				{
					$foto_tmp= $folder_ini .'/' . $producto['Producto']['codigo_imagen'];
					$foto = null;
					if(fopen($foto_tmp.'.jpg', 'r'))
						$foto = $producto['Producto']['codigo_imagen'].'.jpg';
					elseif(fopen($foto_tmp.'.JPG', 'r'))
						$foto = $producto['Producto']['codigo_imagen'].'.JPG';
			/*		elseif(fopen($foto_tmp.'.gif', 'r'))
						$foto = $producto['Producto']['codigo_imagen'].'.gif';
					elseif(fopen($foto_tmp.'.GIF', 'r'))
						$foto = $producto['Producto']['codigo_imagen'].'.GIF';*/
				}
			}

			
			if (! $foto)
			{
				echo ' - foto USA NO ENCONTRADA';
				continue;
			}else{
				//echo 'encontrada';
			}
			echo  'foto USA ENCONTRADA '.$foto.PHP_EOL;
			$codigo =  $producto['Producto']['codigo_imagen'];

			/**
			 * verifica si existe la carpeta y la limpia
			 * si no existe la crea
			 */
			$foto_inicial = $folder_ini . '/'.$foto;
			$foto_final = $folder_local.$producto['Producto']['codigo_imagen'].'_1.jpg';
			if(file_exists($foto_final))
			{
				continue;
			}
			//prx($foto_final);
			echo '  +++ path SI'.PHP_EOL;
			$this->fotito__resizeMV($foto_inicial, $foto_final, $corte);
		/*	}	
			else
			{
				echo '--- path NO'.PHP_EOL;
							die('asdf');

				fwrite($fp,$texto.PHP_EOL);
				continue;
			}

			*/
		

	
			$imagenes_galeria = array(2 => '_B',
									  3 => '_C',
									  4 => '_D',
									  5 => '_E',
									  6 => '_F');
	
			foreach ($imagenes_galeria as $orden => $galeria)
			{
				$texto='';
				$foto_tmp= $folder_ini  .'/' . $producto['Producto']['codigo_imagen'].$galeria;

				$codigo = $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'].$galeria;
				$foto_galeria = null;
			/*if(fopen($foto_tmp.'.gif','r'))
				{
					$foto_galeria = $codigo.'.gif';
				}
				elseif(fopen($foto_tmp.'.GIF','r'))
				{
					$foto_galeria = $codigo.'.GIF';
				} */
				if(fopen($foto_tmp.'.jpg','r'))
				{
					$foto_galeria = $codigo.'.jpg';
				}
				elseif(fopen($foto_tmp.'.JPG','r'))
				{
					$foto_galeria = $codigo.'.JPG';
				}
			
				if (! $foto_galeria)
				{
					continue;
				}

				$foto_inicial = $folder_ini  .'/'.$foto_galeria;
				$foto_final = $folder_local.$producto['Producto']['codigo_imagen'].'_'.$orden.'.jpg';

				$this->fotito__resizeMV($foto_inicial, $foto_final, $corte);

			}
			$texto = '=========='.PHP_EOL;
			//$this->cargar_galeria($producto['Producto']['id'], $localhost);
			fwrite($fp,$texto.PHP_EOL);
		}
		fwrite($fp,'fin');
		fclose($fp);
	}
	function fotos_meli()
	{
		Configure::write('debug',2);
		set_time_limit(0);
		$this->loadModel('Producto');


		//$list = array();
		$list = $this->Producto->find('list', array('fields' => array('id','codigo_completo'),'conditions' => array('coleccion_id' => 21)));
		//$list = array('996479LWMLT');
		$this->Session->write('Archivo.progress',0);
		$basecopy = 'img/Archivo/';
		$filename_log = $basecopy."log.txt";
		$fp = fopen($filename_log,"w");
		fwrite($fp,'=== inicio ' .date('d-m-Y H:i:s').PHP_EOL);
		$localhost = true;
		$options = array(
			'conditions' => array(
				'Producto.codigo_completo' => $list
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.categoria_id',
				'Producto.codigo',
				'Producto.color_id',
				'Producto.foto'
			),
			'contain' => array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
			),
			'limit' => 1500
		);

		if (! $productos = $this->Producto->find('all',$options))
		{
			fwrite($fp,'=== fin ' .date('d-m-Y H:i:s').PHP_EOL);
			fclose($fp);
			die('OK');
		}

		$corte['width'] = 1000;
		$corte['height'] = 1000;
		$corte['crop'] = false;
		$corte['allow_enlarge'] = false;
		$corte['aspect'] = false;

		$folder_local ='D:\xampp\htdocs\skechers\ecomm\webroot\img\Producto_MV/Meli/';


		$cont = 0;
		foreach ($productos as $producto)
		{
			pr($producto);

			$cont++;
			$progress = ($cont/count($productos))*100;
			$producto['Producto']['codigo_imagen'] = $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'];
			// carpeta de origen de las imagenes
			//$folder_ini = 'https://www.skechers.com/dw/image/v2/BDCN_PRD/on/demandware.static/-/Sites-skechers-master/default/dw4d0f4ccc/images/large/';
			$folder_ini = 'D:/xampp/htdocs/skechers/fotos/';
			// carpeta de destino de las imagenes
			$folder_producto = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Producto_MV' . DS;
			if (! is_dir($folder_producto))
			{
				prx('aca');
				fwrite($fp,'--- repositorio invalido '.$folder_producto.PHP_EOL);
				break;
			}



			$foto = null;
			$foto_tmp= $folder_ini  . $producto['Producto']['codigo_imagen'];
			if(fopen($foto_tmp.'.jpg', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.jpg';
			elseif(fopen($foto_tmp.'.JPG', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.JPG';
			/*elseif(fopen($foto_tmp.'.gif', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.gif';
			elseif(fopen($foto_tmp.'.GIF', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.GIF';*/
			else
			{
				$codigo_infant = explode('_',$producto['Producto']['codigo_imagen']);
				if (in_array(substr($codigo_infant[0], ((int)strlen($codigo_infant[0])-1), 1), array('L','N')))
				{
					$producto['Producto']['codigo_imagen'] = substr($codigo_infant[0], 0, -1).'_'.$codigo_infant[1];
				}
				if ($producto['Producto']['codigo_imagen'])
				{
					$foto_tmp= $folder_ini .'/' . $producto['Producto']['codigo_imagen'];
					$foto = null;
					if(fopen($foto_tmp.'.jpg', 'r'))
						$foto = $producto['Producto']['codigo_imagen'].'.jpg';
					elseif(fopen($foto_tmp.'.JPG', 'r'))
						$foto = $producto['Producto']['codigo_imagen'].'.JPG';
			/*		elseif(fopen($foto_tmp.'.gif', 'r'))
						$foto = $producto['Producto']['codigo_imagen'].'.gif';
					elseif(fopen($foto_tmp.'.GIF', 'r'))
						$foto = $producto['Producto']['codigo_imagen'].'.GIF';*/
				}
			}

			
			if (! $foto)
			{
				echo ' - foto USA NO ENCONTRADA';
				continue;
			}else{
				//echo 'encontrada';
			}
			echo  'foto USA ENCONTRADA '.$foto.PHP_EOL;
			$codigo =  $producto['Producto']['codigo_imagen'];

			/**
			 * verifica si existe la carpeta y la limpia
			 * si no existe la crea
			 */
			$foto_inicial = $folder_ini . '/'.$foto;
			$foto_final = $folder_local.$producto['Producto']['codigo_imagen'].'_1.jpg';
			if(file_exists($foto_final))
			{
				continue;
			}
			//prx($foto_final);
			echo '  +++ path SI'.PHP_EOL;
			$this->fotito__resizeML($foto_inicial, $foto_final, $corte);
		/*	}	
			else
			{
				echo '--- path NO'.PHP_EOL;
							die('asdf');

				fwrite($fp,$texto.PHP_EOL);
				continue;
			}

			*/
		

	
			$imagenes_galeria = array(2 => '_B',
									  3 => '_C',
									  4 => '_D',
									  5 => '_E',
									  6 => '_F');
	
			foreach ($imagenes_galeria as $orden => $galeria)
			{
				$texto='';
				$foto_tmp= $folder_ini  .'/' . $producto['Producto']['codigo_imagen'].$galeria;

				$codigo = $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'].$galeria;
				$foto_galeria = null;
			/*if(fopen($foto_tmp.'.gif','r'))
				{
					$foto_galeria = $codigo.'.gif';
				}
				elseif(fopen($foto_tmp.'.GIF','r'))
				{
					$foto_galeria = $codigo.'.GIF';
				} */
				if(fopen($foto_tmp.'.jpg','r'))
				{
					$foto_galeria = $codigo.'.jpg';
				}
				elseif(fopen($foto_tmp.'.JPG','r'))
				{
					$foto_galeria = $codigo.'.JPG';
				}
			
				if (! $foto_galeria)
				{
					continue;
				}

				$foto_inicial = $folder_ini  .'/'.$foto_galeria;
				$foto_final = $folder_local.$producto['Producto']['codigo_imagen'].'_'.$orden.'.jpg';

				$this->fotito__resizeMV($foto_inicial, $foto_final, $corte);

			}
			$texto = '=========='.PHP_EOL;
			//$this->cargar_galeria($producto['Producto']['id'], $localhost);
			fwrite($fp,$texto.PHP_EOL);
		}
		fwrite($fp,'fin');
		fclose($fp);
	}

	function ajax_cargar_fotos()
	{
		Configure::write('debug',2);
		set_time_limit(0);
/*		if (! $this->Session->check('Auth.Administrador'))
			die(false);

		if (empty($this->data))
			die(false);
		if (! isset($this->data['Producto']))
			die(false);
		if (! is_array($this->data['Producto']))
			die(false);
			*/
		$list = array();


		$this->Session->write('Archivo.progress',0);
		$basecopy = 'img/Archivo/';
		$filename_log = $basecopy."log.txt";
		$fp = fopen($filename_log,"w");
		fwrite($fp,'=== inicio ' .date('d-m-Y H:i:s').PHP_EOL);
		$this->loadModel('Producto');
		$localhost = true;
		$options = array(
		
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.categoria_id',
				'Producto.codigo',
				'Producto.color_id'
			),
			'contain' => array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
			)
		);
		if (! $productos = $this->Producto->find('all',$options))
		{
			fwrite($fp,'=== fin ' .date('d-m-Y H:i:s').PHP_EOL);
			fclose($fp);
			die('OK');
		}
		//prx($productos);

		fwrite($fp,'= registros: '.count($productos).PHP_EOL);
		$cont = 0;
		foreach ($productos as $producto)
		{

			$cont++;
			$progress = ($cont/count($productos))*100;
			$this->Session->write('Archivo.progress',(int)$progress);
			$texto = $cont.'- '.$producto['Producto']['id'];
			$producto['Producto']['codigo_imagen'] = $producto['Producto']['codigo'].'_'.strtoupper($producto['Color']['codigo']);
			// carpeta de origen de las imagenes
			$folder_ini = 'D:\xampp\htdocs\tecno\fotos_iniciales/';
		//	$folder_ini ='https://www.skechers.com/dw/image/v2/BDCN_PRD/on/demandware.static/-/Sites-skechers-master/default/dwa409fe4d/images/large/';
			// carpeta de destino de las imagenes
			$folder_producto = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Producto' . DS;
			if (! is_dir($folder_producto))
			{
				fwrite($fp,'--- repositorio invalido '.$folder_producto.PHP_EOL);
				break;
			}
		//	prx($folder_producto);

			// verificar imagen

			$foto = null;
			$foto = $folder_ini . $producto['Producto']['codigo'].'.png';
		//	prx($foto);
			if(fopen($folder_ini . $producto['Producto']['codigo'].'.png', 'r'))
				$foto = $producto['Producto']['codigo'].'.jpg';
		/*	elseif(fopen($folder_ini . $producto['Producto']['codigo_imagen'].'.JPG', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.JPG';
			elseif(fopen($folder_ini . 'xlarge/' . $producto['Producto']['codigo_imagen'].'.gif', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.gif';
			elseif(fopen($folder_ini . 'xlarge/' . $producto['Producto']['codigo_imagen'].'.GIF', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.GIF'; */
			if (! $foto)
			{
				$texto.=' - foto USA NO ENCONTRADA';
				fwrite($fp,$texto.PHP_EOL);
				continue;
			}
			$texto.=' - foto USA ENCONTRADA '.$foto.PHP_EOL;

			/**
			 * verifica si existe la carpeta y la limpia
			 * si no existe la crea
			 */
			if (! $this->limpiar_carpeta($folder_producto.$producto['Producto']['id']))
			{
				$texto.='  *** repositorio creado '.$folder_producto.$producto['Producto']['id'].PHP_EOL;
				@mkdir($folder_producto.$producto['Producto']['id'], 0777, true);
			}
			else
			{
				$texto.='  *** limpieza de repositorio'.PHP_EOL;
			}

			if (copy($folder_ini.$foto, $folder_producto.$producto['Producto']['id'].'/'.$foto))
				$texto.='  +++ path SI'.PHP_EOL;
			else
			{
				$texto.='--- path NO'.PHP_EOL;
				fwrite($fp,$texto.PHP_EOL);
				continue;
			}

			if (copy($folder_ini.$foto, $folder_producto.$producto['Producto']['id'].'/full_'.$foto.'?sw=428'))
				$texto.='  +++ full SI'.PHP_EOL;
			else
			{
				$texto.='--- full NO'.PHP_EOL;
				fwrite($fp,$texto.PHP_EOL);
				continue;
			}

			if (copy($folder_ini.$foto, $folder_producto.$producto['Producto']['id'].'/ith_'.$foto.'?sw=180'))
				$texto.='  +++ ith SI'.PHP_EOL;
			else
			{
				$texto.='--- ith NO'.PHP_EOL;
				fwrite($fp,$texto.PHP_EOL);
				continue;
			}

			if (copy($folder_ini.$foto, $folder_producto.$producto['Producto']['id'].'/mini_'.$foto.'?sw=71'))
				$texto.='  +++ mini SI'.PHP_EOL;
			else
			{
				$texto.='--- mini NO'.PHP_EOL;
				fwrite($fp,$texto.PHP_EOL);
				continue;
			}

			fwrite($fp,$texto);
			$this->Producto->query('UPDATE sitio_productos set foto = "'.$foto.'" WHERE id = '.$producto['Producto']['id'].';');

			$texto = '   ****** GALERIA';
			$folder_galeria = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Galeria' . DS;
			// LIMPIAR GALERIA
			$options = array(
				'conditions' => array(
					'Galeria.producto_id' => $producto['Producto']['id']
				),
				'fields' => array(
					'Galeria.id',
					'Galeria.id'
				)
			);
			if ($galerias = $this->Producto->Galeria->find('list',$options))
			{
				$this->Producto->Galeria->deleteAll(array('Galeria.producto_id' => $producto['Producto']['id']));
				foreach ($galerias as $galeria_id)
				{
					// carpeta de destino de las imagenes
					$folder = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Galeria' . DS . $galeria_id;
					if ( $this->limpiar_carpeta($folder) )
					{
						rmdir($folder);
					}
				}
				$texto.=' reiniciada';
			}
			fwrite($fp,$texto.PHP_EOL);
	
			$imagenes_galeria = array(1 => '_B',
									  2 => '_C',
									  3 => '_D',
									  4 => '_E',
									  5 => '_F',
									  6 => '_G',
									  7 => '_H' );
	
			foreach ($imagenes_galeria as $orden => $galeria)
			{
				$texto='';
				$codigo = $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'].$galeria;
				$foto_galeria = null;
				/*if(fopen($folder_ini.'xlarge/'.$codigo.'.gif','r'))
				{
					$foto_galeria = $codigo.'.gif';
				}
				elseif(fopen($folder_ini.'xlarge/'.$codigo.'.GIF','r'))
				{
					$foto_galeria = $codigo.'.GIF';
				}*/
				if(fopen($folder_ini.$codigo.'.jpg','r'))
				{
					$foto_galeria = $codigo.'.jpg';
				}
				elseif(fopen($folder_ini.$codigo.'.JPG','r'))
				{
					$foto_galeria = $codigo.'.JPG';
				}
			
				
				else
				{
					$codigo_infant = explode('_',$codigo);
					$codigo = null;
					if (in_array(substr($codigo_infant[0], ((int)strlen($codigo_infant[0])-1), 1), array('L','N')))
					{
						$codigo = substr($codigo_infant[0], 0, -1).'_'.$codigo_infant[1].$galeria;
						$foto_galeria = null;
						if(fopen($folder_ini.'xlarge/'.$codigo.'.jpg', 'r'))
						{
							$foto_galeria = $codigo.'.jpg';
						}
						elseif(fopen($folder_ini . 'xlarge/'.$codigo.'.JPG', 'r'))
						{
							$foto_galeria = $codigo.'.JPG';
						}
						elseif(fopen($folder_ini.'xlarge/'.$codigo.'.gif', 'r'))
						{
							$foto_galeria = $codigo.'.gif';
						}
						elseif(fopen($folder_ini . 'xlarge/'.$codigo.'.GIF', 'r'))
						{
							$foto_galeria = $codigo.'.GIF';
						}
					}
				}

				if (! $foto_galeria)
				{
					$texto.='   ------ foto galeria USA NO ENCONTRADA';
					fwrite($fp,$texto.PHP_EOL);
					continue;
				}
				$texto.='   *** foto galeria USA ENCONTRADA '.$foto_galeria.PHP_EOL;
				/**
				 * verifica si existe la carpeta y la limpia
				 * si no existe la crea
				 */
				$this->Producto->Galeria->create();
				$new_galeria = array('orden' => $orden, 'producto_id' => $producto['Producto']['id']);
				if ( $this->Producto->Galeria->save($new_galeria, array('validate' => false)) )
				{
					$id_galeria = $this->Producto->Galeria->id;
					if (! $this->limpiar_carpeta($folder_galeria.$id_galeria))
					{
						$texto.='   *** repositorio creado '.$folder_galeria.$id_galeria.PHP_EOL;
						@mkdir($folder_galeria.$id_galeria, 0777, true);
					}
					else
					{
						$texto.='   *** limpieza de repositorio'.PHP_EOL;
					}

					if (copy($folder_ini.$foto_galeria, $folder_galeria.$id_galeria.'/'.$foto_galeria))
						$texto.='   +++ path SI'.PHP_EOL;
					else
					{
						$texto.='--- path NO'.PHP_EOL;
						fwrite($fp,$texto.PHP_EOL);
						continue;
					}

					if (copy($folder_ini.$foto_galeria, $folder_galeria.$id_galeria.'/full_'.$foto_galeria.'?sw=428'))
						$texto.='   +++ full SI'.PHP_EOL;
					else
					{
						$texto.='--- full NO'.PHP_EOL;
						fwrite($fp,$texto.PHP_EOL);
						continue;
					}

					if (copy($folder_ini.$foto_galeria, $folder_galeria.$id_galeria.'/ith_'.$foto_galeria.'?sw=180'))
						$texto.='   +++ ith SI'.PHP_EOL;
					else
					{
						$texto.='--- ith NO'.PHP_EOL;
						fwrite($fp,$texto.PHP_EOL);
						continue;
					}

					if (copy($folder_ini.$foto_galeria, $folder_galeria.$id_galeria.'/mini_'.$foto_galeria.'?sw=71'))
						$texto.='   +++ mini SI'.PHP_EOL;
					else
					{
						$texto.='--- mini NO'.PHP_EOL;
						fwrite($fp,$texto.PHP_EOL);
						continue;
					}

					$this->Producto->Galeria->query('UPDATE sitio_galerias set imagen = "'.$foto_galeria.'" WHERE id = '.$id_galeria.';');
				}
				fwrite($fp,$texto.PHP_EOL);
			}
			$texto = '=========='.PHP_EOL;
			//$this->cargar_galeria($producto['Producto']['id'], $localhost);
			fwrite($fp,$texto.PHP_EOL);
		}
		fwrite($fp,'fin');
		fclose($fp);
		die('OK');
	}

function recargar_fotos()
	{
		Configure::write('debug',2);
		set_time_limit(0);
		$this->Session->write('Archivo.progress',0);
		$basecopy = 'img/Archivo/';
		$filename_log = $basecopy."log.txt";
		$fp = fopen($filename_log,"w");
		fwrite($fp,'=== inicio ' .date('d-m-Y H:i:s').PHP_EOL);
		$this->loadModel('Producto');
		$localhost = true;
		$options = array(
			
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.categoria_id',
				'Producto.codigo',
				'Producto.color_id'
			),
			'contain' => array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
			),
			'order' => array('Producto.id' => 'desc')
		);
		if (! $productos = $this->Producto->find('all',$options))
		{
			fwrite($fp,'=== fin ' .date('d-m-Y H:i:s').PHP_EOL);
			fclose($fp);
			die('OK');
		}
		$context = stream_context_create( array(
  'http'=>array(
    'timeout' => 2.0
  )
));
		fwrite($fp,'= registros: '.count($productos).PHP_EOL);
		$cont = 0;
		foreach ($productos as $producto)
		{

			$cont++;
			$progress = ($cont/count($productos))*100;
			$this->Session->write('Archivo.progress',(int)$progress);
			$texto = $cont.'- '.$producto['Producto']['id'];
			$producto['Producto']['codigo_imagen'] = $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'];
			// carpeta de origen de las imagenes
			$folder_ini = 'https://image.skechers.com/img/productimages/';
			// carpeta de destino de las imagenes
			$folder_producto = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Producto' . DS;
			if (! is_dir($folder_producto))
			{
				fwrite($fp,'--- repositorio invalido '.$folder_producto.PHP_EOL);
				break;
			}
		//	prx($folder_producto);

			// verificar imagen

			$foto = null;
			if(fopen($folder_ini . 'xlarge/' . $producto['Producto']['codigo_imagen'].'.jpg', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.jpg';
			elseif(fopen($folder_ini . 'xlarge/' . $producto['Producto']['codigo_imagen'].'.JPG', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.JPG';
			elseif(fopen($folder_ini . 'xlarge/' . $producto['Producto']['codigo_imagen'].'.gif', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.gif';
			elseif(fopen($folder_ini . 'xlarge/' . $producto['Producto']['codigo_imagen'].'.GIF', 'r'))
				$foto = $producto['Producto']['codigo_imagen'].'.GIF';
			
		
			if (! $foto)
			{
				$texto.=' - foto USA NO ENCONTRADA';
				fwrite($fp,$texto.PHP_EOL);
				continue;
			}
			$texto.=' - foto USA ENCONTRADA '.$foto.PHP_EOL;

			/**
			 * verifica si existe la carpeta y la limpia
			 * si no existe la crea
			 */
			if (! $this->limpiar_carpeta($folder_producto.$producto['Producto']['id']))
			{
				$texto.='  *** repositorio creado '.$folder_producto.$producto['Producto']['id'].PHP_EOL;
				@mkdir($folder_producto.$producto['Producto']['id'], 0777, true);
			}
			else
			{
				$texto.='  *** limpieza de repositorio'.PHP_EOL;
			}

			if (copy($folder_ini.'xlarge/'.$foto, $folder_producto.$producto['Producto']['id'].'/'.$foto))
				$texto.='  +++ path SI'.PHP_EOL;
			else
			{
				$texto.='--- path NO'.PHP_EOL;
				fwrite($fp,$texto.PHP_EOL);
				continue;
			}

			if (copy($folder_ini.'large/'.$foto, $folder_producto.$producto['Producto']['id'].'/full_'.$foto))
				$texto.='  +++ full SI'.PHP_EOL;
			else
			{
				$texto.='--- full NO'.PHP_EOL;
				fwrite($fp,$texto.PHP_EOL);
				continue;
			}

			if (copy($folder_ini.'medium/'.$foto, $folder_producto.$producto['Producto']['id'].'/ith_'.$foto))
				$texto.='  +++ ith SI'.PHP_EOL;
			else
			{
				$texto.='--- ith NO'.PHP_EOL;
				fwrite($fp,$texto.PHP_EOL);
				continue;
			}

			if (copy($folder_ini.'small/'.$foto, $folder_producto.$producto['Producto']['id'].'/mini_'.$foto))
				$texto.='  +++ mini SI'.PHP_EOL;
			else
			{
				$texto.='--- mini NO'.PHP_EOL;
				fwrite($fp,$texto.PHP_EOL);
				continue;
			}

			fwrite($fp,$texto);
			prx('aca');
			$this->Producto->query('UPDATE sitio_productos set foto = "'.$foto.'" WHERE id = '.$producto['Producto']['id'].';');
	
		}
		fwrite($fp,'fin');
		fclose($fp);
		die('OK');
	}
	


	
	function ajax_recargar_imagenes()
	{
		Configure::write('debug',1);
		$progress = 0;
		if (! isset($this->params['form']['start']))
			die(false);
		if (! $this->params['form']['start'])
			die(false);
		if ($this->params['form']['start']=='si')
			$this->Session->write('Archivo.progress',0);
		if ($this->Session->check('Archivo.progress'))
			$progress = $this->Session->read('Archivo.progress');
		die(json_encode(array('porcentaje' => $progress)));
	}
	
	private function limpiar_carpeta($carpeta)
	{
		if (is_dir($carpeta))
		{
			foreach(glob($carpeta . "/*") as $archivos_carpeta)
			{
				unlink($archivos_carpeta);
			}
			return true;
		}
		return false;
	}
	
	private function generar_foto($id = null, $codigo = null, $localhost = false)
	{
		if (! $id)
		{
			return false;
		}
		// CONFIG IMAGEN
		// carpeta de origen de las imagenes
		if ($localhost)
		{
			$folder_ini = 'D:'.DS.'cargas'.DS.'skechers';
		}
		else
		{
			$folder_ini = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS.'masivos';
		}
		// carpeta de destino de las imagenes
		$folder = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Producto' . DS . $id;

		// verificar imagen
		$foto = null;
		if(file_exists($folder_ini . DS . $codigo.'.JPG'))
		{
			$foto = $codigo.'.JPG';
		}
		elseif(file_exists($folder_ini . DS . $codigo.'.jpg'))
		{
			$foto = $codigo.'.jpg';
		}
		
		if (! $foto)
		{
			$codigo_infant = explode('_',$codigo);
			if (in_array(substr($codigo_infant[0], ((int)strlen($codigo_infant[0])-1), 1), array('L','N')))
			{
				$codigo_infant = substr($codigo_infant[0], 0, -1).'_'.$codigo_infant[1];
			}
			if ($codigo_infant)
			{
				if(file_exists($folder_ini . DS . $codigo_infant.'.JPG'))
				{
					$foto = $codigo_infant.'.JPG';
				}
				elseif(file_exists($folder_ini . DS . $codigo_infant.'.jpg'))
				{
					$foto = $codigo_infant.'.jpg';
				}
			}
		}
		

		// guardar campo foto si es que existe la imagen
		$this->loadModel('Producto');
		if ( $foto)
		{
			$this->limpiar_carpeta($folder);
			$this->Producto->query('UPDATE sitio_productos set foto = "'.$foto.'" WHERE id = '.$id.';');
			copy($folder_ini.DS.$foto, $folder.DS.$foto);
			// generar imagenes desde cortes
			$cortes = $this->Archivo->Behaviors->Image->settings['Producto']['fields']['foto']['versions'];
			foreach ($cortes as $corte)
			{
				if (in_array($corte['prefix'], array('full', 'ith','mini')))
				{
					// generar corte
					$this->fotito__resize($folder_ini, $folder, $foto, $corte['prefix'] . '_' . $foto, 'foto', $corte);
				}
			}
			return true;
		}
		elseif(file_exists($folder_ini . DS . $codigo))
		{
			$this->limpiar_carpeta($folder);
			$this->Producto->query('UPDATE sitio_productos set foto = "'.$codigo.'.jpg" WHERE id = '.$id.';');
			copy($folder_ini.DS.$codigo, $folder.DS.$codigo.'.jpg');
			// generar imagenes desde cortes
			$cortes = $this->Archivo->Behaviors->Image->settings['Producto']['fields']['foto']['versions'];
			foreach ($cortes as $corte)
			{
				if (in_array($corte['prefix'], array('full', 'ith','mini')))
				{
					// generar corte
					$this->fotito__resize($folder_ini, $folder, $codigo, $corte['prefix'] . '_' . $codigo.'.jpg', 'foto', $corte);
				}
			}
			return true;
		}
		return false;
	}
	
	private function cargar_galeria($id = null, $localhost = false)
	{
		Configure::write('debug',0);
		$this->loadModel('Producto');
		$folder_ini = 'http://cdn4.skechers-usa.com/img/productimages/';
		$folder_galeria = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Galeria' . DS;
		// LIMPIAR GALERIA
		$producto = $this->Producto->find('first', array('conditions' => array('Producto.id' => $this->params['form']['id']),
														 'fields' => array('Producto.id',
																		 'Producto.nombre',
																		 'Producto.categoria_id',
																		 'Producto.codigo',
																		  'Producto.color_id'),
														'contain' => array('Color' => array('fields' => array('Color.id',
																											  'Color.codigo')),
																		   )
														));
		$galerias = $this->Producto->Galeria->find('list', array('conditions' => array('Galeria.producto_id' => $id),
																 'fields' => array('Galeria.id',
																				   'Galeria.id')));
		
		if ($galerias)
		{
			$this->Producto->Galeria->deleteAll(array('Galeria.producto_id' => $id));
			foreach ($galerias as $galeria_id)
			{
				// carpeta de destino de las imagenes
				$folder = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Galeria' . DS . $galeria_id;
				if ( $this->limpiar_carpeta($folder) )
				{
					rmdir($folder);
				}
			}
		}

		$imagenes_galeria = array(1 => '_B',
								  2 => '_C',
								  3 => '_D',
								  4 => '_E',
								  5 => '_F');

		foreach ($imagenes_galeria as $orden => $galeria)
		{
			$codigo = $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'].$galeria;
			//prx($codigo);
			// verificar imagen
			$foto_galeria = null;
			if(fopen($folder_ini.'large/'.$codigo.'.jpg','r'))
			{
				$foto_galeria = $codigo.'.jpg';
			}
			elseif(fopen($folder_ini.'large/'.$codigo.'.JPG','r'))
			{
				$foto_galeria = $codigo.'.JPG';
			}
			elseif(fopen($folder_ini.'large/'.$codigo.'.gif','r'))
			{
				$foto_galeria = $codigo.'.gif';
			}
			elseif(fopen($folder_ini.'large/'.$codigo.'.GIF','r'))
			{
				$foto_galeria = $codigo.'.GIF';
			}
			else
			{
				$codigo_infant = explode('_',$codigo);
				$codigo = null;
				if (in_array(substr($codigo_infant[0], ((int)strlen($codigo_infant[0])-1), 1), array('L','N')))
				{
					$codigo = substr($codigo_infant[0], 0, -1).'_'.$codigo_infant[1].$galeria;
					$foto_galeria = null;
					if(fopen($folder_ini.'large/'.$codigo.'.jpg', 'r'))
					{
						$foto_galeria = $codigo.'.jpg';
					}
					elseif(fopen($folder_ini . 'large/'.$codigo.'.JPG', 'r'))
					{
						$foto_galeria = $codigo.'.JPG';
					}
					elseif(fopen($folder_ini.'large/'.$codigo.'.gif', 'r'))
					{
						$foto_galeria = $codigo.'.gif';
					}
					elseif(fopen($folder_ini . 'large/'.$codigo.'.GIF', 'r'))
					{
						$foto_galeria = $codigo.'.GIF';
					}
				}
			}

			if (! $foto_galeria)
			{
				continue;
			}
			/**
			 * verifica si existe la carpeta y la limpia
			 * si no existe la crea
			 */
			$this->Producto->Galeria->create();
			$new_galeria = array('orden' => $orden, 'producto_id' => $producto['Producto']['id']);
			if ( $this->Producto->Galeria->save($new_galeria, array('validate' => false)) )
			{
				$id_galeria = $this->Producto->Galeria->id;
				if (! $this->limpiar_carpeta($folder_galeria.$id_galeria))
				{
					@mkdir($folder_producto.$producto['Producto']['id'], 0777, true);
				}
				copy($folder_ini.'large/'.$foto_galeria, $folder_galeria.$id_galeria.'/'.$foto_galeria);
				copy($folder_ini.'large/'.$foto_galeria, $folder_galeria.$id_galeria.'/full_'.$foto_galeria);
				copy($folder_ini.'medium/'.$foto_galeria, $folder_galeria.$id_galeria.'/ith_'.$foto_galeria);
				copy($folder_ini.'small/'.$foto_galeria, $folder_galeria.$id_galeria.'/mini_'.$foto_galeria);
				$this->Producto->Galeria->query('UPDATE sitio_galerias set imagen = "'.$foto_galeria.'" WHERE id = '.$id_galeria.';');
			}
		}
		return true;
	}
	
	function generar_foto_galeria($id = null, $codigo = null, $orden = 1, $localhost = false)
	{
		if (! $id)
			return false;
		
		$this->loadModel('Galeria');

		// carpeta de origen de las imagenes
		if ($localhost)
			$folder_ini = 'D:'.DS.'cargas'.DS.'skechers';
		else
			$folder_ini = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS.'masivos';

		$cortes = $this->Archivo->Behaviors->Image->settings['Galeria']['fields']['imagen']['versions'];

		// verificar imagen
		$foto = null;
		if(file_exists($folder_ini . DS . $codigo.'.JPG'))
			$foto = $codigo.'.JPG';
		elseif(file_exists($folder_ini . DS . $codigo.'.jpg'))
			$foto = $codigo.'.jpg';

		$this->Galeria->create();
		if ( $foto)
		{
			$new_galeria = array('orden' => $orden, 'producto_id' => $id);
			if ( $this->Galeria->save($new_galeria, array('validate' => false)) )
			{
				$id_galeria = $this->Galeria->id;
				// carpeta de destino de las imagenes
				$folder = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Galeria' . DS . $id_galeria;
				$this->limpiar_carpeta($folder);
				$this->Galeria->query("UPDATE sitio_galerias SET imagen = '".$foto."' WHERE id = ".$id_galeria);
				copy($folder_ini.DS.$foto, $folder.DS.$foto);
				foreach ($cortes as $corte)
				{
					if (in_array($corte['prefix'], array('full', 'ith','mini')))
					{
						// generar corte
						$this->fotito__resize($folder_ini, $folder, $foto, $corte['prefix'] . '_' . $foto, 'foto', $corte);
					}
				}
			}
		}
		elseif(file_exists($folder_ini . DS . $codigo))
		{
			$new_galeria = array('orden' => $orden, 'producto_id' => $id);
			if ( $this->Galeria->save($new_galeria, array('validate' => false)) )
			{
				$id_galeria = $this->Galeria->id;
				// carpeta de destino de las imagenes
				$folder = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Galeria' . DS . $id_galeria;
				$this->limpiar_carpeta($folder);
				$this->Galeria->query("UPDATE sitio_galerias SET imagen = '".$codigo.".jpg' WHERE id = ".$id_galeria);
				copy($folder_ini.DS.$codigo, $folder.DS.$codigo.'.jpg');
				foreach ($cortes as $corte)
				{
					if (in_array($corte['prefix'], array('full', 'ith','mini')))
					{
						// generar corte
						$this->fotito__resize($folder_ini, $folder, $codigo, $corte['prefix'] . '_' . $codigo.'.jpg', 'foto', $corte);
					}
				}
			}
		}
		return false;
	}
	
	private function fotito__resize($folder_ini, $folder, $originalName, $newName = null, $field, $fieldParams)
	{ 
		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type
		$fullpath = $folder;
		uses ('folder');
		uses ('file');
		$folder = &new Folder($path = $folder, $create = true, $mode = 0777);
		//prx(compact('$folder_ini', 'folder', 'originalName', 'newName', 'field', 'fieldParams'));

		$url = $folder_ini.'/'.$originalName;

		if (!($size = getimagesize($url)))
			return; // image doesn't exist

			$width=$fieldParams['width'];
			$height=$fieldParams['height'];

			/* Custom by PedroFuentes */
			$original_width=$fieldParams['width'];
			$original_height=$fieldParams['height'];

			if(isset($fieldParams['crop']) && $fieldParams['crop']===true){

				if (($size[1]/$height) < ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
					$width = ceil(($size[0]/$size[1]) * $height);
				else
					$height = ceil($width / ($size[0]/$size[1]));

			} else {

				if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
					$width = ceil(($size[0]/$size[1]) * $height);
				else
					$height = ceil($width / ($size[0]/$size[1]));

			}

			/* End Custom */

		if ($fieldParams['allow_enlarge']===false) { // don't enlarge image
			if (($width>$size[0])||($height>$size[1])) {
				$width=$size[0];
				$height=$size[1];
			}
		} else {
			if ($fieldParams['aspect']) { // adjust to aspect.
				if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
					$width = ceil(($size[0]/$size[1]) * $height);
				else
					$height = ceil($width / ($size[0]/$size[1]));
			}
		}

		//$prefix=$this->__getPrefix($fieldParams);
		//$cachefile = $fullpath.DS.$prefix.'_'.basename($originalName);  // location on server
		$cachefile = $fullpath.DS.$newName;  // location on server

		if (file_exists($cachefile)) {
			$csize = getimagesize($cachefile);
			$cached = ($csize[0] == $width && $csize[1] == $height); // image is cached
			if (@filemtime($cachefile) < @filemtime($url)) // check if up to date
				$cached = false;
		} else {
			$cached = false;
		}

		if (!$cached) {
			$resize = ($size[0] > $width || $size[1] > $height) || ($size[0] < $width || $size[1] < $height || ($fieldParams['allow_enlarge']===false));
		} else {
			$resize = false;
		}

		if ($resize) {
			$image = call_user_func('imagecreatefrom'.$types[$size[2]], $url);
			if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor ($width, $height))) {
				/* EDU :P */
				$size_blanco = array('width' => $width-1,
									 'height' => $height-1);
				imagefilledrectangle($temp, 0, 0, $size_blanco['width'], $size_blanco['height'], 0xFFFFFF);
				/* FIN EDU */
				call_user_func('image'.$types[$size[2]], $temp, $newName, 100);
				imagealphablending($temp, false);
				imagesavealpha($temp, true);
				imagecopyresampled ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			} else {
				$temp = imagecreate ($width, $height);
				imagecopyresized ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			}

			/* Custom by PedroFuentes **/
			if(isset($fieldParams['crop']) && $fieldParams['crop']===true){
				if (function_exists("imagecreatetruecolor") && ($croped = imagecreatetruecolor ($original_width, $original_height))) {
					/* EDU :P */
					$size_blanco = array('width' => $original_width-1,
										 'height' => $original_height-1);
					imagefilledrectangle($croped, 0, 0, $size_blanco['width'], $size_blanco['height'], 0xFFFFFF);
					/* FIN EDU */
					call_user_func('image'.$types[$size[2]], $croped, 100);
					imagealphablending($croped, false);
					imagesavealpha($croped, true);
					imagecopy ($croped, $temp, ( floor($original_width/2) - floor($width/2) ), ( floor($original_height/2) - floor($height/2) ), 0, 0, $width, $height);
				} else {
					$croped = imagecreate ($original_width, $original_height);
					/* EDU :P */
					$size_blanco = array('width' => $original_width-1,
										 'height' => $original_height-1);
					imagefilledrectangle($croped, 0, 0, $size_blanco['width'], $size_blanco['height'], 0xFFFFFF);
					/* FIN EDU */
					imagecopy ($croped, $temp, ( floor($original_width/2) - floor($width/2) ), ( floor($original_height/2) - floor($height/2) ), 0, 0, $width, $height);
				}
				//imagefilter($croped,IMG_FILTER_CONTRAST,-20); // EDITAR CONTRASTE - MAS FLUOR
				call_user_func("image".$types[$size[2]], $croped, $cachefile);
				imagedestroy ($croped);
			} else {
				//imagefilter($temp,IMG_FILTER_CONTRAST,-20); // EDITAR CONTRASTE - MAS FLUOR
				call_user_func("image".$types[$size[2]], $temp, $cachefile);
			}
			/* End Custom */
			
			imagedestroy ($image);
			imagedestroy ($temp);

		}
	}


//private function fotito__resize($folder_ini, $folder, $originalName, $newName = null, $field, $fieldParams)


private function fotito__resizeMV($foto_inicial, $foto_final , $fieldParams)
	{ 
		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type
		//$fullpath = $folder;
		uses ('folder');
		uses ('file');

		$url = $foto_inicial;

		if (!($size = getimagesize($url)))
			return; // image doesn't exist

			$width=$fieldParams['width'];
			$height=$fieldParams['height'];

			/* Custom by PedroFuentes */
			$original_width=$fieldParams['width'];
			$original_height=$fieldParams['height'];

			if(isset($fieldParams['crop']) && $fieldParams['crop']===true)
			{

				if (($size[1]/$height) < ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
					$width = ceil(($size[0]/$size[1]) * $height);
				else
					$height = ceil($width / ($size[0]/$size[1]));

			} else {

				if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
					$width = ceil(($size[0]/$size[1]) * $height);
				else
					$height = ceil($width / ($size[0]/$size[1]));

			}

			/* End Custom */

		if ($fieldParams['allow_enlarge']===false) { // don't enlarge image
			if (($width>$size[0])||($height>$size[1])) {
				$width=$size[0];
				$height=$size[1];
			}
		} else {
			if ($fieldParams['aspect']) { // adjust to aspect.
				if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
					$width = ceil(($size[0]/$size[1]) * $height);
				else
					$height = ceil($width / ($size[0]/$size[1]));
			}else{
		
				$width = $original_width;
				$height = ceil($original_height * ($original_width / $size[0])); 
			}
		}


		//$prefix=$this->__getPrefix($fieldParams);
		//$cachefile = $fullpath.DS.$prefix.'_'.basename($originalName);  // location on server
		$cachefile = $foto_final;  // location on server

		if (file_exists($cachefile)) {
			$csize = getimagesize($cachefile);
			$cached = ($csize[0] == $width && $csize[1] == $height); // image is cached
			if (@filemtime($cachefile) < @filemtime($url)) // check if up to date
				$cached = false;
		} else {
			$cached = false;
		}

		if (!$cached) {
			$resize = ($size[0] > $width || $size[1] > $height) || ($size[0] < $width || $size[1] < $height || ($fieldParams['allow_enlarge']===false));
		} else {
			$resize = false;
		}
			$resize = true;
			$fieldParams['crop'] = true;

		if ($resize) {
			//prx('aca');
			$image = call_user_func('imagecreatefrom'.$types[$size[2]], $url);
			if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor ($width, $height))) {
				/* EDU :P */
				$size_blanco = array('width' => $width,
									 'height' => $height);
				imagefilledrectangle($temp, 0, 0, $size_blanco['width'], $size_blanco['height'], 0xFFFFFF);
				/* FIN EDU */
				call_user_func('image'.$types[$size[2]], $temp, $cachefile, 100);
				imagealphablending($temp, false);
				imagesavealpha($temp, true);
				imagecopyresampled ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			} else {
				$temp = imagecreate ($width, $height);
				imagecopyresized ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			}

			/* Custom by PedroFuentes **/
			if(isset($fieldParams['crop']) && $fieldParams['crop']===true){
				if (function_exists("imagecreatetruecolor") && ($croped = imagecreatetruecolor ($original_width, $original_height))) {
					/* EDU :P */
					$size_blanco = array('width' => $original_width-1,
										 'height' => $original_height-1);
					imagefilledrectangle($croped, 0, 0, $size_blanco['width'], $size_blanco['height'], 0xFFFFFF);
					/* FIN EDU */
					call_user_func('image'.$types[$size[2]], $croped, 100);
					imagealphablending($croped, false);
					imagesavealpha($croped, true);
					imagecopy ($croped, $temp, ( floor($original_width/2) - floor($width/2) ), ( floor($original_height/2) - floor($height/2) ), 0, 0, $width, $height);
				} else {
					$croped = imagecreate ($original_width, $original_height);
					/* EDU :P */
					$size_blanco = array('width' => $original_width-1,
										 'height' => $original_height-1);
					imagefilledrectangle($croped, 0, 0, $size_blanco['width'], $size_blanco['height'], 0xFFFFFF);
					/* FIN EDU */
					imagecopy ($croped, $temp, ( floor($original_width/2) - floor($width/2) ), ( floor($original_height/2) - floor($height/2) ), 0, 0, $width, $height);
				}
				//imagefilter($croped,IMG_FILTER_CONTRAST,-20); // EDITAR CONTRASTE - MAS FLUOR
				call_user_func("image".$types[$size[2]], $croped, $cachefile);
				imagedestroy ($croped);
			} else {
				//imagefilter($temp,IMG_FILTER_CONTRAST,-20); // EDITAR CONTRASTE - MAS FLUOR
				call_user_func("image".$types[$size[2]], $temp, $cachefile);
			}
			/* End Custom */
			
			imagedestroy ($image);
			imagedestroy ($temp);

		}
	}


	private function fotito__resizeML($foto_inicial, $foto_final , $fieldParams)
	{ 
		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type
		//$fullpath = $folder;
		uses ('folder');
		uses ('file');

		$url = $foto_inicial;

		if (!($size = getimagesize($url)))
			return; // image doesn't exist

			$width=$fieldParams['width'];
			$height=$fieldParams['height'];

			/* Custom by PedroFuentes */
			$original_width=$fieldParams['width'];
			$original_height=$fieldParams['height'];

			if(isset($fieldParams['crop']) && $fieldParams['crop']===true)
			{

				if (($size[1]/$height) < ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
					$width = ceil(($size[0]/$size[1]) * $height);
				else
					$height = ceil($width / ($size[0]/$size[1]));

			} else {

				if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
					$width = ceil(($size[0]/$size[1]) * $height);
				else
					$height = ceil($width / ($size[0]/$size[1]));

			}

			/* End Custom */

		if ($fieldParams['allow_enlarge']===false) { // don't enlarge image
			if (($width>$size[0])||($height>$size[1])) {
				$width=$size[0];
				$height=$size[1];
			}
		} else {
			if ($fieldParams['aspect']) { // adjust to aspect.
				if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
					$width = ceil(($size[0]/$size[1]) * $height);
				else
					$height = ceil($width / ($size[0]/$size[1]));
			}else{
		
				$width = $original_width;
				$height = ceil($original_height * ($original_width / $size[0])); 
			}
		}


		//$prefix=$this->__getPrefix($fieldParams);
		//$cachefile = $fullpath.DS.$prefix.'_'.basename($originalName);  // location on server
		$cachefile = $foto_final;  // location on server

		if (file_exists($cachefile)) {
			$csize = getimagesize($cachefile);
			$cached = ($csize[0] == $width && $csize[1] == $height); // image is cached
			if (@filemtime($cachefile) < @filemtime($url)) // check if up to date
				$cached = false;
		} else {
			$cached = false;
		}

		if (!$cached) {
			$resize = ($size[0] > $width || $size[1] > $height) || ($size[0] < $width || $size[1] < $height || ($fieldParams['allow_enlarge']===false));
		} else {
			$resize = false;
		}
			$resize = true;
			$fieldParams['crop'] = true;

		if ($resize) {
			//prx('aca');
			$image = call_user_func('imagecreatefrom'.$types[$size[2]], $url);
			if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor ($width, $height))) {
				/* EDU :P */
				$size_blanco = array('width' => $width,
									 'height' => $height);
				imagefilledrectangle($temp, 0, 0, $size_blanco['width'], $size_blanco['height'], 0xFFFFFF);
				/* FIN EDU */
				call_user_func('image'.$types[$size[2]], $temp, $cachefile, 100);
				imagealphablending($temp, false);
				imagesavealpha($temp, true);
				imagecopyresampled ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			} else {
				$temp = imagecreate ($width, $height);
				imagecopyresized ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			}

			/* Custom by PedroFuentes **/
			if(isset($fieldParams['crop']) && $fieldParams['crop']===true){
				if (function_exists("imagecreatetruecolor") && ($croped = imagecreatetruecolor ($original_width, $original_height))) {
					/* EDU :P */
					$size_blanco = array('width' => $original_width-1,
										 'height' => $original_height-1);
					imagefilledrectangle($croped, 0, 0, $size_blanco['width'], $size_blanco['height'], 0xFFFFFF);
					/* FIN EDU */
					call_user_func('image'.$types[$size[2]], $croped, 100);
					imagealphablending($croped, false);
					imagesavealpha($croped, true);
					imagecopy ($croped, $temp, ( floor($original_width/2) - floor($width/2) ), ( floor($original_height/2) - floor($height/2) ), 0, 0, $width, $height);
				} else {
					$croped = imagecreate ($original_width, $original_height);
					/* EDU :P */
					$size_blanco = array('width' => $original_width-1,
										 'height' => $original_height-1);
					imagefilledrectangle($croped, 0, 0, $size_blanco['width'], $size_blanco['height'], 0xFFFFFF);
					/* FIN EDU */
					imagecopy ($croped, $temp, ( floor($original_width/2) - floor($width/2) ), ( floor($original_height/2) - floor($height/2) ), 0, 0, $width, $height);
				}
				//imagefilter($croped,IMG_FILTER_CONTRAST,-20); // EDITAR CONTRASTE - MAS FLUOR
				call_user_func("image".$types[$size[2]], $croped, $cachefile);
				imagedestroy ($croped);
			} else {
				//imagefilter($temp,IMG_FILTER_CONTRAST,-20); // EDITAR CONTRASTE - MAS FLUOR
				call_user_func("image".$types[$size[2]], $temp, $cachefile);
			}
			/* End Custom */
			
			imagedestroy ($image);
			imagedestroy ($temp);

		}
	}
	function admin_cargar_descuentos()
	{
		$stats = array();
		$this->loadModel('Descuento');
		if ( ! empty($this->data) )
		{
			$this->data['Archivo']['administrador_id']		= $this->Auth->user('id');
			$this->data['Archivo']['tipo'] = 2;

			foreach ( $this->data['Categoria'] as $index => $categoria )
			{
				if ( $categoria['categoria_id'] )
				{
					$this->data['Categoria'][$index]['categoria_id'] = $index;
				}
				else
				{
					unset($this->data['Categoria'][$index]);
				}
			}

			if (! $this->data['Categoria'])
			{
				unset($this->data['Categoria']);
			}

			$this->Archivo->create();
			if ( $this->Archivo->save($this->data['Archivo']) )
			{
				$id = $this->Archivo->id;
				// BENCHMARKING
				set_time_limit(0);
				$inicio			= microtime(true);
				$stats 			= array('nuevos' => 0, 'repetidos' => 0, 'errores' => 0, 'memoria' => 0, 'tiempo' => 0);

				$archivo = $this->Archivo->find('first', array('conditions' => array('Archivo.id' => $id,
																					 'Archivo.tipo' => 2)));

				if (! $archivo )
				{
					$this->Session->setFlash(__('No se encontro el archivo', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Archivo->save(array('Archivo' => array('id' => $id,
															  'flag' => 1)));

				// MAPEO DE CAMPOS
				$mapeo	 		= array(0 => 'codigo');
				$registros		= $this->Masivo->procesarArchivo('img/' . $archivo['Archivo']['nombre']['path'], $mapeo);

				if ( ! $registros )
				{
					$this->Session->setFlash(__('No se encontraron registros en el archivo', true));
					$this->redirect(array('action' => 'index'));
				}

				$verificar_codigo = $this->Descuento->find('list', array('fields' => array('Descuento.id', 'Descuento.codigo')));
				$caducidad = $this->data['Descuento']['fecha_caducidad']['year'] . '-' . $this->data['Descuento']['fecha_caducidad']['month'] . '-' . $this->data['Descuento']['fecha_caducidad']['day'];
				$escolar = 0;
				if (isset($this->data['Descuento']['escolar']) && $this->data['Descuento']['escolar'])
				{
					$escolar = 1;
				}
				foreach($registros as $registro)
				{
					if ( trim($registro['codigo']) )
					{
						$codigo = trim($registro['codigo']);
						if (! in_array($codigo, $verificar_codigo))
						{
							// generar descuento
							$new_descuento = array('Descuento' => array('nombre' 			=> $this->data['Descuento']['nombre'],
																		'cantidad' 			=> 1,
																		'maximo'			=> $this->data['Descuento']['maximo'],
																		'web_tienda'		=> $this->data['Descuento']['web_tienda'],
																		'fecha_caducidad' 	=> $caducidad,
																		'codigo' 			=> $codigo,
																		'contador' 			=> 0,
																		'escolar' 			=> $escolar,
																		'tipo' 				=> $this->data['Descuento']['tipo'],
																		'descuento' 		=> $this->data['Descuento']['descuento']
																		)
												   );
							if ( $this->data['Categoria'] )
								$new_descuento['Categoria'] = $this->data['Categoria'];
							$this->Descuento->create();
							if ( $this->Descuento->saveAll($new_descuento) )
							{
								$verificar_codigo[$this->Descuento->id] = $codigo;
								$stats['nuevos']++;
							}
							else
							{
								$stats['errores']++;
							}
						}
						else
						{
							$stats['repetidos']++;
						}
					}
					else
					{
						$stats['errores']++;
					}
				}
				$stats['memoria']		= (memory_get_peak_usage(true) / 1024 / 1024) . ' MB';
				$stats['tiempo']		= round(microtime(true) - $inicio, 3);
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$categorias = $this->Descuento->Categoria->find('list', array('conditions' => array('Categoria.id' => array(1,2,3,4,6,7,8,9,10,11)),
																	  'fields' => array('Categoria.id', 'Categoria.nombre'),
																	  'order' => array('Categoria.id' => 'ASC')));
		$this->set(compact('stats', 'categorias'));
	}

	/** CARGA DE DESCRIPCION Y FICHAS DE PRODUCTOS
	 * verifica producto segun codigo y color
	 * actualiza descripcion del producto
	 * asocia lineas de ficha a listado de detalle para la ficha del producto
	 * asume si la linea es de titulo o de texto dependiendo de la estructura de la linea
	 * actualiza ficha del producto
	 */
	function admin_ingreso_ficha()
	{
		Configure::write('debug',2);
		if (! empty($this->data))
		{
			if (! isset($this->data['Archivo']['archivo']['tmp_name']))
			{
				$this->Session->setFlash(__('Debe ingresar un archivo', true));
				$this->redirect(array('action' => 'ingreso_ficha'));
			}
			if (! file_exists($this->data['Archivo']['archivo']['tmp_name']))
			{
				$this->Session->setFlash(__('Debe ingresar un archivo', true));
				$this->redirect(array('action' => 'ingreso_ficha'));
			}
			// BENCHMARKING
			set_time_limit(0);
			$inicio			= microtime(true);
			$guardar = true;

			$archivo = $this->data['Archivo']['archivo']['tmp_name'];
	
			if ( ! $archivo )
				return false;
	
			// MAPEO DE CAMPOS
			$mapeo	 		= array('codigo', 'descripcion', 'ficha');
			$registros		= $this->Masivo->procesarArchivo($archivo, $mapeo);
	
			if ( ! $registros )
			{
				$this->Session->setFlash(__('Archivo vacio', true));
				$this->redirect(array('action' => 'ingreso_ficha'));
			}

			$basecopy = 'img/Archivo/';
			$filename_log = $basecopy."log_carga_fichas.txt";
			$fp = fopen($filename_log,"a");
			fwrite($fp,'=== INICIO ' .date('d-m-Y H:i:s').PHP_EOL);
			
			// RECOLECCION DE ESTADISTICAS
			$stats 			= array('lineas_leidas' => 0, 'productos_leidos' => 0, 'productos_actualizados' => 0, 'productos_omitidos' => 0);

			$limite = count($registros);
			$this->loadModel('Producto');
			
			$productos = array();
			$codigo = '';
			fwrite($fp,'+ inicio lectura de archivo ' .date('d-m-Y H:i:s').PHP_EOL.PHP_EOL);
			foreach ($registros as $index => $registro)
			{
				$stats['lineas_leidas']++;
				if (trim($registro['codigo']))
				{
					$codigo = trim($registro['codigo']);
					$productos[$codigo] = array(
						'codigo' => $codigo,
						'descripcion' => '',
						'ficha' => array()
					);
					fwrite($fp,$codigo.' ');
				}
				if (! $codigo)
					continue;
				if (! isset($productos[$codigo]))
				{
					$productos[$codigo] = array(
						'codigo' => $codigo,
						'descripcion' => '',
						'ficha' => array()
					);
				}
				if (trim($registro['descripcion']))
					$productos[$codigo]['descripcion'].= utf8_encode(trim($registro['descripcion']));
				if (trim($registro['ficha']))
					$productos[$codigo]['ficha'][] = utf8_encode(trim($registro['ficha']));
			}

			fwrite($fp,PHP_EOL.PHP_EOL.'++ codigos encontrados '.count($productos).PHP_EOL);
			if (! $productos)
			{
				fclose($fp);
				$this->Session->setFlash(__('No se encontraron productos par actualizar', true));
				$this->redirect(array('action' => 'ingreso_ficha'));
			}
			fwrite($fp,'+ inicio actualizacion de productos ' .date('d-m-Y H:i:s').PHP_EOL.PHP_EOL);
			foreach ($productos as $producto)
			{
				$options = array(
					'conditions' => array(
						'OR' => array(
							array('Producto.codigo' => $producto['codigo']),
							array('Producto.codigo' => $producto['codigo'].'N'),
							array('Producto.codigo' => $producto['codigo'].'L')
						)
						
					),
					'fields' => array(
						'Producto.id',
						'Producto.nombre'
					),
					'recursive' => -1
				);

				if (! $listado = $this->Producto->find('list',$options))
					continue;

				foreach ($listado as $productoId => $productoName)
				{
					$stats['productos_leidos']++;
					$save = array(
						'id' => $productoId,
						'descripcion' => '<h4>'.$productoName.'</h4><br>'.$producto['descripcion'],
						'ficha' => ''
					);
					if ($producto['ficha'])
					{
						foreach ($producto['ficha'] as $linea)
						{
							if (reset($producto['ficha']) == $linea)
							{
								$save['ficha'].='<h4>'.trim(str_replace(':','',$linea)).':</h4><ul>';
								continue;
							}
							$limpio = Inflector::slug(strtolower($linea));
							if (in_array($limpio,array('construccion','detalles')))
							{
								$save['ficha'].='</ul><h4>'.trim(str_replace(':','',$linea)).':</h4><ul>';
								continue;
							}
							$save['ficha'].='<li>'.trim($linea).'</li>';
						}
					}
					if ($save['ficha'])
					{
						if (substr($save['ficha'],-5) == '</li>')
						{
							$save['ficha'].='</ul>';
						}
					}
					if ($guardar && $save)
					{
						if ($this->Producto->save($save))
						{
							fwrite($fp,$save['id'].' ');
							$stats['productos_actualizados']++;
						}
					}
				}
			}
			$stats['memoria']		= (memory_get_peak_usage(true) / 1024 / 1024) . ' MB';
			$stats['tiempo']		= round(microtime(true) - $inicio, 3);
			fwrite($fp,PHP_EOL.PHP_EOL.'++ productos afectados '.$stats['productos_leidos'].PHP_EOL);
			fwrite($fp,'++ productos actualizados '.$stats['productos_actualizados'].PHP_EOL);
			fwrite($fp,'== FIN '.date('d-m-Y H:i:s').PHP_EOL);
			fwrite($fp,PHP_EOL.'################################################################################'.PHP_EOL);
			fclose($fp);
			$this->set(compact('stats'));
		}
	}

	function fixstring($str)
	{
		return strtolower(preg_replace('/[^a-z0-9\s]/i', '', iconv('UTF-8', 'ASCII//IGNORE//TRANSLIT', $str)));
	}

	function sc($str)
	{
		$conectores	= array('por', 'la', 'de', 'un', 'unos', 'unas', 'del', 'te', 'el', 'los ', 'las', 'con', 'sin', 'ir', 'al', 'por', 'en', 'a');
		return implode(' ', array_diff(explode(' ', $str), $conectores));
	}
	
	function admin_cargar_precios()
	{
		if (! empty($this->data))
		{
			$this->data['Archivo']['administrador_id']		= $this->Auth->user('id');
			$this->data['Archivo']['tipo'] = 3;
			$this->Archivo->create();
			if ( $this->Archivo->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
	}
	
	function admin_procesar_precios($id = null)
	{
		
		// BENCHMARKING
		set_time_limit(0);

		$archivo = $this->Archivo->find('first', array('conditions' => array('Archivo.id' => $id,'Archivo.tipo' => 3)));

		if (! $archivo )
		{
			$this->Session->setFlash(__('No se encontro el archivo', true));
			$this->redirect(array('action' => 'index'));
		}

		//$this->Archivo->query('UPDATE sitio_archivos SET flag = 0 WHERE tipo = 3');
		//$this->Archivo->save(array('Archivo' => array('id' => $id,'flag' => 1)));

		// MAPEO DE CAMPOS
		$mapeo	 		= array('codigo', 'precio', 'precio_oferta', 'descripcion', 'div', 'categoria', 'outlet');
		$registros		= $this->Masivo->procesarArchivo('img/' . $archivo['Archivo']['nombre']['path'], $mapeo);

		if ( ! $registros )
		{
			$this->Session->setFlash(__('No se encontraron registros en el archivo', true));
			$this->redirect(array('action' => 'index'));
		}
		// prx($registros);

		$update_productos = array();
		$this->loadModel('Producto');
		foreach ($registros as $registro)
		{
			if (! $registro['codigo'])
				continue;
			$options = array(
				'conditions' => array(
					'Producto.codigo_completo' => trim($registro['codigo'])
				),
				'fields' => array(
					'Producto.id',
					'Producto.activo'
				),
				'recursive' => -1
			);
			if (! $producto = $this->Producto->find('first',$options))
				continue;

			if (trim($registro['precio']))
			{
				$precio = (int)(ereg_replace("[^0-9]", "", $registro['precio']));
				if (is_numeric($precio) && $precio >= 5000)
				{
					$producto['Producto']['precio'] = $precio;
					$producto['Producto']['oferta'] = 0;
					if (trim($registro['precio_oferta']))
					{
						$oferta = (int)(ereg_replace("[^0-9]", "", $registro['precio_oferta']));
						if ($oferta && $oferta >= 5000 && $oferta < $precio)
						{
							$oferta = (int)($oferta/10);
							$oferta = $oferta*10;
							$producto['Producto']['precio_oferta'] = $oferta;
							$producto['Producto']['oferta'] = 1;
							$producto['Producto']['excluir_descuento'] = 1;
						}
					}
				}
			}
			if (trim($registro['descripcion']))
			{
				$producto['Producto']['nombre'] = utf8_encode(trim($registro['descripcion']));
			}
			if (trim($registro['div']))
			{
				$producto['Producto']['division'] = trim($registro['div']);
			}
			if (trim($registro['categoria']))
			{
				$categoriaName = str_replace(array(' ', '-'), '', strtolower(trim($registro['categoria'])));
				$categoriaName = utf8_encode($categoriaName);
				$categoriaId = 0;
				if ( in_array($categoriaName, array('men', 'hombres', 'hombre', 'newmen', 'newhombre', 'newhombres')) )
					$categoriaId = 2;
				elseif ( in_array($categoriaName, array('girl', 'girls', 'niña', 'niñas', 'newgirl', 'newgirls', 'newniña', 'newniñas', 'infants','kidsgirlsinf','kidsgirls', 'girlsinf')) )
					$categoriaId = 4;
				elseif ( in_array($categoriaName, array('boy', 'boys', 'niño', 'niños', 'newboy', 'newboys', 'newniño', 'newniños','kidsboys','kidsboysinf','boysinf')) )
					$categoriaId = 3;
				elseif ( in_array($categoriaName, array('women', 'mujeres', 'mujer', 'newwomen', 'newmujer', 'newmujeres')) )
					$categoriaId = 1;
				elseif ( in_array($categoriaName, array('ropawomen')) )
					$categoriaId = 60;
				$producto['Producto']['categoria_id'] = $categoriaId;
			}
			if (trim($registro['outlet']))
			{
				$producto['Producto']['outlet'] = $producto['Producto']['excluir_descuento'] = 1;
			}
			array_push($update_productos,$producto);
		}
		if ($update_productos)
		{
			if ( $this->Producto->saveAll($update_productos) )
				$this->Session->setFlash(__('Precios actualizados exitosamente', true));
			else
				$this->Session->setFlash(__('No se pudieron actualizar los productos', true));
		}
		else
		{
			$this->Session->setFlash(__('No se encontraron productos para actualizar', true));
		}
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_verificar_sin_galeria()
	{
		Configure::write('debug',1);
		set_time_limit(0);
		$this->loadModel('Producto');
		$categorias_publicas = $this->Producto->Categoria->find('list', array('conditions' => array('Categoria.publico' => 1),
																			  'fields' => array('Categoria.id',
																								'Categoria.id')));
		echo ('========== PRODUCTOS SIN FOTO ==========<br />');
		$productos_sin_foto = $this->Producto->find('all', array('conditions' => array('OR' => array(array('Producto.foto' => NULL),
																									 array('Producto.foto' => '')),
																					   'Producto.categoria_id' => $categorias_publicas),
																 'fields' => array('Producto.id',
																				   'Producto.codigo',
																				   'Producto.codigo_completo',
																				   'Producto.color_id'),
																 'contain' => array('Color' => array('fields' => array('Color.id',
																													   'Color.codigo')))));
		if ($productos_sin_foto)
		{
			foreach ($productos_sin_foto as $producto)
			{
				echo($producto['Producto']['id'].';'.$producto['Producto']['codigo'].';'.$producto['Color']['codigo'].';'.$producto['Producto']['codigo_completo'].'<br/>');
			}
		}
		echo ('========== PRODUCTOS SIN GALERIA ==========<br />');
		$productos = $this->Producto->find('all', array('conditions' => array('Producto.categoria_id' => $categorias_publicas),
														'fields' => array('Producto.id',
																		  'Producto.codigo',
																		  'Producto.codigo_completo',
																		  'Producto.color_id'),
														'contain' => array('Color' => array('fields' => array('Color.id',
																											  'Color.codigo')))));
		if ($productos)
		{
			foreach ($productos as $producto)
			{
				$count_galeria = $this->Producto->Galeria->find('count', array('conditions' => array('Galeria.producto_id' => $producto['Producto']['id'])));
				if ($count_galeria != 5)
				{
					echo($producto['Producto']['id'].';'.$producto['Producto']['codigo'].';'.$producto['Color']['codigo'].';'.$producto['Producto']['codigo_completo'].'<br/>');
				}
			}
		}
		prx('---');
	}
	
	
	function procesar_test($archivo = null)
	{
		// BENCHMARKING
		Configure::write('debug',0);
		set_time_limit(0);
		$archivo = 'products_10082014.csv';

		$inicio			= microtime(true);
		$guardar = true;

		if ( ! $archivo )
			return false;

		$mapeo = array(0 => '1', '2', 'categoria', 'codigo', 'color',  'nombre', '7','precio',  'sku', 'talla', 'stock');

		$registros		= $this->Masivo->procesarArchivo("img/cargas_andain/{$archivo}", $mapeo, ',');
		if ( ! $registros )
			return false;

		// RECOLECCION DE ESTADISTICAS
		$stats 			= array('nuevos' => 0, 'errores' => 0, 'error_numero' => 0, 'rango_cantidad' => 0, 'no_stgo' => 0, 'memoria' => 0, 'tiempo' => 0);

		$this->loadModel('Stock');
		$productos = $this->lista_productos__();
		$options = array('conditions' => array('Tienda.region_id' => 13,
											   'Tienda.id NOT' => array(20,32)),
						 'fields' => array('Tienda.id', 'Tienda.id'));
		$tienda = $this->Stock->Tienda->find('first',$options);

		// INICIALIZA STOCK
		$new_stock = array();
		
		for( $i = 1; $i < count($registros); $i++ )
		{
			$registro = $registros[$i];
			$codigo = trim($registro['codigo']).trim($registro['color']);
			$talla = trim($registro['talla']);
			$cantidad = 0;

			if (isset($productos[$codigo]))
			{
				if (in_array(trim($registro['stock']), array('y','Y')))
					$cantidad = 10;
				if ($cantidad)
				{
					$new_stock[] = array('tienda_id' => $tienda['Tienda']['id'],
										 'producto_id' => $productos[$codigo],
										 'talla' => $talla,
										 'cantidad' => $cantidad
										 );
				}
			}
		}

		/********** GUARDAR EN BASE DE DATOS **********/
		if ($guardar && $new_stock )
		{
			$this->Stock->query('TRUNCATE TABLE sitio_stocks;');
			
			if ( $this->Stock->saveAll($new_stock, array('validate' => false)) )
			{
				$stats['nuevos'] = count($new_stock);
				
				// CARGA DE STOCK TOTAL DE PRODUCTOS POR TALLA
				if ($new_stock)
				{
					foreach ($new_stock as $index => $stock)
					{
						unset($new_stock[$index]['tienda_id']);
					}

					$this->loadModel('Talla');
					$this->Talla->query('TRUNCATE TABLE sitio_tallas;');
					$this->Talla->saveAll($new_stock, array('validate' => false));
				}
				$this->cargar_menu();
			}
		}

		$stats['memoria']		= (memory_get_peak_usage(true) / 1024 / 1024) . ' MB';
		$stats['tiempo']		= round(microtime(true) - $inicio, 3);
		die('cargados: '.$stats['nuevos'].' en '.(int)$stats['tiempo'].' seg.');
	}
	
	function lista_productos__()
	{
		$this->loadModel('Stock');
		$consulta = 'SELECT
						Producto.id,
						Producto.codigo,
						Color.codigo
					FROM
						sitio_productos AS Producto
						LEFT JOIN sitio_colores AS Color ON (Color.id = Producto.color_id)';
		$productos = $this->Stock->Producto->query($consulta);
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
	
	function admin_procesar_ropa($id = null)
	{
		// BENCHMARKING
		set_time_limit(0);

		$archivo = $this->Archivo->find('first', array('conditions' => array('Archivo.id' => $id,'Archivo.tipo' => 4)));

		if (! $archivo )
		{
			$this->Session->setFlash(__('No se encontro el archivo', true));
			$this->redirect(array('action' => 'index'));
		}

		// MAPEO DE CAMPOS
		$mapeo	 		= array('codigo','color','codigo_color','descripcion','genero','precio','descript','tallas');
		$registros		= $this->Masivo->procesarArchivo('img/' . $archivo['Archivo']['nombre']['path'], $mapeo);

		if ( ! $registros )
		{
			$this->Session->setFlash(__('No se encontraron registros en el archivo', true));
			$this->redirect(array('action' => 'index'));
		}

		$this->loadModel('Producto');
		$cont = array('cargados' => 0,
					  'omitidos' => 0);
		foreach ($registros as $registro)
		{
			if (isset($registro['codigo']) && trim($registro['codigo']))
			{
				if (isset($registro['color']) && trim($registro['color']))
				{
					$options = array('conditions' => array('Color.codigo' => trim($registro['color'])),
									 'fields' => array('Color.id'),
									 'recursive' => -1);
					$color = $this->Producto->Color->find('first',$options);
					if ($color)
					{
						$nombre = trim($registro['codigo']).trim($registro['color']);
						if (isset($registro['descripcion']) && trim($registro['descripcion']))
						{
							$nombre = trim($registro['descripcion']);
						}
						$categoria = 12;
						if (isset($registro['genero']) && trim($registro['genero']))
						{
							$validar_categoria = trim($registro['genero']);
							$validar_categoria = str_replace(array(' ', '-'), '', $validar_categoria);
							$validar_categoria = strtolower($validar_categoria);
							if ( in_array($validar_categoria, array('men', 'hombres', 'hombre', 'newmen', 'newhombre', 'newhombres')) )
							{
								$categoria = 13;
							}
						}
						$precio = 0;
						if (isset($registro['precio']) && trim($registro['precio']))
						{
							$precio = str_replace(array('.', '$'),'',trim($registro['precio']));
						}
						$descripcion = '';
						if (isset($registro['descript']) && trim($registro['descript']))
						{
							$descripcion = $registro['descript'];
						}
						$options = array('conditions' => array('Producto.codigo_completo' => trim($registro['codigo']).trim($registro['color'])),
										 'fields' => array('Producto.id'),
										 'recursive' => -1);
						$verificar = $this->Producto->find('first',$options);
	
						if (! $verificar)
						{
							$producto = array('nombre' 			=> $nombre,
											  'categoria_id'	=> $categoria,
											  'coleccion_id'	=> 0,
											  'color_id'		=> $color['Color']['id'],
											  'codigo'			=> trim($registro['codigo']),
											  'codigo_completo' => trim($registro['codigo']).trim($registro['color']),
											  'descripcion' 	=> $descripcion,
											  'precio'			=> $precio,
											  'oferta' 			=> 0,
											  'new' => 1,
											  'excluir_descuento' => 0);
							$this->Producto->create();
							if ($this->Producto->save($producto))
							{
								$cont['cargados']++;
							}
							else
							{
								$cont['omitidos']++;
							}
						}
						else
						{
							$cont['omitidos']++;
						}
					}
					else
					{
						$cont['omitidos']++;
					}
				}
				else
				{
					$cont['omitidos']++;
				}
			}
			else
			{
				$cont['omitidos']++;
			}
		}
		$this->Archivo->save(array('id' => $id,'flag' => 1));
		$this->Session->setFlash(__('Proceso de archivo finalizado. Se cargaron: '.$cont['cargados'].' nuevos productos. Se omitieron: '.$cont['omitidos'].' registros.', true));
		$this->redirect(array('action' => 'index'));
	}

	function admin_cargar_fotos()
	{
		$this->loadModel('Producto');

		$stats = array(
			'total' => 0,
			'actualizados' => 0,
			'detalle' => array()
		);

		$origen = 'E:'.DS.'skechers'.DS;
		$extensiones = array('.jpg', '.JPG', '.gif', '.GIF');
		$galerias = array(1 => '_B', 2 => '_C', 3 => '_D', 4 => '_E', 5 => '_F');
		$cortes = $this->Archivo->Behaviors->Image->settings['Producto']['fields']['foto']['versions'];
		foreach ($cortes as $index => $corte)
		{
			if (! in_array($corte['prefix'], array('full', 'ith','mini')) )
				unset($cortes[$index]);
		}

		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Producto.foto' => null),
					array('length(Producto.foto) <=' => 10)
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.color_id',
				'Producto.codigo',
				'Producto.codigo_completo',
				'Producto.foto',
			),
			'contain' => array(
				'Color.codigo',
			)
		);
		if ($productos = $this->Producto->find('all', $options))
		{
			$stats['total'] = count($productos);
			foreach ($productos as $producto)
			{
				$codigo = $producto['Producto']['codigo'] . '_' . $producto['Color']['codigo'];
				$codigo_infant = substr($producto['Producto']['codigo'], 0, -1) . '_' . $producto['Color']['codigo'];
				
				$foto = false;
				foreach ( $extensiones as $extension )
				{
					if (file_exists($origen . $codigo . $extension))
					{
						$foto = $codigo . $extension;
						break;
					}
					elseif (file_exists($origen . $codigo_infant . $extension))
					{
						$foto = $codigo_infant . $extension;
						break;
					}
				}

				if (! $foto)
					continue;

				$destino = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Producto' . DS . $producto['Producto']['id'] . DS;

				if (! $this->limpiar_carpeta( $destino ) )
				{
					@mkdir( $destino, 0777, true );
				}

				if ( copy($origen . $foto, $destino . $foto) )
				{
					$stats['actualizados']++;
					$stats['detalle'][$producto['Producto']['id']] = array(
						'foto' => $foto,
						'galeria' => array()
					);
					// guardar registro
					$this->Producto->query('UPDATE sitio_productos SET foto = "' . $foto . '" WHERE id = ' . $producto['Producto']['id'] . ';');
					foreach ($cortes as $corte)
					{
						$this->fotito__resize($origen, $destino, $foto, $corte['prefix'] . '_' . $foto, 'foto', $corte);
					}

					// GALERIA
					foreach ($galerias as $orden => $galeria)
					{
						$foto = false;

						foreach ( $extensiones as $extension )
						{
							if (file_exists($origen . $codigo . $galeria . $extension))
							{
								$foto = $codigo . $galeria . $extension;
								break;
							}
							elseif (file_exists($origen . $codigo_infant . $galeria . $extension))
							{
								$foto = $codigo_infant . $galeria . $extension;
								break;
							}
						}
						if (! $foto )
							continue;

						$lastGalery = $this->Producto->Galeria->find('first', array(
							'fields' => array(
								'Galeria.id'
							),
							'order' => array(
								'Galeria.id' => 'DESC'
							)
						));
						$galeriaId = $lastGalery['Galeria']['id'] + 1;

						$destino = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Galeria' . DS . $galeriaId . DS;

						if (! $this->limpiar_carpeta( $destino ) )
						{
							@mkdir( $destino, 0777, true );
						}

						if ( copy($origen . $foto, $destino . $foto) )
						{
							array_push($stats['detalle'][$producto['Producto']['id']]['galeria'], array(
								'foto' => $foto,
								'orden' => $orden
							));
							// guardar registro
							$this->Producto->Galeria->query("INSERT INTO sitio_galerias (id, imagen, producto_id, orden, created, modified) VALUES ('" . $galeriaId . "', '" . $foto . "', '" . $producto['Producto']['id'] . "', '" . $orden . "', '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "');");
							foreach ($cortes as $corte)
							{
								$this->fotito__resize($origen, $destino, $foto, $corte['prefix'] . '_' . $foto, 'foto', $corte);
							}
						}
					}
				}
			}
		}
		prx($stats);
	}
}
