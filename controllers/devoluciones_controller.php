<?php
class DevolucionesController extends AppController
{
	var $name = 'Devoluciones';

	function estado($id = null)
	{
		if (! empty($this->data))
		{
			if ($this->data['Devolucion']['clave'])
				$this->data['Devolucion']['clave'] = $this->Auth->password($this->data['Devolucion']['clave']);

			if ($this->Auth->login($this->data['Devolucion']))
			{
				$this->redirect(array('action' => 'estado', $this->data['Devolucion']['id']));
			}
			else
			{
				$this->Session->setFlash(__('Datos invalidos', true));
				$this->redirect(array('action' => 'estado', $this->data['Devolucion']['id']));
			}
		}

		if (! $id)
			$this->redirect(array('controller' => 'productos', 'action' => 'inicio'));

		if ($this->Auth->user())
		{
			$options = array(
				'conditions' => array(
					'Devolucion.id' => $id
				),
			);
			$devolucion = $this->Devolucion->find('first',$options);
			$this->set(compact('devolucion'));
		}
	}

	function admin_index()
	{
		$this->Devolucion->recursive = 0;
		$this->set('devoluciones', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('devolucion', $this->Devolucion->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Devolucion->create();
			if ( $this->Devolucion->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$productosCompras = $this->Devolucion->ProductosCompra->find('list');
		$compras = $this->Devolucion->Compra->find('list');
		$this->set(compact('productosCompras', 'compras'));
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
			if ( $this->Devolucion->save($this->data) )
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
			$this->data = $this->Devolucion->read(null, $id);
		}
		$productosCompras = $this->Devolucion->ProductosCompra->find('list');
		$compras = $this->Devolucion->Compra->find('list');
		$this->set(compact('productosCompras', 'compras'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Devolucion->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>