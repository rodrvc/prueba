<?php
class ColoresController extends AppController
{
	var $name = 'Colores';

	function admin_index()
	{
		$this->Color->recursive = 0;
		$this->set('colores', $this->paginate());
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
			$this->Color->create();
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
		$secundarios = $primarios = $this->Color->Primario->find('list', array('order' => array('Primario.nombre ASC')));
		$this->set(compact('primarios', 'secundarios'));
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
		if ( $this->Color->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>