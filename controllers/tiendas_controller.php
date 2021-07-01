<?php
class TiendasController extends AppController
{
	var $name = 'Tiendas';
	var $helpers = array('Cache');
	var $cacheAction = array('index' => '3600');

	function index()
	{
		$options = array(
			'fields' => array(
				'Zona.id',
				'Zona.nombre'
			),
			'contain' => array(
				'Tienda' => array(
					'fields' => array(
						'Tienda.id',
						'Tienda.nombre',
						'Tienda.codigo',
						'Tienda.direccion',
						'Tienda.telefono',
						'Tienda.region_id',
						'Tienda.comuna_id',
						'Tienda.zona_id',
						'Tienda.latitud',
						'Tienda.longitud',
						'Tienda.imagen',
						'Tienda.outlet',
						'Tienda.remodelacion',
						'Tienda.horario'
					),
					'Region' => array(
						'fields' => array(
							'Region.id',
							'Region.nombre'
						)
					),
					'Comuna' => array(
						'fields' => array(
							'Comuna.id',
							'Comuna.nombre'
						)
					)
				)
			)
		);
		$zonas = $this->Tienda->Zona->find('all', $options);
		$this->loadModel('Retiro');
		$retiros = $this->Retiro->find('list', array('conditions' => array('Retiro.tipo_id' => 3, 'Retiro.activo' => 1),
													'fields' => array('codigo','codigo')));
		//prx($retiros);
		$this->set(compact('zonas','retiros'));
	}
	function traspasar()
	{
		$this->loadModel('Retiro');
		$retiros = $this->Retiro->find('list', array('fields' => array('codigo','id'), 'conditions' => array('tipo_id' => 3)));
		$tiendas = $this->Tienda->find('all');
		foreach($tiendas as $tienda)
		{
			$save = array(		'nombre' 		=> $tienda['Tienda']['nombre'],
								'calle'  		=> $tienda['Tienda']['direccion'],
								'region_id'		=> $tienda['Tienda']['region_id'],
								'comuna_id'		=> $tienda['Tienda']['comuna_id'],
								'codigo'		=> $tienda['Tienda']['codigo'],
								'activo'		=> 1,
								'telefono'		=> $tienda['Tienda']['telefono'],
								'tipo_id'		=> 3); 

			$this->Retiro->create();
			$this->Retiro->save($save);


		}
	}


	function admin_index()
	{
		$this->Tienda->recursive = 0;
		$this->set('tiendas', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('tienda', $this->Tienda->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Tienda->create();
			if ( $this->Tienda->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$regiones = $this->Tienda->Region->find('list');
		$comunas = $this->Tienda->Region->Comuna->find('list');
		$zonas = $this->Tienda->Zona->find('list');
		$this->set(compact('regiones', 'comunas', 'zonas'));
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
			if ( $this->Tienda->save($this->data) )
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
			$this->data = $this->Tienda->read(null, $id);
		}
		$regiones = $this->Tienda->Region->find('list');
		$comunas = $this->Tienda->Region->Comuna->find('list');
		$zonas = $this->Tienda->Zona->find('list');
		$this->set(compact('regiones', 'comunas', 'zonas'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Tienda->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
