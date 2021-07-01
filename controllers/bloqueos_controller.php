<?php
class BloqueosController extends AppController
{
	var $name = 'Bloqueos';

	function admin_index()
	{
		$this->Bloqueo->recursive = 0;
		$this->set('bloqueos', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$color = $this->Color->read(null, $id);
		$primarios = $this->Color->Primario->find('first', array('conditions' => array('Primario.id' => $color['Color']['primario_id'])));
		$this->set(compact('color', 'primarios'));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Bloqueo->create();
			if ( $this->Bloqueo->save($this->data) )
			{
				$producto = $this->Bloqueo->Producto->Stock->find('first', array('conditions' => array ('producto_id' => $this->data['Bloqueo']['producto_id'], 'talla' => $this->data['Bloqueo']['talla'] )));
				if($producto)
					$this->Bloqueo->Producto->Stock->delete($producto['Stock']['id']);
				$producto = $this->Bloqueo->Producto->Talla->find('first', array('conditions' => array ('producto_id' => $this->data['Bloqueo']['producto_id'], 'talla' => $this->data['Bloqueo']['talla'] )));
				if($producto)
					$this->Bloqueo->Producto->Talla->delete($producto['Stock']['id']);
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$productos = $this->Bloqueo->Producto->find('list', array('fields' => array('id','codigo_completo'), 'conditios' => array('Producto.activo' => 1), 'order' => array('Producto.codigo_completo ASC')));
		$this->set(compact('productos'));
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
			if ( $this->Color->save($this->data) )
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
			$this->data = $this->Color->read(null, $id);
		}
		$secundarios = $primarios = $this->Color->Primario->find('list', array('order' => array('Primario.nombre ASC')));
		$this->set(compact('primarios', 'secundarios'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Bloqueo->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>