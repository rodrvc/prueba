<?php
class GaleriasController extends AppController
{
	var $name = 'Galerias';

	function beforeRender()
	{
		parent::beforeRender();
		//LAYOUT EXCEL
		if ( in_array($this->action, array('admin_delete')) )
		{
			Configure::write('debug', 0);
			$this->layout	= 'ajax';
		}
	}

	function admin_index()
	{
		$this->Galeria->recursive = 1;
		$this->set('galerias', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('galeria', $this->Galeria->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			$this->Galeria->create();
			if ( $this->Galeria->save($this->data) )
			{
				$this->Session->setFlash(__('Registro guardado correctamente', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
			}
		}
		$productos = $this->Galeria->Producto->find('list');
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
			if ( $this->Galeria->save($this->data) )
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
			$this->data = $this->Galeria->read(null, $id);
		}
		$productos = $this->Galeria->Producto->find('list');
		$this->set(compact('productos'));
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
			die('Registro Invalido.');
		
		if ( $this->Galeria->delete($id) )
		{
			die('READY');
		}
		else
		{
			die('Se produjo un error al intentar eliminar el registro');
		}
	}
	
	function admin_ordenar()
	{
		if (isset($this->data['Galeria']) && $this->data['Galeria'])
		{
			$cont = 0;
			foreach ($this->data['Galeria'] as $galeria_id => $galeria)
			{
				$update_galeria = array('Galeria' => array('id' => $galeria_id));
				if (isset($galeria['imagen']['name']) && $galeria['imagen']['name'])
					$update_galeria['Galeria']['imagen'] = $galeria['imagen'];
				if (isset($galeria['orden']) && $galeria['orden'])
					$update_galeria['Galeria']['orden'] = $galeria['orden'];
				// Guardar
				if ($this->Galeria->save($update_galeria))
				{
					$cont++;
				}
			}
			$this->Session->setFlash(__('Se modificaron '.$cont.' registros.', true));
		}
		else
		{
			$this->Session->setFlash(__('Registro invalido', true));
		}
		$this->redirect($this->referer());
		
	}
}
?>