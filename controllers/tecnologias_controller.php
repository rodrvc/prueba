<?php
class TecnologiasController extends AppController
{
	var $name = 'Tecnologias';

	function admin_index()
	{
		$this->Tecnologia->recursive = 1;
		$this->set('tecnologias', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Tecnologia->recursive = 2;
		$this->set('tecnologia', $this->Tecnologia->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Tecnologia->create();
			if ( $this->Tecnologia->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$this->Tecnologia->recursive = 2;
		$parentTecnologias = $this->Tecnologia->ParentTecnologia->find('list');
		$productos = $this->Tecnologia->Producto->find('list');
		$this->set(compact('parentTecnologias', 'productos'));
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
			if ( $this->Tecnologia->save($this->data) )
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
			$this->data = $this->Tecnologia->read(null, $id);
		}
		$this->Tecnologia->recursive = 2;
		$parentTecnologias = $this->Tecnologia->ParentTecnologia->find('list');
		$productos = $this->Tecnologia->Producto->find('list');
		$this->set(compact('parentTecnologias', 'productos'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Tecnologia->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>