<?php
class DescuentosController extends AppController
{
	var $name = 'Descuentos';
	var $components = array('Masivo');
	
	function beforeRender()
	{
		parent::beforeRender();
		//LAYOUT EXCEL
		if ( $this->params['action'] == 'admin_exportar_codigos_validados' )
			$this->layout = 'ajax';
	}

	function admin_masivo_listar()
	{
		$this->loadModel('Archivo');
		$options = array(
			'conditions' => array(
				'Archivo.tipo' => 2
			),
			'fields' => array(
				'Archivo.id',
				'Archivo.nombre',
				'Archivo.tipo',
				'Archivo.flag',
				'Archivo.administrador_id',
				'Archivo.created'
			),
			'contain' => array(
				'Administrador' => array(
					'fields' => array(
						'Administrador.id',
						'Administrador.nombre'
					)
				)
			),
			'order' => array('Archivo.created' => 'DESC'),
			'limit' => 10
		);
		if ($archivos = $this->Archivo->find('all',$archivos))
		{
			foreach ($archivos as $index => $archivo)
			{
				$size = 0;
				if (file_exists("img/{$archivo['Archivo']['nombre']['path']}"))
					$size = round(filesize("img/{$archivo['Archivo']['nombre']['path']}") / 1024 / 1024, 2);
				$archivos[$index]['Archivo']['size'] = $size;
			}
		}
		$this->set(compact('archivos'));
	}

	function admin_masivo_add()
	{
		Configure::write('debug',2);
		$stats = array();
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

			$showrooms = null;
			if (isset($this->data['Showroom']))
			{
				foreach ($this->data['Showroom'] as $index => $showroom)
				{
					if ($index < 1)
						continue;
					if ($showroom['valor'])
					{
						$showrooms.=(($showrooms)?',':'').$showroom['valor'];
					}
				}
			}



			$this->loadModel('Archivo');
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

				$this->Archivo->save(array(
					'Archivo' => array(
						'id' => $id,
						'flag' => 1
					)
				));

				// MAPEO DE CAMPOS
				$mapeo	 		= array(0 => 'codigo', 2=>'correlativo',1=>'responsable');
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
					$escolar = 1;

				foreach($registros as $registro)
				{
					if (! $codigo = trim($registro['codigo']) )
					{
						$stats['errores']++;
						continue;
					}
					if (in_array($codigo, $verificar_codigo))
					{
						$stats['repetidos']++;
						continue;
					}

					$new_descuento = array(
						'Descuento' => array(
							'nombre' => $this->data['Descuento']['nombre'],
							'cantidad' => $this->data['Descuento']['cantidad'],
							'maximo' => $this->data['Descuento']['maximo'],
							'web_tienda' => $this->data['Descuento']['web_tienda'],
                            'super' => $this->data['Descuento']['super'],
							'fecha_caducidad' => $caducidad,
							'codigo' => $codigo,
							'contador' => 0,
							'escolar' => $escolar,
							'tipo' => $this->data['Descuento']['tipo'],
							'responsable' => $registro['responsable'],
							'correlativo' => $registro['correlativo'],
							'descuento' => $this->data['Descuento']['descuento'],
							'showroom' => $showrooms
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
				$stats['memoria']		= (memory_get_peak_usage(true) / 1024 / 1024) . ' MB';
				$stats['tiempo']		= round(microtime(true) - $inicio, 3);
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$options = array(
			'conditions' => array(
				'Categoria.publico' => 1
			),
			'fields' => array(
				'Categoria.id',
				'Categoria.nombre'
			),
			'order' => array('Categoria.nombre' => 'ASC')
		);
		$categorias = $this->Descuento->Categoria->find('list', $options);
		$options = array(
			'conditions' => array(
				'NOT' => array(
					array('Producto.showroom' => null)
				)
			),
			'fields' => array(
				'Producto.showroom',
				'Producto.showroom'
			),
			'order' => array(
				'Producto.showroom' => 'ASC'
			)
		);

		$showrooms = $this->Descuento->Categoria->Producto->find('list',$options);
		$this->set(compact('categorias', 'stats','showrooms'));
	}

	function admin_listar()
	{
		$this->Descuento->recursive = 0;
		$this->set('descuentos', $this->paginate());
	}

	function admin_buscador($buscar = null)
	{
		if (! empty($this->data))
		{
			if (isset($this->data['Descuento']['buscar']) && $this->data['Descuento']['buscar'])
				$this->redirect(array('action' => 'buscador', $this->data['Descuento']['buscar']));
		}
		if (! $buscar)
		{
			$this->Session->setFlash(__('No se encontrarón descuentos.', true));
			$this->redirect(array('action' => 'listar'));
		}

		$this->data['Descuento']['buscar'] = $buscar;
		$this->Usuario->recursive = 0;
		$this->paginate = array(
			'conditions' => array(
				'OR' => array(
					array('Descuento.codigo LIKE' => '%'.$buscar.'%'),
					array('Descuento.nombre LIKE' => '%'.$buscar.'%')
				)
			)
		);
		$this->set('descuentos', $this->paginate());
		$this->render('admin_listar');
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'listar'));
		}
		$this->Descuento->recursive = 1;
		$this->set('descuento', $this->Descuento->read(null, $id));
	}

	function admin_add()
	{

		if ( ! empty($this->data) )
		{
			// limpia los check no seleccionados
			foreach( $this->data['Categoria'] as $index => $categoria )
			{
				if( $categoria['categoria_id'] )
				{
					$this->data['Categoria'][$index]['categoria_id'] = $index;
				}
				else
				{
					unset( $this->data['Categoria'][$index] );
				}
			}
			if (! $this->data['Categoria'])
			{
				unset( $this->data['Categoria'] );
			}
			$showrooms = null;
			if (isset($this->data['Showroom']))
			{
				foreach ($this->data['Showroom'] as $index => $showroom)
				{
					if ($index < 1)
						continue;
					if ($showroom['valor'])
					{
						$showrooms.=(($showrooms)?',':'').$showroom['valor'];
					}
				}
			}

			$this->Descuento->create();
			$save = array(
				'Descuento' => $this->data['Descuento'],
				'Categoria' => $this->data['Categoria']
			);
			$save['Descuento']['showroom'] = $showrooms;
			if ( $this->Descuento->saveAll($save) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'listar'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$options = array(
			'conditions' => array(
				'Categoria.publico' => 1
			),
			'order' => array(
				'Categoria.nombre' => 'ASC'
			)
		);
		$categorias = $this->Descuento->Categoria->find('list',$options);
		// opciones y atributos radio
		$opciones_radio = array('DIN' => 'Dinero', 'POR' => 'Porcentaje');
		$attr_radio = array('label' => array('class' => 'radio'), 'legend'=>false, 'value' => 'DIN');
		$options = array(
			'conditions' => array(
				'NOT' => array(
					array('Producto.showroom' => null)
				)
			),
			'fields' => array(
				'Producto.showroom',
				'Producto.showroom'
			),
			'order' => array(
				'Producto.showroom' => 'ASC'
			)
		);

		$showrooms = $this->Descuento->Categoria->Producto->find('list',$options);

		$this->set(compact('categorias', 'opciones_radio', 'attr_radio','showrooms'));
	}

	function admin_edit($id = null)
	{
		prx('aca'); 
		if ( ! $id && empty($this->data) )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'listar'));
		}
		
		if ( ! empty($this->data) )
		{
			// limpia los check no seleccionados
			foreach( $this->data['Categoria'] as $index => $categoria )
			{
				if( $categoria['categoria_id'] )
				{
					$this->data['Categoria'][$index]['categoria_id'] = $index;
				}
				else
				{
					unset( $this->data['Categoria'][$index] );
				}
			}
			if (! $this->data['Categoria'])
			{
				unset( $this->data['Categoria'] );
			}
			$options = array('CategoriasDescuento.descuento_id' => $this->data['Descuento']['id']);
			$this->Descuento->CategoriasDescuento->deleteAll($options);

			$showrooms = null;
			if (isset($this->data['Showroom']))
			{
				foreach ($this->data['Showroom'] as $index => $showroom)
				{
					if ($index < 1)
						continue;
					if ($showroom['valor'])
					{
						$showrooms.=(($showrooms)?',':'').$showroom['valor'];
					}
				}
			}

			$save = array(
				'Descuento' => $this->data['Descuento'],
				'Categoria' => $this->data['Categoria']
			);
			$save['Descuento']['showroom'] = $showrooms;

			if ( $this->Descuento->saveAll($save) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'listar'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		if ( empty($this->data) )
		{
			$options = array(
				'conditions' => array('Descuento.id' => $id),
				'recursive' => -1
			);
			if ($this->data = $this->Descuento->find('first',$options))
			{
				$options = array(
					'conditions' => array(
						'CategoriasDescuento.descuento_id' => $id
					),
					'fields' => array(
						'CategoriasDescuento.categoria_id',
						'CategoriasDescuento.categoria_id'
					)
				);
				if ($categorias_descuento = $this->Descuento->CategoriasDescuento->find('list',$options))
				{
					foreach ( $categorias_descuento as $categoria_id )
					{
						$this->data['Categoria'][$categoria_id]['categoria_id'] = 1;
					}
				}
			}
		}

		$categorias = $this->Descuento->Categoria->find('list', array(
			'conditions' => array('Categoria.publico' => 1),
			'order' => array('Categoria.nombre' => 'ASC')
		));

		// opciones y atributos radio
		$opciones_radio = array('DIN' => 'Dinero', 'POR' => 'Porcentaje');
		$attr_radio = array('label' => array('class' => 'radio'), 'legend'=>false, 'value' => $this->data['Descuento']['tipo']);
		$options = array(
			'conditions' => array(
				'NOT' => array(
					array('Producto.showroom' => null)
				)
			),
			'fields' => array(
				'Producto.showroom',
				'Producto.showroom'
			),
			'order' => array(
				'Producto.showroom' => 'ASC'
			)
		);
		$showrooms = $this->Descuento->Categoria->Producto->find('list',$options);
		prx($showrooms);
		
		$this->set(compact('categorias', 'cate_check', 'opciones_radio', 'attr_radio','showrooms'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'listar'));
		}
		if ( $this->Descuento->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'listar'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'listar'));
	}

	function admin_index()
	{
		if (! empty($this->data))
		{
			if (isset($this->data['Descuento']['codigo']) && $this->data['Descuento']['codigo'])
			{
				$compras = $clientes = array();
				$options = array(
					'conditions' => array(
						'Descuento.codigo' => $this->data['Descuento']['codigo'],
						'Descuento.web_tienda' => array(1,2)
					),
					'fields' => array(
						'Descuento.id',
						'Descuento.nombre',
						'Descuento.codigo',
						'Descuento.cantidad',
						'Descuento.fecha_caducidad',
						'Descuento.contador',
						'Descuento.tipo',
						'Descuento.descuento',
						'Descuento.web_tienda',
						'Descuento.maximo',
						'Descuento.escolar'
					),
					'contain' => array(
						'Categoria' => array(
							'fields' => array(
								'Categoria.id',
								'Categoria.nombre'
							)
						)
					)
				);
				if ($descuento = $this->Descuento->find('first',$options))
				{
					$options = array(
						'conditions' => array(
							'ProductosCompra.descuento_id' => $descuento['Descuento']['id']
						),
						'fields' => array(
							'ProductosCompra.compra_id',
							'ProductosCompra.compra_id'
						)
					);
					if ($lista_compras = $this->Descuento->Categoria->Producto->ProductosCompra->find('list',$options))
					{
						$compras = $this->Descuento->Categoria->Producto->Compra->find('all', array(
							'conditions' => array(
								'Compra.id' => $lista_compras
							),
							'fields' => array(
								'Compra.id',
								'Compra.usuario_id',
								'Compra.total',
								'Compra.created'
							),
							'contain' => array(
								'Usuario' => array(
									'fields' => array(
										'Usuario.id',
										'Usuario.nombre',
										'Usuario.apellido_paterno'
									)
								)
							)
						));
					}
					$clientes = $this->Descuento->ClientesTienda->find('all', array(
						'conditions' => array(
							'ClientesTienda.descuento_id' => $descuento['Descuento']['id']
						),
						'fields' => array(
							'ClientesTienda.id',
							'ClientesTienda.administrador_id',
							'ClientesTienda.nombre',
							'ClientesTienda.created'
						),
						'contain' => array(
							'Administrador' => array(
								'fields' => array(
									'Administrador.id',
									'Administrador.nombre'
								)
							)
						)
					));
				}
				$this->set(compact('descuento', 'compras', 'clientes'));
			}
		}
	}

	function admin_usar_descuento()
	{
		if (! empty($this->data))
		{
			if ($this->data['Descuento']['id'] && $this->data['Descuento']['codigo'])
			{
				$options = array(
					'conditions' => array(
						'Descuento.id' => $this->data['Descuento']['id'],
						'Descuento.codigo' => $this->data['Descuento']['codigo']
					),
					'recursive' => -1
				);
				if (! $descuento = $this->Descuento->find('first',$options))
				{
					$this->Session->setFlash(__('Registro invalido...', true));
					$this->redirect(array('action' => 'index'));
				}
				// verificar caducidad
				if (date('Y-m-d') > $descuento['Descuento']['fecha_caducidad'])
				{
					$this->Session->setFlash(__('Descuento caducado', true));
					$this->redirect(array('action' => 'index'));
				}
				// verificar limite de descuentos
				if ($descuento['Descuento']['contador'] >= $descuento['Descuento']['cantidad'])
				{
					$this->Session->setFlash(__('El descuento ya ha sido utilizado.', true));
					$this->redirect(array('action' => 'index'));
				}
				
				$cliente = array('administrador_id' => $this->Auth->user('id'));
				if ($this->data['Cliente']['nombre'])
				{
					$cliente['nombre'] = $this->data['Cliente']['nombre'];
				}
				if ($this->data['Cliente']['rut'])
				{
					$cliente['rut'] = $this->data['Cliente']['rut'];
				}
				if ($this->data['Cliente']['telefono'])
				{
					$cliente['telefono'] = $this->data['Cliente']['telefono'];
				}
				$quemar_descuento = array(
					'Descuento' => array(
						'id' => $descuento['Descuento']['id'],
						'contador' => $descuento['Descuento']['contador']+1
					)
				);
				if ($cliente)
				{
					$quemar_descuento['ClientesTienda'][0] = $cliente;
				}
				if ( $this->Descuento->saveAll($quemar_descuento) )
				{
					$this->Session->setFlash(__('Descuento aplicado exitosamente.', true));
					$this->redirect(array('action' => 'view', $descuento['Descuento']['id']));
				}
				else
				{
					$this->Session->setFlash(__('No fue posible aplicar el descuento.', true));
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash(__('Registro invalido..', true));
				$this->redirect(array('action' => 'index'));
			}
		}
		else
		{
			$this->Session->setFlash(__('Registro invalido.', true));
			$this->redirect(array('action' => 'index'));
		}
	}
	
	function admin_exportar_codigos_validados()
	{
		
		/** Exporte en excel los códigos de descuento validados.
		 * Este excel debe tener todos los campos que permiten identificar quién cobró:
		 * 		el descuento,
		 * 		fecha,
		 * 		en qué tienda,
		 * 		etc…
		 */
		$descuentos = $this->Descuento->ClientesTienda->find('all', array(
			'fields' => array(
				'ClientesTienda.id',
				'ClientesTienda.descuento_id',
				'ClientesTienda.administrador_id',
				'ClientesTienda.nombre',
				'ClientesTienda.rut',
				'ClientesTienda.telefono',
				'ClientesTienda.created'
			),
			'contain' => array(
				'Descuento' => array(
					'fields' => array(
						'Descuento.id',
						'Descuento.nombre',
						'Descuento.codigo',
						'Descuento.tipo',
						'Descuento.fecha_caducidad',
						'Descuento.descuento',
						'Descuento.web_tienda',
						'Descuento.comentario'
					)
				),
				'Administrador' => array(
					'fields' => array(
						'Administrador.id',
						'Administrador.nombre'
					)
				)
			),
			'order' => array('ClientesTienda.id' => 'DESC'),
			//'limit' => 20
		));
		$this->set(compact('descuentos'));
	}
}
?>