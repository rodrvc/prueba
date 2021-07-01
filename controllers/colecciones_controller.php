<?php
class ColeccionesController extends AppController
{
	var $name = 'Colecciones';

	function admin_index()
	{
		$this->Coleccion->recursive = 0;
		$this->set('colecciones', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$coleccion = $this->Coleccion->find('first', array('conditions' => array('Coleccion.id' => $id),
														   'fields' => array('Coleccion.id',
																			 'Coleccion.nombre',
																			 'Coleccion.created'),
														   'contain' => array('Producto' => array('fields' => array('Producto.id',
																													'Producto.nombre',
																													'Producto.codigo',
																													'Producto.codigo_completo')))));
		$this->set(compact('coleccion'));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Coleccion->create();
			if ( $this->Coleccion->save($this->data) )
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

	function admin_edit($id = null)
	{
		if ( ! $id && empty($this->data) )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( ! empty($this->data) )
		{
			if ( $this->Coleccion->save($this->data) )
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
			$this->data = $this->Coleccion->read(null, $id);
		}
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Coleccion->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}

}
?>