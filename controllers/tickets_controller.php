<?php
class TicketsController extends AppController
{
	var $name = 'Tickets';
	var $components = array('Masivo');
	
	function beforeRender()
	{
		parent::beforeRender();
		//LAYOUT EXCEL
		if ( $this->params['action'] == 'admin_exportar_tickets_validados' )
			$this->layout = 'ajax';
	}


	function admin_masivo_add()
	{
		$stats = array();
		if ( ! empty($this->data) )
		{
			//prx($this->data);
			$this->data['Archivo']['administrador_id']		= $this->Auth->user('id');
			$this->data['Archivo']['tipo'] = 2;

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

				$mapeo	 		= array('codigo',	'numero_guia',	'fecha_guia',	'codigo_producto',	'color',	'talla');
				$registros		= $this->Masivo->procesarArchivo('img/' . $archivo['Archivo']['nombre']['path'], $mapeo);

				if ( ! $registros )
				{
					$this->Session->setFlash(__('No se encontraron registros en el archivo', true));
					$this->redirect(array('action' => 'index'));
				}

				$verificar_codigo = $this->Ticket->find('list', array('fields' => array('Ticket.id', 'Ticket.codigo')));
		
			
				$i=0;
				foreach($registros as $registro)
				{
					if($i++ == 0)
						continue;
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
					$arr_fecha = explode('/',$registro['fecha_guia']);
					$fecha = $arr_fecha[2].'-'.$arr_fecha[1].'-'.$arr_fecha[0];

					$new_ticket = array(
						'Ticket' => array(
							'fecha_guia' => $fecha,
							'codigo' => $codigo,
							'numero_guia' => $registro['numero_guia'],
							'codigo_producto' => $registro['codigo_producto'],
							'talla' => $registro['talla'],
							'color' => $registro['color']
						)
					);
				
					$this->Ticket->create();
					if ( $this->Ticket->save($new_ticket) )
					{
						$verificar_codigo[$this->Ticket->id] = $codigo;
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
		//$categorias = $this->Descuento->Categoria->find('list', $options);
		$this->set(compact( 'stats'));
	}

	function admin_listar()
	{
		$this->Ticket->recursive = 0;
		$this->set('tickets', $this->paginate());
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
		$this->Ticket->recursive = 1;
		$this->set('ticket', $this->Ticket->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			if ( $this->Ticket->saveAll($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'listar'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		
	}

	function admin_edit($id = null)
	{
		if ( ! $id && empty($this->data) )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'listar'));
		}
		
		if ( ! empty($this->data) )
		{
			// limpia los check no seleccionados
				$save = $this->data;
				//prx($save);
			if ( $this->Ticket->save($save) )
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
				'conditions' => array('Ticket.id' => $id),
				'recursive' => -1
			);
			if (!$this->data = $this->Ticket->find('first',$options))
			{
				$this->Session->setFlash(__('No se encontro el ticket', true));
				$this->redirect(array('action' => 'listar'));
			
			}
		}

		
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'listar'));
		}
		if ( $this->Ticket->delete($id) )
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
			if (isset($this->data['Ticket']['codigo']) && $this->data['Ticket']['codigo'])
			{
				$options = array(
					'conditions' => array(
						'Ticket.codigo' => $this->data['Ticket']['codigo']
					),
					'contain' => 'Administrador'
				);
				if($ticket = $this->Ticket->find('first', $options))
				{
									$this->set(compact('ticket'));

				}
			
			}
		
			}
		}

	function admin_usar_ticket()
	{
		if (! empty($this->data))
		{
			if ($this->data['Ticket']['id'] && $this->data['Ticket']['codigo'])
			{
				$options = array(
					'conditions' => array(
						'Ticket.id' => $this->data['Ticket']['id'],
						'Ticket.codigo' => $this->data['Ticket']['codigo']
					),
					'recursive' => -1
				);
				if (! $ticket = $this->Ticket->find('first',$options))
				{
					$this->Session->setFlash(__('Registro invalido...', true));
					$this->redirect(array('action' => 'index'));
				}
				// verificar caducidad
			
				// verificar limite de descuentos
				if ($ticket['Ticket']['estado'])
				{
					$this->Session->setFlash(__('El Ticket ya ha sido utilizado.', true));
					$this->redirect(array('action' => 'index'));
				}
				$ticket = array();
				$ticket['administrador_id'] =  $this->Auth->user('id');
				if ($this->data['Ticket']['nombre'])
				{
					$ticket['nombre'] = $this->data['Ticket']['nombre'];
				}
				if ($this->data['Ticket']['rut'])
				{
					$ticket['rut'] = $this->data['Ticket']['rut'];
				}
				if (isset($this->data['Ticket']['telefono']))
				{
					$ticket['telefono'] = $this->data['Ticket']['telefono'];
				}
				$ticket['codigo_producto_nuevo'] = $this->data['Ticket']['codigo_producto_nuevo'];
				$ticket['color_nuevo'] = $this->data['Ticket']['color_nuevo'];
				$ticket['talla_nuevo'] = $this->data['Ticket']['talla_nuevo'];

				$ticket['id'] = $this->data['Ticket']['id'];
				$ticket['fecha_cobro'] = date ('Y-m-d H:i:s');
				$ticket['estado'] = 1;
				if ( $this->Ticket->save($ticket) )
				{
					$this->Session->setFlash(__('Ticket utilizado exitosamente.', true));
					$this->redirect(array('action' => 'view', $ticket['Ticket']['id']));
				}
				else
				{
					$this->Session->setFlash(__('No fue posible aplicar el Ticket.', true));
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
	
	function admin_exportar_tickets_validados()
	{
		
		/** Exporte en excel los códigos de descuento validados.
		 * Este excel debe tener todos los campos que permiten identificar quién cobró:
		 * 		el descuento,
		 * 		fecha,
		 * 		en qué tienda,
		 * 		etc…
		 */
		$tickets = $this->Ticket->find('all', array('conditions' => 
													array('estado' => 1),
													'contain' => 'Administrador'));
		$this->set(compact('tickets'));
	}
}
?>