<?php
class DespachosController extends AppController
{
	var $name = 'Despachos';
	
	function add()
	{
		if ( ! empty($this->data) )
		{
			
			$this->Despacho->create();
			if ( $this->Despacho->save($this->data) )
			{
				$this->Session->write('Despacho.id', $this->Despacho->id);
				$this->redirect(array('controller' =>'productos', 'action' => 'pago'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
				$this->redirect(array('controller' =>'productos', 'action' => 'despacho'));
			}
		}

	}
	
	function admin_index()
	{
		$this->Despacho->recursive = 1;
		$this->set('despachos', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Despacho->recursive = 1;
		$this->set('despacho', $this->Despacho->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Despacho->create();
			if ( $this->Despacho->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$this->Despacho->recursive = 1;
		$usuarios = $this->Despacho->Usuario->find('list');
		$regiones = $this->Despacho->Region->find('list');
		$this->set(compact('usuarios', 'regiones'));
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
			if ( $this->Despacho->save($this->data) )
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
			$this->data = $this->Despacho->read(null, $id);
		}
		$this->Despacho->recursive = 1;
		$usuarios = $this->Despacho->Usuario->find('list');
		$regiones = $this->Despacho->Region->find('list');
		$this->set(compact('usuarios', 'regiones'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Despacho->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>