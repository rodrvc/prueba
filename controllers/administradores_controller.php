<?php
class AdministradoresController extends AppController
{
	var $name = 'Administradores';

	var $perfiles = array(
		5 => 'Tienda',
		6 => 'Agencia',
		4 => 'Tienda Admin',
		0 => 'Ver Compras',
		1 => 'Full Compras',
		2 => 'Full Productos-Compras',
		3 => 'Admin'
	);
	function admin_login()
	{
		if ($this->Auth->user())
		{
			$usuario = $this->Auth->user();
			if (isset($usuario['Administrador']['perfil']))
			{
				// guardar log
				$this->guardar_log($usuario['Administrador']['id'], 'administradores', 'admin_login', 'loguear', $_SERVER['REMOTE_ADDR']);
				switch($usuario['Administrador']['perfil'])
				{
					// SUPER ADMIN
					case 3 :
						$this->redirect(array('controller' => 'administradores', 'action' => 'estadisticas'));
						break;
					// FULL PRODUCTOS
					case 2 :
						$this->redirect(array('controller' => 'compras', 'action' => 'index'));
						break;
					// FULL COMPRAS
					case 1 :
						$this->redirect(array('controller' => 'compras', 'action' => 'index'));
						break;
					// VER COMPRAS
					case 0 :
						$this->redirect(array('controller' => 'compras', 'action' => 'index'));
						break;
					// TIENDAS ADMIN
					case 4 :
						$this->redirect(array('controller' => 'compras', 'action' => 'index'));
						break;
					//TIENDAS
					case 5 :
						$this->redirect(array('controller' => 'descuentos', 'action' => 'index'));
						break;
					// AGENCIA
					case 6 :
						$this->redirect(array('controller' => 'banners', 'action' => 'index'));
						break;
				}
			}
		}
	}

	function admin_logout()
	{
		$this->Session->delete("Auth.{$this->Auth->userModel}");
		$this->redirect($this->Auth->logout());
	}

	function admin_index()
	{
		if (! $this->Auth->user('perfil') == 3)
		{
			$this->redirect(array('controller' => 'administradores', 'action' => 'admin_login'));
		}
		$this->Administrador->recursive = 0;
		$options = array(
			'fields' => array(
				'Administrador.id',
				'Administrador.nombre',
				'Administrador.usuario',
				'Administrador.perfil',
				'Administrador.created'
			)
		);
		if (isset($this->params['named']['search']) && $this->params['named']['search'])
		{
			$this->data['Buscar']['search'] = $this->params['named']['search'];
			$options['conditions'] = array(
				'Administrador.nombre LIKE' => '%'.$this->params['named']['search'].'%'
			);
		}
		$this->paginate = $options;
		$administradores = $this->paginate();
		
		$perfiles = $this->perfiles;
		$this->set(compact('administradores', 'perfiles'));
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$acceso = false;
		if ( $this->Auth->user('perfil') == 3 )
		{
			$acceso = true;
		}
		elseif ($this->Auth->user('id') == $id )
		{
			$acceso = true;
		}

		if (! $acceso)
		{
			$this->redirect(array('controller' => 'administradores', 'action' => 'admin_login'));
		}
		$this->set('administrador', $this->Administrador->read(null, $id));
	}

	function admin_add()
	{
		$acceso = false;
		if ( $this->Auth->user('perfil') == 3 )
		{
			$acceso = true;
		}
		elseif ($this->Auth->user('id') == $id )
		{
			$acceso = true;
		}

		if (! $acceso)
		{
			$this->redirect(array('controller' => 'administradores', 'action' => 'admin_login'));
		}

		if ( ! empty($this->data) )
		{
			$this->Administrador->create();
			if ( $this->Administrador->save($this->data) )
			{
				// guardar log
				$this->guardar_log($this->Auth->user('id'), 'administradores', 'admin_add', 'crear admin #'.$this->Administrador->id, $_SERVER['REMOTE_ADDR']);
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$perfiles = $this->perfiles;
		$this->set(compact('perfiles'));
	}

	function admin_edit($id = null)
	{
		if ( ! $id && empty($this->data) )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}

		if ( ! empty($this->data) )
		{
			$acceso = false;
			if ( $this->Auth->user('perfil') == 3 )
			{
				$acceso = true;
			}
			elseif ($this->Auth->user('id') == $this->data['Administrador']['id'] )
			{
				$acceso = true;
			}
	
			if (! $acceso)
			{
				$this->redirect(array('controller' => 'administradores', 'action' => 'admin_login'));
			}

			if ( $this->data['Administrador']['clave'] == $this->Auth->password(''))
				unset($this->data['Administrador']['clave']);
			if ( $this->Administrador->save($this->data) )
			{
				// guardar log
				$this->guardar_log($this->Auth->user('id'), 'administradores', 'admin_add', 'editar admin #'.$this->data['Administrador']['id'], $_SERVER['REMOTE_ADDR']);
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		if ( empty($this->data) )
		{
			$this->data = $this->Administrador->read(null, $id);
			unset($this->data['Administrador']['clave']);
		}
		$perfiles = $this->perfiles;
		$this->set(compact('perfiles'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}

		$acceso = false;
		if ( $this->Auth->user('perfil') == 3 )
		{
			$acceso = true;
		}

		if (! $acceso)
		{
			$this->redirect(array('controller' => 'administradores', 'action' => 'admin_login'));
		}

		if ( $this->Administrador->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_administracion()
	{
		$procesos = array(
			array(
				'proceso' => 'Estadisticas',
				'url' => false
			),
			array(
				'proceso' => 'Estadisticas de ventas',
				'descripcion' => 'Ver estadisticas de ventas',
				'url' => array(
					'action' => 'estadisticas'
				),
				'icon' => 'statistics_32.png',
				'confirm' => false
			),
			array(
				'proceso' => 'Ventas del día',
				'descripcion' => 'Ver las ventas de ayer',
				'url' => array(
					'action' => 'ventasdiarias'
				),
				'icon' => 'statistics_32.png',
				'confirm' => false
			),
			array(
				'proceso' => 'Revision del Log',
				'descripcion' => 'Lista logs filtrados por dia',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'log',
				),
				'icon' => 'diagram_32.png',
				'confirm' => false
			),
			array(
				'proceso' => 'Productos',
				'url' => false
			),
			array(
				'proceso' => 'Cargar fotos',
				'descripcion' => 'Recargar fotos de productos',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'cargar_fotos'
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Agregar Etiquetas',
				'descripcion' => 'Agrega etiqueta a productos (grupos)',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'agregar_tag'
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Quitar Etiquetas',
				'descripcion' => 'Quita etiqueta a productos (grupos)',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'quitar_tag'
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Ingresar Fichas',
				'descripcion' => 'Carga fichas desde archivo.',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'cargar_fichas'
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Autocompletar Fichas',
				'descripcion' => 'Busca los productos sin ficha y luego verifica si existe un producto del mismo estilo con ficha y la carga.',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'autocompletar_fichas'
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Corregir Fichas Defectuosas',
				'descripcion' => 'Busca los productos con fichas mal cargadas y les vacia el campo.',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'fichas_defectuosas'
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Ver imagen y galeria de productos',
				'descripcion' => 'Lista productos con sus imagenes principales y sus galerias de imagenes.',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'verificar_imagenes',
					'galeria' => 'incompleto'
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Activar productos',
				'descripcion' => 'Chequea si el precio y foto del producto de ultima coleccion estan correctos, y los activa',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'validar_productos',
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Reordenar productos',
				'descripcion' => 'Da orden para mostrar en el catalogo',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'admin_reordenar',
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Corregir precios oferta',
				'descripcion' => 'Busca los precios que sean igual o menores que el precio oferta y quita el estado oferta.',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'precio_oferta'
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Cargas',
				'url' => false
			),
			array(
				'proceso' => 'Verificar',
				'descripcion' => 'Verificar productos desde archivo csv de carga de productos, separando los que estan actualmente en el sistema y los que faltan por cargar.',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'verificar_productos_csv'
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Recargar ofertas',
				'descripcion' => 'Remueve el estado oferta, lee y carga archivo con lista de productos en oferta',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'productos_liquidacion',
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
			array(
				'proceso' => 'Compras',
				'url' => false
			),
			array(
				'proceso' => 'Correos de despacho',
				'descripcion' => 'Re enviar correos de despacho',
				'url' => array(
					'controller' => 'administradores',
					'action' => 'correo_despacho',
				),
				'icon' => 'gear_32.png',
				'confirm' => true
			),
		);
		$this->set(compact('procesos'));
	}
	
	function admin_log()
	{
		$fecha = date('Y-m-d');
		$tipo = 'admin';
		if (! empty($this->data))
		{
			if (isset($this->data['Administrador']['fecha']) && $this->data['Administrador']['fecha'] && is_array($this->data['Administrador']['fecha']))
				$fecha = $this->data['Administrador']['fecha']['year'].'-'.$this->data['Administrador']['fecha']['month'].'-'.$this->data['Administrador']['fecha']['day'];

			if (isset($this->data['Administrador']['tipo']) && in_array($this->data['Administrador']['tipo'],array('sitio')))
				$tipo = $this->data['Administrador']['tipo'];
		}
		
		$startDate = $fecha.' 00:00:00';
		$endDate = $fecha.' 23:59:59';
		$this->loadModel('Log');
		$options = array(
			'conditions' => array(
				'Log.accion LIKE' => 'admin_%',
				'Log.created BETWEEN ? AND ?' => array(
					$startDate,
					$endDate
				)
			),
			'fields' => array(
				'Log.id',
				'Log.usuario_id',
				'Log.controlador',
				'Log.accion',
				'Log.detalle',
				'Log.ip',
				'Log.created',
				'Usuario.nombre'
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_administradores',
					'alias' => 'Usuario',
					'type' => 'LEFT',
					'conditions' => array(
						'Usuario.id = Log.usuario_id'
					)
				),
			),
			'order' => array(
				'Log.id' => 'ASC'
			),
			'recursive' => -1
		);
		if ($tipo == 'sitio')
		{
			$options['conditions'] = array(
				'NOT' => array(
					array('Log.accion LIKE' => 'admin_%')
				),
				'Log.created BETWEEN ? AND ?' => array(
					$startDate,
					$endDate
				)
			);
			$options['fields'][] = 'Usuario.apellido_paterno';
			$options['joins'][0] = array(
				'table' => 'sitio_usuarios',
				'alias' => 'Usuario',
				'type' => 'LEFT',
				'conditions' => array(
					'Usuario.id = Log.usuario_id'
				)
			);
		}
		$logs = $this->Log->find('all',$options);
		$this->set(compact('logs'));
	}
	
	function admin_verificar_productos_csv()
	{
		$this->loadModel('Producto');
		// BENCHMARKING
		set_time_limit(0);

		$archivo = 'cargas_andain/5_I.csv';
		App::import('Component', 'Masivo');
		$Masivo = new MasivoComponent();

		if ( ! $archivo )
			return false;

		if (! empty($this->data))
		{
			if (isset($this->data['Producto']))
			{
				if ($this->Producto->saveAll($this->data['Producto'], array('validate' => false)))
				{
					$this->Session->setFlash(__('Se cargaron '.count($this->data['Producto']).' nuevos productos exitosamente.', true));
					$this->redirect(array('action' => 'admin_verificar_productos_csv'));
				}
				else
				{
					$this->Session->setFlash(__('Lo sentimos, no fue posible cargar los productos. Por favor intentelo nuevamente.', true));
					$this->redirect(array('action' => 'admin_verificar_productos_csv'));
				}
			}
			elseif ($this->data['Actualizar'])
			{
				if ($this->Producto->saveAll($this->data['Actualizar'], array('validate' => false)))
				{
					$this->Session->setFlash(__('Se actualizaron '.count($this->data['Actualizar']).' productos exitosamente.', true));
					$this->redirect(array('action' => 'admin_verificar_productos_csv'));
				}
				else
				{
					$this->Session->setFlash(__('Lo sentimos, no fue posible cargar los productos. Por favor intentelo nuevamente.', true));
					$this->redirect(array('action' => 'admin_verificar_productos_csv'));
				}
			}
		}
		
		// MAPEO DE CAMPOS
		$mapeo	 		= array('codigo_completo', 'codigo', 'color', 'nombre', 'division', 'precio', 'categoria');
		$registros		= $Masivo->procesarArchivo("img/{$archivo}", $mapeo);

		if ( ! $registros )
			return false;
		$list = array();
		foreach ($registros as $registro)
		{
			if($cod = $registro['codigo_completo'])
				$list[] = $registro['codigo_completo'];
		}
		$resultado = array('cargados' => array(),
						   'no_cargados' => array(),
						   'sin_foto' => array(),
						   'sin_descripcion' => array(),
						   'inactivos' => array(),
						   'colores_faltantes' => array());
		// productos sin foto
		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Producto.foto' => null),
					array('Producto.foto' => '')
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.id'
			)
		);
		$sin_foto = $this->Producto->find('list',$options);
		// listado de productos sin descripcion
		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Producto.descripcion' => null),
					array('Producto.descripcion' => '')
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.id'
			)
		);
		$sin_descripcion = $this->Producto->find('list',$options);
		// listado de productos con codigo completo
		$options = array(
			'conditions' => array(
				'Producto.codigo_completo' => $list
			),
			'fields' => array(
				'Producto.codigo_completo',
				'Producto.id'
			)
		);
		$codigos_completos = $this->Producto->find('list',$options);
		// colores
		$options = array(
			'fields' => array(
				'Color.codigo',
				'Color.id'
				
			)
		);
		$colores = $this->Producto->Color->find('list',$options);
		foreach ($registros as $index => $registro)
		{
			$linea = $index+1;
			$id = 0;
			if (isset($codigos_completos[$registro['codigo_completo']]))
				$id = $codigos_completos[$registro['codigo_completo']];
			elseif (isset($codigos_completos[$registro['codigo'].$registro['color']]))
				$id = $codigos_completos[$registro['codigo'].$registro['color']];
			else
			{
				$options = array(
					'conditions' => array(
						'Producto.codigo' => $registro['codigo'],
					),
					'fields' => array(
						'Producto.id'
					),
					'joins' => array(
						array(
							'table' => 'sitio_colores',
							'alias' => 'Color',
							'type' => 'INNER',
							'conditions' => array(
								'Color.id = Producto.color_id',
								'Color.codigo = "'.$registro['color'].'"'
							)
						),
					),
				);
				if ($producto = $this->Producto->find('first',$options))
					$id = $producto['Producto']['id'];
			}
			if ($id)
			{
				$resultado['cargados'][$id] = $registro['codigo'].''.$registro['color'];
				if (in_array($id,$sin_foto))
					$resultado['sin_foto'][$id] = $registro['codigo'].''.$registro['color'];
				if (in_array($id,$sin_descripcion))
					$resultado['sin_descripcion'][$id] = $registro['codigo'].''.$registro['color'];
				
				$categoria = 0;
				$dato = str_replace(array(' ','-'),'',strtoupper(trim($registro['categoria'])));
				if (in_array($dato,array('KIDSBOYS','KIDSBOYSINF')))
					$categoria = 3;
				elseif (in_array($dato,array('KIDSGIRLS','KIDSGIRLSINF')))
					$categoria = 4;
				elseif (in_array($dato,array('WOMEN')))
					$categoria = 1;
				elseif (in_array($dato,array('MEN')))
					$categoria = 2;
				elseif (in_array($dato,array('PERWOMEN')))
					$categoria = 6;
				elseif (in_array($dato,array('PERMEN')))
					$categoria = 9;

				$precio = 0;
				$dato = (int)(str_replace(array('$','.',','),'',trim($registro['precio'])));
				if ($dato && is_numeric($dato))
					$precio = $dato;
				
				if ($categoria && $precio)
				{
					$producto = array(
						'id' => $id,
						'nombre' => trim($registro['nombre']),
						'categoria_id' => $categoria,
						'coleccion_id' => 5,
						'precio' => $precio,
						'oferta' => 0
					);
					if (isset($registro['division']) && $registro['division'])
						$producto['division'] = $registro['division'];
					$this->data['Actualizar'][$id] = $producto;
				}
			}
			else
			{
				$resultado['no_cargados'][$linea] = $registro['codigo_completo'];
				if (! isset($colores[trim($registro['color'])]))
				{
					$resultado['colores_faltantes'][$registro['color']] = $registro['color'];
				}
				else
				{
					$categoria = 0;
					$dato = str_replace(array(' ','-'),'',strtoupper(trim($registro['categoria'])));
					if (in_array($dato,array('KIDSBOYS','KIDSBOYSINF')))
						$categoria = 3;
					elseif (in_array($dato,array('KIDSGIRLS','KIDSGIRLSINF')))
						$categoria = 4;
					elseif (in_array($dato,array('WOMEN')))
						$categoria = 1;
					elseif (in_array($dato,array('MEN')))
						$categoria = 2;
					elseif (in_array($dato,array('PERWOMEN')))
						$categoria = 6;
					elseif (in_array($dato,array('PERMEN')))
						$categoria = 9;

					$precio = 0;
					$dato = (int)(str_replace(array('$','.',','),'',trim($registro['precio'])));
					if ($dato && is_numeric($dato))
						$precio = $dato;
					$color = 0;
					if (isset($colores[trim($registro['color'])]))
						$color = $colores[trim($registro['color'])];

					if ($categoria && $precio && $color)
					{
						$producto = array(
							'nombre' 			=> trim($registro['nombre']),
							'categoria_id'	=> $categoria,
							'coleccion_id'	=> 5,
							'color_id'		=> $color,
							'codigo'			=> trim($registro['codigo']),
							'codigo_completo' => trim($registro['codigo']).trim($registro['color']),
							'precio'			=> $precio,
							'oferta' 			=> 0,
							'new' => 1,
							'excluir_descuento' => 0);
						if (isset($registro['division'])  && trim($registro['division']))
							$producto['division'] = trim($registro['division']);
						$this->data['Producto'][$linea] = $producto;
					}
				}
			}
		}
		$this->set(compact('resultado'));
	}
	
	function admin_verificar_imagenes()
	{
		if (! empty($this->data))
		{
			$url = array('action' => 'verificar_imagenes');
			if (isset($this->data['Administrador']['categoria_id']) && $this->data['Administrador']['categoria_id'])
				$url['categoria'] = $this->data['Administrador']['categoria_id'];
			if (isset($this->data['Administrador']['galeria']) && $this->data['Administrador']['galeria'])
				$url['galeria'] = $this->data['Administrador']['galeria'];
			if (isset($this->data['Administrador']['coleccion_id']) && $this->data['Administrador']['coleccion_id'])
				$url['coleccion'] = $this->data['Administrador']['coleccion_id'];
			$this->redirect($url);
		}
		$this->loadModel('Producto');

		$options = array(
			'fields' => array(
				'Producto.id',
				'Producto.foto',
				'Producto.codigo_completo',
			),
			'contain' => array(
				'Galeria' => array(
					'fields' => array(
						'Galeria.id',
						'Galeria.producto_id',
						'Galeria.imagen'
					)
				)
			),
			'joins' => array(
				0 => array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'Categoria.publico' => 1
					)
				),
			),
			'limit' => 200
		);
		if (isset($this->params['named']['categoria']) && $this->params['named']['categoria'])
		{
			$this->data['Administrador']['categoria_id'] = $this->params['named']['categoria'];
			$options['joins'][0]['conditions']['Categoria.id'] = $this->params['named']['categoria'];
		}
		if (isset($this->params['named']['galeria']) && $this->params['named']['galeria'])
		{
			$this->data['Administrador']['galeria'] = $this->params['named']['galeria'];
			if ($this->params['named']['galeria']=='incompleto')
			{
				$options['joins'][1] = array(
					'table' => 'sitio_galerias',
					'alias' => 'CountImg',
					'type' => 'LEFT',
					'conditions' => array(
						'CountImg.producto_id = Producto.id',
					)
				);
				$options['group'] = array(
					'Producto.id HAVING COUNT(CountImg.id) < 5'
				);
			}
			elseif ($this->params['named']['galeria']=='sobrecargado')
			{
				$options['joins'][1] = array(
					'table' => 'sitio_galerias',
					'alias' => 'CountImg',
					'type' => 'LEFT',
					'conditions' => array(
						'CountImg.producto_id = Producto.id',
					)
				);
				$options['group'] = array(
					'Producto.id HAVING COUNT(CountImg.id) > 5'
				);
			}
		}
		if (isset($this->params['named']['coleccion']) && $this->params['named']['coleccion'])
		{
			$this->data['Administrador']['coleccion_id'] = $this->params['named']['coleccion'];
			$options['conditions']['Producto.coleccion_id'] = $this->params['named']['coleccion'];
		}
		$this->paginate = $options;
		$productos = $this->paginate('Producto');
		$options = array(
			'conditions' => array(
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre'
			),
			'order' => array(
				'Categoria.nombre' => 'ASC'
			)
		);
		$categorias = $this->Producto->Categoria->find('list',$options);
		$options = array(
			'fields' => array(
				'Coleccion.id',
				'Coleccion.nombre'
			),
			'order' => array(
				'Coleccion.id' => 'DESC'
			)
		);
		$colecciones = $this->Producto->Coleccion->find('list',$options);
		$this->set(compact('productos', 'categorias', 'colecciones'));
	}

	function recargar_galeria__($id = null)
	{
		//if (! $this->Session->check('Auth.Administrador'))
			$this->redirect(array('controller' => 'productos','action' => 'inicio'));
		die('...');
		
		Configure::write('debug',0);
		if (! $this->Session->check('Auth.Administrador'))
			$this->redirect(array('controller' => 'productos','action' => 'inicio'));
		if (! $id)
			die('Invalid');
		$options = array(
			'conditions' => array(
				'Producto.id' => $id
			),
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
		if (! $producto = $this->Producto->find('first',$options))
			die('Producto invalido');
		$options = array(
			'conditions' => array(
				'Galeria.producto_id' => $id
			),
			'fields' => array(
				'Galeria.id',
				'Galeria.id'
			)
		);
		if ($galerias = $this->Producto->Galeria->find('list',$options))
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
		$folder_ini = 'http://cdn4.skechers-usa.com/img/productimages/';
		$folder_galeria = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS. 'img' . DS . 'Galeria' . DS;

		foreach ($imagenes_galeria as $orden => $galeria)
		{
			$codigo = $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'].$galeria;
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
		
			if (! $foto_galeria)
			{
				$codigo_infant = explode('_',$codigo);
				$codigo = null;
				if (in_array(substr($codigo_infant[0], ((int)strlen($codigo_infant[0])-1), 1), array('L','N')))
				{
					$codigo = substr($codigo_infant[0], 0, -1).'_'.$codigo_infant[1].$galeria;
				}
				if ($codigo)
				{
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
		
			if ($foto_galeria)
			{
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
						$folder = &new Folder($folder_galeria.$id_galeria, $create = true, $mode = 0777);
					}
					copy($folder_ini.'large/'.$foto_galeria, $folder_galeria.$id_galeria.'/'.$foto_galeria);
					copy($folder_ini.'large/'.$foto_galeria, $folder_galeria.$id_galeria.'/full_'.$foto_galeria);
					copy($folder_ini.'medium/'.$foto_galeria, $folder_galeria.$id_galeria.'/ith_'.$foto_galeria);
					copy($folder_ini.'small/'.$foto_galeria, $folder_galeria.$id_galeria.'/mini_'.$foto_galeria);
					$this->Producto->Galeria->query('UPDATE sitio_galerias set imagen = "'.$foto_galeria.'" WHERE id = '.$id_galeria.';');
				}
			}
		}

		$options = array(
			'conditions' => array(
				'Producto.id' => $id
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.foto'
			),
			'contain' => array(
				'Galeria' => array(
					'fields' => array(
						'Galeria.id',
						'Galeria.imagen',
						'Galeria.producto_id'
					)
				),
			)
		);
		$producto = $this->Producto->find('first',$options);

		echo '
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>principal</th>
					<th>B</th>
					<th>C</th>
					<th>D</th>
					<th>E</th>
					<th>F</th>
					<th>.</th>
				<tr>
			</thead>
			<tbody>
				<tr>
					<td>'.$producto['Producto']['id'].'</td>
					<td><img src="'.$this->webroot.'img/'.$producto['Producto']['foto']['mini'].'" /></td>';
		$cont = 0;
		if ($producto['Galeria'])
		{
			foreach ($producto['Galeria'] as $galeria)
			{
				echo '<td><img src="'.$this->webroot.'img/Galeria/'.$galeria['id'].'/mini_'.$galeria['imagen'].'" /></td>';
				$cont++;
			}
		}
		if ($cont<5)
		{
			for($x=$cont;$x<5;$x++)
			{
				echo '<td></td>';
			}
		}
		echo '
					<td>
						<a href="'.$this->webroot.'admin/productos/edit/'.$producto['Producto']['id'].'" style="margin: 0 7px;" target="_blank">edit</a>
					</td>
				</tr>
			</tbody>
		</table>';
		die('=== FIN ===');
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

	function admin_autocompletar_fichas()
	{
		// buscar productos sin ficha
		$options = array(
			'conditions' => array(
				'OR' => array(
					array('Producto.ficha' => null),
					array('Producto.ficha' => '')
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.codigo',
			),
		);
		$cont = 0;
		$this->loadModel('Producto');
		if ($productos = $this->Producto->find('list',$options))
		{
			foreach ($productos as $productoId => $productoCodigo)
			{
				// buscar productos del mismo estilo CON ficha
				$modelo = str_replace(array('N','L'), '', $productoCodigo);
				$modelos = array($modelo, $modelo.'L', $modelo.'N');
				$options = array(
					'conditions' => array(
						'Producto.codigo' => $modelos,
						'NOT' => array(
							'OR' => array(
								array('Producto.ficha' => null),
								array('Producto.ficha' => '')
							)
						)
					),
					'fields' => array(
						'Producto.id',
						'Producto.descripcion',
						'Producto.ficha'
					),
					'recursive' >= -1,
					'order' => array(
						'Producto.modified' => 'DESC'
					)
				);
				if ($similar = $this->Producto->find('first',$options))
				{
					$update = array(
						'id' => $productoId,
						'ficha' => $similar['Producto']['ficha']
					);
					if ($similar['Producto']['descripcion'])
						$update['descripcion'] = $similar['Producto']['descripcion'];
					if($this->Producto->save($update))
						$cont++;
				}
			}
		}
		$titulo = 'Resultado Autocompletar Fichas';
		$resultado = 'Se actualizaron: '.$cont.' de '.count($productos).' productos sin ficha';
		$this->set(compact('titulo','resultado'));
		$this->render('admin_resultado');
	}

	function admin_fichas_defectuosas()
	{
		if (! $this->Session->check('Auth.Administrador'))
			$this->redirect(array('controller' => 'productos','action' => 'inicio'));
		//Configure::write('debug',1);
		$options = array(
			'conditions' => array(
				'Producto.ficha' => array('<ul>','</ul>','<li>','</li>')
			),
			'fields' => array(
				'Producto.id',
				'Producto.codigo',
				'Producto.ficha'
			)
		);
		$this->loadModel('Producto');
		$cont = 0;
		if ($productos = $this->Producto->find('all',$options))
		{
			foreach ($productos as $producto)
			{
				$update = array(
					'id' => $producto['Producto']['id'],
					'ficha' => null
				);
				if ($this->Producto->save($update))
					$cont++;
			}
		}
		$titulo = 'Resultado Fichas Defectuosas';
		$resultado = 'Se actualizaron: '.$cont.' de '.count($productos).' productos';
		$this->set(compact('titulo','resultado'));
		$this->render('admin_resultado');
	}
	
	function admin_quitar_tag()
	{
		if (! empty($this->data))
		{
			if (isset($this->data['Administrador']['etiqueta']) && $this->data['Administrador']['etiqueta'])
			{
				$cont=0;
				$options = array(
					'conditions' => array(
						'Producto.grupo LIKE' => '%['.$this->data['Administrador']['etiqueta'].']%'
					),
					'fields' => array(
						'Producto.id',
						'Producto.grupo'
					)
				);
				$this->loadModel('Producto');
				if ($productos = $this->Producto->find('list',$options))
				{
					foreach ($productos as $productoId => $productoTag)
					{
						$newTag = str_replace('['.$this->data['Administrador']['etiqueta'].']','',$productoTag);
						if (strlen($newTag) <= 1)
							$newTag = null;
						if ($this->Producto->save(array('id' => $productoId,'grupo' => $newTag)))
							$cont++;
					}
				}
				$titulo = 'Resultado de Quitar Etiqueta';
				$resultado = 'Se actualizaron: '.$cont.' de '.count($productos).' productos';
				$this->set(compact('titulo','resultado'));
				$this->render('admin_resultado');
			}
		}
	}
	
	function admin_agregar_tag()
	{
		//Configure::write('debug',2);
		if (! empty($this->data))
		{
			if (isset($this->data['Administrador']['etiqueta']) && $etiqueta = $this->data['Administrador']['etiqueta'])
			{
				if (isset($this->data['Administrador']['listado']) && $this->data['Administrador']['listado'])
				{
					$codigos = explode(',',$this->data['Administrador']['listado']);
					if ($codigos && is_array($codigos))
					{
						$cont=0;

						$options = array(
							'conditions' => array(
								'Producto.codigo_completo' => $codigos
							),
							'fields' => array(
								'Producto.id',
								'Producto.grupo'
							),
						);

						$this->loadModel('Producto');
						if ($productos = $this->Producto->find('list',$options))
						{
							foreach ($productos as $productoId => $productoTag)
							{
								$newTag = array();
								if ($tags = explode('][',$productoTag))
								{
									foreach ($tags as $oldTag)
									{
										if ($oldTag)
										{
											$tag = Inflector::slug(strtolower(str_replace(array('[',']'),'',$oldTag)));
											if (strlen($tag) >= 30)
												continue;
											$newTag[$tag] = $tag;
										}
									}
								}
								elseif ($productoTag)
								{
									$tag = Inflector::slug(strtolower(str_replace(array('[',']'),'',$productoTag)));
									$newTag[$tag] = $tag;
								}
								
								$tag = Inflector::slug(strtolower($this->data['Administrador']['etiqueta']));
								$newTag[$tag] = $tag;
								$save = array(
									'id' => $productoId,
									'grupo' => '['.implode('][',$newTag).']'
								);

								if ($this->Producto->save($save))
									$cont++;
							}
						}
						$titulo = 'Resultado de Agregar Etiqueta';
						$resultado = 'Se actualizaron: '.$cont.' de '.count($productos).' productos';
						$this->set(compact('titulo','resultado'));
						$this->render('admin_resultado');
					}
				}
			}
		}
		$this->loadModel('Producto');
		$categorias = array();
		$options = array(
			'conditions' => array(
				'Categoria.publico' => 1,
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
		$colecciones = $this->Producto->Coleccion->find('list',array(
			'fields' => array(
				'Coleccion.id',
				'Coleccion.nombre'
			),
			'order' => array(
				'Coleccion.nombre' => 'DESC'
			)
		));
		$this->set(compact('categorias','colecciones'));
	}
	
	function ajax_filtrotag()
	{
		if (! $this->Session->check('Auth.Administrador'))
			die(false);
		if (empty($this->data))
			die(false);
		if (! isset($this->data['Producto']))
			die(false);
		$data = $this->data['Producto'];

		$options = array(
			'fields' => array(
				'Producto.id',
				'Producto.id',
			)
		);
		if (isset($data['nombre']) && $data['nombre'])
			$options['conditions']['Producto.nombre LIKE'] = '%'.$data['nombre'].'%';
		if (isset($data['grupo']) && $data['grupo'])
			$options['conditions']['Producto.grupo LIKE'] = '%'.$data['grupo'].'%';
		if (isset($data['categoria_id']) && $data['categoria_id'])
			$options['conditions']['Producto.categoria_id'] = $data['categoria_id'];
		if (isset($data['coleccion_id']) && $data['coleccion_id'])
			$options['conditions']['Producto.coleccion_id'] = $data['coleccion_id'];
		if (isset($data['showroom']) && $data['showroom'])
			$options['conditions']['Producto.showroom'] = $data['showroom'];
		if (isset($data['activo']))
		{
			if ($data['activo']==='0')
				$options['conditions']['Producto.activo'] = 0;
			elseif ($data['activo']==='1')
				$options['conditions']['Producto.activo'] = 1;
		}
		$this->loadModel('Producto');
		if ($productos = $this->Producto->find('list',$options))
			die(json_encode(implode(',',$productos)));
		die(false);
	}
	
	function admin_precio_oferta()
	{
		$this->loadModel('Producto');
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
		if ($productos = $this->Producto->find('list',$options))
		{
			$this->Producto->updateAll(array('Producto.oferta' => 0),array('Producto.id' => $productos));
		}
		$titulo = 'Resultado de Agregar Etiqueta';
		$resultado = 'Se actualizaron: '.count($productos).' productos';
		$this->set(compact('titulo','resultado'));
		$this->render('admin_resultado');
	}
	
	function admin_cargar_fotos()
	{
		$this->loadModel('Producto');
		$options = array(
			'fields' => array(
				'Coleccion.id',
				'Coleccion.id'
			)
		);
		if (isset($this->params['url']['coleccion']) && $this->params['url']['coleccion'])
		{
			$options['conditions']['Coleccion.id'] = $this->data['Producto']['coleccion_id'] = $this->params['url']['coleccion'];
		}

		$coleccion = $this->Producto->Coleccion->find('list',$options);

		$options = array(
			'conditions' => array(
				'Producto.coleccion_id' => $coleccion,
				'OR' => array(
					array('Producto.foto' => ''),
					array('Producto.foto' => NULL)
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.categoria_id',
				'Producto.coleccion_id',
				'Producto.codigo',
				'Producto.foto',
				'Producto.color_id',
				'Producto.slug',
				
				'Categoria.id',
				'Categoria.nombre'
			),
			'contain' => array(
				'Color' => array(
					'fields' => array(
						'Color.id',
						'Color.codigo'
					)
				),
			),
			'joins' => array(
				array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'INNER',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id',
						'OR' => array(
							array('Categoria.publico' => 1),
							array('Categoria.id' => 11),
							array('Categoria.parent_id' => 11)
						)
					)
				)
			)
		);
		$productos = $this->Producto->find('all',$options);

		if ($productos)
		{
			foreach ($productos as $index => $producto)
			{
				$productos[$index]['Producto']['codigo_imagen'] = $producto['Producto']['codigo'].'_'.$producto['Color']['codigo'];
			}
		}
		$options = array(
			'fields' => array(
				'Coleccion.id',
				'Coleccion.nombre'
			),
			'order' => array(
				'Coleccion.id' => 'DESC'
			)
		);
		$colecciones = $this->Producto->Coleccion->find('list',$options);
		$this->set(compact('productos','colecciones'));
	}

	function admin_correo_despacho()
	{
		$fecha = date('Y-m-d');
		$tipo = 'admin';
		if (! empty($this->data))
		{
			if (isset($this->data['Administrador']['fecha']) && $this->data['Administrador']['fecha'] && is_array($this->data['Administrador']['fecha']))
				$fecha = $this->data['Administrador']['fecha']['year'].'-'.$this->data['Administrador']['fecha']['month'].'-'.$this->data['Administrador']['fecha']['day'];
		}
		
		$startDate = $fecha.' 00:00:00';
		$endDate = $fecha.' 23:59:59';
		$this->loadModel('Compra');
		$options = array(
			'conditions' => array(
				'Compra.estado' => 1,//compras pagadas
				'Compra.despachado' => 1,// compras despachadas
				'OR' => array(
					array('Compra.created BETWEEN ? AND ?' => array($startDate,$endDate)),
					array('Compra.modified BETWEEN ? AND ?' => array($startDate,$endDate)),
				)
			),
			'order' => array(
				'Compra.id' => 'ASC'
			),
			'recursive' => -1
		);
		$compras = $this->Compra->find('all',$options);
		//prx($compras);
		$this->set(compact('compras'));
	}
	
	function ajax_enviar_correo_despacho()
	{
		Configure::write('debug',0);
		if (! $this->Session->check('Auth.Administrador'))
			die(false);
		if (! $this->params['form']['id'])
			die(false);
		$options = array(
			'conditions' => array(
				'Compra.id' => $this->params['form']['id']
			),
			'fields' => array(
				'Compra.id',
				'fecha_enviado',
				'Compra.usuario_id',
				'Compra.rural',
				'Compra.direccion_rural',
				'Compra.cod_despacho',
				'Compra.despacho_id'
			),
			'contain' => array(
				'Despacho' => array(
					'fields' => array(
						'Despacho.id',
						
					),
					'Direccion' => array(
						'Comuna',
						'Region'
					)
				),
				'Usuario' => array(
					'fields' => array(
						'Usuario.id',
						'Usuario.nombre',
						'Usuario.email'
					),
				),
			)
		);
		$this->loadModel('Compra');
		if (! $compra = $this->Compra->find('first',$options))
			die('ERROR');
		$this->set(compact('compra'));
		//EMAIL

				$this->Email->smtpOptions = array(
					'port' => '587',
					'timeout' => '30',
					'auth' => true,
					'host' => 'mail.smtp2go.com',
					'username' => 'noresponder@skechers-chile.cl',
					'password' => 'eXV6M2k1cWp4Yzcw'
			);
		// DATOS DESTINATARIO (CLIENTE)
		//$this->Email->to = 'ehenriquez@andain.cl';
		$this->Email->to = $compra['Usuario']['email'];
		$this->Email->bcc	= array('solanger@skechers.com','rsilva@skechers.com','sdelvillar@andain.cl');
		$this->Email->subject = '[Skechers - Tienda en linea] Despacho Enviado #' . $compra['Compra']['id'];
		$this->Email->from = 'Skechers <ventas@skechers-chile.cl>';
		$this->Email->replyTo = 'ventas@skechers-chile.cl';
		$this->Email->sendAs = 'html';
		$this->Email->template	= 'despacho';
		$this->Email->delivery = 'smtp';
		if ($this->Email->send())
		{
			$this->Compra->save(array('id' => $this->params['form']['id'], 'mail_confirmacion' => 1));
			die('OK');
		}
		die('ERROR');
	}
	
	function admin_productos_liquidacion()
	{
		$basedir = 'C:'.DS.'xampp'.DS.'public_html'.DS.'andain'.DS.'skechers'.DS.'catalogo'.DS.'sitio2'.DS.'webroot'.DS.'img'.DS.'cargas_andain'.DS;
		//$basedir = DS.'home'.DS.'skechile'.DS.'public_html'.DS.'store'.DS.'webroot'.DS.'img'.DS.'cargas_andain'.DS;

		$name = 'ofertas.csv';
		$archivo = $basedir.$name;
		if ( ! $archivo )
		{
			$this->redirect($this->referer());
		}

		if (! file_exists($archivo))
		if ( ! $archivo )
		{
			$this->redirect($this->referer());
		}

		$mapeo = array('CODIGO','PORCENTAJE','VALOR');
		$this->loadModel('Producto');
		$separador = ',';
		$cont=0;
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
				if (! $registro)
					continue;
				if (! $codigo = $registro['CODIGO'])
					continue;

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
				if (! $producto = $this->Producto->find('first',$options))
					continue;
				if (isset($registro['PORCENTAJE']) && $registro['PORCENTAJE'] && $registro['PORCENTAJE'] >= 1 && $registro['PORCENTAJE'] <= 100)
				{
					$producto['Producto']['oferta'] = 1;
					$descontar = ($producto['Producto']['precio']*($registro['PORCENTAJE']))/1000;
					$descontar = ((int)$descontar)*10;
					$oferta = $producto['Producto']['precio']-$descontar;
					if (! ($oferta%10))
						$oferta = $oferta-10;
					$producto['Producto']['precio_oferta'] = $oferta;
				}
				elseif (isset($registro['VALOR']) && $registro['VALOR'])
				{
					$producto['Producto']['oferta'] = 1;
					$producto['Producto']['precio_oferta'] = $registro['VALOR'];
				}

				if ($producto['Producto']['precio'] > $producto['Producto']['precio_oferta'])
				{
					if ($this->Producto->save($producto))
						$cont++;
				}
			}
		}

		$titulo = 'Resultado Productos Oferta';
		$resultado = 'Se actualizaron: '.$cont.' productos';
		$this->set(compact('titulo','resultado'));
		$this->render('admin_resultado');
	}
	
	function admin_validar_productos()
	{
		set_time_limit(0);
		$cont=0;
		$this->loadModel('Producto');
		$options = array(
			'fields' => array(
				'Coleccion.id'
			),
			'order' => array(
				'Coleccion.id' => 'DESC'
			)
		);
		$coleccion = $this->Producto->Coleccion->find('first',$options);

		$options = array(
			'conditions' => array(
				'Producto.coleccion_id' => $coleccion['Coleccion']['id']
			),
			'fields' => array(
				'Producto.id',
				'Producto.foto',
				'Producto.precio',
				'Producto.oferta',
				'Producto.precio_oferta',
				'Producto.categoria_id'
			),
			'recursive' => -1
		);
		if ($productos = $this->Producto->find('all',$options))
		{
			foreach ($productos as $producto)
			{
				$activo = 0;
				if ($producto['Producto']['foto'])
				{
					if ($producto['Producto']['precio'] >= 1000)
					{
						if ($producto['Producto']['oferta'])
						{
							if ($producto['Producto']['precio'] > $producto['Producto']['precio_oferta'])
							{
								$activo = 1;
							}
						}
						else
						{
							$activo = 1;
						}
					}
				}
				if ($this->Producto->save(array('id' => $producto['Producto']['id'],'activo' => $activo)))
					$cont++;
			}
		}
		$titulo = 'Resultado Productos Oferta';
		$resultado = 'Se activaron: '.$cont.' productos';
		$this->set(compact('titulo','resultado'));
		$this->render('admin_resultado');
	}
	
	function admin_simular_stock()
	{
		set_time_limit(0);
		$cont=0;
		$this->loadModel('Producto');
		$options = array(
			'fields' => array(
				'Talla.producto_id',
				'Talla.producto_id'
			)
		);
		if ($productosStock = $this->Producto->Talla->find('list',$options))
		{
			$options = array(
				'conditions' => array(
					'Producto.coleccion_id' => 6,
					'Producto.activo' => 1,
					'NOT' => array(
						array('Producto.id' => $productosStock)
					)
				),
				'fields' => array(
					'Producto.id',
					'Producto.categoria_id'
				)
			);
			if ($productos = $this->Producto->find('list',$options))
			{
				foreach ($productos as $productoId => $categoriaId)
				{
					$talla = 30;
					if (in_array($categoriaId,array(1,2)))
						$talla = 40;
					$save = array(
						'producto_id' => $productoId,
						'talla' => $talla,
						'cantidad' => 10
					);
					$this->Producto->Talla->create();
					if ($this->Producto->Talla->save($save))
					{
						$save['tienda_id'] = 10;
						$this->Producto->Stock->create();
						$this->Producto->Stock->save($save);
						$cont++;
					}
				}
			}
		}
		$titulo = 'Simular Stock';
		$resultado = 'Se cargaron: '.$cont.' stock';
		$this->set(compact('titulo','resultado'));
		$this->render('admin_resultado');
	}

	function admin_reordenar()
	{
		$this->loadModel('Producto');
		$this->Producto->query('UPDATE sitio_productos SET orden = 0;');
		$cont = $orden = 0;
		// ordenar colecciones
		$options = array(
			'fields' => array(
				'Coleccion.id',
				'Coleccion.id'
			),
			'order' => array(
				'Coleccion.id' => 'ASC'
			)
		);
		if ($colecciones = $this->Producto->Coleccion->find('list',$options))
		{
			$orden++;
			foreach ($colecciones as $coleccion)
			{
				if ($this->Producto->updateAll(array('Producto.orden' => $orden),array('Producto.coleccion_id' => $coleccion)))
				{
					$orden++;
					$options = array('conditions' => array('Producto.coleccion_id' => $coleccion));
					$cont+=count($this->Producto->find('count',$options));
				}
			}
		}
		$estilos = array('sandalias','bobs','go_step','you','go_flex','gowalk','gowalk2','gowalk3','gowalk4','gowalk5','sport','street','relaxed_fit','memory_foam','luces','luces2','train','running', 'golf', 'dlites', 'streetwear','waterresistant', 'waterproof','outdoor','bota_botin','semana');
		foreach($estilos as $estilo )
		{
			$options = array(
				'conditions' => array(
					'Producto.grupo LIKE' => '%['.$estilo.']%'
				),
				'fields' => array(
					'Producto.id',
					'Producto.id'
				)
			);
			if ($productos = $this->Producto->find('list',$options))
			{
				$orden++;
				if ($this->Producto->updateAll(array('Producto.orden' => $orden),array('Producto.id' => $productos)))
				{
					$cont+=count($productos);
				}
			}
		}


	
		
		$titulo = 'Reordenar Productos';
		$resultado = 'Se actualizaron: '.$cont.' productos';
		$this->set(compact('titulo','resultado'));
		$this->render('admin_resultado');
	}
	
	function admin_estadisticas()
	{
		$this->loadModel('Compra');
		$titulo = 'Estadisticas';
		$dias = 30;
		$date = date('Y-m-d',(strtotime(date('Y-m-d'))-(86400*$dias))).' 00:00:00';
		$options = array(
			'conditions' =>  array(
				'Compra.created >=' => $date,
			),
			'fields' => array(
				'Compra.created',
				'COUNT(Compra.id) as total',
				'SUM(if (Compra.estado = 1,1,0)) AS exito',
				'SUM(if (Compra.estado = 1,Compra.total,0)) AS monto'
			),
			'group' => array(
				'YEAR(Compra.created)',
				'MONTH(Compra.created)',
				'DAY(Compra.created)'
			),
			'order' => array(
				'Compra.created' => 'ASC'
			)
		);
		if ($compras = $this->Compra->find('all',$options))
		{
			$promedio_ventas = $promedio_monto = 0;
			foreach ($compras as $compra)
			{
				$promedio_ventas+=$compra[0]['exito'];
				$promedio_monto+=$compra[0]['monto'];
			}
			if ($promedio_ventas)
				$promedio_ventas = (int)($promedio_ventas/$dias);
			if ($promedio_monto)
				$promedio_monto = (int)($promedio_monto/$dias);
			foreach ($compras as $index => $compra)
			{
				$compras[$index][0] = array_merge(
					$compras[$index][0],
					array(
						'promedio_ventas' => $promedio_ventas,
						'promedio_monto' => $promedio_monto
					)
				);
			}
		}
		$options = array(
			'conditions' => array(
				'Compra.created >= ' => $date,
				'Compra.estado' => 1
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.codigo_completo',
				'Producto.foto',
				'COUNT(Producto.id) AS count'
			),
			'joins' => array(
				array(
					'table' => 'sitio_productos_compras',
					'alias' => 'ProductosCompra',
					'type' => 'INNER',
					'conditions' => array(
						'ProductosCompra.producto_id = Producto.id'
					)
				),
				array(
					'table' => 'sitio_compras',
					'alias' => 'Compra',
					'type' => 'INNER',
					'conditions' => array(
						'Compra.id = ProductosCompra.compra_id'
					)
				)
			),
			'group' => array(
				'Producto.id'
			),
			'order' => array(
				'count' => 'DESC'
			),
			'limit' => 10
		);
		$productos = $this->Compra->Producto->find('all',$options);

		$this->set(compact('titulo','compras','productos'));
	}
	
	function admin_ventasdiarias()
	{
		App::import('Model','Compra');
		$CompraOBJ = new Compra();
		$hoy = strtotime(date('d-m-Y H:i:s'));
		$ayer = strtotime(date('d-m-Y',$hoy)) - (60*60*24*1);
		//$ayer = strtotime('2015-09-11');
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
			'GIFTCARD' => array(
				'cantidad' => 0,
				'total' => 0
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
				'Compra.descuento',
				'Compra.usuario_id',
				'Compra.estado',
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
		$reales = $detalle = array();
		if ($compras = $CompraOBJ->find('all',$options))
		{
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

					if ($compra['Compra']['descuento'])
					{
						$reporte['GIFTCARD']['cantidad']++;
						$reporte['GIFTCARD']['total']+=$compra['Compra']['descuento'];
					}

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
					$mensaje.='<tr><td rowspan="'.(count($producto['Talla'])+2).'" style="border-top: 1px solid #777;text-align:left;"><img src="http://www.skechers.cl/img/Producto/'.$producto['id'].'/mini_'.$producto['foto'].'" /></td><td style="border-top: 1px solid #777;color:#000;text-align:left;">'.$producto['codigo'].'</td><td style="border-top: 1px solid #777;text-align:right;color:#000;">'.$producto['seleccionado'].'</td></tr>';
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
		$resultado = $mensaje;
		$titulo = 'Ventas del día '.date('d-m-Y',$ayer);
		$this->set(compact('titulo','resultado'));
		$this->render('admin_resultado');
	}
	
	function admin_cargashowroom()
	{
		Configure::write('debug',1);
		set_time_limit(0);
		$basedir = DS.'home'.DS.'skechers'.DS.'public_html'.DS.'webroot'.DS.'img'.DS.'cargas_andain'.DS;

		$name = 'showroom.csv';
		$archivo = $basedir.$name;
		if ( ! $archivo )
		{
			$this->redirect(array('action' => 'administracion'));
		}

		if (! file_exists($archivo))
		if ( ! $archivo )
		{
			$this->redirect(array('action' => 'administracion'));
		}

		$mapeo = array('style','color','showroom');
		$this->loadModel('Producto');
		$separador = ';';
		$linea=$cont=0;
		if ( ( $handle = fopen($archivo, 'r') ) !== FALSE )
		{
			while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
			{
				if (++$linea <= 1)
					continue;
				$registro = array();
				foreach ( $datos as $index => $valor )
				{
					if ( ! isset($mapeo[$index]) )
						continue;
					$registro[$mapeo[$index]]	= trim($valor);
				}
				if (! $registro)
					continue;
				$codigo = trim($registro['style']).trim($registro['color']);
				$showroom = null;
				if (trim($registro['showroom']))
					$showroom = trim($registro['showroom']);
				$options = array(
					'conditions' => array(
						'Producto.codigo_completo' => $codigo
					),
					'fields' => array(
						'Producto.id'
					)
				);
				if (! $producto = $this->Producto->find('first',$options))
					continue;
				$producto['Producto']['showroom'] = $showroom;
				if ($this->Producto->save($producto))
					$cont++;
			}
		}
		prx($cont);
	}
	
	function admin_refrezcar_estilos()
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
		die($reporte);
	}

	function simularStock($clave = null)
	{
		if ($clave != 'ANDAIN111')
			die('...');
		die('...');
		$this->loadModel('Producto');
		$options = array(
			'conditions' => array(
				'Producto.activo' => 1,
				'Producto.categoria_id' => array(59,60),
				'NOT' => array(
					array('Producto.foto' => null),
					array('Producto.foto' => '')
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.id'
			)
		);
		$productos = $this->Producto->find('list',$options);
		foreach ($productos as $productoId)
		{
			$save = array(
				'producto_id' => $productoId,
				'talla' => 2,
				'cantidad' => 10
			);
			$this->Producto->Stock->create();
			$this->Producto->Stock->save($save);
			$this->Producto->Talla->create();
			$this->Producto->Talla->save($save);
		}
		die('OK');
	}

	function ajax_refresh_info()
	{
				//Configure::write('debug',2);

		if (! $this->Session->check('Auth.Administrador'))
			die(false);
		$desde = date('Y-m-d').' 00:00:00';
		$options = array(
			'conditions' => array(
				'Compra.created >=' => $desde,
				'Compra.local' => 0
			),
			'fields' => array(
				'COUNT(Compra.id) AS total',
				'SUM(if (Compra.estado = 1,1,0)) AS exito',
				'SUM(if (Compra.estado = 1 AND Compra.mail_compra = 0,1,0)) AS pendiente'
			),
			'recursive' => -1
		);
		$sql = "select count(*) as cantidad from sitio_productos_compras where compra_id in ( select id from sitio_compras where created >= '".$desde."' and estado = 1 and local =0)";
		$db = ConnectionManager::getDataSource("default"); // name of your database connection
		$pares = $db->fetchAll($sql);
		$this->loadModel('Compra');
		if (! $compras = $this->Compra->find('first',$options))
			die(false);
		$sql = "select format(count(*),0,'de_DE') as cantidad, format(sum(total),0,'de_DE') as venta, ip from sitio_compras where local =1 and created >= '".$desde."' group by ip";
		$marketplaces = $db->fetchAll($sql);
		//prx($marketplaces);
	


		$compras[0]['pares'] = $pares[0][0]['cantidad'];
		$compras[0]['marketaplaces'] =$marketplaces;
		die(json_encode(reset($compras)));
		
	}

	function admin_producto_fast_edit()
	{
		$productos = array(
			array('10267N-BLMT','21990','16990','GIRLS'),
			array('10268N-TQMT','21990','16990','GIRLS'),
			array('10272L-WBHP','23990','14990','GIRLS'),
			array('10331L-MTPK','23990','16990','GIRLS'),
			array('10380L-DMLT','24990','17990','GIRLS'),
			array('10380N-WMLT','21990','16990','GIRLS'),
			array('10388L-BKWP','24990','17990','GIRLS'),
			array('10400L-TQMT','25990','17990','GIRLS'),
			array('10435L-CAMO','24990','17990','GIRLS'),
			array('13732-AQUA','27990','19990','WOMEN'),
			array('13732-PNK','27990','19990','WOMEN'),
			array('13995-BKPR','54990','44990','WOMEN'),
			array('22474-TPE','34990','27990','WOMEN'),
			array('33536-BLK','24990','17990','WOMEN'),
			array('33536-WHT','24990','17990','WOMEN'),
			array('38165-BLK','25990','19990','WOMEN'),
			array('38165-KHK','25990','19990','WOMEN'),
			array('38233-BRN','25990','19990','WOMEN'),
			array('38233-DKNT','25990','19990','WOMEN'),
			array('38233-TURQ','25990','19990','WOMEN'),
			array('38303-BRN','27990','21990','WOMEN'),
			array('38326-TEAL','27990','21990','WOMEN'),
			array('38326-YEL','27990','21990','WOMEN'),
			array('38882-BLK','29990','19990','WOMEN'),
			array('39109-PUR','34990','19990','WOMEN'),
			array('47357-GRY','37990','27990','WOMEN'),
			array('48959-DSCH','34990','27990','WOMEN'),
			array('48963-CHOC','37990','27990','WOMEN'),
			array('48963-DSCH','37990','27990','WOMEN'),
			array('48963-TPE','37990','27990','WOMEN'),
			// array('51400-TPBR	$ 32.990	YA EN LA PAGINA (OJO','TOPAG',' MLA)'),
			array('53914-CCOR','37990','29990','MEN'),
			array('68031-BLK','39990','29990','MEN'),
			array('68031-CHAR','42990','29990','MEN'),
			array('68033-GYCC','39990','27990','MEN'),
			array('68113-TAN','42990','27990','MEN'),
			array('80878L-NPLM','21990','17990','GIRLS'),
			// array('82217L-GYPK	$ 17.990	EN','LECIÓ','01 II'),
			array('82819L-BKMT','23990','17990','GIRLS'),
			array('82819L-WMLT','23990','17990','GIRLS'),
			array('82836L-BKMT','21990','17990','GIRLS'),
			array('86277L-PKLV','12990',' 9990','GIRLS'),
			array('86293L-BKLD','19990','15990','GIRLS'),
			array('86453N-HTP','14990',' 9990','GIRLS'),
			array('86457L-BLU','19990','15990','GIRLS'),
			array('86457L-RED','19990','15990','GIRLS'),
			array('86532L-NPNK','19990','15990','GIRLS'),
			array('86645L-TURQ','12990',' 9990','GIRLS'),
			array('93800L-OFWT','23990','17990','BOYS'),
			array('95365L-BSLB','25990','19990','BOYS'),
			array('95379L-BKRY','25990','19990','BOYS'),
			array('95556L-BKSL','27990','24990','BOYS'),
			array('95596L-BBLM','25990','19990','BOYS'),
			array('95792L-BKYR','23990','17990','BOYS'),
		);
		$this->loadModel('Producto');
		$codigos = $this->Producto->find('list', array(
			'fields' => array(
				'codigo_completo',
				'id'
			)
		));
		foreach ($productos as $data)
		{
			$codigo = trim(str_replace(array(' ','-'), '', $data[0]));
			if (isset($codigos[$codigo]))
			{
				$categoria = 1;
				if ($data[3] == 'GIRLS')
					$categoria = 4;
				elseif ($data[3] == 'BOYS')
					$categoria = 3;
				elseif ($data[3] == 'MEN')
					$categoria = 2;
				$update = array(
					'id' => $codigos[$codigo],
					'precio' => trim($data[1]),
					'precio_oferta' => trim($data[2]),
					'oferta' => 1,
					'outlet' => 1,
					'activo' => 1,
					'categoria_id' => $categoria
				);
				// $this->Producto->save($update);
			}
			
		}
		prx('fin');
	}

	function admin_cargar_fichas()
	{

	}

	function admin_convertir_ficha()
	{
		Configure::write('debug',1);
		if (empty($this->data))
		{
			$this->redirect($this->referer());
		}
		if (! isset($this->data['Administrador']['archivo']['tmp_name']))
		{
			$this->Session->setFlash(__('Debe ingresar un archivo', true));
			$this->redirect(array('action' => 'cargar_fichas'));
		}
		if (! file_exists($this->data['Administrador']['archivo']['tmp_name']))
		{
			$this->Session->setFlash(__('Debe ingresar un archivo', true));
			$this->redirect(array('action' => 'cargar_fichas'));
		}
		// BENCHMARKING
		set_time_limit(0);

		$archivo = $this->data['Administrador']['archivo']['tmp_name'];

		if ( ! $archivo )
			return false;

		// MAPEO DE CAMPOS
		$mapeo	 		= array('modelo', 'nombre', 'descripcion','detalle' ,'ficha');
		$registros = $fichas = $save = $omitidos = array();
		$linea = $cont = 0;
		$separador = ';';
		if ( ( $handle = fopen($archivo, 'r') ) !== FALSE )
		{
			while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
			{
				//prx($datos);
				if (++$linea <= 1)
					continue;
				$registro = array();
				foreach ( $datos as $index => $valor )
				{
					if ( ! isset($mapeo[$index]) )
						continue;
					$registro[$mapeo[$index]]	= trim($valor);
				}
				if (! $registro)
					continue;
				array_push($registros, $registro);
			}
		}

		if (! $registros)
		{
			$this->Session->setFlash(__('No se encontraron registros en el archivo.', true));
			$this->redirect(array('action' => 'cargar_fichas'));
		}
		$ficha = array(
			'modelo' => '',
			'descripcion' => '',
			'ficha' => ''
		);
		foreach ($registros as $registro)
		{
			/**
			*	SI viene el modelo, verifica si ya se leyo un modelo previamente con lo cual se asume lectura de ficha para un producto
			*	agrega al listado de fichas la ficha nueva y reinicia la ficha a generar
			*/
			if (trim($registro['modelo']))
			{
				if ($ficha['modelo']) // verifica si se tiene un modelo
				{
					$fichas[$ficha['modelo']] = $ficha;	//	agrega a fichas
				}
				// reinicia ficha
				$ficha = array(
					'modelo' => trim($registro['modelo']),
					'descripcion' => '',
					'ficha' => '',
					'detalle' => ''
				);
			}
			if (trim($registro['nombre']))
			{
				if ($texto = strtolower(Inflector::slug(utf8_encode(trim($registro['nombre'])))))
				{
					if (strlen($texto) >= 5)
					{
						$ficha['descripcion'].='<b>'.trim($registro['nombre']).'</b><br>';
					}
				}
			}
			if (trim($registro['descripcion']))
			{
				if ($texto = strtolower(Inflector::slug(utf8_encode(trim($registro['descripcion'])))))
				{
					if (strlen($texto) >= 5)
					{
						$ficha['descripcion'].='<p>'.utf8_encode(trim($registro['descripcion'])).'</p>';	
					}
				}
			}
				if (trim($registro['detalle']))
			{
				if ($texto = strtolower(Inflector::slug(utf8_encode(trim($registro['detalle'])))))
				{
					if (strlen($texto) >= 5)
					{
						$ficha['ficha'].='<h4>Descripcion</h4>'.str_replace(array(':'),'',utf8_encode(trim($registro['detalle'])));
						
					
					}
					else
					{
						array_push($omitidos,$ficha['modelo']);
					}
				}
				else
				{
					array_push($omitidos,$ficha['modelo']);
				}
			}
			if (trim($registro['ficha']))
			{
				if ($texto = strtolower(Inflector::slug(utf8_encode(trim($registro['ficha'])))))
				{
					if (strlen($texto) >= 5)
					{
						$ficha['ficha'].='<h4>Construccion</h4>'.str_replace(array(':'),'',utf8_encode(trim($registro['ficha'])));

					}
					else
					{
						array_push($omitidos,$ficha['modelo']);
					}
				}
				else
				{
					array_push($omitidos,$ficha['modelo']);
				}
			}
		}

		// agregar ultima ficha a listado de fichas
		if (isset($ficha['modelo']) && $ficha['modelo']) // verifica si se tiene un modelo
		{
			$fichas[$ficha['modelo']] = $ficha;	//	agrega a fichas
		}
		if ($fichas)
		{
			$this->loadModel('Producto');
			foreach ($fichas as $ficha)
			{
				if (! $ficha['modelo'])
				{
					continue;
				}
				$options = array(
					'conditions' => array(
						'OR' => array(
							array('Producto.codigo' => $ficha['modelo']),
							array('Producto.codigo' => $ficha['modelo'].'L'),
							array('Producto.codigo' => $ficha['modelo'].'N')
						)
					),
					'fields' => array(
						'Producto.id',
						'Producto.id'
					)
				);
				if (! $productos = $this->Producto->find('list',$options))
				{
					array_push($omitidos,$ficha['modelo']);
					continue;
				}
				if ($ficha['descripcion'] || $ficha['ficha'])
				{
					foreach ($productos as $producto)
					{
						$save[$producto] = array('id' => $producto);
						if ($ficha['descripcion'])
						{
							$save[$producto]['descripcion'] = $ficha['descripcion'];
						}
						if ($ficha['ficha'])
						{
							$save[$producto]['ficha'] = $ficha['ficha'];
						}
					}
				}
			}
			if ($save)
			{
				$folder = str_replace('controllers', '', dirname(__FILE__)) .'webroot'.DS.'img'.DS.'cargas_andain'.DS.'fichas'.DS.Inflector::slug(date('Y-m-d H:i:s')).DS;
				@mkdir($folder, 0755, true);
				copy($this->data['Administrador']['archivo']['tmp_name'], $folder.'file.csv');

				$fp = fopen($folder."bkp.csv","w");
				fwrite($fp,'Id|Name|Style|Cod|Slug|descripcion|ficha'.PHP_EOL);

				$options = array(
					'conditions' => array(
						'Producto.id' => array()
					),
					'fields' => array(
						'Producto.id',
						'Producto.nombre',
						'Producto.codigo',
						'Producto.codigo_completo',
						'Producto.slug',
						'Producto.descripcion',
						'Producto.ficha'
					),
					'recursive' => -1
				);
				foreach ($save as $producto)
				{
					array_push($options['conditions']['Producto.id'],$producto['id']);
				}
				
				if ($productos = $this->Producto->find('all',$options))
				{
					foreach ($productos as $producto)
					{
						fwrite($fp,utf8_decode($producto['Producto']['id'].'|'.$producto['Producto']['nombre'].'|'.$producto['Producto']['codigo'].'|'.$producto['Producto']['codigo_completo'].'|'.$producto['Producto']['slug'].'|'.$producto['Producto']['descripcion'].'|'.$producto['Producto']['ficha'].PHP_EOL));
					}
				}
				fclose($fp);
				
				// guardar
				if ($this->Producto->saveAll($save))
				{
					$cont+=count($save);
				}
				$fp = fopen($folder."final.csv","w");
				fwrite($fp,'Id|Name|Style|Cod|Slug|descripcion|ficha'.PHP_EOL);
				if ($productos = $this->Producto->find('all',$options))
				{
					foreach ($productos as $producto)
					{
						fwrite($fp,utf8_decode($producto['Producto']['id'].'|'.$producto['Producto']['nombre'].'|'.$producto['Producto']['codigo'].'|'.$producto['Producto']['codigo_completo'].'|'.$producto['Producto']['slug'].'|'.$producto['Producto']['descripcion'].'|'.$producto['Producto']['ficha'].PHP_EOL));
					}
				}
				fclose($fp);
			}
		}
		$this->Session->setFlash(__('Se actualizaron las fichas de'.$cont.' productos.', true));
		$this->redirect(array('action' => 'cargar_fichas'));
	}

	function test_mail()
	{
		set_time_limit(0);
		$time = strtotime(date('Y-m-d H:i:s'));
		$stats = array(
			'registro' => array(
				'activar' => false,
				'email' => 'no'
			),
			'recuperar' => array(
				'activar' => false,
				'email' => 'no'
			),
			'despacho' => array(
				'activar' => false,
				'email' => 'no'
			),
			'compra' => array(
				'activar' => true,
				'email' => 'no'
			),
			'compra2' => array(
				'activar' => false,
				'email' => 'no'
			),
			'log_tbk' => array(
				'activar' => false,
				'email' => 'no'	
			)
		);

		$this->params['form']['id'] = $id = 51259;

		$options = array(
			'conditions' => array(
				'Compra.id' => $id
			),
			'fields' => array(
				'Compra.id',
				'Compra.boleta',
				'Compra.picking_number',
				'Compra.cod_despacho',
				'Compra.numId',
				'Compra.rural',
				'Compra.direccion_rural',
				'Compra.local',
				'Compra.despacho_id',
				'Compra.usuario_id',
				'Compra.subtotal',
				'Compra.iva',
				'Compra.neto',
				'Compra.descuento',
				'Compra.total',
				'Compra.valor_despacho',
				'Compra.pago_id',
				'Compra.estado',
				'Compra.fecha_enviado',
				'Compra.cod_despacho',
				'Compra.created'
			),
			'contain' => array(
				'Despacho' => array(
					'fields' => array(
						'Despacho.id',
						'Despacho.usuario_id',
						'Despacho.direccion_id',
						'Despacho.fecha_despacho',
						'Despacho.rural'
					),
					'Direccion' => array(
						'fields' => array(
							'Direccion.id',
							'Direccion.usuario_id',
							'Direccion.calle',
							'Direccion.numero',
							'Direccion.depto',
							'Direccion.otras_indicaciones',
							'Direccion.comuna_id',
							'Direccion.region_id',
							'Direccion.codigo_postal',
							'Direccion.telefono',
							'Direccion.celular',
							'Direccion.nombre',
						),
						'Comuna' => array(
							'fields' => array(
								'id',
								'nombre',
								'region_id'
							),
							'Region.nombre'
						),
						'Region' => array(
							'fields' => array(
								'Region.id',
								'Region.nombre'
							)
						)
					)
				),
				'Usuario' => array(
					'fields' => array(
						'Usuario.id',
						'Usuario.nombre',
						'Usuario.apellido_paterno',
						'Usuario.apellido_materno',
						'Usuario.sexo_id',
						'Usuario.rut',
						'Usuario.fecha_nacimiento',
						'Usuario.email',
						'Usuario.telefono',
					)
				),
				'Pago' => array(
					'fields' => array(
						'Pago.id',
						'Pago.usuario_id',
						'Pago.compra_id',
						'Pago.numeroOrden',
						'Pago.monto',
						'Pago.numeroTarjeta',
						'Pago.fecha',
						'Pago.hora',
						'Pago.estado',
						'Pago.mac',
						'Pago.codAutorizacion',
						'Pago.cuotas',
						'Pago.expiracion',
						'Pago.fechaContable',
						'Pago.tipoPago',
						'Pago.codigo',
						'Pago.respuesta',
						'Pago.created'
					)
				),
				'Producto' => array(
					'fields' => array(
						'Producto.id',
						'Producto.nombre',
						'Producto.categoria_id',
						'Producto.coleccion_id',
						'Producto.foto',
						'Producto.color_id',
						'Producto.codigo',
						'Producto.codigo_completo',
						'Producto.precio',
						'Producto.oferta',
						'Producto.precio_oferta',
						'Producto.slug',
						'Producto.colores',
					),
					'Color' => array(
						'fields' => array(
							'id',
							'nombre',
							'codigo',
						)
					)
				)
			)
		);

		$this->loadModel('Compra');
		if ( $compra = $this->Compra->find('first',$options) )
		{
			pr($compra);
			$clave = "123456";
			$this->data['Usuario'] = $usuario['Usuario'] = $compra['Usuario'];
			$this->data['Usuario']['repetir_clave'] = $compra['Compra']['cod_despacho'] = $clave;
			$compra['Compra']['fecha_enviado'] = date('d-m-Y');

			$productos = array();
			foreach ($compra['Producto'] as $producto)
			{
				$data['Producto'] = $producto;
				unset($data['Producto']['ProductosCompra']);
				$data['Producto']['precio'] = $data['Producto']['precio_oferta'] = $producto['ProductosCompra']['valor'];
				$data['Producto']['foto'] = array(
					'path' => 'Producto/'.$producto['id'].'/'.$producto['foto'],
					'mini' => 'Producto/'.$producto['id'].'/mini_'.$producto['foto']
				);
				$data['ProductosCompra'] = $producto['ProductosCompra'];
				array_push($productos, $data);
			}

			$this->set(compact('compra'));
			//EMAIL
			$this->Email->smtpOptions = array(
				'port' => '25',
				'timeout' => '30',
				'auth' => true,
				'host' => 'skechers-chile.cl',
				'username' => 'noreply@skechers-chile.cl',
				'password' => 'andainandain'
			);
			// DATOS DESTINATARIO (CLIENTE)
			//$this->Email->to = 'ehenriquez@andain.cl';
			$this->Email->to = 'cherrera@skechers.com';
			$this->Email->bcc = array('ehenriquez@andain.cl');
			$this->Email->subject = '[Skechers - Tienda en linea] Despacho Enviado #' . $compra['Compra']['id'] . ' '.$time;
			$this->Email->from = 'Skechers <ventas@skechers-chile.cl>';
			$this->Email->sendAs = 'html';
			$this->Email->template	= 'despacho';
			$this->Email->delivery = 'smtp';
			if ( isset($stats['despacho']['activar']) && $stats['despacho']['activar'] && $this->Email->send() )
				$stats['despacho']['email'] = 'si';

			$this->set(compact('productos', 'compra', 'usuario'));
			$this->Email->subject = '[Skechers - Tienda en linea] Compra #' . $compra['Compra']['id'] . ' '.$time;
			$this->Email->template	= 'compra2';
			if ( isset($stats['compra2']['activar']) && $stats['compra2']['activar'] && $this->Email->send() )
				$stats['compra2']['email'] = 'si';

			$mensaje = '<h3>Error en transaccion Numero xxx</h3><p>Se detecto un error en el log tbk asociado a una compra en el sistema.</p>';
			$mensaje.= '<p>La transacción se encuentra rechazada y el sitio Web ha enviado al servidor Webpay el mensaje de acuse de recibo con el texto “ACEPTADO”.</p>';
			$mensaje.= '<p>El monto de la transacción no se encuentra cargado en la cuenta del cliente debido a que la transacción fue rechazada por el Banco Emisor o la marca de la tarjeta.</p>';
			$mensaje.='<p>Como prevensión el estado de la compra a sido actualizado a PENDIENTE.</p>';
			$mensaje.='<p>El cliente no ha sido notificado aun, se recomienda gestionar este caso a la brevedad ya que el correo de notificacion debe ser enviado dentro de un plazo de 24 horas.</p>';

			$this->set(compact('mensaje'));
			$this->Email->subject = '[Skechers - Tienda en linea] Notificacion Proceso Log' . ' '.$time;
			$this->Email->template	= 'mensaje';
			if ( isset($stats['log_tbk']['activar']) && $stats['log_tbk']['activar'] && $this->Email->send() )
				$stats['log_tbk']['email'] = 'si';

			$this->Email->subject = '[Skechers - Tienda en linea] Registro' . ' '.$time;
			$this->Email->template	= 'registro';
			if ( isset($stats['registro']['activar']) && $stats['registro']['activar'] && $this->Email->send() )
				$stats['registro']['email'] = 'si';

			$this->set(compact('usuario', 'clave'));
			$this->Email->subject = '[Skechers - Tienda en linea] Recuperacion de Contraseña' . ' '.$time;
			$this->Email->template	= 'recuperar';
			if ( isset($stats['recuperar']['activar']) && $stats['recuperar']['activar'] && $this->Email->send() )
				$stats['recuperar']['email'] = 'si';

			// color: #0080c0
			$mensaje = '
			<h2 style="color:#000;">Estimado(a) '.$compra['Usuario']['nombre'].'</h2>
			<h3 style="color:#000;font-weight:normal;">Tu número de orden es: <b>'.$compra['Compra']['id'].'</b></h3>
			<hr>
			<p style="color:#4d4d4d;">
				Estamos verificando tu información de pago. En caso de existir cualquier problema o dificultad relativa al medio de pago utilizado (u otra circunstancia grave que impida procesar la orden de compra), la orden deberá ser anulada.
			</p>
			<p style="color:#4d4d4d;">
				Tu orden será despachada en un máximo de 7 días hábiles.
			</p>
			<p style="color:#4d4d4d;">
				Tu Boleta se emitirá de forma electrónica y será enviada al mail que has registrado.  Conserva tu boleta para efectos de hacer valer tus derechos de garantía legal.  
			</p>
			<hr>
			<table width="100%">
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>REALIZADA EL:</b></td>
					<td style="color:#4d4d4d;font-size:small;">'.date('d-m-Y',strtotime($compra['Compra']['created'])).'</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>NOMBRE:</b></td>
					<td style="color:#4d4d4d;font-size:small;">'.$compra['Usuario']['nombre'].' '.$compra['Usuario']['apellido_paterno'].' '.$compra['Usuario']['apellido_materno'].'</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>RUT:</b></td>
					<td style="color:#4d4d4d;font-size:small;">'.$compra['Usuario']['rut'].'</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>NÚMERO DE TARJETA:</b></td>
					<td style="color:#4d4d4d;font-size:small;">XXXX- XXXX- XXXX - '.$compra['Pago'][0]['numeroTarjeta'].'</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>DIRECCIÓN DE DESPACHO:</b></td>
					<td style="color:#4d4d4d;font-size:small;">'.$compra['Despacho']['Direccion']['calle'].(($compra['Despacho']['Direccion']['numero'])?' #'.$compra['Despacho']['Direccion']['numero']:'').(($compra['Despacho']['Direccion']['depto'])?', '.$compra['Despacho']['Direccion']['depto']:'').(($compra['Despacho']['Direccion']['Comuna']['nombre'])?' - '.$compra['Despacho']['Direccion']['Comuna']['nombre']:'').'</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>FONO CONTACTO:</b></td>
					<td style="color:#4d4d4d;font-size:small;">';
			if ($compra['Despacho']['Direccion']['telefono'])
			{
				$mensaje.=$compra['Despacho']['Direccion']['telefono'];
				if ($compra['Despacho']['Direccion']['celular'])
					$mensaje.=' | '.$compra['Despacho']['Direccion']['celular'];
			}
			elseif ($compra['Despacho']['Direccion']['celular'])
			{
				$mensaje.=$compra['Despacho']['Direccion']['celular'];
			}
			$mensaje.='</td>
				</tr>
				<tr>
					<td style="color:#4d4d4d;font-size:x-small;"><b>EMAIL:</b></td>
					<td style="color:#4d4d4d;font-size:small;">'.$compra['Usuario']['email'].'</td>
				</tr>
			</table>
			<br>
			<table width="100%" style="border: 1px solid #0080c0;">
				<tr>
					<th colspan="6" style="color:#000;text-align:left;">DATOS DE COMPRA</th>
				</tr>';
			if ($compra['Producto'])
			{
				foreach ($compra['Producto'] as $producto)
				{
					$mensaje.='
					<tr>
						<td width="15%" style="color:#000;text-align:left;font-size:small;"><img src="http://www.skechers.cl/img/Producto/'.$producto['id'].'/mini_'.$producto['foto'].'" alt="" /></td>
						<td width="25%" style="color:#000;text-align:left;font-size:x-small;"><b>'.$producto['nombre'].'</b><br><i>'.$producto['codigo'].'</i></td>
						<td width="20%" style="color:#000;text-align:left;font-size:x-small;">'.$producto['Color']['nombre'].'<br><i>'.$producto['Color']['codigo'].'</i></td>
						<td width="12%" style="color:#000;text-align:left;font-size:x-small;">talla: '.$producto['ProductosCompra']['talla'].'</td>
						<td width="13%" style="color:#000;text-align:left;font-size:x-small;">cantidad: 1</td>
						<td width="15%" style="color:#000;text-align:right;font-size:small;">'.number_format($producto['ProductosCompra']['valor'],0,',','.').'</td>
					</tr>';
				}
			}
			$mensaje.='
				<tr>
					<td colspan="6"><hr></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4" style="color:#0080c0;text-align:left;font-size:x-small;">SUBTOTAL</td>
					<td style="color:#0080c0;text-align:right;font-size:small;">'.number_format($compra['Compra']['subtotal'],0,',','.').'</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4" style="color:#0080c0;text-align:left;font-size:x-small;">IVA (19%)</td>
					<td style="color:#0080c0;text-align:right;font-size:small;">'.number_format($compra['Compra']['iva'],0,',','.').'</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4" style="color:#0080c0;text-align:left;font-size:x-small;">DESCUENTO</td>
					<td style="color:#0080c0;text-align:right;font-size:small;">'.number_format($compra['Compra']['descuento'],0,',','.').'</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4" style="color:#0080c0;text-align:left;font-size:x-small;">DESPACHO A DOMICILIO</td>
					<td style="color:#0080c0;text-align:right;font-size:small;">'.number_format($compra['Compra']['valor_despacho'],0,',','.').'</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4" style="color:#0080c0;text-align:left;font-size:x-small;"><b>TOTAL</b></td>
					<td style="color:#0080c0;text-align:right;font-size:small;"><b>'.number_format($compra['Compra']['total'],0,',','.').'</b></td>
				</tr>
			</table>
			';
			$this->set(compact('mensaje'));

			$this->Email->subject = '[Skechers - Tienda en linea] Compra #' . $compra['Compra']['id'] . ' '.$time;
			$this->Email->template	= 'mensaje';
			if ( isset($stats['compra']['activar']) && $stats['compra']['activar'] && $this->Email->send() )
				$stats['compra']['email'] = 'si';
			// email blast
		}

		prx($stats);
	}

	function admin_bajar_stock()
	{
		if (! isset($this->params['url']))
			die('Parametros invalidos');
		if (! $this->params['url'])
			die('Parametros invalidos');
		if (! isset($this->params['url']['style']))
			die('Style invalido');
		if (! $this->params['url']['style'])
			die('Style invalido');
		if (! isset($this->params['url']['color']))
			die('Style invalido');
		if (! $this->params['url']['color'])
			die('Style invalido');
		if (! isset($this->params['url']['talla']))
			die('Style invalido');
		if (! $this->params['url']['talla'])
			die('Style invalido');
		Configure::write('debug',1);
		$listado = array(
			array('64582', 'TAN', '43'),
			array('64582', 'TAN', '41.5'),
			array('10374N', 'PRPK', '24'),
			array('80240L', 'NVHP', '27'),
			array('80868N', 'BLMT', '20'),
			array('80868N', 'BLMT', '23'),
			array('81068N', 'GYMT', '24'),
			array('81852L', 'MLT', '28'),
			array('81875L', 'BKHP', '28'),
			array('81875L', 'HPMT', '28'),
			array('81875L', 'HPMT', '35'),
			array('53995', 'NVLM', '41.5'),
			array('90460L', 'BKSL', '33'),
			array('90475L', 'BLBK', '29'),
			array('68123', 'NVY', '40'),
			array('13820', 'NVY', '36.5'),
			array('12062', 'NVY', '36.5'),
			array('12100', 'BKW', '38'),
			array('51258', 'BKW', '40'),
			array('51292', 'BRN', '40'),
			array('51292', 'BRN', '42'),
			array('51378', 'TPOL', '42'),
			array('10474N', 'MLT', '23'),
			array('10506L', 'GYTQ', '26'),
			array('10506L', 'GYTQ', '28'),
		);
		$this->loadModel('Producto');
		foreach ($listado as $stock)
		{
			$options = array(
				'conditions' => array(
					'Producto.codigo' => $stock[0],
					'Color.codigo' => $stock[1],
					'Stock.talla' => $stock[2]
				),
				'fields' => array(
					'Producto.id',
					'Producto.color_id',
					'Producto.codigo',
					'Producto.codigo_completo',
					'Color.id',
					'Color.codigo',
					'Stock.id',
					'Stock.producto_id',
					'Stock.talla',
				),
				'joins' => array(
					array(
						'table' => 'sitio_colores',
						'alias' => 'Color',
						'type' => 'INNER',
						'conditions' => array(
							'Color.id = Producto.color_id'
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
				)
			);

			if ($producto = $this->Producto->find('first', $options))
			{
				if ( isset($this->params['url']['delete']) && $this->params['url']['delete'] == 1 )
				{
					if ($this->Producto->Stock->delete( $producto['Stock']['id'] ))
					{
						$options = array(
							'conditions' => array(
								'Talla.producto_id' => $producto['Producto']['id'],
								'Talla.talla' => $stock[2]
							),
							'fields' => array(
								'Talla.id'
							)
						);
						if ( $talla = $this->Producto->Talla->find('first',$options) )
						{
							$this->Producto->Talla->delete( $talla['Talla']['id'] );
						}
						pr('Stock eliminado... '.$producto['Stock']['id']);
					}
				}
				else
				{
					
					pr($producto);
				}
			}
			else
			{
				pr('No encontrado: '.$stock[0].$stock[1].' '.$stock[2]);
			}
		}
		die('FIN');
	}

	function admin_remover_despacho()
	{
		$this->loadModel('Compra');
		$options = array(
			'conditions' => array(
				'Compra.estado' => 1,
				'Compra.cod_despacho' => 0,
				'Compra.mail_condirmacion' => 1
			),
			'fields' => array(
				'Compra.id',
				'Compra.id'
			)
		);
		$compras = $this->Compra->find('list', $options);
	}

	function descuento_padre()
	{
		die(false);
		$list = array();
		$this->loadModel('Producto');
		$excluidos = $this->Producto->find('list', array(
			'conditions' => array(
				'Producto.categoria_id' => 2,
				'OR' => array(
					'Producto.grupo LIKE' => '%[golf]%'
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.id',
			)
		));
		if ($excluidos)
		{
			foreach ($excluidos as $producto_id)
			{
				$save = array(
					'id' => $producto_id,
					'oferta' => 0
				);
			}
		}

		$outlet = $this->Producto->find('list', array(
			'conditions' => array(
				'Producto.categoria_id' => 2,
				'Producto.outlet' => 1,
			),
			'fields' => array(
				'Producto.id',
				'Producto.id',
			)
		));
		if ($outlet)
		{
			$excluidos = array_merge($excluidos, $outlet);
		}

		$options = array(
			'conditions' => array(
				'Producto.categoria_id' => 2,
				'NOT' => array(
					array('Producto.id' => $excluidos),
				)
			),
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				'Producto.codigo_completo',
				'Producto.precio',
				'Producto.oferta',
				'Producto.precio_oferta',
				'Producto.outlet',
				'Producto.grupo'
			)
		);
		$productos = $this->Producto->find('all',$options);
		foreach ( $productos as $producto )
		{
			$save = array(
				'id' => $producto['Producto']['id'],
				'oferta' => 1,
				'precio_oferta' => $this->aplicar_descuento($producto['Producto']['precio'], 20)
			);
			array_push($list, $producto['Producto']['id']);
			$this->Producto->save($save);
		}
		prx(implode(', ',$list));
		prx('fin');
	}

	private function aplicar_descuento($precio = 0, $descuento = 0)
	{
		if (! $precio)
			return 0;
		if (! $descuento)
			return $precio;
		$valores = array(
			'descuento' => ($precio*20) / 100,
			'precio' => $precio
		);
		if ($valores['descuento'] <= $valores['precio'])
		{
			$precio = $valores['precio'] - $valores['descuento'];
			$precio = ($precio / 10);
			$precio = (int)$precio * 10;
		}
		return $precio;
	}

	private function bajar_xml($tipo = '')
	{
		$path = DS."home".DS."skechers".DS."public_html".DS."webroot".DS."xml".DS.date('Y_m').DS;
		if (! is_dir($path))
			@mkdir($path, 0755, true);
		$fileName = $path."archivo.csv";
		if ($tipo == 'inclusive')
		{
			$fileName = $path."archivo_inclusive.csv";
		}
		if (file_exists($fileName))
			return $fileName;
		$readName = "https://www.skechers.com/en-us/catalog";
		if ($tipo == 'inclusive')
		{
			$readName = "https://www.skechers.com/en-us/catalog/inclusive";
		}
		$xml = simplexml_load_file($readName);
		$productos ;
		$i=1;
		$fp = fopen($fileName,"w");
		foreach($xml->style as $modelo)
		{
		    $producto = array();
			$producto["codigo"] = (string)$modelo->attributes()->code;
			$producto["division"] = (string)$modelo->attributes()->division;
			$producto["gender"] = (string)$modelo->attributes()->gender;
			$producto["nombre"] = (string)$modelo->attributes()->name;
			$producto["desc_corta"] = (string) $modelo->{'short-description'};
			$producto["desc_larga"] = '';
			if ($modelo->{'long-description'})
				$producto["desc_larga"].= 'details:'.preg_replace("/\r\n+|\r+|\n+|\t+/i", "", (string) $modelo->{'long-description'} );
			if ($modelo->{'construction'})
				$producto["desc_larga"].= 'construction:'.preg_replace("/\r\n+|\r+|\n+|\t+/i", "", (string) $modelo->{'construction'} );
			
			$producto["meta"]	=(string) $modelo->{'meta'};
			$producto["division"] = (string)$modelo->attributes()->division;
			foreach ($modelo->product as $zapatilla) 
			{
				$producto["precio"] = (string)$zapatilla->sku->attributes()->price;
				$producto["color"] = (string)$zapatilla->attributes()->{'primary-color'};
				$producto["color_cod"] = (string)$zapatilla->attributes()->color;
				fwrite($fp,implode('|', $producto).PHP_EOL);
			}
		}
		fclose($fp);
		return $fileName;
	}

	function generar_excel_xml($tipo = '')
	{
		Configure::write('debug',0);
		if (! $this->Session->check('Auth.Administrador.id') )
			die(false);
		
		set_time_limit(0);
		$archivo = $this->bajar_xml($tipo);
		$separador='|';
		$mapeo = array(
			0 => 'codigo',
			'division',
			'gender',
			'nombre',
			'desc_corta',
			'desc_larga',
			'meta',
			'precio',
			'color',
			'color_cod'
		);
		$registros = array();
		// iniciar lectura del archivo
		if ( ( $handle = fopen($archivo, 'r') ) !== FALSE )
		{
			while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
			{
				$registro = array();
				foreach ( $datos as $index => $valor )
				{
					if ( ! isset($mapeo[$index]) )
						continue;
					$registro[$mapeo[$index]] = trim($valor);
				}
				array_push($registros, $registro);
			}
		}

		$fileName = "productos_".date('Y-m').'.xls';
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Disposition: attachment; filename=".$fileName);

		echo '
		<table border="1">
			<tr>
				<td>Modelo</td>
				<td>Color</td>
				<td>Codigo</td>
				<td>Nombre</td>
				<td>Sexo</td>
				<td>Division</td>
				<td>Precio</td>
				<td>Desc. Corta</td>
				<td>Desc. Larga</td>
				<td> Meta </td>
			</tr>';
		foreach($registros as $registro)
		{
			echo '
			<tr>
				<td>'.$registro["codigo"].'</td>
				<td>'.$registro["color_cod"].'</td>
				<td>'.$registro["codigo"].$registro["color_cod"].'</td>
				<td>'.$registro["nombre"].'</td>
				<td>'.$registro["gender"].'</td>
				<td>'.$registro["division"].'</td>
				<td>'.$registro["precio"].'</td>
				<td>'.$registro["desc_corta"].'</td>
				<td>'.$registro["desc_larga"].'</td>
				<td>'.$registro["meta"].'</td>
			</tr>';
		}
		die('</table>');
	}

	function descargar_imagenes_xml($tipo = '')
	{
		 if (! $this->Session->check('Auth.Administrador.id') )
			die(false);
		Configure::write('debug',0);
		set_time_limit(0);
		$archivo = $this->bajar_xml($tipo);

		$separador='|';
		$mapeo = array(
			0 => 'codigo',
			'division',
			'gender',
			'nombre',
			'desc_corta',
			'desc_larga',
			'meta',
			'precio',
			'color',
			'color_cod'
		);
		$requeridos = array(
			'codigo',
			'color_cod'
		);
		$registros = array();
		// iniciar lectura del archivo
		if ( ( $handle = fopen($archivo, 'r') ) !== FALSE )
		{
			while ( ( $datos = fgetcsv($handle, 0, $separador) ) !== FALSE )
			{
				$registro = array();
				foreach ( $datos as $index => $valor )
				{
					if ( ! isset($mapeo[$index]) )
						continue;
					if (! in_array($mapeo[$index], $requeridos))
						continue;
					$registro[$mapeo[$index]] = trim($valor);
				}
				if (! isset($registro['codigo']) )
					continue;
				if (! isset($registro['color_cod']) )
					continue;
				if (! $registro['codigo'])
					continue;
				if (! $registro['color_cod'])
					continue;
				$codigo = $registro['codigo'].$registro['color_cod'];
				if ( isset($registros[ $codigo ]) )
					continue;
				$registros[ $codigo ] = $registro;
			}
		}
		$limit = 10;
		$cont = 0;
		//$url ='http://cdn4.skechers-usa.com/img/productimages/xlarge/';
		$url ='https://d1szvu1sjta0pg.cloudfront.net/img/productimages/xlarge/';
		//$path = DS."home".DS."skechers".DS."public_html".DS."webroot".DS."xml".DS.date('Y_m').DS.'imagenes';
		$path = DS."home".DS."skechers".DS."public_html".DS."webroot".DS."xml".DS.'imagenes';

		if (! is_dir($path))
			@mkdir($path, 0755, true);
		$logName = $path.DS."log.txt";
		if (file_exists( $logName ))
			$logName = $path.DS."log_".str_replace(array('-',':',' '), '', date('Y-m-d H:i:s')).".txt";
		$fp = fopen($logName,"w");
		fwrite($fp,'PATH: '.$path.PHP_EOL.'fecha: '.date('d-m-Y H:i:s').PHP_EOL.'registros: '.count($registros).PHP_EOL);

		$galerias = array(
			'.jpg',
			'_B.jpg',
			'_C.jpg',
			'_D.jpg',
			'_E.jpg',
			'_F.jpg'
		);

		foreach ($registros as $registro)
		{
			++$cont;
			// if ($cont > $limit)
			// {
			// 	break;
			// }
			$style = $registro['codigo']."_".$registro['color_cod'];
			$dir = $path.DS.$style;
			if (! is_dir($dir))
				@mkdir($dir, 0755, true);
			foreach ($galerias as $galeria)
			{
				if (file_exists( $dir."/".$style.$galeria ))
					continue;
				if (! copy($url.$style.$galeria, $dir."/".$style.$galeria))
				{
					if ($galeria == reset($galerias))
					{
						if (file_exists( $dir."/".$style.'.gif' ))
							continue;
						if (! copy($url.$style.'.gif', $dir."/".$style.'.gif'))
						{
							fwrite($fp,'ERROR '.$style.'.gif'.PHP_EOL);
						}
					}
					else
					{
						fwrite($fp,'ERROR '.$style.$galeria.PHP_EOL);
					}
				}
			}
		}
		fwrite($fp,'DESCARGA FINALIZADA'.PHP_EOL.'fecha: '.date('d-m-Y H:i:s').PHP_EOL);
		fclose($fp);
		die('fin');
	}

	function admin_file_manager()
	{

	}

	function generar_xml()
	{
		Configure::write('debug',1);
		$this->loadModel('Producto');
		$options = array(
			'fields' => array(
				'Producto.id',
				'Producto.nombre',
				// 'Producto.categoria_id',
				// 'Producto.coleccion_id',
				// 'Producto.color_id',
				'Producto.codigo',
				'Producto.precio',
				'Producto.oferta',
				'Producto.precio_oferta',
				'Producto.escolar',
				'Producto.outlet',
				'Producto.tipo',
				'Producto.division',
				'Producto.grupo',
				'Categoria.nombre',
				'Coleccion.nombre',
				// 'Color.nombre',
				'Color.codigo'
			),
			'joins' => array(
				array(
					'table' => 'sitio_categorias',
					'alias' => 'Categoria',
					'type' => 'LEFT',
					'conditions' => array(
						'Categoria.id = Producto.categoria_id'
					)
				),
				array(
					'table' => 'sitio_colecciones',
					'alias' => 'Coleccion',
					'type' => 'LEFT',
					'conditions' => array(
						'Coleccion.id = Producto.coleccion_id'
					)
				),
				array(
					'table' => 'sitio_colores',
					'alias' => 'Color',
					'type' => 'LEFT',
					'conditions' => array(
						'Color.id = Producto.color_id'
					)
				)
			),
			'contain' => array(
				'Stock' => array(
					'fields' => array(
						'Stock.id',
						'Stock.talla',
						'Stock.cantidad'
					),
					'order' => array(
						'Stock.talla' => 'ASC'
					)
				)
			)
		);
		$productos = $this->Producto->find('all', $options);
		if (! $productos)
			die(false);
		// prx($productos);
		$xml = new DomDocument('1.0', 'UTF-8');
 
	    $catalogo = $xml->createElement('catalogo');
	    $catalogo = $xml->appendChild($catalogo);

	 	foreach ($productos as $producto)
	 	{
	 		$style = $xml->createElement('producto');
		    $style = $catalogo->appendChild($style);

		    $precio = $producto['Producto']['precio'];
		    if ($producto['Producto']['oferta'] && $producto['Producto']['precio'] > $producto['Producto']['precio_oferta'] && $producto['Producto']['precio_oferta'] > 3000)
		    {
		    	$precio = $producto['Producto']['precio_oferta'];
		    }
		    $producto['Producto']['precio'] = $precio;
		    unset($producto['Producto']['oferta']);
		    unset($producto['Producto']['precio_oferta']);

		    if ($producto['Producto']['grupo'])
		    {
		    	$grupos = explode('][', $producto['Producto']['grupo']);
		    	if (! is_array($grupos))
		    	{
		    		$grupos = array($grupos);
		    	}
		    	$producto['Producto']['grupo'] = array();
		    	foreach ($grupos as $grupo)
		    	{
		    		if (! $grupo)
		    			continue;
		    		if (strlen($grupo) >= 30)
		    			continue;
		    		array_push($producto['Producto']['grupo'], str_replace(array('[', ']'), '', $grupo));
		    	}
		    }

		    $dato = $xml->createElement('id',$producto['Producto']['id']);
		    $dato = $style->appendChild($dato);

		    // $dato = $xml->createElement('nombre',trim($producto['Producto']['nombre']));
		    $dato = $style->appendChild($xml->createElement('nombre'))->appendChild($xml->createTextNode(trim($producto['Producto']['nombre'])));

		    $dato = $xml->createElement('codigo',$producto['Producto']['codigo']);
		    $dato = $style->appendChild($dato);

		    $dato = $xml->createElement('color',$producto['Color']['codigo']);
		    $dato = $style->appendChild($dato);

		    $dato = $xml->createElement('categoria',$producto['Categoria']['nombre']);
		    $dato = $style->appendChild($dato);

		    $dato = $xml->createElement('coleccion',$producto['Coleccion']['nombre']);
		    $dato = $style->appendChild($dato);

		    $dato = $xml->createElement('precio',$producto['Producto']['precio']);
		    $dato = $style->appendChild($dato);

		    $dato = $xml->createElement('escolar', ( ($producto['Producto']['escolar']) ? 'si':'no' ) );
		    $dato = $style->appendChild($dato);

		    $dato = $xml->createElement('outlet', ( ($producto['Producto']['outlet']) ? 'si':'no' ) );
		    $dato = $style->appendChild($dato);

		    if ($producto['Producto']['tipo'])
		    {
		    	$dato = $xml->createElement('tipo',$producto['Producto']['tipo']);
			    $dato = $style->appendChild($dato);
		    }
			   
		    if ($producto['Producto']['division'])
		    {
		    	$dato = $xml->createElement('division',$producto['Producto']['division']);
			    $dato = $style->appendChild($dato);
		    }
		    // estilos
		    if ($producto['Producto']['grupo'])
		    {
		    	$estilos = $xml->createElement('estilos');
			    $estilos = $style->appendChild($estilos);
			    foreach ($producto['Producto']['grupo'] as $grupo)
			    {
			    	$dato = $xml->createElement('nombre',$grupo);
			    	$dato = $estilos->appendChild($dato);
			    }
		    }
		    if ($producto['Stock'])
		    {
		    	$stocks = $xml->createElement('stock');
			    $stocks = $style->appendChild($stocks);
			    foreach ($producto['Stock'] as $stock)
			    {
			    	if (! $stock['cantidad'])
			    		continue;
			    	$dato = $xml->createElement('talla',$stock['talla']);
			    	$dato = $stocks->appendChild($dato);
			    }
		    }
	 	}
		    
	   
	 
	    $xml->formatOutput = true;
	    $el_xml = $xml->saveXML();
	    $xml->save('libros.xml');
	 
	    //Mostramos el XML puro
	    echo "<p><b>El XML ha sido creado.... Mostrando en texto plano:</b></p>".
	         htmlentities($el_xml)."<br/><hr>";
	    die ('fin');
	}
}
?>