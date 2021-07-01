<?php
class DireccionesController extends AppController
{
	var $name = 'Direcciones';
	
	function ajax_datos()
	{
		if (! $this->Session->check('Auth.Usuario'))
			die(false);
		if (! $this->params['form']['direccion_id'])
			die(false);
		$options = array(
			'conditions' => array(
				'Direccion.id' => $this->params['form']['direccion_id'],
				'Direccion.usuario_id' => $this->Auth->user('id')
			),
			'fields' => array(
				'Direccion.id',
				'Direccion.nombre',
				'Direccion.calle',
				'Direccion.numero',
				'Direccion.depto',
				'Direccion.comuna_id',
				'Direccion.codigo_postal',
				'Direccion.telefono',
				'Direccion.celular',
				'Comuna.nombre',
				'Region.nombre'
			),
			'joins' => array(
				array(
					'table' => 'sitio_comunas',
					'alias' => 'Comuna',
					'type' => 'LEFT',
					'conditions' => array(
						'Comuna.id = Direccion.comuna_id'
					)
				),
				array(
					'table' => 'sitio_regiones',
					'alias' => 'Region',
					'type' => 'LEFT',
					'conditions' => array(
						'Region.id = Comuna.region_id'
					)
				)
			)
		);
		if ($direccion = $this->Direccion->find('first',$options))
			die(json_encode($direccion));
		die(false);
	}

	function actualizar()
	{
		$fila = 0;
		if (($gestor = fopen("d:/xampp/htdocs/skechers/direcciones.csv", "r")) !== FALSE) {
	    while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
	        $numero = count($datos);
	        echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
	      	if($fila++ <  5623)
	      		continue;
	      	//prx($datos);
	      	$save = array(		'id' 					=> $datos[0],
	      						'calle' 				=> $datos[1],
	      						'numero'				=> $datos[2],
	      						'depto'					=> $datos[3],
	      						'otras_indicaciones'	=> $datos[4],
	      						'telefono'				=> $datos[6],
	      						'celular'				=> $datos[7]);
	      	$this->Direccion->save($save);
	    }
	    fclose($gestor);
	}

}
	
	
	function ajax_cargar()
	{
		if (! $this->Session->check('Auth.Usuario'))
			die(false);
		if (! isset($this->params['form']['id']))
			die(false);
		if (! $this->params['form']['id'])
			die(false);

		$options = array(
			'conditions' => array(
				'Direccion.id' => $this->params['form']['id'],
				'Direccion.usuario_id' => $this->Auth->user('id')
			),
			'fields' => array(
				'Direccion.id',
				'Direccion.nombre',
				'Direccion.calle',
				'Direccion.numero',
				'Direccion.depto',
				'Direccion.region_id',
				'Direccion.comuna_id',
				'Direccion.codigo_postal',
				'Direccion.telefono',
				'Direccion.celular',
			),
			'recursive' => -1
		);

		if (! $direccion = $this->Direccion->find('first',$options))
			die(false);

		$options = array(
			'conditions' => array(
				'Comuna.region_id' => $direccion['Direccion']['region_id']
			),
			'fields' => array(
				'Comuna.id',
				'Comuna.nombre'
			),
			'order' => array(
				'Comuna.nombre' => 'ASC'
			)
		);
		$direccion['Comunas'] = $this->Direccion->Comuna->find('all',$options);
		die(json_encode($direccion));
	}
	
	function ajax_comunas($region_id = null){
		if($region_id != null){
			$comunas = $this->Direccion->Comuna->find('all',array('conditions'    => array('Comuna.region_id' => $region_id, 'activo' => 1),
																  'fields' => array('Comuna.id','Comuna.nombre'),
																  'order'        => array('Comuna.nombre ASC')
																  )
													  );
			$this->set(compact('comunas'));
		}
		else{
			$this->set('comunas',array());
		}
	}

	function ajax_delete()
	{
		if (! $this->Session->check('Auth.Usuario'))
			die(false);
		if (! isset($this->params['form']['id']))
			die(false);
		if (! $this->params['form']['id'])
			die(false);
		$options = array(
			'conditions' => array(
				'Direccion.id' => $this->params['form']['id'],
				'Direccion.usuario_id' => $this->Auth->user('id')
			),
			'fields' => array(
				'Direccion.id'
			),
			'recursive' => -1
		);
		if (! $direccion = $this->Direccion->find('first',$options))
			die(false);
		$delete = array(
			'id' => $direccion['Direccion']['id'],
			'usuario_id' => 0
		);
		if ($this->Direccion->save($delete))
			die('OK');
		die(false);
	}

	function ajax_guardar()
	{
		Configure::write('debug',0);
		if (! $this->Session->check('Auth.Usuario'))
			die(false);
		if (empty($this->data))
			die(false);
		if (! isset($this->data['Direccion']))
			die(false);
		if (! $this->data['Direccion'])
			die(false);

		$requeridos = array('calle','numero','comuna_id','region_id','telefono','nombre');
		$continuar = true;
		$mensaje = '';
		foreach ($requeridos as $campo)
		{
			if (isset($this->data['Direccion'][$campo]) && $this->data['Direccion'][$campo])
				continue;

			$continuar = false;
			if ($mensaje)
				$mensaje.=', ';
			$mensaje = str_replace('_id','',$campo);
		}
		if (! $continuar)
			die(json_encode(array('estado' => 'error','mensaje' => 'Debe ingresar '.$mensaje.'.')));
		$this->data['Direccion']['usuario_id'] = $this->Session->read('Auth.Usuario.id');
		$this->Direccion->create();
		if ($this->Direccion->save($this->data['Direccion']))
		{
			$options = array(
				'conditions' => array(
					'Direccion.usuario_id' => $this->Session->read('Auth.Usuario.id')
				),
				'fields' => array(
					'Direccion.id',
					'Direccion.nombre'
				),
				'recursive' => -1
			);
			$direcciones = $this->Direccion->find('all',$options);
			die(json_encode(array('estado' => 'OK', 'mensaje' => $direcciones)));
		}
		die(json_encode(array('estado' => 'error','mensaje' => 'No fue posible guardar la nueva direccón. Por favor intentelo nuevamente.')));
	}

	function agregar()
	{
		if (! $this->Auth->user())
			$this->redirect($this->referer());
		if (empty($this->data))
			$this->redirect($this->referer());
		if (! isset($this->data['Direccion']))
			$this->redirect($this->referer());
		if (! $this->data['Direccion'])
			$this->redirect($this->referer());

		$requeridos = array('calle','numero','comuna_id','region_id','telefono','nombre');
		$continuar = true;
		$mensaje = '';
		foreach ($requeridos as $campo)
		{
			if (isset($this->data['Direccion'][$campo]) && $this->data['Direccion'][$campo])
				continue;

			$continuar = false;
			if ($mensaje)
				$mensaje.=', ';
			$mensaje = str_replace('_id','',$campo);
		}
		if (! $continuar)
		{
			$this->Session->setFlash('Para agregar una nueva dirección debe ingresar '.$mensaje.'.', 'default', array('class' => 'alert alert-warning','rel' => 'form-direccion'));
			$this->redirect(array('controller' => 'usuarios', 'action' => 'perfil_datos', '?' => array('tab' => 'direcciones')));
		}
		$this->data['Direccion']['usuario_id'] = $this->Auth->user('id');
		$this->Direccion->create();
		if ($this->Direccion->save($this->data['Direccion']))
		{
			$this->Session->setFlash('La dirección ingresada se ha agregado exitosamente.', 'default', array('class' => 'alert alert-success','rel' => 'form-direccion'));
			$this->redirect(array('controller' => 'usuarios', 'action' => 'perfil_datos', '?' => array('tab' => 'direcciones')));
		}
		$this->Session->setFlash('Lo sentimos, no ha sido posible guardar el registro. Por favor intentelo nuevamente.', 'default', array('class' => 'alert alert-warning','rel' => 'form-direccion'));
		$this->redirect(array('controller' => 'usuarios', 'action' => 'perfil_datos', '?' => array('tab' => 'direcciones')));
	}

	function edit()
	{
		if (! $this->Auth->user())
			$this->redirect($this->referer());
		if (empty($this->data))
			$this->redirect($this->referer());
		if (! isset($this->data['Direccion']))
			$this->redirect($this->referer());
		if (! $this->data['Direccion'])
			$this->redirect($this->referer());
		if (! isset($this->data['Direccion']['id']))
			$this->redirect($this->referer());
		if (! $this->data['Direccion']['id'])
			$this->redirect($this->referer());

		$options = array(
			'conditions' => array(
				'Direccion.id' => $this->data['Direccion']['id'],
				'Direccion.usuario_id' => $this->Auth->user('id')
			),
			'fields' => array(
				'Direccion.id'
			),
			'recursive' => -1
		);
		if ($direccion = $this->Direccion->find('first',$options))
			$this->data['Direccion']['id'];

		$requeridos = array('calle','numero','comuna_id','region_id','telefono','nombre');
		$continuar = true;
		$mensaje = '';
		foreach ($requeridos as $campo)
		{
			if (isset($this->data['Direccion'][$campo]) && $this->data['Direccion'][$campo])
				continue;

			$continuar = false;
			if ($mensaje)
				$mensaje.=', ';
			$mensaje = str_replace('_id','',$campo);
		}
		if (! $continuar)
		{
			$this->Session->setFlash('Para editar la dirección debe ingresar '.$mensaje.'.', 'default', array('class' => 'alert alert-warning','rel' => 'form-direccion'));
			$this->redirect(array('controller' => 'usuarios', 'action' => 'perfil_datos', '?' => array('tab' => 'direcciones')));
		}

		$this->data['Direccion']['usuario_id'] = $this->Auth->user('id');
		if ($this->Direccion->save($this->data['Direccion']))
		{
			$this->Session->setFlash('La dirección se ha editado exitosamente.', 'default', array('class' => 'alert alert-success','rel' => 'form-direccion'));
			$this->redirect(array('controller' => 'usuarios', 'action' => 'perfil_datos', '?' => array('tab' => 'direcciones')));
		}
		$this->Session->setFlash('Lo sentimos, no ha sido posible guardar el registro. Por favor intentelo nuevamente.', 'default', array('class' => 'alert alert-warning','rel' => 'form-direccion'));
		$this->redirect(array('controller' => 'usuarios', 'action' => 'perfil_datos', '?' => array('tab' => 'direcciones')));
	}

	function view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->write('Despacho.direccion_id', $id);
		$this->Direccion->recursive = 1;
		$this->set('direccion', $this->Direccion->read(null, $id));
	}
	
	function add()
	{
		if ( ! empty($this->data) )
		{
			$this->Direccion->create();
			$num_tel = $this->data['Direccion']['telefono'];
			$num_cel = $this->data['Direccion']['celular'];
			$this->data['Direccion']['telefono'] = $this->data['Direccion']['pre_telefono'] . '-' . $this->data['Direccion']['telefono'];
			$this->data['Direccion']['celular'] = $this->data['Direccion']['pre_celular'] . '-' . $this->data['Direccion']['celular'];
			if ( $this->Direccion->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				// redirecciona
				if ( $this->Session->read('Logueo.estado') )
				{
					$this->redirect(array('controller' => $this->Session->read('Logueo.controller'), 'action' => $this->Session->read('Logueo.action')));
				}
				$this->redirect($this->referer());
			}
			else
			{
				$this->data['Direccion']['telefono'] = $num_tel;
				$this->data['Direccion']['celular'] = $num_cel;
				$this->Session->setFlash(__('El registro no fue guardado. Intenta nuevamente', true));
				// redirecciona
				if ( $this->Session->read('Logueo.estado') )
				{
					$this->redirect(array('controller' => $this->Session->read('Logueo.controller'), 'action' => $this->Session->read('Logueo.action')));
				}
				$this->redirect($this->referer());
			}
		}
	}
	
	function mis_direcciones()
	{
		// ----- VERIFICA LOGUEO
		if(! $this->Auth->user('') )
			$this->redirect(array('controller' => 'usuarios', 'action' => 'login'));
		
		if ( ! empty($this->data) )
		{
			$this->Direccion->create();
			$num_tel = $this->data['Direccion']['telefono'];
			$num_cel = $this->data['Direccion']['celular'];
			$this->data['Direccion']['telefono'] = $this->data['Direccion']['pre_telefono'] . '-' . $this->data['Direccion']['telefono'];
			$this->data['Direccion']['celular'] = $this->data['Direccion']['pre_celular'] . '-' . $this->data['Direccion']['celular'];
			$this->data['Direccion']['usuario_id'] = $this->Auth->user('id');
			if ( $this->Direccion->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'mis_direcciones'));
			}
			else
			{
				$this->data['Direccion']['telefono'] = $num_tel;
				$this->data['Direccion']['celular'] = $num_cel;
				$this->Session->setFlash(__('El registro no fue guardado. Intenta nuevamente', true));
			}
		}
		
		
		//$comunas = $this->Direccion->Comuna->find('list');
		$regiones = $this->Direccion->Region->find('list');
		$mis_direcciones = $this->Direccion->find('all', array('conditions'	=> array('Direccion.usuario_id' => $this->Auth->user('id')),
															   'fields'		=> array('Direccion.id', 'Direccion.nombre')
															   )
												  );
		$this->set(compact('regiones', 'mis_direcciones'));
	}
	
	function editar($id = null)
	{
		// ----- VERIFICA LOGUEO
		if(! $this->Auth->user('') )
			$this->redirect(array('controller' => 'usuarios', 'action' => 'login'));
		
		if ( ! $id )
			$this->redirect(array('action' => 'mis_direcciones'));
			
		$direccion = $this->Direccion->find('first', array('conditions' => array('Direccion.id' => $id,
																				 'Direccion.usuario_id' => $this->Auth->user('id'))
														   )
											);
		if( $direccion )
		{
			if ( ! empty($this->data) )
			{
				if ( $this->Direccion->save($this->data) )
				{
					$this->Session->setFlash(__('Registro guardado correctamente', true));
					$this->redirect(array('action' => 'mis_direcciones'));
				}
				else
				{
					$this->Session->setFlash(__('El registro no fue guardado. Intenta nuevamente', true));
					$this->redirect(array('action' => 'mis_direcciones'));
				}
			}
			if ( empty($this->data) )
			{
				$this->data = $direccion;
			}
		}
		else
		{
			$this->Session->setFlash(__('Registro invalido.', true));
			$this->redirect(array('action' => 'mis_direcciones'));
		}
		
		$comunas = $this->Direccion->Comuna->find('list', array('conditions' => array('Comuna.region_id' => $direccion['Direccion']['region_id'])));
		$regiones = $this->Direccion->Region->find('list');
		$mis_direcciones = $this->Direccion->find('all', array('conditions'	=> array('Direccion.usuario_id' => $this->Auth->user('id')),
															   'fields'		=> array('Direccion.id', 'Direccion.nombre')
															   )
												  );
		$this->set(compact('comunas', 'regiones', 'mis_direcciones'));
	}
	
	function eliminar($id = null)
	{
		// ----- VERIFICA LOGUEO
		if(! $this->Auth->user('') )
			$this->redirect(array('controller' => 'usuarios', 'action' => 'login'));
		
		if ( ! $id )
			$this->redirect(array('action' => 'mis_direcciones'));
			
		$direccion = $this->Direccion->find('first', array('conditions' => array('Direccion.id' => $id,
																				 'Direccion.usuario_id' => $this->Auth->user('id'))
														   )
											);
		if( $direccion )
		{
			if ( $this->Direccion->save(array('Direccion' => array('id' => $id,
																   'usuario_id' => 0))) )
			{
				$this->Session->setFlash(__('Registro eliminado', true));
				$this->redirect(array('action' => 'mis_direcciones'));
			}
			$this->Session->setFlash(__('El registro no pudo ser eliminado.', true));
			$this->redirect(array('action' => 'mis_direcciones'));
		}
		else
		{
			$this->Session->setFlash(__('Registro invalido.', true));
			$this->redirect(array('action' => 'mis_direcciones'));
		}
	}
	
	function admin_index()
	{
		$this->Direccion->recursive = 0;
		$this->set('direcciones', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->Direccion->recursive = 1;
		$this->set('direccion', $this->Direccion->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Direccion->create();
			if ( $this->Direccion->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$usuarios = $this->Direccion->Usuario->find('list');
		$comunas = $this->Direccion->Comuna->find('list');
		$regiones = $this->Direccion->Region->find('list');
		$this->set(compact('usuarios', 'comunas', 'regiones'));
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
			if ( $this->Direccion->save($this->data) )
			{
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
			$this->data = $this->Direccion->read(null, $id);
		}
		$usuarios = $this->Direccion->Usuario->find('list');
		$comunas = $this->Direccion->Comuna->find('list');
		$regiones = $this->Direccion->Region->find('list');
		$this->set(compact('usuarios', 'comunas', 'regiones'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Direccion->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>