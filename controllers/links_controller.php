<?php
class LinksController extends AppController
{
	var $name = 'Links';

	function admin_index()
	{
		$this->Link->recursive = 0;
		$this->paginate = array(
			'contain' => array(
				'LinkParametro'
			)
		);
		$this->set('links', $this->paginate());
	}

	function admin_view($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('link', $this->Link->read(null, $id));
	}

	function admin_add()
	{
		if ( ! empty($this->data) )
		{
			// verifica si existe ruta
			$options = array(
				'conditions' => array(
					'Link.ruta' => $this->data['Link']['ruta']
				),
				'fields' => array(
					'Link.id'
				)
			);
			if (! $link = $this->Link->find('first',$options))
			{
				// verifica si existe controlador + action + param
				$options = array(
					'conditions' => array(
						'Link.controlador' => $this->data['Link']['controlador'],
						'Link.action' => $this->data['Link']['action'],
						'Link.parametro' => $this->data['Link']['parametro']
					),
					'fields' => array(
						'Link.id'
					)
				);
				if (! $link = $this->Link->find('first',$options))
				{
					$this->Link->create();
					if ( $this->Link->saveAll($this->data) )
					{
						$this->Session->setFlash(__('Registro guardado correctamente', true));
						$this->redirect(array('action' => 'index'));
					}
					else
					{
						$this->Session->setFlash(__('El registro no pudo ser guardado. Por favor intenta nuevamente', true));
					}
				}
				else
				{
					$this->Session->setFlash(__('Ya existe un link creado para esta ruta. Por favor intente con otra.', true));
				}
			}
			else
			{
				$this->Session->setFlash(__('El nuevo link ingresado ya se encuentra registrado. Por favor intente con otro.', true));
			}
		}
		$grupos = array();
		$this->loadModel('Producto');
		$options = array(
			'conditions' => array(
				'NOT' => array(
					array('Producto.grupo' => null),
					array('Producto.grupo' => '')
				)
			),
			'fields' => array(
				'Producto.grupo'
			),
			'group' => array(
				'Producto.grupo'
			)
		);
		if ($registros = $this->Producto->find('list',$options))
		{
			foreach ($registros as $registro) 
			{
				if ($tags = explode('][',$registro))
				{
					foreach ($tags as $tag)
					{
						$grupo = trim(str_replace(array(']','['), '', $tag));
						$largo = strlen($grupo);
						if ($largo > 1 && $largo <= 20)
						{
							$grupos = array_merge($grupos,array(
								$grupo => $grupo
							));
						}
					}
				}
			}
		}
		asort($grupos);
		$this->set(compact('grupos'));
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
			if ( $this->Link->save($this->data) )
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
			$this->data = $this->Link->read(null, $id);
		}
	}

	function admin_delete($id = null)
	{
		if ( ! $id )
		{
			$this->Session->setFlash(__('Registro inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if ( $this->Link->delete($id) )
		{
			$this->Session->setFlash(__('Registro eliminado', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('El registro no pudo ser eliminado. Por favor intenta nuevamente', true));
		$this->redirect(array('action' => 'index'));
	}

}
?>