<?php
class ProductosTecnologiasController extends AppController
{
	var $name = 'ProductosTecnologias';

	function admin_index()
	{
		$this->ProductosTecnologia->recursive = 0;
		$this->set('productosTecnologias', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('productosTecnologia', $this->ProductosTecnologia->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->ProductosTecnologia->create();
			if ( $this->ProductosTecnologia->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$productos = $this->ProductosTecnologia->Producto->find('list');
		$tecnologias = $this->ProductosTecnologia->Tecnologia->find('list');
		$this->set(compact('productos', 'tecnologias'));
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
			if ( $this->ProductosTecnologia->save($this->data) )
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
			$this->data = $this->ProductosTecnologia->read(null, $id);
		}
		$productos = $this->ProductosTecnologia->Producto->find('list');
		$tecnologias = $this->ProductosTecnologia->Tecnologia->find('list');
		$this->set(compact('productos', 'tecnologias'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->ProductosTecnologia->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>